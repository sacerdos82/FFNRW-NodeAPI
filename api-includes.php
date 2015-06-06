<?php

require_once(__PATH__ . '/api/helper-checkRequiredFields.php');
require_once(__PATH__ . '/api/helper-response.php');

require_once(__PATH__ . '/api/route-get-node.php');
require_once(__PATH__ . '/api/route-get-node-meshlinks.php');
require_once(__PATH__ . '/api/route-get-node-additionalInformation.php');
require_once(__PATH__ . '/api/route-get-node-statistics-clients.php');
require_once(__PATH__ . '/api/route-get-node-byHWID-activated.php');
require_once(__PATH__ . '/api/route-get-node-byFastD-activated.php');
require_once(__PATH__ . '/api/route-get-node-byFastD-hwid.php');

require_once(__PATH__ . '/api/route-get-nodes.php');
require_once(__PATH__ . '/api/route-get-nodes-active.php');
require_once(__PATH__ . '/api/route-get-nodes-count.php');
require_once(__PATH__ . '/api/route-get-nodes-count-active.php');
require_once(__PATH__ . '/api/route-get-nodes-format-geojson.php');
require_once(__PATH__ . '/api/route-get-nodes-active-format-geojson.php');
require_once(__PATH__ . '/api/route-get-nodes-inactive-format-geojson.php');
require_once(__PATH__ . '/api/route-get-nodes-active-meshlinks-format-geojson.php');
require_once(__PATH__ . '/api/route-get-nodes-new-format-geojson.php');
require_once(__PATH__ . '/api/route-get-nodes-lost-format-geojson.php');

require_once(__PATH__ . '/api/route-get-community.php');
require_once(__PATH__ . '/api/route-get-community-format-ffapi.php');
require_once(__PATH__ . '/api/route-get-communities.php');

require_once(__PATH__ . '/api/route-get-nodes-community.php');
require_once(__PATH__ . '/api/route-get-nodes-community-active.php');
require_once(__PATH__ . '/api/route-get-nodes-community-count.php');
require_once(__PATH__ . '/api/route-get-nodes-community-count-active.php');
require_once(__PATH__ . '/api/route-get-nodes-community-format-netmon.php'); // Wird entfernt. Statt dessen .../format/nodelist verweden.
require_once(__PATH__ . '/api/route-get-nodes-community-format-nodelist.php');
require_once(__PATH__ . '/api/route-get-nodes-community-format-geojson.php');
require_once(__PATH__ . '/api/route-get-nodes-community-active-format-geojson.php');
require_once(__PATH__ . '/api/route-get-nodes-community-inactive-format-geojson.php');
require_once(__PATH__ . '/api/route-get-nodes-community-active-meshlinks-format-geojson.php');
require_once(__PATH__ . '/api/route-get-nodes-community-new-format-geojson.php');
require_once(__PATH__ . '/api/route-get-nodes-community-lost-format-geojson.php');


require_once(__PATH__ . '/api/route-get-clients-count.php');
require_once(__PATH__ . '/api/route-get-clients-community-count.php');

require_once(__PATH__ . '/api/route-cron.php');
	
?>