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
									lastseen FROM '. TBL_NODES .' WHERE ID = [u] "'. $ID . '" [u]', 'ALL');				
			
			
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
			
			$this->lastSeen	= new DateTime();
			$this->lastSeen->SetTimezone(new DateTimeZone('Europe/Berlin'));
			$this->lastSeen->SetTimestamp($row->lastseen);
	
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
	public function hideOnMap()					{ if($this->hideOnMap == '1') { return true; } else { return false; } }
	public function getHWID()					{ return $this->hwid; }
	public function getIPV6()					{ return $this->ipv6; }
	public function getBuild()					{ return $this->build; }
	public function getClientsCount()			{ return $this->clients; }
	public function VPNActive()					{ if($this->VPNActive == '1') { return true; } else { return false; } }
	public function getGatewayQuality()			{ return $this->gatewayQuality; }
	
	public function getLastSeen()				{ return $this->lastSeen->format('Y-m-d H:i:s'); }
	public function getLastSeenWithTimezone()	{ return $this->lastSeen->format('Y-m-d H:i:sP'); }
	
	public function getCommunity() { 

		$community = new db_communities($this->communityID);
		return $community->getTheName(); 
		
	}
	
	public function isActive() {
		
		if($this->lastSeen->getTimestamp() > (OPTION_WHATMEANSACTIVEINMINUTES * 60)) {
			
			return true;
			
		} else { return false; }
		
	}
		
}
	
?>