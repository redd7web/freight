<?php
include "protected/global.php";

$schedule_table = $dbprefix."_utility";
$container_table = $dbprefix."_containers";
$container_list = $dbprefix."_list_of_containers";
$data_table = $dbprefix."_utility_data_table";

$resukt = $db->where("utility_sched_id",$_GET['sched_id'])->get($schedule_table,"*");
$accnt = new Account();

$get_gallons = $db->where("account_no",$resukt[0]['account_no'])->where("route_id",$resukt[0]['rout_no'])->get($data_table,"*");// get oil pickup data from data table



switch($_GET['mode']){
    case "address":
        //echo $resukt[0]['account_no'];
        echo $accnt->singleField($resukt[0]['account_no'],"name")."<br/>".$accnt->singleField($resukt[0]['account_no'],"address")."<br/>".$accnt->singleField($resukt[0]['account_no'],"city").", ".$accnt->singleField($resukt[0]['account_no'],"state")." ".$accnt->singleField($resukt[0]['account_no'],"zip");    
    break;
    case "containment":
        //echo $_GET['mode']."<br/>";
        if ($resukt[0]['type_of_service'] == 100){

            echo "1) ".containerNumToName($resukt[0]['container_label'])." / swapped for ". containerNumToName($resukt[0]['container_being_swapped_label']) ." - quantity: ". $resukt[0]['quantity'] ;
        }else {
            echo "1) ".containerNumToName($resukt[0]['container_label'])." / ";  service_call_decode( $resukt[0]['type_of_service']); 
        }
         
    break;
    case "amount":
         echo $bhgty[0]['amount_holds'];
    break;
    case "gpi_number":
        echo $bhgty[0]['gpi'];
    break;
    case "num":
        echo $accnt->singleField($resukt[0]['account_no'],"account_ID");        
    break;
    case "acountname":
        echo $accnt->name;
    break;    
    case "field":
        echo $get_gallons[0]['fieldreport'];
        break;
}      



?>