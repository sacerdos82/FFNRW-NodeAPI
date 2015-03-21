<?php

$api->get('/get/nodes/active/meshlinks/format/geojson/', function() use ($api) {

	$geoJSON = array();
	
	$geoJSON['type'] = 'FeatureCollection';

	$timestamp = time() - (OPTION_WHATMEANSACTIVEINMINUTES * 60);
	$result = dbSQL('SELECT id FROM '. TBL_NODES .' WHERE lastseen > "'. $timestamp .'" ORDER BY id ASC');
	while($row = $result->fetch_object()) {

		$node = new db_nodes($row->id);
		
		foreach($node->getMeshlinks() as $meshlink) {
			
			$meshnode = new db_nodes($meshlink['NodeID']);
		
			$lengthInMeters = measureFromCoordinates(floatval($node->getLongitude()), floatval($node->getLatitude()), floatval($meshnode->getLongitude()), floatval($meshnode->getLatitude()));
		
			if(!$meshnode->hideOnMap() && $meshnode->isActive()) {
				
				$geoJSON['features'][] = 	array(	'type'			=> 'Feature',
													'geometry'		=> 	array( 	'type'			=> 'LineString',
																				'coordinates'	=> 	array( 
																										array( 	floatval($node->getLongitude()), 
																												floatval($node->getLatitude()) 
																										),
																										array(  floatval($meshnode->getLongitude()), 
																												floatval($meshnode->getLatitude()) 
																										)
																									)
																		),
													'properties'	=> 	array(	'id'				=> $meshnode->getID(),
																				'name'				=> $meshnode->getTheName(),
																				'active'			=> $meshnode->isActive(),
																				'linkQuality'		=> $meshlink['LinkQuality'],
																				'lengthInMeters'	=> $lengthInMeters
																		)
											);		
			
			}						
			
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