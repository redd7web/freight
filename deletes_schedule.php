<?php
include "protected/global.php";
ini_set("display_errors",1);


$first = $db->query("SELECT grease_route_no,route_status,account_no FROM sludge_grease_traps WHERE grease_no = $_POST[grease_no]");

if(count($first)>0){
   switch($first[0]['route_status']){
        case "scheduled"://stop is not in enroute
            $db->query("DELETE FROM sludge_grease_traps WHERE grease_no=$_POST[grease_no]");
            break;
        case "enroute"://stop is enroute
            
            $db->query("DELETE FROM sludge_grease_traps WHERE grease_no = $_POST[grease_no]");
            $db->query("UPDATE sludge_ikg_grease SET account_numbers  = REPLACE(account_numbers,'$_POST[account_no]|','') WHERE route_id = ".$_POST['route_id']);
            
        break;
        
   }
}


?>