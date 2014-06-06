<?php

class mailer extends model {
 private $config;
 private $phpMail;
 
 public function __construct(smtpConfig $config) {
  $this->config = $config;
  $this->setUpMailer();
 }
 
 protected function setUpMailer() {
  $this->phpMail = new PHPMailer;
  
  $this->phpMail->WordWrap = $this->config->wordWrapLength > 0 ? $this->config->wordWrapLength : 50;
 
  $this->phpMail->isSMTP();                                      // Set mailer to use SMTP
  $this->phpMail->Host = $this->config->host;  // Specify main and backup SMTP servers
  $this->phpMail->SMTPAuth = $this->config->auth;                               // Enable SMTP authentication
  $this->phpMail->Username = $this->config->username;                 // SMTP username
  $this->phpMail->Password = $this->config->password;                           // SMTP password
  $this->phpMail->SMTPSecure = $this->config->secure;                            // Enable encryption, 'ssl' also accepted
  
  //set up who it's from
  $this->phpMail->From = $this->config->from->address;
  $this->phpMail->FromName = $this->config->from->name;
  
  if ($this->config->replyTo->name != '')
   $this->phpMail->addReplyTo($this->config->replyTo->address, $this->config->replyTo->name);
  else
   $this->phpMail->addReplyTo($this->config->replyTo->address);
  
  if ($this->config->sendTo != '') $this->addRecipient($this->config->sendTo);
  
 }
 
 public function addRecipient(address $address,$isCC=false,$isBCC=false) {
  $fn = $isCC ? "addCC" : ( $isBCC ? "addBCC" : "addAddress");
  
  if ($address->name != '') $this->phpMail->$fn($address->address);     // Add a recipient
  else $this->phpMail->$fn($address->address,$address->name);
  
 }
 
 public function addAttachment($filePath, $fileName) {
  $this->phpMail->addAttachment($filePath, $fileName);
 }
 
 public function sendMail($subject,$body,$altBody,$isHTML) {
  $this->phpMail->isHTML($isHTML);                                  // Set email format to HTML
  $this->phpMail->Subject = $subject;
  $this->phpMail->Body    = $body;
  $this->phpMail->AltBody = $altBody;
  
  $result = $this->phpMail->send();
  
  if($result) {
   $this->clearLastEmail();
  }
  
  return $result;
 }
 
 public function clearLastEmail() {
  $this->phpMail->clearAttachments();
  $this->phpMail->clearAllRecipients();
 }
 
 
}

class address {
 public $address;
 public $name;
}

class smtpConfig {

 public $wordWrapLength;
 public $host;
 public $auth; //true or false
 public $username;
 public $password;
 public $secure; //ssl or tls
 public $from; //address class
 public $replyTo; //address class
 public $sendTo; //address class
 
}

class mailerFactory {

 static public function createAddress($address,$name) {
  $result = new address();
  $result->address = $address;
  $result->name = $name;
  
  return $result;
 }
 static public function createSMTPConfig($host,$auth,$un,$pw,$secure,address $from,address $reply,$send='',$wordWrap=0) {
  $config = new smtpConfig();
  $config->host = $host;
  $config->auth = $auth;
  $config->username = $un;
  $config->password = $pw;
  $config->secure = $secure;
  $config->from=$from;
  $config->replyTo=$reply;
  $config->sendTo = $send;
  $config->wordWrapLength = $wordWrap;
  
  return $config;
 }
}
