<?php
include "protected/global.php";

function get_lat_long($address){
        
        
        return $latitude." ".$longitude;
}


$acnts = $db->get($dbprefix."_accounts LIMIT 0,10","account_ID,address,city,state,zip,name");


if(count($acnts)>0){
    foreach($acnts as $account){
        echo $account['name']."<br/>";
        $address =$account['address'].",+".$account['city'].",+".$account['state']."+".$account['zip'];
        $add = urlencode($address);
        $request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$add."&sensor=false";
        $xml = simplexml_load_file($request_url) or die("url not loading");
        $status = $xml->status;
        echo $status."<br/>";
          if ($status=="OK") {
              $Lat = $xml->result->geometry->location->lat;
              $Lon = $xml->result->geometry->location->lng;
              $LatLng = "$Lat,$Lon";
              echo $LatLng;
          }
      
        echo"<br/><br/>";
    }
}
?>