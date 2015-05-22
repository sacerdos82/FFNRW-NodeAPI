<?php

$api->get('/get/node/byFastD/:nodeFastD/activated', function($nodeFastD) use ($api) {

	$result = dbSQL('SELECT activated FROM '. TBL_NODES .' WHERE fastd = "'. $nodeFastD .'" LIMIT 1');
	
	if($result->num_rows == 0) { 
		
		$errorMsg = returnError('E0003', 'API-Get-Node-ByFastD-activated');
		$response['error'] = true;
		$response['code'] = $errorMsg['code'];
		$response['message'] = $errorMsg['message'];

		API_Response(200, $response);
		$api->stop();
	
	}
	
	$row = $result->fetch_object();
	
	if($row->activated == 1) { $activated = true; } else { $activated = false; }
	
	API_Response(200, $activated);
	
});

?>