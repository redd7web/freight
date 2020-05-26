<?php
include "protected/global.php";
$list_of_routes = $dbprefix."_list_of_grease";

$db->query("UPDATE $list_of_routes set inc = inc -1 WHERE inc>0 AND route_id = $_POST[route]");



?>