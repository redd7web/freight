<?php
include "protected/global.php";

function get_lat_long($address){
    $region = "US";
    $address = str_replace(" ", "+", $address);
    
    $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
    $json = json_decode($json);

    $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
    return $lat.','.$long;
}

$y = $db->query("SELECT Name,account_ID,address,city,state,zip FROM freight_accounts WHERE division =15");

if(count($y)>0){
    foreach($y as $c){
        $ltln = explode(",",get_lat_long("$c[address], $c[city] $c[state], $c[zip]"));
        echo "$c[Name] $ltln[0] $ltln[1]<br/><br/>";
        $db->query("UPDATE freight_accounts SET latitude = $ltln[0],longitude = $ltln[1] WHERE account_ID = $c[account_ID]");
    }
}
?>