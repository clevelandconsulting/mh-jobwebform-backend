<?php

class router {
 private $routes;
 
 public function __construct() {
  $this->routes = Array();
 }
 
 public function getControllerName($action) {
  foreach ($this->routes as $route) {
   if ($route->isRoute($action)) {
    return $route->getControllerName();
   }
  }
  
 }
 
 public function defineRoute($route) {
  array_push($this->routes,$route);
 }
 
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