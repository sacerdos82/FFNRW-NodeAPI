<?php

$api->get('/get/clients/count', function() use ($api) {

	$clients = 0;
	$timestamp = time() - (OPTION_WHATMEANSACTIVEINMINUTES * 60);

	$result = dbSQL('SELECT clients FROM '. TBL_NODES .' WHERE lastseen > "'. $timestamp .'" ORDER BY id ASC');
	while($row = $result->fetch_object()) {
		
		$clients = $clients + intval($row->clients);
		
	}
	
	API_Response(200, $clients);
		
});

?>