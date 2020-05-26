<?php
include "protected/global.php";
ini_set("display_errors",1);

$db->query("DELETE FROM assets.truck WHERE truck.truck_id = $_POST[vid]");
$db->query("DELETE FROM sludge_truck_id WHERE module_id = $_POST[vid]");

?>