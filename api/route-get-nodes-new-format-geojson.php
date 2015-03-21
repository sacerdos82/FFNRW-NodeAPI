<?php

$api->get('/get/nodes/new/format/geojson', function() use ($api) {

	$geoJSON = array();
	
	$geoJSON['type'] = 'FeatureCollection';

	$timestamp = time() - (OPTION_WHATMEANSNEWINDAYS * 86400);
	$result = dbSQL('SELECT id FROM '. TBL_NODES .' WHERE firstseen > "'. $timestamp .'" AND firstseen != "0" ORDER BY id ASC');
	while($row = $result->fetch_object()) {

		$node = new db_nodes($row->id);
		
		if(!$node->hideOnMap()) {
			
			$geoJSON['features'][] = $node->getGeoJSON();	
		
		}						
		
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
		
		API_Response(200, $geoJSON);
		
	}
	
});

?>