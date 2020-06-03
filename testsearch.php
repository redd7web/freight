<?php
ini_set("display_errors",1);
ini_set('memory_limit', '-1');
include "protected/global.php";
$count =0;

$paidx = array();
$ratex = array();
$occurred_once = array();
function get_index(){
    global $db;
    $xo =  $db->query("SELECT date,percentage FROM freight_jacobsen ORDER BY DATE DESC LIMIT 0,1 ");
    
    if(count($xo)>0){
        return $xo;
    } else {
        return 0;
    }
}


$uo = $db->query("SELECT freight_data_table.entry_number,freight_data_table.account_no,freight_data_table.date_of_pickup,freight_data_table.schedule_id,freight_data_table.sum, (freight_data_table.sum - (freight_data_table.sum * freight_accounts.miu) ) as adj,freight_data_table.route_id, freight_accounts.name,freight_accounts.payment_method, freight_accounts.miu,freight_accounts.index_percentage,freight_accounts.ppg_jacobsen_percentage,freight_accounts.price_per_gallon,freight_accounts.account_ID,freight_accounts.payment_method as pm_two FROM freight_data_table LEFT JOIN freight_accounts ON freight_accounts.account_ID = freight_data_table.account_no WHERE freight_data_table.account_no IS NOT NULL AND freight_data_table.payment_method IN ('Per Gallon') ORDER BY  freight_accounts.account_ID ASC ,date_of_pickup ASC ");
$ko =get_index();
if(count($uo)>0){
    foreach($uo as $stops){
        $count++;
        echo "<br/>$count $stops[entry_number]--------";
        /**/
        $ppg = 0;
         switch($stops['payment_method']){
            case "Jacobson": case "Index":
                $ppg = $stops['index_percentage'] *$ko[0]['percentage'] *7.56;
            break;
            default:
            case "Per Gallon": 
                $ppg =  $stops['ppg_jacobsen_percentage'];
            break;
           case "No Pay": case "Normal":
                $ppg =  0.00;
            break;
           case "O.T.P. Per Gallon": case "O.T.P. PG":
            if(!in_array($stops['account_ID'],$occurred_once)){
                $occurred_once[]=$stops['account_ID'];
                 $one_time = $stops['ppg_jacobsen_percentage']; 
                $ppg =  $ppg + $stops['ppg_jacobsen_percentage']; 
            } else {
                $one_time = "";
                $ppg =  $ppg;
            }
           
            break;
             
            
        }
        
        switch($stops['payment_method']){
            case "Jacobson":case "Index":
            $paid =  $stops['index_percentage'] *$ko[0]['percentage'] * $stops['adj']*7.56;
            //echo "<br/>Jacobsen / Index ".$stops['index_percentage']." * ".$ko[0]['percentage']." * ".$stops['adj']." * 7.56<br/>";
            break;
            default:
            case "Per Gallon":
            
            $paid = $stops['ppg_jacobsen_percentage']* $stops['adj'];
            break;
            case "O.T.P. Per Gallon": case "O.T.P. PG":
                $ppg = $stops['price_per_gallon'] *$stops['adj'];
                if(!in_array($stops['account_ID'],$paidx)){
                    $db->query("UPDATE freight_accounts SET paid = 1 WHERE account_ID = $stops[account_ID]");
                    $paidx[]=$stops['account_ID'];
                     $one_time = $stops['ppg_jacobsen_percentage']; 
                    $paid = $ppg + $stops['ppg_jacobsen_percentage'];
                    echo "<br/>$stops[account_ID] First time paid for this account<br/>"; 
                } else {
                    $one_time = "";
                    $paid = $ppg;
                    echo "<br/>$stops[account_ID] already paid for this account<br/>";
                }
            break;
            case "No Pay": case "Normal":
                $paid = 0.00;
            break;
        }
        switch($stops['payment_method']){
            case "Jacobson":  case"Index":
                $rate = $stops['index_percentage'];
                break;
            case "Per Gallon":
                $rate = $stops['ppg_jacobsen_percentage'];
                break;
            case "Normal":
                $rate = $stops['price_per_gallon'];
                break;
            case "O.T.P. Per Gallon": case "O.T.P. PG":
            
                if(!in_array($stops['account_ID'],$ratex)){
                    $ratex[]=$stops['account_ID'];
                    $rate = $one_time + $stops['price_per_gallon'];
                } else {
                    $rate = $stops['price_per_gallon'];
                }
                break;
             case "No Pay":
                $rate = 0.00;
                break;
        }
        
        
        if(strlen($ppg)<0 || strlen($ppg) == 0){
            $ppg = 0;
        }
        
        if(strlen($rate)<0 || strlen($rate) == 0){
            $rate = 0;
        }
        
        if(strlen($stops['miu'])==0){
            $miu = .25;
        } else {
            $miu = $stops['miu'];
        }
        
        $indice = $ko[0]['percentage'];
        
        echo "<br/><br/>Name: $stops[name] <br/>Date of pickup: $stops[date_of_pickup] <br/>Payment Method: $stops[payment_method]<br/>Gallons retrieved: $stops[sum]<br/>Adju Gallons: $stops[adj]<br/>MIU: $stops[miu] <br/>PPG: $ppg <br/>Paid: $paid<br/>Rate: $rate <br/>Index at pickup:$indice<br/> UPDATE freight_data_table SET rate=$rate,	ppg= $ppg,index_at_pickup = $indice, temp_miu = $miu,paid = $paid,payment_method='$stops[pm_two]' WHERE route_id= $stops[route_id] AND account_no = $stops[account_ID] AND schedule_id =$stops[schedule_id] <br/><br/>------- ";
        $db->query("UPDATE freight_data_table SET rate=$rate,	ppg= $ppg,index_at_pickup = $indice, temp_miu = $miu,paid = $paid,payment_method='$stops[pm_two]' WHERE entry_number = $stops[entry_number]");
    }
}












?>