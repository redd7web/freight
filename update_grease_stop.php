<?php
include "protected/global.php";



$grease_pack = array(
    "notes"=>$_POST['note'],
    "grease_trap_size"=>$_POST['volume'],
    "frequency"=>$_POST['freq'],
     "price_per_gallon"=>$_POST['ppg'],
    "service"=>"$_POST[service_type]",
    "active"=>"$_POST[active]",
    "grease_name"=>"$_POST[label]",
    "volume"=>$_POST['volume'],
    "base_rate"=>$_POST['rate'],
    "service_date"=>$_POST['dos'],
    "active"=>$_POST['active'],
    "addt_info"=>$_POST['addt']
);

$db->where("grease_no",$_POST['grease_no'])->update("freight_grease_traps",$grease_pack);


?>