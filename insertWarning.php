<?php
include "protected/global.php";
$person = new Person();
$data_table = $dbprefix."_data_table";
$list_of_routes = $dbprefix."_list_of_routes";

    $pack = array(
        "account_no"=>$_POST['account'],
        "zero_gallon_zeron"=>$_POST['reason_for_skip_id']
    );
   
    
    
    //var_dump($pack);
    echo $db->query("INSERT INTO $data_table (account_no,zero_gallon_reason) values($_POST[account],$_POST[reason_for_skip_id])");
    
    $track = array(
        "date"=>date("Y-m-d H:i:s"),
        "user"=> $person->user_id,
        "actionType"=>"Warning inserted",
        "descript"=>$_POST['reason_for_skip_id'],
        "account"=>$_POST['account'],
         "pertains"=>2,
        "type"=>7
    );
    $db->insert($dbprefix."_activity",$track);

?>