<?php

$api->get('/get/communities', function() use ($api) {

	$communitiesDataset = array();

	$result = dbSQL('SELECT id FROM '. TBL_COMMUNITIES .' ORDER BY id ASC');
	while($row = $result->fetch_object()) {	
	
		$community = new db_communities($row->id);
	
		$communitiesDataset[] = array(	'ID'							=> $community->getID(),
										'Name'							=> $community->getTheName(),
										'Latitude'						=> $community->getLatitude(),
										'Longitude'						=> $community->getLongitude() );
	
	}
	
	
	if(isset($_SESSION['errors'])) {
		
		$codes = '';
		$messages = '';
		foreach($_SESSION['errors'] as $error) {
			
			$codes .= $error['code'] .' | ';
			$messages .= $error['message'] .' | ';
			
		}
		
		$response['error'] = true;
		$response['code'] = $codes;
		$response['message'] = $messages;
		
		API_Response(200, $response);
		$api_internal->stop();
		
	} else {
		
		API_Response(200, $communitiesDataset);
		
	}
	
});

?>