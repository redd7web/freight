<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);
//FROM UTIL manifest
include "protected/global.php";

    
    $person = new Person();    
    $acnts = explode("|",$_POST['accounts']);
    array_pop($acnts);
    $sches = explode("|",$_POST['schedules']);
    array_pop($sches);
    $ikg = Array(
        "ikg_manifest_route_number"=>$_POST['ikg_manifest_route_number'],
        "tank1"=>$_POST['tank_1'],
        "tank2"=>$_POST['tank_2'],
        "time_start"=>$_POST['time_start'],
        "start_mileage"=>$_POST['start_mileage'],
        "scheduled_date"=>$_POST['sched_route_start'],
        "truck"=>$_POST['vehicle'],
        "first_stop"=>$_POST['first_stop'],
        "first_stop_mileage"=>$_POST['first_stop_mileage'],
        "completed_date"=>$_POST['completion_date'],
        "license_plate"=>$_POST['lic_plate'],
        "last_stop"=> $_POST['last_stop'],
        "last_top_mileage"=>$_POST['last_stop_mileage'],    
        "ikg_decal"=>$_POST['ikg_decal'],
        "end_time"=>$_POST['end_time'],
        "end_mileage"=>$_POST['end_mileage'],
        "ikg_collected"=>$_POST['ikg_collected'],
        "fuel"=>$_POST['fuel'],
        "inventory_code"=>$_POST['inventory_code'],
        "lot_no"=>$_POST['lot_no'],
        "gross_weight"=>$_POST['gross_weight'],
        "recieving_facility"=>$_POST['reciev_fac'],
        "tare_weight"=>$_POST['tara_weight'],
        "facility_address"=>$_POST['fac_address'],
        "net_weight"=>$_POST['net_weight'],       
        "ikg_transporter"=>$_POST['ikg_transporter'],
        "number_days_route"=>$_POST['mult_day_route'],
        "driver"=>$_POST['drivers'],
        "account_numbers"=>$_POST['accounts'],
        "location"=>$_POST['location']        
    );
        
   
    $db->where('route_id',$_POST['rid'])->update($dbprefix."_ikg_utility",$ikg);
   // updating list of routes
    $list_of_routes = array(        
        "ikg_manifest_route_number"=>$_POST['ikg_manifest_route_number'],
        "driver"=>$_POST['drivers'],
        "facility"=>$_POST['reciev_fac'],
        "scheduled"=>$_POST['sched_route_start']
    );
    $db->where("route_id",$_POST['rid'])->update($dbprefix."_list_of_utility",$list_of_routes);
    
   
    
      
    
     if(isset($_POST['newly_added_stops']) && isset($_POST['newly_added_accounts'])  && strlen($_POST['newly_added_stops'])>0 && strlen($_POST['newly_added_accounts'])>0 ){//if user is adding stops, update new stops as enroute and route id         
        //echo "new stops ".$_POST['newly_added_stops']." ".$_POST['newly_added_accounts']."<br/>";
         $new_stops = array_map('intval', explode('|', $_POST['newly_added_stops']));
         $new_accounts = array_map('intval', explode('|', $_POST['newly_added_accounts']));
         array_pop($new_stops);
         array_pop($new_accounts);
         $db->query("UPDATE freight_utility SET route_status='enroute', rout_no=$_POST[rid] WHERE utility_sched_id in(".implode(" , ", $new_stops).")");
   }
   
    $bv = $db->query("SELECT COUNT(utility_sched_id) as stopos FROM freight_utility WHERE rout_no =$_POST[rid]");
     
   if(count($bv)>0){
     $opc = $bv[0]['stopos'];
   } else {
    $opc = 0;
   }
   
   
   $bu = $db->query("SELECT count(utility_sched_id) as inkomple FROM freight_utility WHERE route_status='enroute' AND rout_no =$_POST[rid]");
   
    $list_of_routes = array(        
        "ikg_manifest_route_number"=>$_POST['ikg_manifest_route_number'],
        "driver"=>$_POST['drivers'],
        "facility"=>$_POST['reciev_fac'],
        "stops"=>$opc,
        "inc"=>$bu[0]['inkomple']
    );
    
    $db->where("route_id",$_POST['rid'])->update($dbprefix."_list_of_utility",$list_of_routes);
    
   
  
   
   if(   isset($_POST['return_stops']) && strlen($_POST['return_stops']) >0 && $_POST['return_stops'] !='' ){    
       $return_this = array_map('intval', explode('|', $_POST['return_stops']));
        array_pop($return_this);
        $ia = 0;
        foreach($return_this as $update_this_stop){
            $sched_info = new Util_Stop($update_this_stop);
             echo "Stop: ".$update_this_stop." removed <br/>";
             $ia++;
             $k .= $sched_info->account_number."|";  
        }
        $db->query("UPDATE freight_utility SET route_status='scheduled', rout_no=null WHERE utility_sched_id IN( ". implode(',',$return_this)."  )");
        $db->query("UPDATE freight_ikg_utility SET account_numbers = REPLACE(account_numbers, '$k', '') WHERE rout_no=$_POST[rid]");
    }
    
    $db->query("UPDATE freight_utility SET date_of_service ='$_POST[sched_route_start]' WHERE route_no = $_POST[rid]");
    
    
    $uo = $db->query("SELECT account_numbers FROM freight_ikg_utility WHERE route_id=$_POST[rid] AND account_numbers NOT LIKE '%|%'");
    if(count($uo)>0){
        $ko = wordwrap($uo[0]['account_numbers'],5,'|',true);
        $ko = $ko."|";
        $ko = str_replace("||","|",$ko);
        $db->query("UPDATE freight_ikg_utility SET account_numbers = '$ko' WHERE rout_no=$_POST[rid]");
    }
    
    $history = array(    
        "route_no"=>(int)$_POST['rid'],
        "what_day"=>(int)$_POST['what_day'],
        "start_mileage"=>trim($_POST['start_mileage']),
        "first_stop_mileage"=>trim($_POST['first_stop_mileage']),
        "last_stop_mileage"=>$_POST['last_stop_mileage'],
        "first_stop"=>trim($_POST['first_stop']),
        "last_stop"=>trim($_POST['last_stop']),
        "driver"=>$_POST['drivers'],
        "truck_id"=>trim($_POST['vehicle']),
        "gross_weight"=>$_POST['gross_weight'],
        "tare_weight"=>$_POST['tara_weight'],
        "start_date"=>date("Y-m-d")." ".$_POST['time_start'],
        "end_mileage"=>$_POST['end_mileage'],
        "end_date"=>$_POST['completion_date']." ".date("H:i:s"),
        "last_stop_mileage"=>$_POST['last_stop_mileage'],
        "type"=>'util'        
    );

    $exist_ = $db->where('route_no',$_POST['rid'])->where('what_day',$_POST['what_day'])->get($dbprefix."_rout_history");
    
    if( count($exist_) == 0 ){
        $db->insert($dbprefix."_rout_history",$history);
    }  else {
        $db->where('route_no',$_POST['rid'])->where('what_day',$_POST['what_day'])->update($dbprefix."_rout_history",$history);
    }
    
?>

