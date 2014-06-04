<?php

class sendjobController extends viewController {

 public function __construct($app) {
  require_once($app->config->viewFolder . 'sendjobView.php');
  $view = new sendjobView();
  parent::__construct($view,$app);
 }

 public function post() {
  $this->getJobData();
  $this->display();
 }
 public function get() {
  $this->getJobData();
  $this->display();
 }
 public function delete() {
  $this->methodNotAllowed();
 }
 public function put() {
  $this->methodNotAllowed();
 }
 
 private function getJobData() {
  $data = '';
  if (isset($_POST['data'])) {
   $data = $_POST['data'];
  }
  else {
   $data = json_decode(file_get_contents('php://input'));
  }
  
  if ( $data == '' ) {
    $this->setError(400,'Missing job data.');
  }
  else {
    $this->sendJobData($data);
    //$this->setResponse(200,"We got the data for session " . $this->app->sessionId . ".<br />");
  }

 }
 
 private function sendJobData($data) {
  //add attachments
  $attachments = $this->getAttachments();
  foreach($attachments as $attachment) {
   $this->app->mailer->addAttachment($this->app->getUploadFilePath() . $attachment, $attachment);
  }
  
  $subject = "New Job Request";
  $body = 'This is the HTML message body <b>in bold!</b><br /><br /><pre><code>' . file_get_contents('php://input') . '</code></pre>';
  $altBody = 'This is the body in plain text for non-HTML mail clients';
  
  $result = $this->app->mailer->sendMail($subject,$body,$altBody,true);
    
  if(!$result) {
   $this->setError(500,"Email could not be delivered. " . $mail->ErrorInfo);
  } else {
   $this->setResponse(200,"Job data has been delivered!");
  }

 }
 
 private function getAttachments() {
  $files = scandir($this->app->getUploadFilePath());
  $attachments = Array();
  
  foreach($files as $file) {
   if ( !is_dir($file) ) array_push($attachments,$file);
  }
  
  return $attachments;
 }
 
}

?>