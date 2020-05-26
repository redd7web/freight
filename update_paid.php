<?php
ini_set("display_errors",1);
include "protected/global.php";


$ytc = $db->query("SELECT sludge_grease_data_table.*,sludge_accounts.grease_ppg as cod,sludge_accounts.Name,sludge_grease_traps.grease_no
FROM `sludge_grease_data_table`
INNER JOIN sludge_grease_traps ON sludge_grease_data_table.schedule_id = sludge_grease_traps.grease_no
INNER JOIN sludge_accounts ON sludge_grease_data_table.account_no = sludge_accounts.account_ID
WHERE sludge_grease_traps.payment_method LIKE '%Cash On Delivery%'")
;

if(count($ytc)>0){
    foreach($ytc as $ch){
       $db->query("UPDATE sludge_grease_data_table SET ppg = $ch[cod] WHERE schedule_id=$ch[grease_no] AND account_no=$ch[account_no]");
    }
}else{
    echo "No Results";
}


?>