<?php
$date = date("Y-m-d");

include "protected/global.php";
$account_table = $dbprefix."_accounts";
$list_of_routes = $dbprefix."_list_of_routes";
$schedules = $dbprefix."_scheduled_routes";



// update list of routes
$status = array(
    "status"=>$_POST['status'],
    "completed_date"=>$date
);

$db->where("route_id",$_POST['route_id'])->update($dbprefix."_list_of_routes",$status);




//update manifest info
$completed_date = array(
    "completed_date"=>$date
); 
$db->where('route_id',$_POST['route_id'])->update($dbprefix.'_ikg_manifest_info',$completed_date);




//update schedules routes page
$db->query("UPDATE $schedule_table set route_status='scheduled', route_id = NULL WHERE  route_status in('scheduled','enroute') AND route_id =$_POST[route_id]");


//echo $_POST['status'];

$history = array(
    "end_date"=>$date.date("H:i:s")
)




?>