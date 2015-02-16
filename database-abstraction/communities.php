<?php

class db_communities {
	
	private $ID;
	private $name;
	private $latitude;
	private $longitude;
	private $href;
	private $email;
	private $screenname;
	private $phone;
	private $facebook;
	private $twitter;
	private $description;
		
	
	// Variablen die keine Entsprechung in der Datenbank haben
	private $exists;
	
	
	public function __construct($ID = '') { 
		
		$this->exists = true;
		
		if($ID != '') {			
			
			$result = dbSQL('SELECT id,
									name,
									lat,
									lon,
									url,
									email,
									screenname,
									phone,
									facebook,
									twitter,
									description
									FROM '. TBL_COMMUNITIES .' WHERE ID = [u] "'. $ID . '" [u]', 'ALL');				
			
			
			// Fehlermeldung, wenn keine Abfrage zustande kam
			if(!$row = $result->fetch_object()) { $this->exists = false; return false; }
			
			
			// Daten in Klasse laden
			$this->ID 						= $row->id;
			$this->name						= $row->name;
			$this->latitude					= $row->lat;
			$this->longitude				= $row->lon;
			$this->url						= $row->url;
			$this->email					= $row->email;
			$this->screenname				= $row->screenname;
			$this->phone					= $row->phone;
			$this->facebook					= $row->facebook;
			$this->twitter					= $row->twitter;
			$this->description				= $row->description;
	
		}
		
	}


	// Rückgabe, wenn kein Mitglied gefunden wurde
	public function exists() { return $this->exists; }
	
	
	// Variablen auslesen
	public function getID() 					{ return $this->ID; }
	public function getTheName()				{ return $this->name; }
	public function getLatitude()				{ return $this->latitude; }
	public function getLongitude()				{ return $this->longitude; }
	public function getURL()					{ if($this->url != NULL) { return $this->url; } else { return ''; } }
	public function getEmail()					{ if($this->email != NULL) { return $this->email; } else { return ''; } }
	public function getScreenname()				{ if($this->screenname != NULL) { return $this->screenname; } else { return ''; } }
	public function getPhone()					{ if($this->phone != NULL) { return $this->phone; } else { return ''; } }
	public function getFacebook()				{ if($this->facebook != NULL) { return $this->facebook; } else { return ''; } }
	public function getTwitter()				{ if($this->twitter != NULL) { return $this->twitter; } else { return ''; } }
	public function getDescription()			{ if($this->description != NULL) { return $this->description; } else { return ''; } }
	
	public function getNodesCount()	{
		
		$result = dbSQL('SELECT COUNT(*) AS nodes FROM '. TBL_NODES .' WHERE community = "'. $this->ID .'" ORDER BY id ASC');
		$row = $result->fetch_object();
		return $row->nodes;
		
	}
		
}
	
?>