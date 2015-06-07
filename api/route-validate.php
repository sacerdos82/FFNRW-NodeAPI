<?php

$api->get('/validate/:validationHash', function($validationHash) use ($api) {

	$result = dbSQL('SELECT id FROM '. TBL_NODES .' WHERE validationhash = [u] "'. $validationHash .'" [u] ORDER BY id ASC LIMIT 1');
		
	if($result->num_rows == 0) { 
		
		$errorMsg = returnError('E0003', 'API-Validation');
		$response['error'] = true;
		$response['code'] = $errorMsg['code'];
		$response['message'] = $errorMsg['message'];

		API_Response(200, $response);
		$api->stop();
	
	}
	
	$row = $result->fetch_object();
	$node = new db_nodes($row->id);
	
	// Alternative Datenbankverbindung
	$db2 = new mysqli('localhost', 'root', '123', 'freifunk-nodes-dump');
	if($db2->connect_errno) { logToFile('mysql-db2-errors', 'Connection Error ' . $db2->connect_errno); } // Verbindungsfehler Protokollieren
	$db2->query("SET NAMES 'utf8'");
	
	
	// Freischalten
	$db2->query('UPDATE '. TBL_NODES .' SET activated = "1", validationhash = "" WHERE id = "'. $node->getID() .'"');
	

	// Email senden
	$mail = new PHPMailer;

	$mail->isSMTP();
	$mail->Host 		= 'we70a.netcup.net';
	$mail->SMTPAuth		= true;
	$mail->Username 	= 'web570p7';
	$mail->Password 	= 'Pk4jBKXx';
	$mail->SMTPSecure 	= 'tls';
	$mail->Port 		= 587;
	$mail->CharSet 		= "UTF-8";
	
	$mail->From 		= 'no-reply@vfn-nrw.de';
	$mail->FromName 	= 'Verbund freier Netzwerke NRW e.V.';

	$mail->addAddress($node->getContact());
	
	$mail->isHTML(false);
	
	$mail->Subject 	= '[Freifunk] Knotenfreischaltung erfolgreich';
	
	$message =	 	'Hallo,' ."\n"
					.	"\n"
					.	'Dein Knoten wurde erfolgreich freigeschaltet.' ."\n"
					.	"\n"
					.	'Detailinformationen kannst du unter:' ."\n"
					.	"\n"
					.	'http://map.vfn-nrw.de/?'. $node->getLongitude() .'-'. $node->getLatitude() .'-19-'. $node->getID() ."\n"
					.	"\n"
					.	'anrufen.' ."\n"
					.	"\n"
					.	'Wenn du noch Fragen hast oder etwas nicht funktioniert schreib uns bitte eine Email an info@vfn-nrw.de' ."\n"
					.	"\n"
					.	'Viel Spaß mit deinem neuen Knoten :)' ."\n";
											
	$mail->Body   	= $message;
	
	
	if(!$mail->send()) {
	
		$errorPayload = 'API Freischaltungsfehler: Email Knoten '. $node->getID() . ' konnte nicht gesendet werden. ('. $node->getContact() .' / '. $mail->ErrorInfo .')';
		logToFile('registration-mail-errors', 'Mail für ID '. $this->ID .' konnte nicht gesendet werden: '. $mail->ErrorInfo);
		return false;
	
	}
	
	
	// WebHook an Slack senden
	$url = 'https://hooks.slack.com/services/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';

	$payload = '[NodeAPI] Knoten '. $node->getID() .' wurde erfolgreich für Community '. ucfirst($node->getCommunitySting()) .' freigeschaltet.';
			 
	$fields = array( 'payload' => '{"text": "'. $payload .'"}' );

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
	$response = curl_exec($ch);
	curl_close($ch);

	
	API_Response(200, 'Vielen Dank, dass du deine Email-Adresse verifiziert hast. Dein Knoten wird nun freigeschaltet.');
					
});

?>