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

class fmJob extends model {
	
	private $typeData;
	private $uploadPath;
	
	public $medium;
	public $PDT;
	public $copyright;
	public $type;
	public $job_num;
	public $orig_job_number;
	public $campaign_id;
	public $title;
	public $discipline;
	public $requestor;
	public $notes;
	public $cost_center;
	public $state;
	public $case_number;
	public $linked_opportunity;
	public $field_marketing_f;
	public $designer;
	public $delivery_date;
	public $quantity;
	public $bind_order_num;
	public $print_dept;
	public $page_count;
	public $flat_size;
	public $finish_size;
	public $shipping;
	public $in_warehouse_f;
	public $package_f;
	public $package_part_nums;
	public $req_date;
	public $start_date;
	public $deployment_date;
	public $closed_date;
	public $cover_inks;
	public $cover_colors;
	public $cover_coatings;
	public $inside_inks;
	public $inside_colors;
	public $inside_coatings;
	public $paper_inside_weight;
	public $paper_inside_finish;
	public $paper_inside_grade;
	public $paper_cover_weight;
	public $paper_cover_finish;
	public $paper_cover_grade;
	public $project_options;
	public $paper_notes;
	public $binding;
	public $special_handling;
	public $account;
	public $first_design_aprv_date;
	public $design_aprv_date;
	public $concept_aprv_date;
	public $purpose;
	public $objective;
	public $message;
	public $prod_category;
	public $email_target_aud;
	public $email_states;
	public $email_grades;
	public $email_blackout;
	public $email_bus_objective;
	public $email_other_collateral;
	public $email_list_source;
	public $email_other;
	public $email_subject;
	public $email_body;
	public $email_action;
	public $media_URL;
	public $media_network;
	public $media_keywords;
	public $media_orgs;
	public $webinar_start_time;
	public $webinar_end_time;
	public $webinar_presenter_name;
	public $webinar_presenter_email;
	public $webinar_presenter_title;
	public $webinar_presenter_org;
	public $webinar_presenter_photo;
	public $webinar_presenter_num_add;
	public $webinar_reg_info;
	public $primary_audience;
	public $seconday_audience;
	public $tertiary_audience;
	public $video_distrib_channel;
	public $months_on_market;
	public $video_communication_purpose;
	public $budget;
	public $microsite_cust_requests;
	public $microsite_more_Info_YN;
	public $microsite_reason;
	
	public $version_type;
	
	//public $revision_type;
	//public $revision_number;
	//public $print_project_details;
	//public $microsite_time_priority;
	//public $microsite_budget_priority;
	//public $microsite_quality_priority;
 //public $media_length;
 //public $campaign_end_date;
	
	public function parseDate($date) {
		$parsed = DateTime::createFromFormat('Y-m-d', $date);
  return $parsed->format('m/d/Y');
	}
	
	private function parseArray($data) {
		$result = "";
		foreach ($data as $object) {
			$result = $this->add($result,$object);
		}
		return $result;
	}
	
	private function add($list,$item) {
		return $list . ( $list !== "" ? "\n" : "") . $item;
	}
	
	public function getTypeData() {
		return $this->typeData;
	}
	
	public function setTypeData($data) {
		$this->typeData = $data;
	}
	
	public function getUploadPath() {
		return $this->uploadPath;
	}
	
	public function setUploadPath($uploadPath) {
		$this->uploadPath = $uploadPath;
	}
	
