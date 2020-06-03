<?php
include "protected/global.php";

$asc = $db->where("ikg_manifest_route_number",$_POST['ikg'])->get($dbprefix.'_ikg_manifest_info','route_id');

echo $asc[0]['route_id'];
?>