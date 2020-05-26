<?php
include "protected/global.php";
ini_set("display_errors",1);

switch($_POST['mode']){
    case 1:
        $db->query("UPDATE sludge_ikg_grease SET other_expense_value = $_POST[oev] WHERE route_id=$_POST[route_id]");
    break;
    case 2:
        $db->query("UPDATE sludge_ikg_grease SET other_expense_desc = '$_POST[oed]' WHERE route_id=$_POST[route_id]");
    break;
}



?>