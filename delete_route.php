<?php
include "protected/global.php";


$db->query("DELETE FROM sludge_ikg_manifest_info WHERE route_id = $_POST[route_id]");//remove route from route manifest
$db->query("DELETE FROM sludge_list_of_routes WHERE route_id = $_POST[route_id]");//remote route from route list

$search_stops = $db->query("UPDATE sludge_scheduled_routes SET route_id = NULL,route_status='scheduled' WHERE route_id=$_POST[route_id] AND route_status IN ('enroute','scheduled')");//set stops that are enroute or scheduled back to scheduled pickup pool
?>