<?php

$api->get('/get/nodes/format/geojson', function() use ($api) {

	$geoJSON = array();
	
	$geoJSON['type'] = 'FeatureCollection';

	$result = dbSQL('SELECT id FROM '. TBL_NODES .' ORDER BY id ASC');
	while($row = $result->fetch_object()) {

		$node = new db_nodes($row->id);
		
		if(!$node->hideOnMap()) {
			
			$geoJSON['features'][] = 	array(	'type'			=> 'Feature',
												'geometry'		=> 	array( 	'type'			=> 'Point',
																			'coordinates'	=> 	array( 	floatval($node->getLongitude()), 
																										floatval($node->getLatitude()) 
																								) 
																	),
												'properties'	=> 	array(	'id'			=> $node->getID(),
																			'name'			=> $node->getTheName(),
																			'active'		=> $node->isActive()
																	)
										);		
		
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