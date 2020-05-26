<?php
include "protected/global.php";
ini_set("display_errors",1);
$buffer = htmlspecialchars($_POST['notes']);

$data = array(
    "notes"=>trim($buffer)
);

$db->where("account_ID",$_POST['account_no'])->update("sludge_accounts",$data);

$newvalue = $db->where("account_ID",$_POST['account_no'])->get("sludge_accounts","notes");

echo $newvalue[0]['notes'];

?>