<?php

class db_nodes {
	
	private $ID;
	private $communityID;
	private $name;
	private $hardwareType;
	private $latitude;
	private $longitude;
	private $hideOnMap;
	private $hwid;
	private $ipv6;
	private $build;
	private $clients;
	private $VPNActive;
	private $gatewayQuality;
	private $lastSeen;
	private $lastSeenDifference;
	private $additionalInformation;
		
	
	// Variablen die keine Entsprechung in der Datenbank haben
	private $exists;
	
	
	public function __construct($ID = '') { 
		
		$this->exists = true;
		
		if($ID != '') {			
			
			$result = dbSQL('SELECT id,
									community,
									name,
									hardware_type,
									lat,
									lon,
									hideonmap,
									hwid,
									ip,
									build,
									clients,
									vpnactive,
									gwq,
									lastseen,
									additional_information FROM '. TBL_NODES .' WHERE ID = [u] "'. $ID . '" [u]', 'ALL');				
			
			
			// Fehlermeldung, wenn keine Abfrage zustande kam
			if(!$row = $result->fetch_object()) { $this->exists = false; return false; }
			
			
			// Daten in Klasse laden
			$this->ID 						= $row->id;
			$this->communityID				= $row->community;
			$this->name						= $row->name;
			$this->hardwareType				= $row->hardware_type;
			$this->latitude					= $row->lat;
			$this->longitude				= $row->lon;
			$this->hideOnMap				= $row->hideonmap;
			$this->hwid						= $row->hwid;
			$this->ipv6						= $row->ip;
			$this->build					= $row->build;
			$this->clients					= $row->clients;
			$this->VPNActive				= $row->vpnactive;
			$this->gatewayQuality			= $row->gwq;
			$this->additionalInformation	= $row->additional_information;
			
			$this->lastSeen	= new DateTime();
			$this->lastSeen->SetTimezone(new DateTimeZone('Europe/Berlin'));
			$this->lastSeen->SetTimestamp($row->lastseen);
			
			$difference = timespan(time() - $row->lastseen);
			$this->lastSeenDifference = $difference['d'] .' Tage, '. $difference['h'] .' Stunden, '. $difference['m'] .' Minuten, '. $difference['s'] .' Sekunden';
	
		}
		
	}


	// Rückgabe, wenn kein Mitglied gefunden wurde
	public function exists() { return $this->exists; }
	
	
	// Variablen auslesen
	public function getID() 					{ return $this->ID; }
	public function getCommunityID() 			{ return $this->communityID; }
	public function getTheName()				{ return $this->name; }
	public function getHardwareType()			{ return $this->hardwareType; }
	public function getLatitude()				{ return $this->latitude; }
	public function getLongitude()				{ return $this->longitude; }
	
	public function hideOnMap()	{ 
		
		if($this->hideOnMap == '1') { return true; } 
		elseif($this->latitude == 0 && $this->longitude == 0) { return true; } 
		else { return false; }	
	
	}
	
	public function hideOnMapRAW() 				{ if($this->hideOnMap == '1') { return true; } else { return false; } }
	public function getHWID()					{ return $this->hwid; }
	public function getIPV6()					{ return $this->ipv6; }
	public function getBuild()					{ return $this->build; }
	public function getClientsCount()			{ if($this->isActive()) { return $this->clients; } else { return '0'; } }
	public function VPNActive()					{ if($this->VPNActive == '1') { return true; } else { return false; } }
	public function getGatewayQuality()			{ return $this->gatewayQuality; }
	
	public function getLastSeen()				{ return $this->lastSeen->format('Y-m-d H:i:s'); }
	public function getLastSeenWithTimezone()	{ return $this->lastSeen->format('Y-m-d H:i:sP'); }
	public function getLastSeenDifference()		{ return $this->lastSeenDifference; }
	
	public function getCommunity() { 

		$community = new db_communities($this->communityID);
		return $community->getTheName(); 
		
	}
	
	
	public function isActive() {
		
		$timestamp = time() - (OPTION_WHATMEANSACTIVEINMINUTES * 60);
		if($this->lastSeen->getTimestamp() > $timestamp) {
			
			return true;
			
		} else { return false; }
		
	}
	
	
	public function getMeshlinks() {
		
		$meshlinks = array();
		
		$timestamp = time() - (OPTION_HOWLONGDOESAMESHLINKCOUNTINMINUTES * 60);
		
		$result = dbSQL('	SELECT '. TBL_MESHLINKS .'.to, '. TBL_MESHLINKS .'.tq, '. TBL_NODES .'.name 
							FROM '. TBL_MESHLINKS .' 
							LEFT JOIN '. TBL_NODES .' ON '. TBL_MESHLINKS .'.to = '. TBL_NODES .'.id  
							WHERE '. TBL_MESHLINKS .'.from = "'. $this->ID .'" 
							AND '. TBL_MESHLINKS .'.last > "'. $timestamp .'"');
		
		while($row = $result->fetch_object()) {
			
			$meshlinks[] = array(	'NodeID' 		=> $row->to,
									'LinkQuality'	=> $row->tq,
									'NodeName'		=> $row->name );
			
		}
		
		return $meshlinks;
		
	}
	
	
	public function getDataset() {
		
		$nodeDataset = array(	'ID'							=> $this->getID(),
								'CommunityID'					=> $this->getCommunityID(),
								'Name'							=> $this->getTheName(),
								'HardwareType'					=> $this->getHardwareType(),
								'Latitude'						=> $this->getLatitude(),
								'Longitude'						=> $this->getLongitude(),
								'HideOnMap'						=> $this->hideOnMap(),
								'HideOnMapRAW'					=> $this->hideOnMapRAW(),
								'HWID'							=> $this->getHWID(),
								'IPV6'							=> $this->getIPV6(),
								'Build'							=> $this->getBuild(),
								'ClientsCount'					=> $this->getClientsCount(),
								'VPNActive'						=> $this->VPNActive(),
								'GatewayQuality'				=> $this->getGatewayQuality(),
								'LastSeen'						=> $this->getLastSeen(),
								'LastSeenWithTimezone'			=> $this->getLastSeenWithTimezone(),
								'LastSeenDifference'			=> $this->getLastSeenDifference() );
								
		return $nodeDataset;
		
	}
	
	
	public function getGeoJSON() {
		
		$geoJSON =	array(	'type'			=> 'Feature',
							'geometry'		=> 	array( 	'type'				=> 'Point',
														'coordinates'		=> 	array( 	floatval($this->getLongitude()), 
																						floatval($this->getLatitude()) 
																						) 
												),
							'properties'	=> 	array(	'id'					=> $this->getID(),
														'hwid'					=> $this->getHWID(),
														'name'					=> $this->getTheName(),
														'active'				=> $this->isActive(),
														'lastSeen'				=> $this->getLastSeen(),
														'lastSeenDifference'	=> $this->getLastSeenDifference(),
														'firmwareBuild'			=> $this->getBuild(),
														'clients'				=> $this->getClientsCount(),
														'vpnActive'				=> $this->VPNActive(),
														'gatewayQuality'		=> $this->getGatewayQuality(),
														'ipv6'					=> $this->getIPV6()
												)								
					);	
					
		return $geoJSON;	
		
	}
	
	
	public function getAdditionalInformation() {
		
		// Prüfen ob Zusatzinformationen hinterlegt sind und ob es sich um eine URL handelt
		if($this->additionalInformation == NULL || $this->additionalInformation == '') { return false; }
		if(!isValidURL($this->additionalInformation)) { return false; }
		$fileheaders = @get_headers($this->additionalInformation); if($fileheaders[0] == 'HTTP/1.1 404 Not Found') { return false; }
		
		
		// Prüfen ob die hinterlegte Datei JSON-Code enthält und Inhalt einlesen
		if(!$additionalInformation = decodeJSONandValidate(file_get_contents($this->additionalInformation), true)) { return false; }
		
		// Prüfen ob die hinterlegte ID und HWID übereinstimmen
		if( !array_key_exists('ID', $additionalInformation) || !array_key_exists('HWID', $additionalInformation) ) { return false; } 		
		if($additionalInformation['ID'] != $this->ID || $additionalInformation['HWID'] != $this->hwid) { return false; }
		
		// Ausgabearray aufbauen
		$output = array();
		
		$lastModified = new DateTime();
		$lastModified->SetTimezone(new DateTimeZone('Europe/Berlin'));
		$lastModifiedTimestamp = strtotime(substr($fileheaders[3], 15));
		$lastModified->SetTimestamp($lastModifiedTimestamp);
		$output['LastModified'] = $lastModified->format('Y-m-d H:i:s');
		
		if(array_key_exists('Owner', $additionalInformation)) 			{ $output['Owner'] = cleanInputFromCode($additionalInformation['Owner']); } 				else { $output['Owner'] = ''; }
		if(array_key_exists('Street', $additionalInformation)) 			{ $output['Street'] = cleanInputFromCode($additionalInformation['Street']); } 				else { $output['Street'] = ''; }
		if(array_key_exists('Zip', $additionalInformation)) 			{ $output['Zip'] = cleanInputFromCode($additionalInformation['Zip']); } 					else { $output['Zip'] = ''; }
		if(array_key_exists('City', $additionalInformation)) 			{ $output['City'] = cleanInputFromCode($additionalInformation['City']); } 					else { $output['City'] = ''; }
		if(array_key_exists('Phone', $additionalInformation)) 			{ $output['Phone'] = cleanInputFromCode($additionalInformation['Phone']); } 				else { $output['Phone'] = ''; }
		if(array_key_exists('Email', $additionalInformation)) 			{ $output['Email'] = cleanInputFromCode($additionalInformation['Email']); } 				else { $output['Email'] = ''; }
																																									
		if(array_key_exists('Website', $additionalInformation) 																										
			&& isValidURL($additionalInformation['Website']))			{ $output['Website'] = $additionalInformation['Website']; } 								else { $output['Website'] = ''; }
		
		if(array_key_exists('OpeningHours', $additionalInformation)) { 
			
			if(array_key_exists('Monday', $additionalInformation['OpeningHours'])) 			{ $output['OpeningHours']['Monday'] = cleanInputFromCode($additionalInformation['OpeningHours']['Monday']); } 			else { $output['OpeningHours']['Monday'] = ''; }
			if(array_key_exists('Tuesday', $additionalInformation['OpeningHours'])) 		{ $output['OpeningHours']['Tuesday'] = cleanInputFromCode($additionalInformation['OpeningHours']['Tuesday']); } 		else { $output['OpeningHours']['Tuesday'] = ''; }
			if(array_key_exists('Wednesday', $additionalInformation['OpeningHours'])) 		{ $output['OpeningHours']['Wednesday'] = cleanInputFromCode($additionalInformation['OpeningHours']['Wednesday']); } 	else { $output['OpeningHours']['Wednesday'] = ''; }
			if(array_key_exists('Thursday', $additionalInformation['OpeningHours'])) 		{ $output['OpeningHours']['Thursday'] = cleanInputFromCode($additionalInformation['OpeningHours']['Thursday']); } 		else { $output['OpeningHours']['Thursday'] = ''; }
			if(array_key_exists('Friday', $additionalInformation['OpeningHours'])) 			{ $output['OpeningHours']['Friday'] = cleanInputFromCode($additionalInformation['OpeningHours']['Friday']); } 			else { $output['OpeningHours']['Friday'] = ''; }
			if(array_key_exists('Saturday', $additionalInformation['OpeningHours'])) 		{ $output['OpeningHours']['Saturday'] = cleanInputFromCode($additionalInformation['OpeningHours']['Saturday']); } 		else { $output['OpeningHours']['Saturday'] = ''; }
			if(array_key_exists('Sunday', $additionalInformation['OpeningHours'])) 			{ $output['OpeningHours']['Sunday'] = cleanInputFromCode($additionalInformation['OpeningHours']['Sunday']); } 			else { $output['OpeningHours']['Monday'] = ''; }
			
		} else { 
			
			$output['OpeningHours']['Monday'] = '';
			$output['OpeningHours']['Tuesday'] = '';
			$output['OpeningHours']['Wednesday'] = '';
			$output['OpeningHours']['Thursday'] = '';
			$output['OpeningHours']['Friday'] = '';
			$output['OpeningHours']['Saturday'] = '';
			$output['OpeningHours']['Sunday'] = '';
			
		}
		
		if(array_key_exists('Description', $additionalInformation)) 	{ $output['Description'] = cleanInputFromCode($additionalInformation['Description']); } 	else { $output['Description'] = ''; }
		
		if(array_key_exists('LogoURL', $additionalInformation) && isValidURL($additionalInformation['LogoURL'])) { 
			
			$imageheaders = @get_headers($additionalInformation['LogoURL']);
			if($imageheaders[0] != 'HTTP/1.1 404 Not Found') {
				
				$imagetest = @getimagesize($additionalInformation['LogoURL']); 
				if(!$imagetest) { $output['LogoURL'] = ''; } else { $output['LogoURL'] = $additionalInformation['LogoURL']; }
			
			} else { $output['LogoURL'] = ''; }
		
		} else { $output['LogoURL'] = ''; }
		
		if(array_key_exists('News', $additionalInformation) && is_array($additionalInformation['News'])) {
			
			$output['News'] = array(); 
			foreach($additionalInformation['News'] as $news) {
			
				if(array_key_exists('Date', $news) && array_key_exists('Headline', $news) && array_key_exists('Description', $news)) { 
				
					$output['News'][] = array(	'Date'			=> cleanInputFromCode($news['Date']),
												'Headline'		=> cleanInputFromCode($news['Headline']),
												'Description'	=> cleanInputFromCode($news['Description'])
										);
										
				}
				
			}
			
		} else { $output['News'] = array(); } 
		
		$output['Disclaimer'] = 'Diese Informationen werden vom Eigner des Knotens '. $this->ID .' auf dessen eigenen Servern zur Verfügung gestellt. '.
								'Der Verbund freier Netzwerke NRW e.V. liefert diese Informationen nur aus und ist nicht für deren Inhalt verantwortlich. '. 
								'Verantwortlich für den Inhalt sind ausschließlich der Eigner des Knotens, sowie der Inhaber/Betreiber des Servers, der diese Informationen zur Verfügung stellt. '.
								'Unter dem Punkt "Origin" wird die Orignal-URL der dieser Ausgabe zugrunde liegenden JSON Datei genannt.';
								
		$output['Origin'] = $this->additionalInformation;
			
		return $output;
		
	}
	
	
	public function getClientStatistics($option = 'day') {
		
		$end = time();
		
		switch($option) {
			
			case 'day': 		$start = time() - 86400; 			break;
			case 'week':		$start = time() - (7 * 86400); 		break;
			case 'month':		$start = time() - (30 * 86400); 	break;
			
			default: 			$start = time() - 86400; 			break;
			
		}
		
		$result = 	rrd_fetch( RRD_BASEDIR . '/clients/'. $this->getHWID() .'.rrd', 
						array( 	'AVERAGE', 
								'--resolution', '1200',
								'--start', $start, 
								'--end', $end 
						) 
					);
					
					
		$output = array();
		
		//var_dump($result);
		
		$output['start'] = date('Y-m-d H:i:s', $result['start']);
		$output['end'] = date('Y-m-d H:i:s', $result['end']);
		
		$i = 0;
		$total = 0;
		foreach($result['data']['clients'] as $index => $value) {
			
			$output['steps'][$i]['time'] = date('Y-m-d H:i:s', $index);
			
			if(is_nan($value)) { $count = 0; } else { $count = intval($value); }
			$output['steps'][$i]['count'] = $count;
			
			$total = $total + $count;
			$i++;
			
		}
		
		$output['total'] = $total;
		
		return $output;
		
	}
		
}
	
?>