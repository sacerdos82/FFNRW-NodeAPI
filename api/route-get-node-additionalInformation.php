<?php

$api->get('/get/node/:nodeID/additionalInformation', function($nodeID) use ($api) {

	$nodeID = intval($nodeID);
	$node = new db_nodes($nodeID);
	
	
	if(!$node->exists()) { 
		
		$errorMsg = returnError('E0003', 'API-Get-Node-AdditionalInformation');
		$response['error'] = true;
		$response['code'] = $errorMsg['code'];
		$response['message'] = $errorMsg['message'];

		API_Response(200, $response);
		$api->stop();
	
	}
	
	
	$nodeDataset = $node->getAdditionalInformation();
	
	
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
		
		API_Response(200, $nodeDataset);
		
	}
	
});

?>