<?php

require_once('controller.php');

abstract class viewController extends controller {

 protected $view;
 
 public function __construct(httpView $view, $app) {
  parent::__construct($app);
  $this->view = $view;
  
  $this->checkRequestMeta();

 }
 
 protected function methodNotAllowed($message='') {
  $message = $message == '' ? 'This method is not allowed.' : $message;
  $this->setError(405,$message);
  $this->display();
 }
 
 protected function setResponse($code,$body) {
  $this->view->setBody($body);
  $this->view->setHttpCode($code);
 }
 
 public function display() {
  if ( $this->hasError() ) {
   $error = $this->getError();
   $this->view->setHttpCode($error->code);
   $this->view->setBody($error->message);
   $this->view->setMode(HTTP_VM_JSON);
  }
  
  $response = $this->view->display();
  
 }
 
 abstract public function post();
 abstract public function get();
 abstract public function delete();
 abstract public function put();
 public function options() {
   $this->view->setBody('');
   $this->view->setHttpCode(200);
   $this->display();
 }
 
 protected function checkRequestMeta() {
  $this->checkRequestSize();
 }
 
 private function checkRequestSize() {
  if (isset($_SERVER['CONTENT_LENGTH'])) {
   $requestSize = (int) $_SERVER['CONTENT_LENGTH'];
   
   if ($requestSize > $this->app->maxRequestSize) {
    //request size is to big, error
    $message = 'The request was larger than the PHP size limit.  It can\'t be larger than ' . $this->app->maxRequestSize/MB_BYTE . 'Mb.';
    $this->setError(413,$message);
    
   }
  }
 }
}

