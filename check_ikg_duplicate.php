<?php
include "protected/global.php";

$answer = $db->where("route_no",$_POST['route_no'])->get($dbprefix."_ikg_manifest_info","route_no");

echo count($answer);

?>