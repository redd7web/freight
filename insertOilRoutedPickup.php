<?php
include "protected/global.php";
$rno = str_replace("|","",$_POST['route_id']); // remove delimiter


//************************inserts into accounts on route********************************
$buffer = Array(
    "number"=>$rno,    
    "account_no"=>$_POST['accounts']
);

echo $db->insert($dbprefix."_accounts_on_route",$buffer);


//************************updates Scheduled Route to scheduled********************************
$buffer2 = Array(
    "route_status"=>"scheduled"
);

echo $db->where("route_id",$rno)->update($dbprefix."_scheduled_routes",$buffer2);




//************************updatest list of routes to edit oil_routing.php********************************





?>