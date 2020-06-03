<?php
include "protected/global.php";
$issue_table = $dbprefix."_account_issues";
$buffer = trim( htmlspecialchars($_POST['issues'])    );



//var_dump($package);




$check = $db->where('account_no',$_POST['account_no'])->get($issue_table,'note');



if(count($check)>0){
    $package = array(
        "note"=> $buffer,
        "created"=>date("Y-m-d")
    );
   $db->where("account_no",$_POST['account_no'])->update($issue_table,$package);
} else {
    $package = array(
        "account_no"=>$_POST['account_no'],
        "note"=> $buffer,
        "created"=>date("Y-m-d")
    );

   $db->insert($issue_table,$package);
}



?>