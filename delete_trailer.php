<?php
include "protected/global.php";
ini_set("display_errors",1);
$db->query("DELETE FROM assets.trailers WHERE trailers.truck_id =$_POST[tid]");
$db->query("DELETE FROM sludge_trailers WHERE module_id =$_POST[tid]");

?>