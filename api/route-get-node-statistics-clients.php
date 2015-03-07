<?php

$api->get('/get/node/:nodeID/statistics/clients/:period', function($nodeID, $period) use ($api) {

	$nodeID = intval($nodeID);
	$node = new db_nodes($nodeID);
	
	$periods = array('day', 'week', 'month');
	if(!in_array($period, $periods)) {
		
		$errorMsg = returnError('E0005', 'API-Get-Node-Statistics-Client');
		$response['error'] = true;
		$response['code'] = $errorMsg['code'];
		$response['message'] = $errorMsg['message'];

		API_Response(200, $response);
		$api->stop();
		
	}
	
	if(!$node->exists()) { 
		
		$errorMsg = returnError('E0003', 'API-Get-Node-Statistics-Client');
		$response['error'] = true;
		$response['code'] = $errorMsg['code'];
		$response['message'] = $errorMsg['message'];

		API_Response(200, $response);
		$api->stop();
	
	}
	
	
	$nodeDataset = $node->getClientStatistics($period);
	
	
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