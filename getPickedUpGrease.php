<?php
include "protected/global.php";

$grse = $db->where("schedule_id",$_GET['sched_id'])->where("route_id",$_GET['route'])->get($dbprefix."_grease_data_table","inches_to_gallons");

if(count($grse)>0){
    echo $grse[0]['inches_to_gallons'];
} else {
    echo "0";
}

?>