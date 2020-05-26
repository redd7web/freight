<?php
include "protected/global.php";


$account_table = $dbprefix."_accounts";
$schedule_table = $dbprefix."_scheduled_routes";
$acnt_info = new Account();
$person = new Person();
$remove_this_account = $_POST['account']."|";

    
        
    if($_POST['status'] == "completed"){
        echo "***********************COMPLETED STATUS:*********************8 <br/><br/>";
        // if route was completed set the account gauage back to 0, update schedule with completed status  and make a new schedule frequency days later
        $db->query("UPDATE  $schedule_table set route_status='completed' WHERE schedule_id = $_POST[schedule]");
        //reset accounts gauge to 0
        $db->query("UPDATE $account_table set avg_gallons_per_Month = 0 WHERE account_ID = $_POST[account]");
        
        
        //************************create new schedule frequency days later if one doesn't already exist        
        $exist = $db->where("account_no",$_POST['account'])->where("route_status","scheduled")->get($dbprefix."_scheduled_routes","schedule_id");
        
        if(count($exist) <= 0){
            $new_sched = array(
                "scheduled_start_date"=>addDayswithdate(date("Y-m-d"),$acnt_info->singleField( $_POST['account'],"pickup_frequency") ),
                "facility_origin"=>$acnt_info->singleField($_POST['account'],"division"),
                "route_status"=>"scheduled",
                "created_by"=>$person->user_id,
                "account_no"=>$_POST['account'],
                "code_red"=>0,
                "date_created"=>date("Y-m-d")
            
            );
            //var_dump($new_sched);
            $db->insert($dbprefix."_scheduled_routes",$new_sched);
        }
        //**************************************************
        echo "<br/><brr/>******************************************<br/><br/>";
    } else if($_POST['status'] == "scheduled"){//if pickups were not complete, update with a scehduled status and remove from route list (remove route id)
    
    
        echo "<br/><br/>***************************INCOMPLETE STOP**************************************<br/><br/>";
        echo "schedule $_POST[schedule] - account $_POST[account]<br/>";
        
            
        $db->query("UPDATE $schedule_table set route_id = NULL,route_status='scheduled' WHERE schedule_id = $_POST[schedule]");    
        $remove_sched = $db->where("route_id",$_POST['route_id'])->get($dbprefix."_ikg_manifest_info","account_numbers");
    
        $remmed_value = str_replace($remove_this_account,"",$remove_sched[0]['account_numbers']);
        
        $buffer = array(
            "account_numbers"=>$remmed_value
        );
        
        $db->where("route_id",$_POST['route_id'])->update($dbprefix."_ikg_manifest_info",$buffer);        
        echo "<br/><br/>***********************************************************<br/><br/>";
    }
    
    




//remove schedule from route




?>