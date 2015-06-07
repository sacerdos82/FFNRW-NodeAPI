<?php

$api->get('/get/node/byFastD/:nodeFastD/communityString', function($nodeFastD) use ($api) {

	$result = dbSQL('SELECT community_txt FROM '. TBL_NODES .' WHERE fastd = [u] "'. $nodeFastD .'" [u] LIMIT 1');
	
	if($result->num_rows == 0) { 
		
		$errorMsg = returnError('E0003', 'API-Get-Node-ByFastD-hwid');
		$response['error'] = true;
		$response['code'] = $errorMsg['code'];
		$response['message'] = $errorMsg['message'];

		API_Response(200, $response);
		$api->stop();
	
	}
	
	$row = $result->fetch_object();

	API_Response(200, $row->community_txt);
	
});

?>