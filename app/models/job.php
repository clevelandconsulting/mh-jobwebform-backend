<?php

class job extends model {
 public $type;
 public $typeData;
 public $typeFieldMarketing;
 public $requestDate;
 public $requestorName;
 public $product;
 public $portfolio;
 public $discipline;
 public $costCenter;
 public $title;
 public $creativeBrief;
 public $fieldMarketing;
 public $manager;
 public $multipleCollateralType;
 
 public function Type() { return ucwords($this->type); }
 public function MultipleCollateralType() { return ucwords($this->multipleCollateralType); }
 public function Managers() { return $this->manager; }
 public function FieldMarketing() { return ucwords($this->fieldMarketing); }
 
}