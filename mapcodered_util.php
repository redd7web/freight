<?php
include "protected/global.php";
$account_table = $dbprefix."_accounts";
$utility_table = $dbprefix."_utility";
$person = new Person();
global $coords;

 $search_string = "SELECT  $utility_table.*, $account_table.* FROM $utility_table INNER JOIN $account_table  ON $utility_table.account_no = $account_table.account_ID WHERE (route_status ='' || route_status='scheduled' || route_status='new') && $utility_table.code_red =1" ;
    $full  = $db->query($search_string);


$plothese = Array();

if(count($full)>0){
    $account = new Account();
    foreach($full as $scheduled){
        
       
       
       if(strlen($scheduled['latitude'])>0 && $scheduled['longitude']){
           $ac_name = str_replace("'","",$scheduled['name']);
           //echo $Address."<br/>";
           $plothese[] = array (
                0=>$scheduled['latitude'].",".$scheduled['longitude'],
                1=>$ac_name
           );
       }
       else {
           $address = str_replace(" ","+",$scheduled['address']).",";
           $address .="+".str_replace(" ","+",$scheduled['city']).",";
           $address .="+".$scheduled['state'];
           $address .="+".$scheduled['zip'];
           $ac_name = str_replace("#"," ",$scheduled['name']);
           $ac_name = str_replace(",","",$ac_name);
           $ac_name = str_replace("'","",$ac_name);
           $Address = urlencode($address);
        echo $scheduled['name']." No coordinates set<br/><br/>";
       }
       
    }     
}

$last = "";



$list ="";

if(count($plothese)>0){
 foreach($plothese as $plot){
         $list .= "['$plot[1]',$plot[0],],";
        }

?><!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="Ede Dizon" />
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<style type="text/css">
#map_canvas {
    width: 500px;
    height: 500px;
}
</style>
</head>
<body>
<div id="map_canvas"></div>

<script>
jQuery(function($) {
    // Asynchronously Load the map API 
    var script = document.createElement('script');
    script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
    document.body.appendChild(script);
});

function initialize() {
    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };
                    
    // Display a map on the page
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    map.setTilt(45);
        
    // Multiple Markers
    var markers = [
        <?php 
        echo $list;
        
        ?>
        
    ];
                        
    // Info Window Content
    var infoWindowContent = [
        <?php 
         echo $list;
        ?>
    ];
        
    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    
    // Loop through our array of markers & place each one on the map  
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0]
        });
        
        // Allow each marker to have an info window    
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }

    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(9);
        google.maps.event.removeListener(boundsListener);
    });
    
}


</script>

<?php } else { echo "No Code reds to show" ;} ?>
</body>
</html>