<?php

require_once('framework/app.php');
require_once('mhRouter.php');

class mhApp extends app {

 public function __construct($smtpConfig='', $appConfig='') {
 
  $mailer = $smtpConfig != '' ? new mailer($smtpConfig) : "";
  $config = $appConfig != '' ? $appConfig : new config();
  parent::__construct($config, new mhRouter($this), $mailer);
  
 }
 
 public function getSessionId() {
  return $this->router->getSessionId();
 }
 
 public function getUploadFilePath() {
  return $this->config->uploadFolder . $this->getSessionId() . DIRECTORY_SEPARATOR;
 }
 
}