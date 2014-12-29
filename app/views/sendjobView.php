<?php

class sendjobView extends httpView {
 public function __construct() {
  parent::__construct(HTTP_VM_HTML);
  $this->mustache->addHelper('jobType', 
    function($value) { 
     $type = ucwords($value);
     $type = str_replace(" ", "|", $type);
     $type = str_replace("-", " ", $type);
     $type = ucwords($type);
     $type = str_replace(" ", "/", $type);
     $type = str_replace("|", " ", $type);
     return $type;
    }
  );

 }
 
 public function jobToJson($job) {
	 return json_encode(array("data"=>array($job)));
 }
 
 public function renderJobs($jobs, $sessionId) {
  $jobHTMLArray = Array();
  foreach($jobs as $job) {
    array_push($jobHTMLArray, $this->renderJob($job));
  }
  $strong = 'strong';
  $result = $this->mustache->render('jobs', 
   array(
    'jobs'=>$jobHTMLArray, 
    'sessionId'=>$sessionId,
    'strong'=> function ($text, Mustache_LamdaHelper $helper) {
     return $helper->render('> strong',$text);
    } 
    )
  );
  
  return $result;
 }
 
 private function renderJob($job) {
  //return $job->type;
  
  return $this->mustache->render($job->type,$job);
 }
}