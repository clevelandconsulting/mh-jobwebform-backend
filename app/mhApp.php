<?php

require_once('framework/app.php');

class mhApp extends app {

 public $sessionId;
 public $action;
 
 public function __construct() {
  parent::__construct(new config(), new router());
 }
 
 protected function check_request_meta() {
  parent::check_request_meta();
  
  if (!isset($_POST['sessionId'])) {
   if ($this->devMode && isset($_GET['sessionId'])) 
    $this->sessionId = $_GET['sessionId'];
   else 
    $this->appError(400, 'Session ID not sent.');
  }
  else {
   $this->sessionId = $_POST['sessionId'];
  }
  
 }
 
}