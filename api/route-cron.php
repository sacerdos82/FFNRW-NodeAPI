<?php

$api->get('/cron/:key', function($key) use ($api) {

	if($key != 'defineYourTopSecretKeyHere') {
		 
		API_Response(404, false);
		$api->stop();
	
	}

	$interval_in_minutes = 420;
	$timestamp = time() - ($interval_in_minutes * 60);
	$result = dbSQL('SELECT id FROM '. TBL_NODES .' WHERE firstseen < "'. time() .'" AND firstseen > "'. $timestamp .'" ORDER BY id ASC');
	
	while($row = $result->fetch_object()) {
	
		$node = new db_nodes($row->id);
	
		// WebHook an Slack senden
		$url = 'https://hooks.slack.com/services/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';

		$payload = 'Neuer Knoten in Community '. ucfirst($node->getCommunitySting()) .'.\n'
				 . 'ID: '. $node->getID() .'\n'
				 . 'HWID:'. $node->getHWID() .'\n'
				 . 'Name: '. $node->getTheName() .'\n'
				 . 'http://map.vfn-nrw.de/?'. $node->getLongitude() .'-'. $node->getLatitude() .'-19-'. $node->getID();
				 
		$fields = array( 'payload' => '{"text": "'. $payload .'"}' );
	
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 
		$response = curl_exec($ch);
		curl_close($ch);
		
	}
	
	API_Response(200, true);
		
});

?>