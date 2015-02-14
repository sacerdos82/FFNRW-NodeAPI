<?php

$api->get('/get/community/:communityID', function($communityID) use ($api) {

	$communtyID = intval($communityID);
	$community = new db_communities($communityID);
	
	
	if(!$community->exists()) { 
		
		$errorMsg = returnError('E0004', 'API-Get-Community');
		$response['error'] = true;
		$response['code'] = $errorMsg['code'];
		$response['message'] = $errorMsg['message'];

		API_Response(200, $response);
		$api->stop();
	
	}
	
	
	$communityDataset = array(	'ID'							=> $community->getID(),
								'Name'							=> $community->getTheName(),
								'Latitude'						=> $community->getLatitude(),
								'Longitude'						=> $community->getLongitude(),
								'Href' 							=> $community->getHref(),
								'Email'							=> $community->getEmail() );
	
	
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
		
		API_Response(200, $communityDataset);
		
	}
	
});

?>