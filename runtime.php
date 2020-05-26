<?php

	$host		= "localhost"; 
	$user		= "root"; 
	$pass		= "password1"; 
	$database	= "iwp"; 

	ini_set("display_errors","0");
	error_reporting(0);
	
#	Get start time
    $start	= microtime();
    $start	= explode( ' ', $start );
    $start	= $start[1] + $start[0];

#	attempt connection
    $conn	= mysql_connect($host, $user, $pass)
	or die( "<div style='color: red;'>Error - Failed to connect to host</div><div>" . mysql_error() . "</div>" );
	echo( "<div>host <i>" . $host . "</i> connected OK</div>" );
#	Get end time
    $end	= microtime();
    $end	= explode( " ", $end );
    $end	= $end[1] + $end[0];

#	calculate total run time
    $total	= ( $end - $start );
    echo( "<div>host connection took " . round( $total, 5 ) . " seconds to complete</div>" );

	$start	= "";
	$end	= "";

#	Get start time
    $start	= microtime();
    $start	= explode( ' ', $start );
    $start	= $start[1] + $start[0];
	
#	attempt to select db
	mysql_select_db( $database, $conn )
	or die( "<div style='color: red;'>Error - Could not find database</div><div>" . mysql_error() . "</div>" );
	echo( "<div>db <i>" . $database . "</i> selected OK</div>" );
	
#	Get end time
	$end	= microtime();
	$end	= explode( " ", $end );
	$end	= $end[1] + $end[0];
	
#	calculate total run time
    $total	= ( $end - $start );
    echo( "<div>db selection took " . round( $total, 5 ) . " seconds to complete</div>" );
	
?>
