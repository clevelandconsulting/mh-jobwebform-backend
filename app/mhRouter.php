<?php

class mhRouter extends router {
 
 private $sessionId;
 private $action;
 
 public function __construct($app) {
  parent::__construct($app);
 }
 
 public function validate() {
  if (!isset($_GET['action'])) {
    $this->setError(400, 'Action not sent.');
  }
  else {
    $this->action = $_GET['action'];
    
    if (!isset($_POST['sessionId'])) {
     if (isset($_GET['sessionId'])) 
      $this->sessionId = $_GET['sessionId'];
     else 
      $this->setError(400, 'Session Id not sent.');
    }
    else {
     $this->sessionId = $_POST['sessionId'];
    }
    
  }
 }
 
 public function getSessionId() {
  return $this->sessionId;
 }
 
 public function getAction() {
  return $this->action;
 }

 
}

