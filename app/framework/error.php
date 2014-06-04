<?php

class framework_error {
 public $code;
 public $message;
 
 public function __construct($code,$msg) {
  $this->code = $code;
  $this->message = $msg;
 }
}