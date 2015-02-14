<?php

function API_Response($statusCode, $response) {
	
	// Globale Variablen einbinden
	global $apiResponseHeader;
	
	// Slim Object laden
	$api = \Slim\Slim::getInstance();
	
	// StatusCode - 200, 400 etc. - setzen
	$api->response->setStatus($statusCode);
	
	// Content-Type auf JSON setzen
	$api->response->headers->set('Content-Type', 'application/json');
	
	// Response als JSON ausgeben
	echo json_encode($response);
	
}

?>