<?php
include "protected/global.php";
ini_set("display_errors",1);
switch($_POST['type']){
    case "oil":
        $table = "freight_ikg_manifest_info";
    break;
    case "grease":
        $table="freight_ikg_grease";
    break;
    case "util":
        $table="freight_ikg_utility";
    break;
}

echo "UPDATE $table SET account_numbers='$_POST[new_account_numbers]' WHERE route_id=$_POST[route_id]";
$db->query("UPDATE $table SET account_numbers='$_POST[new_account_numbers]' WHERE route_id=$_POST[route_id]");


?>