<?php

include "protected/global.php";
ini_set("display_errors",1);

$db->query("DELETE FROM sludge_list_of_grease WHERE route_id  = $_GET[route_id]");
$db->query("DELETE FROM sludge_ikg_grease WHERE route_id  = $_GET[route_id]");
$db->query("UPDATE sludge_grease_traps SET grease_route_no = NULL,route_status='scheduled' WHERE grease_route_no = $_GET[route_id]");


?>