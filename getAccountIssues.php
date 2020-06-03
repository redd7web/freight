<?php
include "protected/global.php";


$issue =  $db->where("account_no",$_GET['account_id'])->get($dbprefix."_account_issues");

if(count($issue)>0){
    echo $issue[0]['note'];
} else{
    
}


?>


