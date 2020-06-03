<?php
ini_set("display_errors",1);
include "protected/global.php";


$ytc = $db->query("SELECT freight_grease_data_table.*,freight_accounts.grease_ppg as cod,freight_accounts.Name,freight_grease_traps.grease_no
FROM `freight_grease_data_table`
INNER JOIN freight_grease_traps ON freight_grease_data_table.schedule_id = freight_grease_traps.grease_no
INNER JOIN freight_accounts ON freight_grease_data_table.account_no = freight_accounts.account_ID
WHERE freight_grease_traps.payment_method LIKE '%Cash On Delivery%'")
;

if(count($ytc)>0){
    foreach($ytc as $ch){
       $db->query("UPDATE freight_grease_data_table SET ppg = $ch[cod] WHERE schedule_id=$ch[grease_no] AND account_no=$ch[account_no]");
    }
}else{
    echo "No Results";
}


?>