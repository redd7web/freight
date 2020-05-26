<?php
include "../protected/global.php";
//ini_set("display_errors",1);
$uh = $db->query("SELECT account_no FROM iwp_scheduled_routes WHERE route_id = 42544");

if(count($uh)>0){
    foreach($uh as $stops){
        $account = new Account($stops['account_no']);
        echo "<li>".$account->address.", ".$account->city." ".$account->state." status:<span id='status'></span> proper address: <span id='proper'></span></li>";        
            
    }
}

?>