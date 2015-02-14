<?php

class db_communities {
	
	private $ID;
	private $name;
	private $latitude;
	private $longitude;
	private $href;
	private $email;
		
	
	// Variablen die keine Entsprechung in der Datenbank haben
	private $exists;
	
	
	public function __construct($ID = '') { 
		
		$this->exists = true;
		
		if($ID != '') {			
			
			$result = dbSQL('SELECT id,
									name,
									lat,
									lon,
									href,
									email
									FROM '. TBL_COMMUNITIES .' WHERE ID = [u] "'. $ID . '" [u]', 'ALL');				
			
			
			// Fehlermeldung, wenn keine Abfrage zustande kam
			if(!$row = $result->fetch_object()) { $this->exists = false; return false; }
			
			
			// Daten in Klasse laden
			$this->ID 						= $row->id;
			$this->name						= $row->name;
			$this->latitude					= $row->lat;
			$this->longitude				= $row->lon;
			$this->href						= $row->href;
			$this->email					= $row->email;
	
		}
		
	}


	// Rückgabe, wenn kein Mitglied gefunden wurde
	public function exists() { return $this->exists; }
	
	
	// Variablen auslesen
	public function getID() 					{ return $this->ID; }
	public function getTheName()				{ return $this->name; }
	public function getLatitude()				{ return $this->latitude; }
	public function getLongitude()				{ return $this->longitude; }
	public function getHref()					{ return $this->href; }
	public function getEmail()					{ return $this->email; }
	
	public function getNodesCount()	{
		
		$result = dbSQL('SELECT COUNT(*) AS nodes FROM '. TBL_NODES .' WHERE community = "'. $this->ID .'" ORDER BY id ASC');
		$row = $result->fetch_object();
		return $row->nodes;
		
	}
		
}
	
?>