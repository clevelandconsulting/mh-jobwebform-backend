<?php

class uploadController extends viewController {

 public function __construct($app) {
  require_once($app->config->viewFolder . 'uploadView.php');
  $view = new uploadView();
  parent::__construct($view,$app);
 }

 public function post() {
  $this->upload();
 }
 public function get() { 
  $this->upload();
//  $this->methodNotAllowed();
 }
 public function delete() {
  
  if ( isset( $_GET['name'] ) ) {
   $filename = $this->getUploadFilePath() . $_GET['name'];
   
   if(file_exists($filename)) {
    $result = unlink($filename); 
   }
   else {
    $result = true;
   }
   
   if ( $result ) {
    $this->setResponse(204,'');
   }
   else {
    $this->setError(500,'Unable to delete the file ' . $_GET['name'] . '.');
   }
   
  }
  else {
   $this->setError(400,'File name was not sent');
  }
  
  $this->display();
 }
 public function put() {
  $this->methodNotAllowed();
 }
 
 private function upload() {
  $file = $this->get_file_upload();
  
  if (!$this->hasError()) {
   $tempPath = $file[ 'tmp_name' ];
   $uploadPath = $this->getUploadFilePath();
   
   try {
   
    if (!file_exists($uploadPath))
     $result = mkdir($uploadPath);
    else
     $result = true;
    
    if ($result) {
     
      $uploadFilePath = $uploadPath . $file[ 'name' ];
      move_uploaded_file( $tempPath, $uploadFilePath );
      $answer = array( 'answer' => 'File transfer completed');
      $this->setResponse(200,json_encode( $answer ));
    }
    else {
     $this->setError(500, "Unable to create directory");
    }
    
   }
   catch (Exception $e) {
    $this->setError(500, "Some Error: " . $e->getMessage());
   }
  }
  
  $this->display();
  
 }

 private function get_file_upload() {
  
  if (!isset($_POST['alias'])) {
   $this->setError(400, 'File alias not sent.');
  }
  else {
   $alias = $_POST['alias'];
  }
   
  if ( !empty( $_FILES ) ) {      
   switch ($_FILES[$alias]['error']) {
    case UPLOAD_ERR_OK:
     break;
     
    case UPLOAD_ERR_NO_FILE:
     $this->setError(400, 'No files were received');
     break;
     
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
     $this->setError(413, 'Exceeded PHP file size limit. Files can\'t be larger than ' . $this->app->maxFileSize/MB_BYTE . 'Mb.');
     break;
     
    default:
     $this->setError(500, "An unknown error occurred uploading the file. File was not uploaded."); 
     break;
     
   }
  }
  else {
   $this->setError(400,'The file was not delivered to the server properly.');
  }
  
  if (!$this->hasError())
   return $_FILES[$alias];
 }
 
 private function getUploadFilePath() {
  return $this->app->getUploadFilePath();
 }
}

?>