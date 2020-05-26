<?php
include "protected/global.php";
$ikg_util = $dbprefix."_ikg_utility";
$util_list = $dbprefix."_list_of_route";
$dte = date("Y-m-d H:i:s");


if($_POST['status'] == "scheduled"){
    
    $data = array(// if schedule was incomplete return it to pool (set route to null and status back to scheduled)
        "route_status"=>"scheduled",
        "rout_no"=>NULL
    );
    $db->where("utility_sched_id",$_POST['schedule'])->update($dbprefix."_utility",$data);
    
    
    
    $account_string = $db->where('route_id',$_POST['route'])->get($dbprefix."_ikg_utility","account_numbers");
    
    
    $buf = str_replace($_POST['account']."|","",$account_string[0]['account_numbers']);
    $db->query("UPDATE $ikg_util SET account_numbers='$buf' WHERE route_id=$_POST[route]");
    
    
    
} else if($_POST['status'] == "completed"){
    $data = array(// if schedule was complete update schedule with complete status
        "route_status"=>"completed"
    );
    $db->where("utility_sched_id",$_POST['schedule'])->update($dbprefix."_utility",$data);
  
}





?>