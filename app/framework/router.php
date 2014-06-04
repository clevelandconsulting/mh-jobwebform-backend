<?php

require_once('controller.php');

abstract class router extends controller {
 private $routes;
 
 public function __construct($app) {
  parent::__construct($app);
  $this->routes = Array();
 }
 
 public function getControllerName() {
 
  foreach ($this->routes as $route) {
   if ($route->isRoute($this->getAction())) {
    return $route->getControllerName();
   }
  }
  
 }
 
 public function defineRoute($route) {
  array_push($this->routes,$route);
 }
 
 abstract public function getAction();
 
}

class route {
 private $controller;
 private $action;
 
 public function __construct($action,$controller) {
  $this->action = strtolower($action);
  $this->controller = $controller;
 }
 
 public function isRoute($action) {
  return $this->action == strtolower($action);
 }
 
 public function getControllerName() {
  return $this->controller;
 }
 
}

?>