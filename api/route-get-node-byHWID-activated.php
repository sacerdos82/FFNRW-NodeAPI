<?php

$api->get('/get/node/byHWID/:nodeHWID/activated', function($nodeHWID) use ($api) {

	$result = dbSQL('SELECT activated FROM '. TBL_NODES .' WHERE hwid = "'. $nodeHWID .'" LIMIT 1');
	
	if($result->num_rows == 0) { 
		
		$errorMsg = returnError('E0003', 'API-Get-Node-ByHWID-activated');
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