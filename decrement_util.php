<?php
include "protected/global.php";
$list_of_routes = $dbprefix."_list_of_utilities";

$db->query("UPDATE $list_of_routes set inc = inc -1 WHERE inc>0 && route_id = $_POST[route]");

$db->query("UPDATE sludge_utility SET route_status='completed' WHERE utility_sched_id =$_POST[schedule_number]");


?>