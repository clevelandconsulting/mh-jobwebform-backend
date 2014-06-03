<?php

define('MB_BYTE', 1048576);

require_once('config.php');
require_once('router.php');
require_once('controller.php');

abstract class app {
 public $router;
 public $config;
 
 public $maxFileSize;
 public $maxPostSize;
 public $devMode;
 
 private $httpResponseCode;
 private $httpResponseBody;
 
 
 
 function __construct($config, $router='') {
  $this->maxFileSize = ( (int) ini_get('upload_max_filesize') ) * MB_BYTE;
  $this->maxRequestSize = ( (int) ini_get('post_max_size') ) * MB_BYTE; 
  $this->config = $config;
  $this->router = $router = '' ? new router() : $router;
  $this->httpResponseCode = 200;
  $this->devMode = false;
  
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
   //if options request, just send a blank valid response to get the appropriate headers returned
   
   $this->setResponseBody('');
   $this->sendResponse();
   die(0);
  }
  
  if (isset($_SERVER['CONTENT_LENGTH'])) {
   $requestSize = (int) $_SERVER['CONTENT_LENGTH'];
   
   if ($requestSize > $this->maxRequestSize) {
    //request size is to big, error
    $message = 'The request was larger than the PHP size limit.  It can\'t be larger than ' . $this->maxRequestSize/MB_BYTE . 'Mb.';
    $this->appError(413,$message);
    
   }
  }
  
  $this->check_request_meta();

 }
 
 protected function check_request_meta() {
  if (!isset($_GET['action'])) {
    $this->appError(400, 'Action not sent.');
  }
  else {
    $this->action = $_GET['action'];
  }
  
  if (isset($_GET['development'])) {
   $this->devMode = true;
  }
 }
 
 public function run() {
  $cName = $this->router->getControllerName($this->action);
  if ($cName != '') {
   require_once($this->config->controllerFolder . $cName . '.php');
   $controller = new $cName($this);
   
   $method = strtolower($_SERVER['REQUEST_METHOD']);
   $controller->$method();
  }
  else {
   $this->appError(400, 'Unknown Action.');
  }
 }
 
 public function appError($code,$message) {
  $this->setResponseCode($code);
  $this->setResponseBody($message);
  
  $this->sendResponse();
  
  $this->appendErrorLog($message);
  
  die(0);
 }
 
 public function setResponseCode($code) {
  $this->httpResponseCode = $code;
 }
 
 public function setResponseBody($body) {
  $this->httpResponseBody = $body;
 }
 
 public function sendResponse() {
  header("HTTP/1.1 " . $this->httpResponseCode, true, $this->httpResponseCode);
  header('Content-Type: application/json');
  echo $this->httpResponseBody;
 }
 
 public function appendErrorLog($message) {
  $ts = date('Y-m-d H:i:s') . ' : ';
  $serverInfo = $this->getServerInfo();
  
  file_put_contents($this->config->logFolder . 'error.log', $ts . $message . "\n\t" . $serverInfo . "\n\n", FILE_APPEND);
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