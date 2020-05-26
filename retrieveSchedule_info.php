<?php
include "protected/global.php";

$schedule_table = $dbprefix."_scheduled_routes";
$container_table = $dbprefix."_containers";
$container_list = $dbprefix."_list_of_containers";
$data_table = $dbprefix."_data_table";

$resukt = $db->where("schedule_id",$_GET['sched_id'])->get($schedule_table,"*");


if(count($resukt) > 0){
    $accnt = new Account($resukt[0]['account_no']);

$get_gallons = $db->where("account_no",$resukt[0]['account_no'])->where("route_id",$resukt[0]['route_id'])->get($data_table,"*");// get oil pickup data from data table


$ytrc = $db->where("account_no",$resukt[0]['account_no'])->get($container_table,"*");

$bhgty = $db->where("container_id",$ytrc[0]['container_no'])->get($container_list,"*");



switch($_GET['mode']){
    case "address":
        //echo $resukt[0]['account_no'];
        echo $accnt->name."<br/>".$accnt->address."<br/>".$accnt->city.", ".$accnt->state." ".$accnt->zip;    
    break;
    case "containment":
        //echo $_GET['mode']."<br/>";
        
        if(count($ytrc)>1){
            echo "<br/>";
            $ount = 1;
            foreach($ytrc as $list_of_containers){
               echo "(".$ount.") ".containerNumToName($list_of_containers['container_no'])."<br/><br/>"; 
               $ount++; 
            }
        }else {
            echo "(1) ".containerNumToName($ytrc[0]['container_no']);   
        }
         
    break;
    case "amount":
         echo $bhgty[0]['amount_holds'];
    break;
    case "gpi_number":
        echo $bhgty[0]['gpi'];
    break;
    case "num":
        echo $accnt->acount_id;        
    break;
    case "acountname":
        echo $accnt->name;
    break;
    case "inches_to_gallons":
        echo $get_gallons[0]['inches_to_gallons'];
        break;
    case "inches_entered":
        echo $get_gallons[0]['inches_entered'];
    break;
    case "leftover":
        echo $get_gallons[0]['inches_leftover'];
        break;
    case "leftovergallons":
        echo $get_gallons[0]['inches_to_gallons_leftover'];
        break;
    case "field":
        if(count($get_gallons)>0){
            echo $get_gallons[0]['fieldreport'];    
        }
        
        break;
    case "barrel_num":
               
        echo count($ytrc);
        break;
    case "driver":
        if(count($get_gallons)>0){
            echo $get_gallons[0]['driver'];
        }
    case "zero_reason":
        echo $get_gallons[0]['zero_gallon_reason'];
        break;
    case "mileage":
        echo $get_gallons[0]['mileage'];
        break;
    case "dop":
        if(count($get_gallons)>0){
            echo $get_gallons[0]['date_of_pickup'];
        } else {
            echo date("Y-m-d H:i:s");
        }
        break;
}    
    
} 

      



?>