<?php

require_once("object.php");

abstract class view extends framework_object {
 private $body;
 protected $mustache;
 
 public function __construct() {
  $mustacheConfig = array(
   'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/../templates'),
   'partials_loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/../templates/partials')
  );
  
  $this->mustache = new Mustache_Engine($mustacheConfig);
  $this->mustache->addHelper('uc', function($value) { return ucwords($value); });
  $this->mustache->addHelper('date', 
    function($value) { 
     $result = '';
     
     if ( $value != '') {
      $date = new DateTime($value);
      $result = $date->format('F d, Y');
     }
     
     return $result;
    }
  );
 }
 
 public function setBody($body) {
  $this->body = $body;
 }
 
 public function getBody() {
  return $this->body;
 }
 
 abstract public function display();
 
}