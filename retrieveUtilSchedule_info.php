<?php
include "protected/global.php";
//ini_set("display_errors",1);
$utility_table = $dbprefix."_utility";


$data_table = $dbprefix."_utility_data_table";


$schedule = new Util_Stop($_GET['sched_id']);
$accnt = new Account();

$get_gallons = $db->where('schedule_id',$schedule->schedule_id)->where("account_no",$schedule->account_number)->where("route_id",$schedule->route_id)->get($data_table,"*");// get oil pickup data from data table



switch($_GET['mode']){
    case "address":
        //echo $resukt[0]['account_no'];
        echo $accnt->singleField($schedule->account_number,"name")."<br/>".$accnt->singleField($schedule->account_number,"address")."<br/>".$accnt->singleField($schedule->account_number,"city").", ".$accnt->singleField($schedule->account_number,"state")." ".$accnt->singleField($schedule->account_number,"zip");    
    break;
    case "num":
        echo $accnt->singleField($schedule->account_number,"account_ID");        
    break;
    case "acountname":
        echo $accnt->singleField($schedule->account_number,"Name");
    break;    
    case "field":
        echo $get_gallons[0]['fieldreport'];
    break;
    case "driver":
        echo $get_gallons[0]['driver'];
    break;
    case "dop":
        echo $get_gallons[0]['date_of_pickup'];
    break;
    case "zero":
        echo $get_gallons[0]['zero_gallon_reason'];
    break;
}  




?>