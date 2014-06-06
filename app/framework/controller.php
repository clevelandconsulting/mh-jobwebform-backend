<?php

require_once("object.php");
require_once("error.php");

abstract class controller extends framework_object {
 protected $app;
 protected $error;
 
 public function __construct($app) {
  $this->app = $app; 
  $this->clearError();
  
 }
 
 public function setError($code,$message) {
  $this->error = new framework_error($code,$message);
  $this->appendErrorLog($message);
 }
 
 public function hasError() {
  if ($this->error == '' ) return false;
  else return true;
 }
 
 public function clearError() {
   $this->error = '';
 }
 
 public function getError() {
   return $this->error;
 }
 
 public function appendErrorLog($message) {
  $ts = date('Y-m-d H:i:s') . ' : ';
  $serverInfo = $this->getServerInfo();
  
  file_put_contents($this->app->config->logFolder . 'error.log', $ts . $message . "\n\t" . $serverInfo . "\n\n", FILE_APPEND);
 }
 
 private function getServerInfo() {
   return "Agent: " . $this->getServerEntity('HTTP_USER_AGENT') . 
   "\n\tContent: " . $this->getServerEntity('CONTENT_LENGTH') . " " . $this->getServerEntity('CONTENT_TYPE') . 
   "\n\tOrigin/Referrer: " . $this->getServerEntity('HTTP_ORIGIN') . " " . $this->getServerEntity('HTTP_REFERER') .
   "\n\tURI/Method: " . $this->getServerEntity('REQUEST_URI') . " " . $this->getServerEntity('REQUEST_METHOD') . "\n" . file_get_contents('php://input');
 }
 
 private function getServerEntity($name) {
  if ( isset ($_SERVER[$name])) return $_SERVER[$name];
 }
  
}

?>