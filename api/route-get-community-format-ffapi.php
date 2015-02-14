<?php

$api->get('/get/community/:communityID/format/ffapi', function($communityID) use ($api) {

	$communtyID = intval($communityID);
	$community = new db_communities($communityID);
	
	
	if(!$community->exists()) { 
		
		$errorMsg = returnError('E0004', 'API-Get-Community');
		$response['error'] = true;
		$response['code'] = $errorMsg['code'];
		$response['message'] = $errorMsg['message'];

		API_Response(200, $response);
		$api->stop();
	
	}
	
	
	$now = new DateTime(date('Y-m-d H:i:s'));
	$now->SetTimezone(new DateTimeZone('Europe/Berlin'));

	$response = array(	'name'				=> 'Freifunk '. ucfirst($community->getTheName()),
						'url'				=> $community->getHref(),

						'location'			=> array(	'city'			=> ucfirst($community->getTheName()),
														'country'		=> 'DE',
														'lat'			=> $community->getLatitude(),
														'lon'			=> $community->getLongitude() ),

						'contact'			=> array(	'email'			=> $community->getEmail() ),

						'state'				=> array(	'nodes'			=> $community->getNodesCount(),
														'lastchange'	=> $now->format('Y-m-d H:i:sP') ),
														
						'nodeMaps'			=> array(	array(	'url'				=> OPTION_NODEMAPBASE .'/'. $community->getLatitude() .','. $community->getLongitude() .',17',
																'technicalType'		=> 'batmap',
																'mapType'			=> 'geographical' ),
														array(	'url'				=> __URL__ .'/index.php/get/nodes/community/'. $community->getID() . '/format/netmon',
																'technicalType'		=> 'netmon',
																'mapType'			=> 'list/status' ) ),
																
						'api'				=> '0.4.6' );
	
	API_Response(200, $response);
	
});

?>