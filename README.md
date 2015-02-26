# FFNRW Node API

Zwischenlösung zur Übertragung von Community- und Knotendaten in die Freifunk API und auf freifunk-karte.de. Außerdem allgemeine REST-Schnittstelle zur Verwendung in Apps.

## Installation

Konstanten in constants.php und configuration.php müssen angepasst werden.

## Verwendete Drittsoftware

SLIM Framework (http://www.slimframework.com)

## Nutzbare Routen

* /index.php/get/node/(nodeID)
* /index.php/get/node/(nodeID)/meshlinks
* /index.php/get/nodes
* /index.php/get/nodes/active
* /index.php/get/nodes/count
* /index.php/get/nodes/count/active
* /index.php/get/communities
* /index.php/get/community/(communityID)
* /index.php/get/community/(communityID)/format/ffapi
* /index.php/get/nodes/community/(communityID)
* /index.php/get/nodes/community/(communityID)/active
* /index.php/get/nodes/community/(communityID)/count
* /index.php/get/nodes/community/(communityID)/count/active
* /index.php/get/nodes/community/(communityID)/format/netmon (Diese Route bitte nicht mehr benutzen. Die neue Route ist .../format/nodelist/)
* /index.php/get/nodes/community/(communityID)/format/netmon
* /index.php/get/nodes/format/geojson
* /index.php/get/nodes/active/format/geojson
* /index.php/get/nodes/inactive/format/geojson
* /index.php/get/nodes/active/meshlinks/format/geojson
* /index.php/get/nodes/community/(communityID)/format/geojson
* /index.php/get/nodes/community/(communityID)/active/format/geojson
* /index.php/get/nodes/community/(communityID)/inactive/format/geojson
* /index.php/get/nodes/community/(communityID)/active/meshlinks/format/geojson
* /index.php/get/clients/count