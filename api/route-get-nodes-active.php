<?php

$api->get('/get/nodes/active', function() use ($api) {

	$nodesDataset = array();

	$timestamp = time() - (OPTION_WHATMEANSACTIVEINMINUTES * 60);
	$result = dbSQL('SELECT id FROM '. TBL_NODES .' WHERE lastseen > "'. $timestamp .'" ORDER BY id ASC');
	while($row = $result->fetch_object()) {

		$node = new db_nodes($row->id);
				
		$nodesDataset[] = array(	'ID'							=> $node->getID(),
									'CommunityID'					=> $node->getCommunityID(),
									'Name'							=> $node->getTheName(),
									'HardwareType'					=> $node->getHardwareType(),
									'Latitude'						=> $node->getLatitude(),
									'Longitude'						=> $node->getLongitude(),
									'HideOnMap'						=> $node->hideOnMap(),
									'HWID'							=> $node->getHWID(),
									'IPV6'							=> $node->getIPV6(),
									'Build'							=> $node->getBuild(),
									'ClientsCount'					=> $node->getClientsCount(),
									'VPNActive'						=> $node->VPNActive(),
									'GatewayQuality'				=> $node->getGatewayQuality(),
									'LastSeen'						=> $node->getLastSeen(),
									'LastSeenWithTimezone'			=> $node->getLastSeenWithTimezone() );
								
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