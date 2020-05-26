<?php
include "protected/global.php";
$grease_list_table = $dbprefix."_list_of_grease";
$person = new Person();
if($_POST['status'] == "completed"){
    
    $status = array(//set current schedule to complete
        "route_status"=>$_POST['status']
    );
    
    
    
    $db->where("grease_no",$_POST['schedule'])->update($dbprefix."grease_traps",$status);
    
    $old_info = $db->where("grease_no",$_POST['schedule'])->get($dbprefix."grease_traps"); 
    //create new schedule
    $newsched = array(
        "route_status"=>"scheduled",
        "account_no"=>$_POST['accounts'],
        "created_by"=>$person->user_id,
        "grease_trap_size"=>$old_info[0]['grease_trap_size'],
        "frequency"=>$old_info[0]['frequency'],
        "price_per_gallon"=>$old_info[0]['price_per_gallon'],
        "service"=>$old_info[0]['service'],
        "grease_name"=>$old_info[0]['grease_name'],
        "volume"=>$old_info[0]['volume'],
        "base_rate"=>$old_info[0]['base_rate'],
        "time_of_service"=>$old_info[0]['time_of_service'],
        "active"=>$old_info[0]['active'],
        "service_date"=>addDayswithdate(date("Y-m-d"),$old_info['frequency'])
          
    );
    
    $db->insert($dbprefix."_grease_traps");
    
} else if ($_POST['status']== "scheduled"){
    $data = array(
        "route_status"=>$_POST['status'],
        "route_no"=>NULL
    );
    $db->where("grease_no",$_POST['schedule'])->update($dbprefix."_grease_traps",$data);
     
    $remove_this_account = $_POST['accounts']."|";
    $get_accounts = $db->where('route_id',$_POST['route'])->get($dbprefix."_ikg_grease","account_numbers");
    $new_string = str_replace($get_accounts[0]['account_numbers'],"",$remove_this_account);
    $string = array(
        "account_numbers"=>$new_string
    );
    $db->where('route_id',$_POST['route'])->update($dbprefix."_ikg_grease",$string);
    
    
   
}




?>