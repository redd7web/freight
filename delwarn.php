<?php
include "protected/global.php";
$person = new Person();
$rais = 0;
$reason = $db->where("entry_number",$_POST['entry_n'])->get($dbprefix."_data_table");
if(count($reason)>0){
    $rais = $reason[0]['zero_gallon_reason'];
}


$db->where("entry_number",$_POST['entry_n'])->delete($dbprefix."_data_table");

$track = array(
    "date"=>date("Y-m-d H:i:s"),
    "user"=> $person->user_id,
    "actionType"=>"Warning deleted",
    "descript"=>$rais,
    "account"=>$_POST['account'],
     "pertains"=>2,
    "type"=>7
);
$db->insert($dbprefix."_activity",$track);


?>