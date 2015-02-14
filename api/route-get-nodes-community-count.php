<?php

$api->get('/get/nodes/community/:communityID/count', function($communityID) use ($api) {

	$community = new db_communities($communityID);
	
	if(!$community->exists()) { 
		
		$errorMsg = returnError('E0003', 'API-Get-Node-Communities-Count');
		$response['error'] = true;
		$response['code'] = $errorMsg['code'];
		$response['message'] = $errorMsg['message'];

		API_Response(200, $response);
		$api->stop();
	
	}

	$result = dbSQL('SELECT COUNT(*) AS nodes FROM '. TBL_NODES .' WHERE community = "'. $community->getID() .'" ORDER BY id ASC');
	$row = $result->fetch_object();
	API_Response(200, $row->nodes);
		
});

?>