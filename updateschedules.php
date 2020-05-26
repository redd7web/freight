<?php
include "protected/global.php";

$yg = $db->query("SELECT grease_no,sludge_grease_traps.volume as sched_size,sludge_accounts.grease_volume as should_size   FROM sludge_grease_traps LEFT JOIN sludge_accounts ON sludge_grease_traps.account_no = sludge_accounts.account_ID WHERE sludge_grease_traps.volume != sludge_accounts.grease_volume");
if(count($yg)>0){
    foreach($yg as $top){
        $db->query("UPDATE sludge_grease_traps SET volume=$top[should_size] WHERE grease_no = $top[grease_no]");
    }
}

?>