	public function parseTypeData() {
		switch ($this->medium) {
			case 'print-digital':
			 $this->medium = "Print/Digital";
		  $this->deployment_date = $this->parseDate($this->typeData['deliveryDate']);
		  $this->in_warehouse_f = $this->typeData['warehouse'] === 'yes' ? 1 : 0;
		  $this->quantity = $this->typeData['estimateQuantity'];
		  $this->version_type = $this->typeData['isRevision'] ? "Revision" : "Original";
		  //$this->revision_type = $this->typeData['revisionType'];
		  //$this->revision_number = $this->typeData['revisionNumber'];
		  $this->linked_opportunity = $this->typeData['opportunity'];
		  //$this->print_project_details = $this->typeData['specificProjectDetails'];
		  $this->package_part_nums = $this->typeData['productISBNs'];
		  $this->shipping = $this->typeData['shippingInstructions'];
		  break;
		  
		 case 'email':
		  $this->medium = "Email";
		  $this->version_type = "Original";
		  $this->deployment_date = $this->parseDate($this->typeData['deploymentDate']);
		  //$this->campaign_end_date = $this->parseDate($this->typeData['campaignEndDate']);
		  $this->email_target_aud = $this->typeData['targetAudience'];
		  $this->email_states = $this->parseArray($this->typeData['deploymentStates']);
		  $this->email_grades = $this->parseArray($this->typeData['schoolBuildings']);
		  $this->email_bus_objective = $this->parseArray($this->typeData['primaryObjectives']);
		  $this->email_list_source = $this->parseArray($this->typeData['listSources']);
		  $this->email_blackout = $this->typeData['blackOutTerritories'];
		  $this->email_subject = $this->typeData['subjectLine'];
		  $this->email_body = $this->typeData['bodyCopy'];
		  $this->email_action = $this->typeData['callToAction'];
		 break;
		 
		 case 'microsite-splash':
		  $this->medium = "Microsite";
		  $this->version_type = "Original";
		  $this->deployment_date = $this->parseDate($this->typeData['deploymentDate']);
		  $this->purpose = $this->typeData['deploymentDateFactors'];
		  $this->media_URL = $this->typeData['existingURL'];
		  $this->microsite_reason = $this->typeData['redesignReason'];
		  $this->microsite_more_Info_YN = $this->typeData['moreInfo'];
		  $this->microsite_cust_requests = $this->parseArray($this->typeData['customerRequests']);
		  $this->months_on_market = $this->typeData['monthsInMarket'];
		  $this->budget = $this->typeData['budget'];
		  $this->primary_audience = $this->typeData['primaryAudience'];
		  $this->seconday_audience = $this->typeData['secondaryAudience'];
		  $this->tertiary_audience = $this->typeData['tertiaryAudience'];
		  //$this->microsite_budget_priority = $this->typeData['priorities']->budget;
		  //$this->microsite_time_priority = $this->typeData['priorities']->time;
		  //$this->microsite_quality_priority = $this->typeData['priorities']->quality;
		 break;
		 
		 case 'socialmedia':
		  $this->medium = "Social Media";
		  $this->version_type = "Original";
		  $this->deployment_date = $this->parseDate($this->typeData['deploymentDate']);
		  //$this->media_length = $this->typeData['mediaLength'];
		  $this->media_URL = $this->typeData['link'];
		  $this->media_network = $this->typeData['networkPath'];
		  $this->prod_category = $this->parseArray($this->typeData['relatedCategories']);
		  $this->purpose = $this->parseArray($this->typeData['purposes']);
		  $this->message = $this->typeData['keyMessage'];
		  $this->media_keywords = $this->typeData['keywords'];
		  $this->media_orgs = $this->typeData['audiences'];
		 break;
		 
		 case 'video':
		  $this->medium = "Video";
		  $this->version_type = "Original";
		  $this->deployment_date = $this->parseDate($this->typeData['deploymentDate']);
		  $this->prod_category = $this->parseArray($this->typeData['relatedCategories']);
		  $this->video_communication_purpose = $this->parseArray($this->typeData['purposes']);
		  $this->primary_audience = $this->typeData['primaryAudience'];
		  $this->seconday_audience = $this->typeData['secondaryAudience'];
		  $this->tertiary_audience = $this->typeData['tertiaryAudience'];
		  $this->purpose = $this->typeData['videoPurpose'];
		  $this->objective = $this->typeData['mainObjective'];
		  $this->message = $this->typeData['mainMessage'];
		  $this->video_distrib_channel = $this->parseArray($this->typeData['channels']);
		  $this->months_on_market = $this->typeData['monthsInMarket'];
		 break;
		 
		 case 'webinar':
		  $ai = $this->typeData['additionalInformation'];
		  $exit = "Exit-Survey";
		  $free = "Free-Resources";
		  $foll = "Follow-Up";
		  
		  $this->medium = "Webinar";
		  $this->version_type = "Original";
		  $this->deployment_date = $this->parseDate($this->typeData['deploymentDate']);
		  $this->webinar_presenter_name = $this->typeData['presenter']->Name;
		  $this->webinar_presenter_email = $this->typeData['presenter']->Email;
		  $this->webinar_presenter_title = $this->typeData['presenter']->Title;
		  $this->webinar_presenter_org = $this->typeData['presenter']->Organization;
		  $this->webinar_reg_info = $ai->CEU ? "CEU Credit" : "";
		  $this->webinar_reg_info = $this->add($this->webinar_reg_info,( $ai->Incentives ? "Attendee Incentives" : "" ));
		  $this->webinar_reg_info = $this->add($this->webinar_reg_info,( $ai->Polling ? "Polling Questions" : "" ));
		  $this->webinar_reg_info = $this->add($this->webinar_reg_info,( $ai->$exit ? "Exit Survey" : "" ));
		  $this->webinar_reg_info = $this->add($this->webinar_reg_info,( $ai->$free ? "Free Resources" : "" ));
		  $this->webinar_reg_info = $this->add($this->webinar_reg_info,( $ai->$foll ? "Follow-Up" : "" ));
		  $this->webinar_reg_info = $this->add($this->webinar_reg_info,( $ai->HotLeads ? "Leads" : "" ));
		  $this->webinar_start_time = $this->typeData['startTime'];
		  $this->webinar_end_time = $this->typeData['endTime'];
		  $this->title = $this->typeData['title'];
		  $this->purpose = $this->parseArray($this->typeData['purposes']);
		  $this->objective = $this->typeData['objective'];
		  $this->webinar_presenter_num_add = $this->typeData['additionalPeople'];
		  if (isset($this->typeData['photo'])) {
			  //print_r($this->typeData['photo']);
			  $base64 = $this->base64file($this->uploadPath . $this->typeData['photo'][0]);
			  $this->objective = $base64;
			  $this->webinar_presenter_photo = $base64; 
		  }
		  // = $this->typeData['photo'];
		 break;
		 
		}
	}

}
