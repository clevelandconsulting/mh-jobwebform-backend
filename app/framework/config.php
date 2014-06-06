<?php

require_once("object.php");

class config extends framework_object  {

 public $logFolder;
 public $uploadFolder;
 public $controllerFolder;
 public $viewFolder;
 public $modelFolder;
 
 function __construct($logF='',$uploadF='') {
  $logF = $logF == '' ? 'logs' : $logF;
  $uploadF = $uploadF == '' ? 'uploads' : $uploadF;
  
  $this->logFolder = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $logF . DIRECTORY_SEPARATOR;
  $this->uploadFolder = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $uploadF . DIRECTORY_SEPARATOR;
  $this->controllerFolder = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR;
  $this->viewFolder = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
  $this->modelFolder = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR;
  
 }
}

?>