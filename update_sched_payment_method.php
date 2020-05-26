<?php
include "protected/global.php";
ini_set("display_errors",1);
$bp = $db->query("SELECT grease_no,account_no FROM sludge_grease_traps  INNER JOIN sludge_accounts ON sludge_accounts.account_ID = sludge_grease_traps.account_no WHERE account_no IS NOT NULL OR account_no !='' OR account_no !=' ' ");

if(count($bp)>0){
    foreach($bp as $ki){
        $acnt = new Account($ki['account_no']);
        $db->query("UPDATE sludge_grease_traps SET volume=$acnt->grease_volume, grease_trap_size = $acnt->grease_volume  WHERE grease_no = $ki[grease_no]");
    }
}

?>