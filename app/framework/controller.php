<?php

abstract class controller {
 protected $app;
 protected $error;
 
 public function __construct($app) {
  $this->app = $app; 
  $this->error = '';
  $this->response = '';
  
 }
 
 public function setError($code,$message) {
  $this->error = new controllerResponse($code,$message);
 }
 
 public function hasError() {
  if ($this->error == '' ) return false;
  else return true;
 }
 
 protected function setResponse($code,$body) {
  $this->response = new controllerResponse($code, $body);
 }
 
 protected function methodNotAllowed($message='') {
  $message = $message == '' ? 'This method is not allowed.' : $message;
  $this->setError(405,$message);
  $this->display();
 }
 
 private function getResponse() {
  if ( $this->hasError() ) return $this->error;
  return $this->response;
 }
 
 public function display() {
  $response = $this->getResponse();
  $this->app->setResponseCode($response->code);
  $this->app->setResponseBody($response->body);
  
  $this->app->sendResponse();
 }
 
 abstract public function post();
 abstract public function get();
 abstract public function delete();
 abstract public function put();
}

class controllerResponse {
 public $code;
 public $body;
 
 public function __construct($code,$body) {
  $this->code = $code;
  $this->body = $body;
 }
}

?>