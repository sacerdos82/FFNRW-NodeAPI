<?php

$api->get('/get/nodes/community/:communityID/format/netmon', function($communityID) use ($api) {

	$community = new db_communities($communityID);
	
	if(!$community->exists()) { 
		
		$errorMsg = returnError('E0003', 'API-Get-Node-Communities');
		$response['error'] = true;
		$response['code'] = $errorMsg['code'];
		$response['message'] = $errorMsg['message'];

		API_Response(200, $response);
		$api->stop();
	
	}


	$nodesDataset = array();

	$result = dbSQL('SELECT id FROM '. TBL_NODES .' WHERE community = "'. $community->getID() .'" ORDER BY id ASC');
	while($row = $result->fetch_object()) {

		$node = new db_nodes($row->id);
				
		if(!$node->hideOnMap()) {
			
			$nodesDataset[] = array(	'id'							=> $node->getID(),
										'name'							=> $node->getTheName(),
										'node_type'						=> 'AccessPoint',
										'href'							=> OPTION_NODEHREFBASE . '/' . $node->getHWID(),
										'status'						=> array(	'online'		=> $node->isActive(),
																					'clients'		=> $node->getClientsCount(),
																					'lastContact'	=> $node->getLastSeenWithTimezone() ),
										'position'						=> array(   'lat'			=> $node->getLatitude(),
																					'long'			=> $node->getLongitude() ) );
		
		}		
	}
	
	
	$now = new DateTime(date('Y-m-d H:i:s'));
	$now->SetTimezone(new DateTimeZone('Europe/Berlin'));

	$response = array(	'version'			=> '1.0.0',
						'updated_at'		=> $now->format('Y-m-d H:i:sP'),
						'community'			=> array(	'name' 	=> ucfirst($community->getTheName()),
														'href' 	=> $community->getHref() )	);
	
	$response['nodes'] = $nodesDataset;
	
	API_Response(200, $response);
	
});

?>