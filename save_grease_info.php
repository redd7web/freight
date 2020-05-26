<?php
include "protected/global.php";
ini_set('display_errors',1);
$time = date("H:i");
$person = new Person();
$ant = new Account($_POST['account_no']);
$grease_info = new Grease_Stop($_POST['schedule_number']);
$grese_route = new Grease_IKG($_POST['route_id']);
$id = $_POST['route_id'];





$check_ = $db->query("SELECT * FROM sludge_grease_data_table WHERE route_id=$_POST[route_id] AND schedule_id=$_POST[schedule_number] AND account_no=$_POST[account_no]");
    
    
$route_info = $db->query("SELECT driver FROM sludge_ikg_grease WHERE route_id=$_POST[route_id]");

if(count($check_) >0){
    $data = Array(
        "route_id"=>$_POST['route_id'],
        "schedule_id"=>$_POST['schedule_number'],
        "inches_to_gallons"=>$_POST['picked_up'],
        "account_no"=>$_POST['account_no'],
        "completed"=>1,
        "fieldreport"=>$_POST['field_note'],
        "driver"=>$route_info[0]['driver'],
        "date_of_pickup"=>date("Y-m-d"),
        "facility_origin"=>$grese_route->recieving_facility_no,
        "mileage"=>$_POST['mileage'],
        "arrival"=>$_POST['arrival'],
        "departure"=>$_POST['departure'],
        "net_weight"=>$_POST['net_weight'],
        "percent_split"=>$_POST['percent_split'],
        "route_status"=>"completed"
    );
    
   $db->where("route_id",$_POST['route_id'])->update("sludge_grease_data_table",$data);  
   $track = array(
        "date"=>date("Y-m-d H:i:s"),
        "user"=>$person->user_id,
        "actionType"=>"Grease Trap Serviced Updated",
        "descript"=>"Grease Trap Serviced Updated ".$id,
        "pertains"=>6,
        "type"=>14
    );
    $db->insert("xlogs.".$dbprefix."_activity",$track);
   
} else {
    $data = Array(
        "route_id"=>$_POST['route_id'],
        "schedule_id"=>$_POST['schedule_number'],
        "inches_to_gallons"=>$_POST['picked_up'],
        "account_no"=>$_POST['account_no'],
        "completed"=>1,
        "fieldreport"=>$_POST['field_note'],
        "driver"=>$route_info[0]['driver'],
        "date_of_pickup"=>date("Y-m-d"),
        "facility_origin"=>$grese_route->recieving_facility_no,
        "mileage"=>$_POST['mileage'],
        "arrival"=>$_POST['arrival'],
        "departure"=>$_POST['departure'],
        "net_weight"=>$_POST['net_weight'],
        "percent_split"=>$_POST['percent_split'],
        "route_status"=>"completed"
    );
    $db->insert($dbprefix.'_grease_data_table',$data);
    
    //create cs if checked
    $track = array(
        "date"=>date("Y-m-d H:i:s"),
        "user"=>$person->user_id,
        "actionType"=>"Grease Trap Serviced",
        "descript"=>"Grease Trap Serviced ".$id,
        "pertains"=>6,
        "type"=>14
    );
    $db->insert("xlogs.".$dbprefix."_activity",$track);
}









//reschedule trap frequency days later with same parameters as previous



        







?>