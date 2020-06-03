<?php
include "protected/global.php";

$db->query("UPDATE freight_ikg_grease SET vic = $_POST[value] WHERE route_id = $_POST[route]");

?>