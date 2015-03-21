<?php
	
function measureFromCoordinates($lon1, $lat1, $lon2, $lat2, $unit = 'meters') {
	
	$earthRadius = 6378.137; // Radius of earth in KM
	
	$dLat = ($lat2 - $lat1) * pi() / 180;
	$dLon = ($lon2 - $lon1) * pi() / 180;
	
	$a = sin($dLat/2) * sin($dLat/2) + cos($lat1 * pi() / 180) * cos($lat2 * pi() / 180) * sin($dLon/2) * sin($dLon/2);
		
	$c = 2 * atan2(sqrt($a), sqrt(1-$a));
	$d = $earthRadius * $c;
	
	return round(($d * 1000), 1); // meters

}

?>