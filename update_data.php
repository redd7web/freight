<?php
include "protected/global.php";
ini_set("display_errors",1);
$hc = $db->query("SELECT account_ID,grease_ppg FROM sludge_accounts WHERE payment_method = 'Per Gallon' AND grease_ppg IS NOT NULL");

if(count($hc)>0){
    foreach($hc as $ch){
        
        echo "UPDATE sludge_grease_data_table SET ppg = $ch[grease_ppg] WHERE account_no = $ch[account_ID]<br/><br/>";
        $db->query("UPDATE sludge_grease_data_table SET ppg = $ch[grease_ppg] WHERE account_no = $ch[account_ID]");
    }
}

?>