<?php

define('MB_BYTE', 1048576);

require_once('error.php');
require_once('config.php');
require_once('model.php');
require_once('viewController.php');
require_once('httpView.php');
require_once('router.php');
require_once('mailer.php');

abstract class app extends framework_object  {
 public $router;
 public $config;
 public $mailer;
 
 public $maxFileSize;
 public $maxPostSize;
 public $devMode;
 
 private $httpResponseCode;
 private $httpResponseBody;
 
 
 
 public function __construct($config, $router='',$mailer='') {
  $this->maxFileSize = ( (int) ini_get('upload_max_filesize') ) * MB_BYTE;
  $this->maxRequestSize = ( (int) ini_get('post_max_size') ) * MB_BYTE; 
  $this->config = $config;
  $this->router = $router = '' ? new router() : $router;
  $this->mailer = $mailer;
  $this->httpResponseCode = 200;
  $this->devMode = false;
 
 }
  
 public function run() {
 
  try {
   if (!$this->router->hasError()) {
   
    $cName = $this->router->getControllerName();
    if ($cName != '') {
     require_once($this->config->controllerFolder . $cName . '.php');
     $controller = new $cName($this);
     
     $method = strtolower($_SERVER['REQUEST_METHOD']);
     $controller->$method();
    }
    else {
     $this->router->setError(400, 'Unknown Action.');
    }
   
   }
   
   if ( $this->router->hasError()) {
    
    $view = new httpView(HTTP_VM_JSON);
    $error = $this->router->getError();
    $view->setHttpCode($error->code);
    $view->setBody($error->message);
    
    $view->display();
    
    die();
   }
  }
  catch(Exception $error) {
   header("HTTP/1.1 500", true, 500);
   header('Content-Type: application/json');
   echo json_encode($error->getMessage());
   die();
  }
  
 }
 
}


?>