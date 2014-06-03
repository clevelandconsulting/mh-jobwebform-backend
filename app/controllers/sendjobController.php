<?php

class sendjobController extends controller {

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
  $mail = new PHPMailer;
  $mail->WordWrap = 50;                                 // Set word wrap to 50 characters

  //set up the smtp info
  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = 'mail.clevelandconsulting.com';  // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = 'kevin@clevelandconsulting.com';                 // SMTP username
  $mail->Password = 'C00kie22';                           // SMTP password
  $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
  
  //set up who it's from
  $mail->From = 'kevin@clevelandconsulting.com';
  $mail->FromName = 'Mailer';
  $mail->addReplyTo('info@clevelandconsulting.com', 'Information');
  
  //add recipients
  $mail->addAddress('kevinvile@gmail.com', 'Joe User');     // Add a recipient
  //$mail->addAddress('ellen@example.com');               // Name is optional
  
  //$mail->addCC('cc@example.com');
  //$mail->addBCC('bcc@example.com');
  
  //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
  //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
  
  //add attachments
  $attachments = $this->getAttachments();
  foreach($attachments as $attachment) {
   $mail->addAttachment($this->app->getUploadFilePath() . $attachment, $attachment);
  }
  
  $mail->isHTML(true);                                  // Set email format to HTML
  $mail->Subject = 'New Job Request';
  $mail->Body    = 'This is the HTML message body <b>in bold!</b><br /><br /><pre><code>' . file_get_contents('php://input') . '</code></pre>';
  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
  
  if(!$mail->send()) {
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