<?php
include "protected/global.php";
$b = $db->query("SELECT account_no FROM sludge_grease_traps WHERE grease_route_no = $_GET[route]");
if(count($b)>0){
    foreach($b as $o){
        $string .= "$o[account_no]|";
    }
}

if($db->query("UPDATE sludge_ikg_grease SET account_numbers='$string' WHERE route_id = $_GET[route]")){
    echo "<br/><br/>Stops Recovered!";
}


?>