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
ini_set("display_errors",1);
include "protected/global.php";
$person = new Person();
$accountx = new Account();

 
 if(isset($_POST['ikg_manifest_route_number']) || strlen(trim($_POST['ikg_manifest_route_number']))>0){
    $jix = preg_replace('/[^A-Za-z0-9\-]/', '', trim($_POST['ikg_manifest_route_number']));
 }else{
    $jix = "Route-".date("Y-m-d_h-i-s");
 }
$ikg = Array(
    "route_id"=>0,
    "ikg_manifest_route_number"=> $jix ,
    "scheduled_date"=>$_POST['sched_route_start'],
    "truck"=>1,
    "license_plate"=>$_POST['lic_plate'],
    "ikg_decal"=>$_POST['ikg_decal'],
    "ikg_collected"=>$_POST['ikg_collected'],
    "inventory_code"=>$_POST['inventory_code'],
    "lot_no"=>$_POST['lot_no'],
    "recieving_facility"=>$_POST['reciev_fac'],
    "facility_address"=>str_replace(",","&#44;",$_POST['fac_address']),
    "ikg_transporter"=>$_POST['ikg_transporter'],
    "driver"=>(int)$_POST['drivers'],
    "account_numbers"=>$_POST['accounts'],
    "location"=>$_POST['location']   
);


$db->insert("freight_ikg_manifest_info",$ikg);

$buffer2 = $db->getInsertId();



$ikg2 = Array(
    "status"=>"enroute",
    "ikg_manifest_route_number"=>$_POST['ikg_manifest_route_number'],
    "facility"=>$_POST['reciev_fac'],
    "created_date"=>date("Y-m-d"),
    "created_by"=>$person->user_id,
    "scheduled"=>$_POST['sched_route_start'],
    "driver"=> $_POST['drivers'],   
    "stops"=>(int) trim($_POST['number_of_picksup']),
    "inc"=>(int) trim($_POST['number_of_picksup']),
    "route_id"=>$buffer2,
    "created_date"=> date("Y-m-d"),
    "created_by"=>$person->user_id
);

$db->insert($dbprefix."_list_of_routes",$ikg2);




$these_schedules = explode("|",$_POST['schedules']);

array_pop($these_schedules);


/**/
print_r($these_schedules);

$db->query("UPDATE freight_scheduled_routes SET route_id=$buffer2,route_status='enroute',driver=$_POST[drivers],scheduled_start_date='$_POST[sched_route_start]' WHERE schedule_id IN(".implode(",",$these_schedules).")");

//var_dump($history);




?>