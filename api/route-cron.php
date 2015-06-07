<?php

$api->get('/cron/:key', function($key) use ($api) {

	if($key != 'yourTopSecretKey') {
		 
		API_Response(404, false);
		$api->stop();
	
	}

	$interval_in_minutes = 1620;
	$timestamp = time() - ($interval_in_minutes * 60);
	$result = dbSQL('SELECT id FROM '. TBL_NODES .' WHERE firstseen < "'. time() .'" AND firstseen > "'. $timestamp .'" ORDER BY id ASC');
	
	while($row = $result->fetch_object()) {
	
		$node = new db_nodes($row->id);
		$errorPayload = '';
	
		// Alternative Datenbankverbindung
		$db2 = new mysqli('localhost', 'root', '123', 'freifunk-nodes-dump');
		if($db2->connect_errno) { logToFile('mysql-db2-errors', 'Connection Error ' . $db2->connect_errno); } // Verbindungsfehler Protokollieren
		$db2->query("SET NAMES 'utf8'");
		
		
		// Prüfen ob Gluon Contact vorhanden und Freischaltungsmail schicken.
		if($node->isActivated()) {
			
			$errorPayload = 'API Freischaltungsfehler: Der Knoten '. $node->getID() . ' ist bereits freigeschaltet. Es wurde keine Email versandt.';
			
		} elseif($node->getContact() == '') { 
			
			$errorPayload = 'API Freischaltungsfehler: Keine Email-Adresse für Knoten '. $node->getID() . ' hinterlegt.';
			
		} elseif (!isValidEmail($node->getContact()))  {
			
			$errorPayload = 'API Freischaltungsfehler: Keine valide Email-Adresse für Knoten '. $node->getID() . ' hinterlegt. ('. $node->getContact() .')';
			
		} else {
		
			// ValidationHash erzeugen
			$validationHash = md5($node->getContact().time().randomString(32));
			$db2->query('UPDATE '. TBL_NODES .' SET validationHash = "'. $validationHash .'" WHERE id = "'. $node->getID() .'"');
			
			
			// Email senden
			$mail = new PHPMailer;
	
			$mail->isSMTP();
			$mail->Host 		= '';
			$mail->SMTPAuth		= true;
			$mail->Username 	= '';
			$mail->Password 	= '';
			$mail->SMTPSecure 	= 'tls';
			$mail->Port 		= 587;
			$mail->CharSet 		= "UTF-8";
			
			$mail->From 		= 'no-reply@vfn-nrw.de';
			$mail->FromName 	= 'Verbund freier Netzwerke NRW e.V.';
		
			$mail->addAddress($node->getContact());
			
			$mail->isHTML(false);
			
			$mail->Subject 	= '[Freifunk] Knotenfreischaltung | Bitte bestätige deine Email-Adresse';
			
			$message =	 	'Hallo,' ."\n"
							.	"\n"
							.	'wir freuen uns, dass du dich am Freifunk-Projekt mit einem Knoten beteiligen willst.' ."\n"
							.	"\n"
							.	'Um sicher zu stellen, dass wir dich im Notfall erreichen können, möchten wir dich bitten, deine Emailadresse mit einem Klick auf den folgenden Link zu bestätigen:' ."\n"
							.	"\n"
							.	'http://nodeapi.vfn-nrw.de/validate/'. $validationHash ."\n"
							.	"\n"
							.	'Vielen Dank.' ."\n"
							.	"\n"
							.	'Sobald deine Bestätigung eingegangen ist wird dein Knoten automatisch freigeschaltet.' ."\n"
							.	'Es kann sein, dass das ein paar Minuten dauert. Hab daher bitte ein wenig Geduld und lass deinen Knoten eingeschaltet.' ."\n"
							. 	"\n"
							.	'Wenn du noch Fragen hast oder etwas nicht funktioniert schreib uns bitte eine Email an info@vfn-nrw.de' ."\n"
							.	"\n"
							.	'Viel Spaß mit deinem neuen Knoten :)' ."\n";
													
			$mail->Body   	= $message;
			
			
			if(!$mail->send()) {
			
				$errorPayload = 'API Freischaltungsfehler: Email Knoten '. $node->getID() . ' konnte nicht gesendet werden. ('. $node->getContact() .' / '. $mail->ErrorInfo .')';
				logToFile('registration-mail-errors', 'Mail für ID '. $this->ID .' konnte nicht gesendet werden: '. $mail->ErrorInfo);
				return false;
			
			}
	
		}
		
		
		// WebHook an Slack senden
		$url = '';

		$payload = '[NodeAPI] Neuer Knoten in Community '. ucfirst($node->getCommunitySting()) .'.\n'
				 . 'ID: '. $node->getID() .'\n'
				 . 'HWID: '. $node->getHWID() .'\n'
				 . 'Name: '. $node->getTheName() .'\n'
				 . 'http://map.vfn-nrw.de/?'. $node->getLongitude() .'-'. $node->getLatitude() .'-19-'. $node->getID();
				 
		if($errorPayload != '') { $payload .= '\n\n'. $errorPayload; }
				 
		$fields = array( 'payload' => '{"text": "'. $payload .'"}' );
	
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 
		$response = curl_exec($ch);
		curl_close($ch);
		
		// API_Response(200, $payload);
		
	}
			
});

?>