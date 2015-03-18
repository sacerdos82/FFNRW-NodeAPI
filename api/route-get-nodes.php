<?php

$api->get('/get/nodes', function() use ($api) {

	$nodesDateset = array();

	$result = dbSQL('SELECT id FROM '. TBL_NODES .' ORDER BY id ASC');
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
		
		API_Response(200, $nodesDataset);
		
	}
	
});

?>