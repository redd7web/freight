<?php
include "protected/global.php";


$x = $db->query("SELECT route_id,ikg_manifest_route_number FROM sludge_list_of_routes WHERE status = 'completed' OR status='enroute'");

if(count($x)>0){
    foreach($x as $ko){
        echo "route id:".$ko['route_id']." $ko[ikg_manifest_route_number]<br/>";
        $hj = $db->query("SELECT SUM(inches_to_gallons) as yp FROM sludge_data_table WHERE route_id = $ko[route_id]");
        echo  "sum ".$hj[0]['yp']."<br/>";
        $nb = $hj[0]['yp'];
        $db->query("UPDATE sludge_list_of_routes SET collected = $nb WHERE route_id =$ko[route_id]");
        
    }
}

?>