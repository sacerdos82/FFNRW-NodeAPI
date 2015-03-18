<?php

// Externe Tools
require_once(__PATH__ . '/tools-external/Slim/Slim.php');


// Allgemeine Funktionen
require_once(__PATH__ . '/tools-internal/logToFile.php');
require_once(__PATH__ . '/tools-internal/cleanInputFromCode.php');
require_once(__PATH__ . '/tools-internal/isValidEmail.php');
require_once(__PATH__ . '/tools-internal/isValidURL.php');
require_once(__PATH__ . '/tools-internal/randomString.php');
require_once(__PATH__ . '/tools-internal/isOdd-isEven.php');
require_once(__PATH__ . '/tools-internal/isValidDate.php');
require_once(__PATH__ . '/tools-internal/decodeJSONandValidate.php');
require_once(__PATH__ . '/tools-internal/timespan.php');


// Datenbank
require_once(__PATH__ . '/database/tablenames.php');
require_once(__PATH__ . '/database/dbSQL.php');


// Datenbank Abstraktion
require_once(__PATH__ . '/database-abstraction/nodes.php');
require_once(__PATH__ . '/database-abstraction/communities.php');

?>