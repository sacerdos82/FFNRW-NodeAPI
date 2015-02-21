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
	

	if($community->getScreenname() != '') { $screenname = $community->getScreenname(); } else { $screenname = 'Freifunk '. ucfirst($community->getTheName()); }
	
	$now = new DateTime(date('Y-m-d H:i:s'));
	$now->SetTimezone(new DateTimeZone('Europe/Berlin'));
	
	$response['name'] = $screenname;
	
	if($community->getURL() != '') { $response['url'] = $community->getURL(); }
	
	$response['location'] = array(	'city'			=> ucfirst($community->getTheName()),
									'country'		=> 'DE',
									'lat'			=> floatval($community->getLatitude()),
									'lon'			=> floatval($community->getLongitude()) );

	$response['contact'] = array();
	if($community->getEmail() != '') { $response['contact']['email'] = $community->getEmail(); }
	if($community->getPhone() != '') { $response['contact']['phone'] = $community->getPhone(); }
	if($community->getFacebook() != '') { $response['contact']['facebook'] = $community->getFacebook(); }
	if($community->getTwitter() != '') { $response['contact']['twitter'] = $community->getTwitter(); }
	
	$response['state']['nodes'] = intval($community->getNodesCount());
	if($community->getDescription() != '') { $response['state']['description'] = $community->getDescription(); }
	$response['state']['focus'] = array( 'Public Free Wifi', 'Social Community Building', 'Free internet access' );
	$response['state']['lastchange'] = $now->format('Y-m-d H:i:sP');
	
	$response['nodeMaps'] = array(	/* array(	'url'	=> OPTION_NODEMAPBASE .'/'. $community->getLatitude() .','. $community->getLongitude() .',17',
											'technicalType'		=> 'batmap',
											'mapType'			=> 'geographical' ), */
									array(	'url'				=> __URL__ .'/index.php/get/nodes/community/'. $community->getID() . '/format/netmon',
											'technicalType'		=> 'nodelist',
											'mapType'			=> 'list/status' ) );
	
	$response['techDetails']['firmware']['name'] = 'Lübecker Firmware (Pre-Gluon) / Gluon';
	$response['techDetails']['routing'] = array( 'batman-adv' );
	$response['techDetails']['legals'] = array( 'vpnnational', 'vpninternational' );
	
	$response['api'] = '0.4.6';
	
	
	API_Response(200, $response);
	
});

?>