<?php
/* 

TABLES TO BE INSERTED / UPDATED  - 
    
_ikg_manifest_info
_list_of_routes
_scheduled_routes
    -status, route_no
_notes
    -route_no

*/



include "protected/global.php";
$person = new Person();
//ini_set("display_errors",1);

$ikg = Array(
    "ikg_manifest_route_number"=>trim($_POST['ikg_manifest_route_number']),
    "tank1"=>$_POST['tank_1'],
    "tank2"=>$_POST['tank_2'],
    "time_start"=>$_POST['time_start'],
    "start_mileage"=>$_POST['start_mileage'],
    "scheduled_date"=>$_POST['sched_route_start'],
    "truck"=>$_POST['vehicle'],
    "first_stop"=>$_POST['first_stop'],
    "first_stop_mileage"=>$_POST['first_stop_mileage'],
    "completed_date"=>$_POST['completion_date'],
    "license_plate"=>$_POST['lic_plate'],
    "last_stop"=> $_POST['last_stop'],
    "last_top_mileage"=>$_POST['last_stop_mileage'],    
    "ikg_decal"=>$_POST['ikg_decal'],
    "end_time"=>$_POST['end_time'],
    "end_mileage"=>$_POST['end_mileage'],
    "ikg_collected"=>$_POST['ikg_collected'],
    "fuel"=>$_POST['fuel'],
    "inventory_code"=>$_POST['inventory_code'],
    "lot_no"=>$_POST['lot_no'],
    "gross_weight"=>$_POST['gross_weight'],
    "recieving_facility"=>$_POST['reciev_fac'],
    "tare_weight"=>$_POST['tara_weight'],
    "facility_address"=>$_POST['fac_address'],
    "net_weight"=>$_POST['net_weight'],
    "facility_rep"=>$_POST['fac_rep'],
    "ikg_transporter"=>$_POST['ikg_transporter'],
    "number_days_route"=>$_POST['mult_day_route'],
    "driver"=>$_POST['drivers'],
    "account_numbers"=>$_POST['accounts'],
    "location"=>$_POST['location']        
);


$db->insert($dbprefix."_ikg_utility",$ikg);
$id = $db->getInsertId();

$ikg2 = array(
    "status"=>"enroute",
    "ikg_manifest_route_number"=>trim($_POST['ikg_manifest_route_number']),
    "facility"=>$_POST['reciev_fac'],
    "scheduled"=>trim($_POST['sched_route_start']),
    "completed_date"=>$_POST['completion_date'],
    "stops"=>$_POST['number_of_picksup'],
    "created_date"=> date("Y-m-d"),
    "created_by"=>$person->user_id,
    "inc"=>$_POST['number_of_picksup'] , 
    "route_id"=>$id      
);


$db->insert($dbprefix."_list_of_utility",$ikg2);
$these_schedules = explode("|",$_POST['schedules']);
array_pop($these_schedules);
$note2 =Array(
    "route_id"=>$id,
    "type"=>"utility"
);
$buffer= array(
    "rout_no"=>$id,
    "route_status"=>"enroute"
);
foreach ($these_schedules as $value2){
   $db->where('utility_sched_id',$value2)->update($dbprefix."_utility",$buffer);
   $db->where('schedule_id',$value2)->update($dbprefix."_notes",$note2);   
   
}
$track = array(
    "date"=>date("Y-m-d H:i:s"),
    "user"=>$person->user_id,
    "actionType"=>"Utility Route Updated",
    "descript"=>"Utility Route Updated <form action='ikg_routing.php' target='_blank' method='post' class='ikg_form'><span style='cursor:pointer;color:blue;text-decoration:underline;'>$id</span><input type='hidden' value='$id' name='util_number'/><input type='hidden' value='1' name='from_routed_util_list'/></form>",
    "pertains"=>6,
    "type"=>14
);


$db->query("UPDATE freight_utility SET date_of_service ='$_POST[sched_route_start]' WHERE route_no = $id");

echo $id;
?>