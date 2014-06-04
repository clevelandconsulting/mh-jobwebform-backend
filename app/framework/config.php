<?php

class config {

 public $logFolder;
 public $uploadFolder;
 public $controllerFolder;
 public $viewFolder;
 
 function __construct($logF='',$uploadF='') {
  $logF = $logF == '' ? 'logs' : $logF;
  $uploadF = $uploadF == '' ? 'uploads' : $uploadF;
  
  $this->logFolder = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $logF . DIRECTORY_SEPARATOR;
  $this->uploadFolder = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $uploadF . DIRECTORY_SEPARATOR;
  $this->controllerFolder = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR;
  $this->viewFolder = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
  
 }
}

?>