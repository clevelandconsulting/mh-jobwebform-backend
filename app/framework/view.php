<?php

abstract class view {
 private $body;
 
 public function setBody($body) {
  $this->body = $body;
 }
 
 public function getBody() {
  return $this->body;
 }
 
 abstract public function display();
 
}