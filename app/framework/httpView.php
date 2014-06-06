<?php

define('HTTP_VM_TEXT', 0 );
define('HTTP_VM_HTML', 1 );
define('HTTP_VM_JSON', 2 );

require_once('view.php');

class httpView extends view {
 private $httpCode;
 private $mode;
 private $headers;
 private $content_type;
 
 public function __construct($mode) {
  parent::__construct();
  $this->setMode($mode); 
  $this->headers = Array();
 }
 
 public function setHttpCode($code) {
  $this->httpCode = $code;
 }
 
 public function getHttpCode() {
  return $this->httpCode;
 }
 
 public function setMode($mode) {
  $this->mode = $mode;
  
  switch ($mode) {
   case HTTP_VM_TEXT:
   case HTTP_VM_HTML:
    $this->content_type = "text/html";
    break;
   
   case HTTP_VM_JSON:
    $this->content_type = "application/json";
    break;
  }
  
 }
 
 public function getMode() {
  return $this->mode;
 }
 
 public function addHeader($header) {
  array_push($this->headers, $header);
 }
 
 protected function displayHeaders() {
  header("HTTP/1.1 " . $this->getHttpCode(), true, $this->getHttpCode());
  header('Content-Type: ' + $this->content_type);
  
  foreach($this->headers as $header) {
   header($header);
  }
 }
 
 public function display() {
  $this->displayHeaders();
  $b = $this->getBody();
  switch($this->getMode()) {
   case HTTP_VM_TEXT:
   case HTTP_VM_HTML:
    echo $b;
    break;
   
   case HTTP_VM_JSON:
    echo json_encode($b);
    break;
  }
  
 }
}