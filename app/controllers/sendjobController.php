<?php

class sendjobController extends viewController {

 private $jobHandler;
 
 public function __construct($app) {
  require_once($app->config->viewFolder . 'sendjobView.php');
  require_once($app->config->modelFolder . 'jobHandler.php');
  
  $view = new sendjobView();
  parent::__construct($view,$app);
 }

 public function post() {
  $this->getJobData();
  $this->display();
 }
 public function get() {
  $this->getJobData(true);
  $this->display();
  //$this->methodNotAllowed();
 }
 public function delete() {
  $this->methodNotAllowed();
 }
 public function put() {
  $this->methodNotAllowed();
 }
 
 private function getJobData($test=false) {
  $data = '';
  
  if($test) {
   $data = json_decode(file_get_contents('largeSample.json'));
  }
  else {
   if (isset($_POST['data'])) {
    $data = $_POST['data'];
   }
   else {
    $data = json_decode(file_get_contents('php://input'));
   }
  }
  if ( $data == '' ) {
    $this->setError(400,'Missing job data.');
  }
  else {
   try {
    $this->jobHandler = new jobHandler($data, $this->app->getUploadFilePath());
    $jobs = $this->jobHandler->getJobs();
    
    $client = new GuzzleHttp\Client();
    
    foreach($jobs as $job) {
    
	    $options = [
				  'verify' => false,
				  'auth' => ['Developer', 'letmein'],
				  'body' => $this->view->jobToJson($job)
			  ];
			  
			  //echo $job->medium . "<br />";
			  //print_r($job->getTypeData());
			  
			  //print_r($options['body']);
			  //echo "<br /><br /><br />";
			  //die();
			 
			  $result = $client->post('https://10.0.1.185/RESTfm/MHMarketingMgt/layout/Api-job.json?RFMscript=Job.RestFM.Finalize',$options);
			  $data = json_decode($result->getBody()->getContents());
			  $guid = $data->data[0]->__guid;
			  print_r($guid);
			  echo "<br /><br /><br />";
			  //die();
		 
    }
    
    echo "ATTACHMENTS<br /><br />";
    $attachments = $this->getAttachments();
		  if (is_array($attachments)) {
		   foreach($attachments as $attachment) {
			   $path = $this->app->getUploadFilePath() . $attachment;
			   
			   $type = pathinfo($path, PATHINFO_EXTENSION);
						$data = file_get_contents($path);
						$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
			   
			   echo $base64;
		    //$this->app->mailer->addAttachment($this->app->getUploadFilePath() . $attachment, $attachment);
		    print_r($attachment);
		   }
		  }
    
    die();
    
    
    
    
    $htmlMessage = $this->view->renderJobs($jobs, $this->app->getSessionId());
    $altMessage = '';
    
    $message['body'] = $htmlMessage;
    $message['altbody'] = $altMessage;
    
    $this->sendJobRequest($message);
   }
   catch(Exception $e) {
    $this->setError(500,$e->getMessage());
   }
    //$message = $this->parseJobData($data);
    //if ($message != NULL) $this->sendJobRequest($message);
    //$this->setResponse(200,"We got the data for session " . $this->app->sessionId . ".<br />");
  }

 }
 
 private function parseJobData($data) {
  $result = NULL;
  
  if (property_exists($data, "data")) {
     $vars = get_object_vars($data->data);
     foreach($vars as $key => $value) {
       echo "<h3>" . $key . "</h3>";
       $this->debug($value);
     }
     die();
     //$this->debug($vars);
  }
  else {
   $this->setError(400,"Invalid data sent.");
  }

  return $result;
 }
 
 private function sendJobRequest($message) {
 
    //add attachments
  $attachments = $this->getAttachments();
  if (is_array($attachments)) {
   foreach($attachments as $attachment) {
    $this->app->mailer->addAttachment($this->app->getUploadFilePath() . $attachment, $attachment);
   }
  }
  
  $subject = "New Job Request";
  $body = $message['body'];
  $altBody = $message['altbody']; //'This is the body in plain text for non-HTML mail clients';
  
  $result = $this->app->mailer->sendMail($subject,$body,$altBody,true);
    
  if(!$result) {
   $this->setError(500,"Email could not be delivered. " . $this->app->mailer->ErrorInfo);
  } else {
   $this->setResponse(200,"Job data has been delivered!");
  }

 }
 
 private function getAttachments() {
  try {
   $files = scandir($this->app->getUploadFilePath());
   $attachments = Array();
   
   if (is_array($files)) {
    foreach($files as $file) {
     if ( !is_dir($file) ) array_push($attachments,$file);
    }
   }
   return $attachments;
  }
  catch(Exception $e) {
   //don't need to do anything, probably no attachments...
  }
 }
 
}

?>