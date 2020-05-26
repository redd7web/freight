<?php
include "protected/global.php";

$ytr = $db->get($dbprefix."_accounts","account_ID");

if(count($ytr) >0){
    foreach($ytr as $vle){
        
        $account = new Account($vle['account_ID']);
        echo $account->name." "; 
        var_dump($account->barrel_info);
        echo "<br/><br/>";
    }
}


?>