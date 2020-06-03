<?php
include "protected/global.php";

$info = $db->query("SELECT * FROM assets.truck WHERE truck.truck_id = $_POST[id]");

switch($_POST['choose']){
    
    case 1:
        echo  $info[0]['plates'];
        break;
    case 2:
        echo $info[0]['ikg_decal'];
        break;
}

?>