<?php
ini_set("display_errors",1);
include "protected/global.php";
$i=1;
while($i<12){
   echo "$i<br/>";
    $fhold = array(
        "account_ID"=>200+$i,
        "name"=>"Frack Tank $i",
        "division"=>200+$i,
        "grease_volume"=>20000,
        "address"=>"2586 Shenandoah Way",
        "city"=>"San Bernardino",
        "state"=>"CA",
        "zip"=>92407,
        "billing_address"=>"2586 Shenandoah Way",
        "billing_city"=>"San Bernardino",
        "billing_state"=>"CA",
        "billing_zip"=>92407
    );
    echo "<pre>";
    var_dump($fhold);
    echo "</pre>";
    $db->insert("sludge_accounts",$fhold);
    $i = 1 +$i;
    
}






?>