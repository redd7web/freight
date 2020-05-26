<?php
// complete route from data enter page
include "protected/global.php";
//ini_set("display_errors",1);
$person = new Person();
$data_table = $dbprefix."_data_table";
echo $_GET['route_id']."<br/>";
$date = date("Y-m-d H:i:s");


function get_index(){
    global $db;
    $xo =  $db->query("SELECT date,percentage FROM sludge_jacobsen ORDER BY DATE DESC LIMIT 0,1 ");
    
    if(count($xo)>0){
        return $xo;
    } else {
        return 0;
    }
}



$req = $db->query("SELECT * FROM `sludge_data_table` WHERE route_id =$_GET[route_id] GROUP BY schedule_id");
$count =0;
$tot = 0;
$avg =0;
$nums ="";

if(count($req)>0){
    foreach($req as $stops){        
        echo "schedule id: ".$stops['schedule_id']." date of pickup: ".$stops['date_of_pickup']." account number: ".$stops['account_no']."<br/>";
        //echo "<pre>";
        $ant = new Account($stops['account_no']);
        
        
        $nums .=$stops['account_no']."|";
        $tot +=$stops['sum'];
        $avg +=$stops['avg_exp'];
        $count++;
        
       
        $db->query("UPDATE sludge_accounts SET avg_gallons_per_Month=0 WHERE account_ID = $stops[account_no]");//reset account oil guage back to 0
        
    } 
    
    $db->query("UPDATE sludge_ikg_manifest_info SET account_numbers='$nums',driver_completed_date='$date' WHERE route_id=$_GET[route_id]");
     
    //echo "stops: ".$count."<br/>";    
    //echo $tot."<br/>";
    //echo "expected ".$avg."<br/>";
    
        
   $uo = $db->query("SELECT sludge_data_table.entry_number,sludge_data_table.date_of_pickup,sludge_data_table.schedule_id,sludge_data_table.sum, (sludge_data_table.sum - (sludge_data_table.sum * sludge_accounts.miu) ) as adj,sludge_data_table.route_id, sludge_accounts.name,sludge_accounts.payment_method, sludge_accounts.miu,sludge_accounts.index_percentage,sludge_accounts.ppg_jacobsen_percentage,sludge_accounts.price_per_gallon,sludge_accounts.account_ID FROM sludge_data_table LEFT JOIN sludge_accounts ON sludge_accounts.account_ID = sludge_data_table.account_no WHERE sludge_data_table.route_id =$_GET[route_id] ");
$ko =get_index();
if(count($uo)>0){
    foreach($uo as $stops){
        $jux = $db->query("SELECT paid FROM sludge_accounts WHERE account_ID = $stops[account_ID]");
        echo "<br/>--------";
         switch($stops['payment_method']){
            case "Jacobson": case "Index":
                $ppg = number_format($stops['index_percentage'] *$ko[0]['percentage'] *7.56,2);
            break;
            default:
            case "Per Gallon": 
                $ppg =  $stops['ppg_jacobsen_percentage'];
            break;
            case "Normal":
                $ppg =  0.00;
            break;
           case "O.T.P. Per Gallon": case "O.T.P. PG":            
            if(  $jux[0]['paid'] == 0 || $jux[0]['paid'] == null  ){               
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
                if(  $jux[0]['paid'] == 0 || $jux[0]['paid'] == null  ){
                     $db->query("UPDATE sludge_accounts SET paid = 1 WHERE account_ID = $stops[account_ID]");
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
                if(  $jux[0]['paid'] == 0 || $jux[0]['paid'] == null  ){
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
        
        $indice = $ko[0]['percentage'];
        //echo "<br/><br/>Name: $stops[name] <br/>Date of pickup: $stops[date_of_pickup] <br/>Payment Method: $stops[payment_method]<br/>Gallons retrieved: $stops[sum]<br/>Adju Gallons: $stops[adj]<br/>MIU: $stops[miu] <br/>PPG: $ppg <br/>Paid: $paid<br/>Rate: $rate <br/>Index at pickup:$indice<br/> UPDATE sludge_data_table SET rate=$rate,	ppg= $ppg,index_at_pickup = $indice, temp_miu = $stops[miu],paid = $paid WHERE route_id= $stops[route_id] AND account_no = $stops[account_ID] AND schedule_id =$stops[schedule_id] <br/><br/>------- ";
        $db->query("UPDATE sludge_data_table SET rate=$rate,	ppg= $ppg,index_at_pickup = $indice, temp_miu = $stops[miu],paid = $paid,payment_method='$stops[payment_method]' WHERE entry_number = $stops[entry_number]");
    }
}
   
   
} else {
    echo "No completed stops.";
   
}

$db->query("UPDATE sludge_ikg_manifest_info SET driver_completed_date='$date' WHERE route_id=$_GET[route_id]");
 $db->query("UPDATE sludge_list_of_routes SET stops = $count, collected = $tot, expected = $avg,driver_completed_date='$date' WHERE route_id=$_GET[route_id]");
 
 $db->query("UPDATE sludge_list_of_routes SET inc = inc - $count WHERE route_id=$_GET[route_id] AND inc >0");


//************************************* RETURN uncomplete stops to pickups pool ********************************//    
 $db->query("UPDATE sludge_scheduled_routes set route_status='scheduled',route_id=null WHERE route_id=$_GET[route_id] AND route_status in ('enroute','scheduled')");
//************************************* RETURN uncomplete stops to pickups pool ********************************//
 
 
 
 


?>