<?php
include "protected/global.php";

$db->query("UPDATE sludge_ikg_grease SET vic = $_POST[value] WHERE route_id = $_POST[route]");

?>