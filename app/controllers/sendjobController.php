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
  }elseif (isset($_GET['data'])) {
   $data = $_GET['data'];
  }
  else {
   $this->setError(400,'Missing job data.');
  }
 
  if($data !='' )
   $this->setResponse(200,"We got the data for session " . $this->app->sessionId . ".<br />" . $data);
  
 }
 
}

?>