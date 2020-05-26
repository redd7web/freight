<?php
include "protected/global.php";
$list_of_routes = $dbprefix."_list_of_routes";

$db->query("UPDATE $list_of_routes set inc = inc -1 WHERE inc>0 && route_id = $_POST[route_id]");



?>