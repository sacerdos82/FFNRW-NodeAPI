<?php
	
function timespan( $seconds ){ 
     
	$td = array();
	
    $td['s'] = $seconds % 60; 

    $td['m'] = (($seconds - $td['s']) / 60) % 60; 

    $td['h'] = (((($seconds - $td['s']) /60) - $td['m']) / 60) % 24; 
     
    $td['d'] = floor( ((((($seconds - $td['s']) /60) - $td['m']) / 60) / 24) ); 
                     
    return $td; 
     
} 

?>