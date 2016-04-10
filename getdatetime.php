<?php

// Calc the datetime for the page load - e.g. last updated
$t = microtime(true);
$micro = sprintf("%06d",($t - floor($t)) * 1000000);
$d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
$lastupdated = $d->format("Y-m-d H:i:s.u"); 

echo $lastupdated;

?>