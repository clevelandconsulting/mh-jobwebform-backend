<?php

require_once('object.php');

class model extends framework_object {
 
 protected function base64File($path) {
	 $type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		//return 'data:image/' . $type . ';base64,' . base64_encode($data);
		return base64_encode($data);
 }
 
}
