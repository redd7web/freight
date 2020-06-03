<?php
include "protected/global.php";

$yg = $db->query("SELECT grease_no,freight_grease_traps.volume as sched_size,freight_accounts.grease_volume as should_size   FROM freight_grease_traps LEFT JOIN freight_accounts ON freight_grease_traps.account_no = freight_accounts.account_ID WHERE freight_grease_traps.volume != freight_accounts.grease_volume");
if(count($yg)>0){
    foreach($yg as $top){
        $db->query("UPDATE freight_grease_traps SET volume=$top[should_size] WHERE grease_no = $top[grease_no]");
    }
}

?>