<?php
include "protected/global.php";
ini_set("display_errors",0);
$grease_table = $dbprefix."_grease_traps";

$grease_info = new Grease_Stop($_GET['sched_id']);
$accnt = new Account($grease_info->account_number);
$data_table = $dbprefix."_grease_data_table";




$get_gallons = $db->where("account_no",$grease_info->account_number)->where("route_id",$grease_info->grease_route_no)->where("schedule_id",$grease_info->grease_no)->get($data_table,"*");// get grease pickup data from data table



switch($_GET['mode']){
    case "emergency":
        echo $grease_info->emergency;
    break;
    case "cs":
        echo $get_gallons[0]['cs'];
    break;
    
    case "cs_reason":
        echo $get_gallons[0]['cs_reason'];
    break;
    case "jet_notes":
        echo $get_gallons[0]['jet_notes'];
    break;
    case "jet_hours":
        echo $get_gallons[0]['hours_jetting'];
    break;
    case "jetting":
        echo $grease_info->jetting;
    break;
    case "account_number":
        echo $accnt->acount_id;
    break;
    case "address":
        //echo $resukt[0]['account_no'];
        echo $accnt->name_plain."<br/>".$accnt->address."<br/>".$accnt->city.", ".$accnt->state." ".$accnt->zip;    
        echo "</br/>Payment Method :".$grease_info->payment_method;
    break;
    case "containment":
        //echo $_GET['mode']."<br/>";
         echo "Volume: ".$accnt->grease_volume."<br/>";
         echo "PPG: ".$grease_info->ppg."<br/>";
         if($grease_info->jetting==1){
            echo "Jetting<br/>";
         }         
    break;
    
    case "gpi_number":
        echo $bhgty[0]['gpi'];
    break;
    case "num":
        echo $serv[0]['grease_volume'];        
    break;
    case "acountname":
        echo $accnt->name;
    break;    
    case "field":
        echo $get_gallons[0]['fieldreport'];
        break;
    case "volume":
        echo $accnt->grease_volume;
        break;
    case "picked_up":
        echo $get_gallons[0]['inches_to_gallons'];
        break;
    case "zero_reason":
        echo $get_gallons[0]['zero_gallon_reason'];
        break;
    case "mileage":
        echo $get_gallons[0]['mileage'];
    break;
    case "arrival":
        echo $get_gallons[0]['arrival'];
    break;
    case "departure":
        echo $get_gallons[0]['departure'];
    break;
    case "locked":
    $numb = 0;
    if($accnt->mlocked == 0){
        $numb = 0;
    }else{
        $numb = 1;
    }
    echo $numb;
    break;
    case "charge":
        echo $grease_info->charge;
    break;
    case "payment_method":
        echo $grease_info->payment_method;
    break;
    case "jet_cost":
        $jet_cost = $db->query("SELECT COALESCE( SUM(element_12),NULL,0.00) as total FROM Inetforms.ap_form_29942 WHERE element_1 = $accnt->acount_id AND element_2 = $grease_info->grease_route_no AND element_3 = $grease_info->grease_no ");
        if(count($jet_cost)>0){
            echo number_format($jet_cost[0]['total'],2);
        } else {
            echo number_format(0,2);
        }
    break;
    case "get_payment_method":
        echo $get_gallons[0]['payment_type_recieved'];
    break;
    case "import_note":
        echo $get_gallons[0]['import_note'];
    break;
    case "addtional_cost_description":
        echo $get_gallons[0]['addtional_cost_description'];
    break;
    case "addtional_cost_amount":
        echo $get_gallons[0]['addtional_cost_amount'];
    break;
    case "drop_remove":
        echo $get_gallons[0]['drop_removal'];
    break;
}      



?>