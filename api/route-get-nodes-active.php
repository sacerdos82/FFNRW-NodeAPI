<?php

$api->get('/get/nodes/active', function() use ($api) {

	$nodesDataset = array();

	$timestamp = time() - (OPTION_WHATMEANSACTIVEINMINUTES * 60);
	$result = dbSQL('SELECT id FROM '. TBL_NODES .' WHERE lastseen > "'. $timestamp .'" ORDER BY id ASC');
	while($row = $result->fetch_object()) {

		$node = new db_nodes($row->id);
				
		$nodesDataset[] = $node->getDataset();
								
	}
	
	
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
		
		if(empty($nodesDataset)) { API_Response(200, ''); }
			else { API_Response(200, $nodesDataset); }
		
	}
	
});

?>