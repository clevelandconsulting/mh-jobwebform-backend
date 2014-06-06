<?php

require_once("object.php");

class framework_error extends framework_object  {
 public $code;
 public $message;
 
 public function __construct($code,$msg) {
  $this->code = $code;
  $this->message = $msg;
 }
}