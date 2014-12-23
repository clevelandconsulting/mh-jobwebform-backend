<?php

require_once('job.php');

class jobHandler extends model {
 private $jobData;
 
 public function __construct($jobData) {
  //$this->jobData = $jobData;
  if(!$this->validate($jobData)) throw new Exception("Data not valid.");
  
  $data = $jobData->data;
  $managers = Array();
  if( property_exists($data,"districtManager") ) 
   array_push($managers, array('type'=>'District', 'name'=>$data->districtManager));
  if ( property_exists($data,"relationshipManager") )
    array_push($managers, array('type'=>'Relationship Marketing', 'name'=>$data->relationshipManager));
   if ( property_exists($data,"productManager") )
    array_push($managers, array('type'=>'Product Marketing', 'name'=>$data->productManager));
  
  $collaterals = $this->getCollateral($data);
  
  $this->jobData = Array();
  
  foreach($collaterals as $collateral) {
   $job = new job();
   $job->type = $collateral['type'];
   $job->typeData = $collateral['data'];
   if ( isset ( $collateral['useFieldMarketing'] ) ) $job->typeFieldMarketing = $collateral['useFieldMarketing'];
   $job->requestDate = $data->requestDate;
   $job->requestorName = $data->requestorName;
   $job->product = $data->productValue;
   $job->portfolio = $data->portfolio;
   $job->discipline = $data->discipline;
   $job->costCenter = $data->costCenter;
   $job->title = $data->title;
   if (isset($data->creativeBrief)) $job->creativeBrief = $data->creativeBrief;
   $job->fieldMarketing = $data->fieldMarketing;
   if ($data->multipleCollateral == 'yes') $job->multipleCollateralType = $data->multipleCollateralType;
   $job->manager = $managers;
   
   array_push($this->jobData, $job);
  }

 }
 
 public function getJobs() {
  return $this->jobData;
 }
 
 protected function getCollateral($jobData) {
  $collateral = Array();
  $vars = get_object_vars($jobData->collateral);
  foreach($vars as $type => $dataArray) {
   foreach($dataArray as $data) {
    if ( property_exists($data, "useFieldMarketing") ) {
     $arr = array('type'=>$data->type, 'data'=>get_object_vars($data->data), 'useFieldMarketing'=>$data->useFieldMarketing);
    }
    else {
     $arr = array('type'=>$data->type, 'data'=>get_object_vars($data->data));
    }
    
    array_push($collateral, $arr);
   }
  }
  
  return $collateral;
 }
 
 private function validate($jobData) {
  $result = true;
  
  if (!property_exists($jobData, "data")) $result = false;
  else if(!property_exists($jobData->data,"collateral")) $result = false;
  
  return $result;
 }
}