<?php

require_once('framework/app.php');
require_once('mhRouter.php');

class mhApp extends app {

 public function __construct($smtpConfig='') {
  $mailer = $smtpConfig != '' ? new mailer($smtpConfig) : "";
  parent::__construct(new config(), new mhRouter($this), $mailer);
 }
 
 public function getSessionId() {
  return $this->router->getSessionId();
 }
 
 public function getUploadFilePath() {
  return $this->config->uploadFolder . $this->getSessionId() . DIRECTORY_SEPARATOR;
 }
 
}