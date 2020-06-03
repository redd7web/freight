<?php
include "protected/global.php";

if(isset($_SESSION['freight_id']) ){
    $date = date("Y-m-d");
    $person = new Person();
        
    if(isset($_POST['g_label']) && strlen($_POST['g_label'])>0){
        $db->query("UPDATE freight_accounts SET label = '$_POST[g_label]' WHERE account_ID= $_POST[account]");
    }
    $grease_table = $dbprefix."_grease_traps";
    $kl = new Account($_POST['account']);
     
    switch($kl->payment_method){
        case "Charge Per Pickup":
            $number = $kl->index_percentage;
            break;
        case "Per Gallon":
            $number = $kl->grease_ppg;
            break;
         case "One Time Payment Per Gallon": case "O.T.P. Per Gallon":
            $number =$kl->grease_ppg;
             break;
         case "O.T.P.": case "One Time Payment": case"Cash On Delivery":     
             $number =$kl->ppg_jacobsen_percentage;
        default:
            $number = 0;
            break;
    }
        
    $jet_data = array(
       "account_no"=>$_POST['account']
       ,"date"=>$date
       ,"notes"=>$_POST['trap_description']           
       ,"price_per_gallon"=>$number
       ,"created_by"=>$person->user_id
       ,"frequency"=>$kl->grease_freq
       ,"volume"=>$kl->grease_volume
       ,"service"=>$kl->service_type     
       ,"time_of_service"=>$_POST['time_of_service_note']
       ,"addt_price"=>$_POST['price_additional']
       ,"addt_info"=>trim($_POST['price_additional_info'])          
       ,"service_date"=>$_POST['dos']
       ,"route_status"=>"scheduled"
       ,"active"=>$kl->trap_active
       ,"grease_name"=>$_POST['g_label']
       ,"credit_notes"=>$kl->credit_notes
       ,"credits"=>$kl->credits
       ,"prepay"=>$kl->prepay
       ,"payment_method"=>$kl->payment_method
       
    );
        
        //var_dump($jet_data);
        
    if($person->user_id == 149){//Adam allowed duplicates
       $db->insert($dbprefix."_grease_traps",$jet_data); 
       $id =$db->getInsertId();
       echo $id;
       $db->query("UPDATE freight_accounts SET current_stop=$id WHERE account_ID = $_POST[account]");
    }else {
        $result = $db->query("SELECT * FROM $grease_table WHERE account_no= $_POST[account] AND (route_status IN ('scheduled','enroute') ) ");
        if(count($result) == 0){
            if($db->insert($dbprefix."_grease_traps",$jet_data)){
                $id =$db->getInsertId();
                echo $id;    
                $db->query("UPDATE freight_accounts SET current_stop=$id WHERE account_ID = $_POST[account]");
            }else{
                echo "Insert Failed";
            }
        }
        else {
           $db->query("DELETE  FROM freight_grease_traps WHERE grease_no = ".$result[0]['grease_no']);                          
            if($db->insert($dbprefix."_grease_traps",$jet_data)){
                $id =$db->getInsertId();
                echo $id;    
                $db->query("UPDATE freight_accounts SET current_stop=$id WHERE account_ID = $_POST[account]");
            }else{
                echo "Insert Failed";
            } 
        }
    }
    $track = array(
        "date"=>date("Y-m-d H:i:s"),
        "user"=>$person->user_id,
        "actionType"=>"Grease Trap Manually scheduled",
        "descript"=>"Grease Trap schedule $id manually created",
        "account"=>$_POST['account'],
         "pertains"=>2,
        "type"=>7
    );
    $db->insert($dbprefix."_activity",$track);
                
             /* 
        */    
}else{
    echo "Your session expired please re-login to schedule a grease trap stop.";
}
?>



