<?php

$api->get('/get/nodes/count', function() use ($api) {

	$result = dbSQL('SELECT COUNT(*) AS nodes FROM '. TBL_NODES .' ORDER BY id ASC');
	$row = $result->fetch_object();
	API_Response(200, $row->nodes);
		
});

?>