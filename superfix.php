<?php
include "protected/global.php";


$k = $db->query("SELECT * FROM freight_list_of_routes WHERE status='enroute'");



foreach($k as $o){
    echo $o['status']." $o[route_id]<br/>";
     $ko = $db->query("SELECT * FROM freight_ikg_manifest_info WHERE route_id=$o[route_id]");
     
      $data_check = $db->query("SELECT * FROM freight_data_table WHERE route_id =$o[route_id]");
      if(count($data_check)>0){//pull completed stops from data table then from accounts manifest list
        echo "route has completed stops<br/>";
        foreach($data_check as $comped){
            echo $comped['schedule_id']." ".$comped['route_id']." ".$comped['account_no']."<br/>";
            //$db->query("UPDATE freight_scheduled_routes SET route_id=$o[route_id],route_status='completed' WHERE schedule_id=$comped[schedule_id]");
        }
        
      } else {
        echo "has no completed stops<br/>";
        echo $ko[0]['account_numbers']."<br/>";
        $nc = array_map('intval',  explode("|",$ko[0]['account_numbers'])  );
        array_pop($nc);
        
        /*echo "<pre>";
        echo print_r($nc);
        echo "</pre>";*/
        
        foreach($nc as $cn){
            echo "this account's  $cn  schedules<br/>";    
           
            $gp = $db->query("SELECT account_no,scheduled_start_date,schedule_id FROM freight_scheduled_routes WHERE account_no = $cn AND route_status !='completed' ORDER BY scheduled_start_date DESC");
            if(count($gp)>0){
                foreach($gp as $pg){
                    echo $pg['scheduled_start_date']." ".$pg['account_no']." $pg[schedule_id]";
                    if($pg['scheduled_start_start_date']>'2015-02-11'){
                        echo " - delete this stop.";
                    } else {
                        $check_in_data = $db->query("SELECT * FROM freight_data_table  WHERE schedule_id=".$pg['schedule_id']);
                        if(count($check_in_data)>0){
                          echo " - stop in data table";
                         $db->query("UPDATE freight_scheduled_routes SET route_status='complete' WHERE schedule_id = $pg[schedule_id]");
                        }else {
                           $db->query("UPDATE freight_scheduled_routes SET route_id=$o[route_id], route_status='enroute' WHERE schedule_id=$pg[schedule_id]");
                        }
                    }
                    echo "<br/>";
                }
            } else {
                echo "no schedules for this account<br/>";
            }
        }
        
      }
      
     
   
    echo "--------------------------------------<br/><br/>";
}


?>