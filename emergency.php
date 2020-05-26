<?php
include "protected/global.php";

$pack = array(
    "emergency"=>$_POST['value']
);

if($db->where("grease_no",$_POST['grease_id'])->update("sludge_grease_traps",$pack)){
    echo "Grease Stop Updated!";
}





?>