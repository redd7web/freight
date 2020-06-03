<?php
include "protected/global.php";
    $data = Array(
        "$_POST[field]"=>$_POST['state']
    );
    
    //var_dump($data);
    
   
    
    
    $db->where('account_ID',$_POST['id'])->update("freight_accounts",$data);
    $track = array(
        "date"=>date("Y-m-d H:i:s"),
        "user"=>$person->user_id,
        "actionType"=>"Account Updated",
        "descript"=>"Account $_POST[field] changed to ".$_POST['value'],
        "account"=>$_POST['id'],
        "pertains"=>2,
        "type"=>7
    );
    $db->insert($dbprefix."_activity",$track);
    echo "$_POST[id] | $_POST[value] $_POST[field] \r\n $addition";    

?>