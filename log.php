<?php
include "protected/global.php";
ini_set("display_errors",1);
$kj = $db->query("SELECT * FROM freight_ikg_grease");

if(count($kj)>0){
    foreach($kj as $route){
        $ytc = $db->query("SELECT * FROM freight_rout_history_grease WHERE route_no = $route[route_id]");
            $package = array(
                "route_no"=>$route['route_id'],
                "start_date"=>$route['scheduled_date']." ".$route['time_start'],
                "end_date"=>$route['completed_date']." ".$route['end_time'],
                "first_stop"=>$route['first_stop'],
                "last_stop"=>$route['last_stop'],
                "start_mileage"=>$route['start_mileage'],
                "end_mileage"=>$route['end_mileage'],
                "first_stop_mileage"=>$route['first_stop_mileage'],
                "last_stop_mileage"=>$route['last_top_mileage'],
                "truck_id"=>$route['truck'],
                "driver"=>$route['driver'],
                "gross_weight"=>$route['gross_weight'],
                "tare_weight"=>$route['tare_weight'],
                "time_end"=>$route['end_time'],
                "time_start"=>$route['time_start'],
                "type"=>"grease"                
            );
        if(count($ytc)==0){
            //insert
            $db->insert("freight_rout_history_grease",$package);
        } else {
            //update
            $db->where("route_no",$route['route_id'])->update("freight_rout_history_grease",$package);
        }
    }
}



?>