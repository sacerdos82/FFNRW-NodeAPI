<?php
	
// Datenbank
define('MYSQL_HOST',		'');
define('MYSQL_USER',		'');
define('MYSQL_PASSWORD',	'');
define('MYSQL_DATABASE',	'');

define('RRD_BASEDIR',		''); // Immer ohne schließendes "/"

// SLIM REST Framework
\Slim\Slim::registerAutoloader();

// Interne API
$api = new \Slim\Slim();
$api->config( 
	array(	'debug' 			=> true,
			'log.level' 		=> \Slim\Log::DEBUG,
			'cookies.lifetime' 	=> '60 minutes'
	)
);
$api->setName('vfn-nrw:node_api');


// Diverses
define('OPTION_LOGFILE',								true);
define('OPTION_WHATMEANSACTIVEINMINUTES', 				10);
define('OPTION_HOWLONGDOESAMESHLINKCOUNTINMINUTES',		10);
define('OPTION_WHATMEANSNEWINDAYS',						14);
define('OPTION_HIDENODEINGEODATAWHENOFFLINEFORDAYS',	30);
define('OPTION_NODEHREFBASE', 							'https://freifunk.liztv.net/nodes'); // immer ohne schließendes "/"
define('OPTION_NODEMAPBASE',							'https://freifunk.liztv.net/batmap'); // immer ohne schießendes "/"

?>