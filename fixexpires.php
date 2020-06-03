<?php

include "protected/global.php";

$allacnt = $db->get("freight_accounts","state_date,expires,account_ID");

foreach($allacnt as $check){
    if($check['expires']=="0000-00-00"){
        echo account_NumtoName($check['account_ID'])." $check[state_date] - new expiration".addDayswithdate($check['state_date'],365)."  <br/>";
        $package = array(
            "expires"=>addDayswithdate($check['state_date'],365)
        );
        $db->where("account_ID",$check['account_ID'])->update("freight_accounts",$package);
    }
}

?>