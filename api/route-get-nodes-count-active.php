<?php

$api->get('/get/nodes/count/active', function() use ($api) {

	$timestamp = time() - (OPTION_WHATMEANSACTIVEINMINUTES * 60);
	$result = dbSQL('SELECT COUNT(*) AS nodes FROM '. TBL_NODES .' WHERE lastseen > "'. $timestamp .'" ORDER BY id ASC');
	$row = $result->fetch_object();
	API_Response(200, $row->nodes);
		
});

?>