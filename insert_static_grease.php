<?php
include "protected/global.php";

$grease_pack = array(
    "trap_active"=>"$_POST[active]",
    "service_type"=>"$_POST[service_type]",
    "label"=>"$_POST[label]",
    "grease_freq"=>$_POST['freq'],
    "grease_volume"=>$_POST['volume'],
    "grease_rate"=>$_POST['rate'],
    "grease_ppg"=>$_POST['ppg']
);
echo $db->where("account_ID",$_POST['account_no'])->update("sludge_accounts",$grease_pack);


?>