<?php
include "protected/global.php";
ini_set("display_errors",1);


$ub = $db->query("SELECT grease_no,account_no FROM freight_grease_traps WHERE route_status IN('enroute','scheduled')");
if(count($ub)>0){
    foreach($ub as $stps){
        $db->query("UPDATE freight_accounts SET current_stop=$stps[grease_no] WHERE account_ID = $stps[account_no]");
    }
}

/*
$bc = $db->query("SELECT grease_no,account_no,route_status,grease_route_no FROM freight_grease_traps LEFT JOIN freight_list_of_grease ON freight_grease_traps.grease_route_no = freight_list_of_grease.route_id WHERE freight_list_of_grease.status='enroute' AND freight_grease_traps.route_status ='completed' ");
if(count($bc)>0){
    foreach($bc as $limbo){
        echo $limbo['grease_no']." ".account_NumToName($limbo['account_no'])." ".$limbo['route_status']." ".$limbo['route_status']." ".$limbo['grease_route_no']."<br/>";
    }
}else {
    echo "none";
}
*/

?>