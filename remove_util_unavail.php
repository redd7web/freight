<?php
include "protected/global.php";

$check_sched = $db->query("SELECT route_status FROM freight_utility WHERE utility_sched_id =$_POST[sched] AND route_status in ('enroute','completed')");

if(count($check_sched)>0){
    echo "unavai";
}

?>