<?php
include "protected/global.php";

$check_sched = $db->query("SELECT route_status FROM freight_grease_traps WHERE grease_no =$_POST[sched] AND route_status in ('enroute','completed')");

if(count($check_sched)>0){
    echo "unavai";
}

?>