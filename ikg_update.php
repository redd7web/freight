<?php

    include "protected/global.php";
    ini_set("display_errors",1);
    
    $person = new Person();
    $accountx = new Account();
    $kt = date("Y-m-d");
    $ikg_info = new IKG($_POST['rid']);
    $acnts = explode("|",$_POST['accounts']);
    $these_schedules = explode("|",$_POST['schedules']);
    $new_stops = explode("|",$_POST['scheds_to_add']);
    $new_accounts = explode("|",$_POST['accounts_to_add']);
    array_pop($these_schedules);
    array_pop($acnts);
    array_pop($new_stops);
    array_pop($new_accounts);
    
    $new_tot = 0;
    $update_stops = count($these_schedules);
    $exp =0;
    
    
    
    //*******************UPDATE NEW STOPS WITH ENROUTE AND CORRESPONDING ROUTE ID***************//
    if(strlen($_POST['scheds_to_add'])>0 && strlen($_POST['accounts_to_add'])>0  ){
        $db->query("UPDATE freight_scheduled_routes SET route_status='enroute', route_id=$_POST[rid] WHERE schedule_id in(".implode(" , ", $new_stops).")");
    }
    //*******************UPDATE NEW STOPS WITH ENROUTE AND CORRESPONDING ROUTE ID***************//
    
    
    if( isset($_POST['accounts']) ){
        $db->query("UPDATE freight_ikg_manifest_info SET account_numbers ='$_POST[accounts]' WHERE route_id=$_POST[rid]");
    }
    
     //*********************** REMOVE STOPS ******************************//
    if(isset($_POST['scheds_to_remove']) && strlen($_POST['scheds_to_remove'])> 0 && $_POST['scheds_to_remove'] !=""){    
        $return_this = array_map('intval', explode('|', $_POST['scheds_to_remove']));
        array_pop($return_this);
        $ia = 0;
        foreach($return_this as $update_this_stop){
            $sched_info = new Util_Stop($update_this_stop);
             echo "Stop: ".$update_this_stop." removed <br/>";
             $ia++;
             $k .= $sched_info->account_number."|";  
        }
        $db->query("UPDATE freight_scheduled_routes SET route_status='scheduled', route_id=null WHERE schedule_id IN( ". implode(',',$return_this)."  )");        
        $db->query("UPDATE freight_ikg_manifest_info SET account_numbers = REPLACE(account_numbers, '$k', '') WHERE route_id=$_POST[rid]");
       
        
    }
    //*********************** REMOVE STOPS ******************************//
    
    
    if(isset($_POST['ikg_manifest_route_number'])){
        $jix = preg_replace('/[^A-Za-z0-9\-]/','', trim($_POST['ikg_manifest_route_number']));
        $db->query("UPDATE freight_ikg_manifest_info SET ikg_manifest_route_number ='$jix' WHERE route_id=$_POST[rid]");
         $db->query("UPDATE freight_list_of_routes SET ikg_manifest_route_number ='$jix' WHERE route_id=$_POST[rid]");    
    }
    
    if(   strlen(trim($_POST['start_mileage']))>0  ){
        $db->query("UPDATE freight_ikg_manifest_info SET start_mileage =$_POST[start_mileage] WHERE route_id=$_POST[rid]");    
    }
    if( strlen(trim($_POST['sched_route_start']))>0 ){
         $db->query("UPDATE freight_ikg_manifest_info SET scheduled_date ='$_POST[sched_route_start]' WHERE route_id=$_POST[rid]");
    }
    if(   strlen(trim($_POST['location']))>0   ){
        $db->query("UPDATE freight_ikg_manifest_info SET location ='$_POST[location]' WHERE route_id=$_POST[rid]");
    }
    if( strlen(trim($_POST['inventory_code']))>0  ){
        $db->query("UPDATE freight_ikg_manifest_info SET inventory_code ='$_POST[inventory_code]' WHERE route_id=$_POST[rid]");
    }
    if(  strlen(trim($_POST['gross_weight']))>0   ){
        $db->query("UPDATE freight_ikg_manifest_info SET gross_weight =$_POST[gross_weight] WHERE route_id=$_POST[rid]");    
    }
    
    
    
    if( strlen(trim($_POST['lot_no']))>0   ){
        $db->query("UPDATE freight_ikg_manifest_info SET lot_no ='$_POST[lot_no]' WHERE route_id=$_POST[rid]");    
    }
    if(isset($_POST['reciev_fac']) && $_POST['reciev_fac'] !="ignore" ){
        $db->query("UPDATE freight_ikg_manifest_info SET recieving_facility =$_POST[reciev_fac] WHERE route_id=$_POST[rid]");    
        $db->query("UPDATE freight_list_of_routes SET facility =$_POST[reciev_fac] WHERE route_id=$_POST[rid]");
    }
    
    
    if( strlen(trim($_POST['fac_address']))>0   ){
        $db->query("UPDATE freight_ikg_manifest_info SET facility_address ='$_POST[fac_address]' WHERE route_id=$_POST[rid]");    
        
    }
    if( strlen(trim($_POST['fac_rep']))>0  ){
        $db->query("UPDATE freight_ikg_manifest_info SET facility_rep ='$_POST[fac_rep]' WHERE route_id=$_POST[rid]");    
    }
    if(isset($_POST['drivers']) && $_POST['drivers'] !="-"){
        $db->query("UPDATE freight_ikg_manifest_info SET driver =$_POST[drivers] WHERE route_id=$_POST[rid]");
        $db->query("UPDATE freight_list_of_routes SET driver = $_POST[drivers] WHERE route_id=$_POST[rid]");    
    }
    
    
    
    
    if( strlen(trim($_POST['lic_plate']))>0   ){
        $db->query("UPDATE freight_ikg_manifest_info SET license_plate ='$_POST[lic_plate]' WHERE route_id=$_POST[rid]");
    }
    
    
    if( strlen(trim($_POST['ikg_decal']))>0   ){
        $db->query("UPDATE freight_ikg_manifest_info SET ikg_decal ='$_POST[ikg_decal]' WHERE route_id=$_POST[rid]");    
    }
    if( strlen(trim($_POST['time_start']))>0  ){
         $db->query("UPDATE freight_ikg_manifest_info SET time_start ='$_POST[time_start]' WHERE route_id=$_POST[rid]");
    }
    
    if(  strlen(trim($_POST['first_stop']))>0   ){
        $db->query("UPDATE freight_ikg_manifest_info SET first_stop ='$_POST[first_stop]' WHERE route_id=$_POST[rid]");    
    }
    if( strlen(trim($_POST['last_stop']))>0  ){
        $db->query("UPDATE freight_ikg_manifest_info SET last_stop ='$_POST[last_stop]' WHERE route_id=$_POST[rid]");
    }
    
    if(  strlen(trim($_POST['end_time']))>0   ){
        $db->query("UPDATE freight_ikg_manifest_info SET end_time ='$_POST[end_time]' WHERE route_id=$_POST[rid]");
    }
    
    if( strlen(trim($_POST['fuel']))>0   ){
        $db->query("UPDATE freight_ikg_manifest_info SET fuel =$_POST[fuel] WHERE route_id=$_POST[rid]");
    }
    
    
    if( strlen(trim($_POST['first_stop_mileage']))>0   ){
        $db->query("UPDATE freight_ikg_manifest_info SET first_stop_mileage =$_POST[first_stop_mileage] WHERE route_id=$_POST[rid]");    
    }
    if( strlen(trim($_POST['last_stop_mileage']))>0  ){
        $db->query("UPDATE freight_ikg_manifest_info SET last_top_mileage =$_POST[last_stop_mileage] WHERE route_id=$_POST[rid]");
    }
    if( strlen(trim($_POST['end_mileage']))>0  ){
        if(strlen($_POST['end_mileage'])>0){
            $db->query("UPDATE freight_ikg_manifest_info SET end_mileage =$_POST[end_mileage] WHERE route_id =$_POST[rid]");
        }    
    }
    
   if( strlen(trim($_POST['mult_day_route']))>0    ){
        $db->query("UPDATE freight_ikg_manifest_info SET number_days_route = $_POST[mult_day_route] WHERE route_id =$_POST[rid]");
    }
    
   if( isset($_POST['vehicle']) && $_POST['vehicle'] !="-" ){
        $db->query("UPDATE freight_ikg_manifest_info SET truck = $_POST[vehicle] WHERE route_id =$_POST[rid]");
   }
    
   if( strlen(trim($_POST['collected_weight']))>0  ){
    $db->query("UPDATE freight_ikg_manifest_info SET collected = $_POST[collected_weight] WHERE route_id =$_POST[rid]");
   }  
    
    if( strlen(trim($_POST['gross_weight']))>0   ){
    $db->query("UPDATE freight_ikg_manifest_info SET gross_weight = $_POST[gross_weight] WHERE route_id =$_POST[rid]");
   } 
    
    if( strlen(trim($_POST['tara_weight']))>0   ){
    $db->query("UPDATE freight_ikg_manifest_info SET tare_weight = $_POST[tara_weight] WHERE route_id =$_POST[rid]");
   } 
    
    if( strlen(trim($_POST['net_weight']))>0  ){
    $db->query("UPDATE freight_ikg_manifest_info SET net_weight = $_POST[net_weight] WHERE route_id =$_POST[rid]");
   } 
   
    
    if( strlen(trim($_POST['difference']))>0  ){
    $db->query("UPDATE freight_ikg_manifest_info SET differences = $_POST[difference] WHERE route_id =$_POST[rid]");
   } 
    
   
    
    
    
    $db->query("UPDATE freight_scheduled_routes SET route_id = $_POST[rid],route_status='enroute', scheduled_start_date ='$_POST[sched_route_start]' WHERE schedule_id IN(".implode(',',$these_schedules).")");
    











$ikg_info->fix_self($_POST['rid']);




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
    "time_end"=>"$_POST[end_time]",
    "time_start"=>"$_POST[time_start]"  
);


/**/
$exist_ = $db->where('route_no',$_POST['rid'])->where('what_day',$_POST['what_day'])->get($dbprefix."_rout_history");

if( count($exist_) == 0 ){
    echo $db->insert($dbprefix."_rout_history",$history);
}  else {
    echo $db->where('route_no',$_POST['rid'])->where('what_day',$_POST['what_day'])->update($dbprefix."_rout_history",$history);
}



/*
$track = array(
    "date"=>date("Y-m-d H:i:s"),
    "user"=> $person->user_id,
    "actionType"=>"Route Updated",
    "descript"=>"Route <form action='oil_routing.php' target='_blank' method='post' class='ikg_form'><span style='font-decoration:underline;color:blue;cursor:pointer;'>$_POST[rid]</span><input type='hidden' value='$_POST[rid]' name='manifest'/><input type='hidden' value='1' name='from_routed_oil_pickups'/></form> Updated",
     "pertains"=>6,
    "type"=>14
);
$db->insert($dbprefix."_activity",$track);
*/
?>