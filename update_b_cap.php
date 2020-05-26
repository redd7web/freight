<?php
include "protected/global.php";
ini_set("display_errors",1);
$get = $db->query("SELECT account_ID FROM sludge_accounts");

if(count($get)>0){
    foreach ($get as $acnts){
        $account = new Account($acnts['account_ID']);
        $db->query("UPDATE sludge_accounts SET barrel_capacity = ".$account->total_barrel_capacity." WHERE account_ID=$acnts[account_ID]");
    }
}

?>