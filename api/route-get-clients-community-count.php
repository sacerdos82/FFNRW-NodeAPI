<?php

$api->get('/get/community/:communtiyID/clients/count', function($communityID) use ($api) {

	$clients = 0;
	$timestamp = time() - (OPTION_WHATMEANSACTIVEINMINUTES * 60);
	
	$community = new db_communities($communityID);
	
	if(!$community->exists()) { 
		
		$errorMsg = returnError('E0003', 'API-Get-Node-Communities-Count');
		$response['error'] = true;
		$response['code'] = $errorMsg['code'];
		$response['message'] = $errorMsg['message'];

		API_Response(200, $response);
		$api->stop();
	
	}

	$result = dbSQL('SELECT clients FROM '. TBL_NODES .' WHERE community = "'. $community->getID() .'" AND lastseen > "'. $timestamp .'" ORDER BY id ASC');
	while($row = $result->fetch_object()) {
		
		$clients = $clients + intval($row->clients);
		
	}
	
	API_Response(200, $clients);
		
});

?>