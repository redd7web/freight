<?php // from util

include "protected/global.php";
$list_of_grease = $dbprefix."_list_of_grease";
$person = new Person();
ini_set("display_errors",0);

$grease_ikg = new Grease_IKG($_POST['rid']);

$jix = preg_replace('/[^A-Za-z0-9\-]/', '', trim($_POST['ikg_manifest_route_number']));
$ikg = Array(
    "ikg_manifest_route_number"=>$jix,
    "trailer_decal"=>$_POST['trailer_decal'],
    "trailer_lp"=>$_POST['trailer_lp'],
    "time_start"=>$_POST['time_start'],
    "start_mileage"=>$_POST['start_mileage'],
    "scheduled_date"=>$_POST['sched_route_start'],
    "truck"=>$_POST['vehicle'],
    "first_stop"=>$_POST['first_stop'],
    "first_stop_mileage"=>$_POST['first_stop_mileage'],
    "completed_date"=>$_POST['completion_date'],
    "license_plate"=>$_POST['lic_plate'],
    "last_stop"=>$_POST['last_stop'],
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
    "facility_rep"=>$_POST['fac_rep'],
    "ikg_transporter"=>$_POST['ikg_transporter'],
    "number_days_route"=>$_POST['mult_day_route'],
    "driver"=>$_POST['drivers'],
    "account_numbers"=>$_POST['accounts'],
    "location"=>$_POST['location'],
    "wtn"=>$_POST['wtn'],
    "bol"=>$_POST['bol'],
    "route_notes"=>$_POST['route_notes'],
    "percent_fluid"=>$_POST['percent_fluid'],
    "trailer"=>$_POST['trailer'],
    "conductivity"=>$_POST['conduct'],
    "customer_name"=>$_POST['customer_name']                
);

$db->where("route_id",$_POST['rid'])->update($dbprefix."_ikg_grease",$ikg);

$yt = $db->query("SELECT count(grease_no) as icn FROM freight_grease_traps WHERE grease_route_no=$_POST[rid] AND route_status='enroute'");

$db->query("UPDATE freight_grease_traps SET service_date ='$_POST[sched_route_start]' WHERE grease_route_no =$_POST[rid]");


if(strlen($_POST['drivers'])>0 && $_POST['drivers'] != "-"){
    $drivers = $_POST['drivers'];
} else {
    $drivers =0;
}

if(strlen($_POST['reciev_fac'])>0 && $_POST['reciev_fac'] != "ignore"){
    $fac = $_POST['reciev_fac'];
} else {
    $fac =0;
}

echo "UPDATE freight_list_of_grease SET ikg_manifest_route_number ='$jix',driver=$drivers,facility=$fac,scheduled='$_POST[sched_route_start]',inc=".$yt[0]['icn']." WHERE route_id=$_POST[rid]";

$db->query("UPDATE freight_list_of_grease SET ikg_manifest_route_number ='$jix',driver=$drivers,facility=$fac,scheduled='$_POST[sched_route_start]',inc=".$yt[0]['icn']." WHERE route_id=$_POST[rid]");




if( isset($_POST['remove_stops']) && strlen(trim($_POST['remove_stops']) )>0  ){//did the user remove any stops?
    $removed_stops = explode('|',$_POST['remove_stops']);
    array_pop($removed_stops);
    
    $ko = count($removed_stops);
    $stat = array( 
        "route_status"=>"scheduled",
        "grease_route_no"=>NULL
    );
    
    foreach($removed_stops as $stops ){
        $db->where('grease_no',$stops)->update($dbprefix."_grease_traps",$stat);
        $hj = $db->query("SELECT account_no FROM freight_grease_traps WHERE grease_no = ".$stops);
        $kc .=$hj[0]['account_no']."|";
        $db->query("DELETE FROM freight_data_table WHERE schedule_id =$stops AND route_id=$_POST[rid]");
    }
    $db->query("UPDATE freight_ikg_grease SET account_numbers = REPLACE(account_numbers, '$kc', '') WHERE route_id=$_POST[rid]");
}


if ( strlen( trim($_POST['new_stops']) )>0 && strlen( trim($_POST['new_accounts']) )  >0 ){
    $add_these = array_map('intval',  explode("|",$_POST['new_stops'])   );
    array_pop($add_these);
    $add = count($add_these);   
    $db->query("UPDATE freight_grease_traps SET grease_route_no = $_POST[rid],service_date='$_POST[sched_route_start]', route_status='enroute' WHERE grease_no IN(". implode(",",$add_these) .")");
    $db->query("UPDATE freight_list_of_grease SET stops = stops + $add, inc = inc+ $add WHERE route_id= $_POST[rid]");  
    foreach($add_these as $q){
        $g = new Grease_Stop($q);
        $ant = new Account($g->account_number);
        switch($ant->payment_method){
            case "Charge Per Pickup":
                $number = $ant->index_percentage;
                break;
            case "Per Gallon":
                $number = $ant->grease_ppg;
                break;
            default:
                $number = 0;
                break;
        }        
        
    }
    
    //***************** TEXT DRIVER NEW STOPS IF THERE ARE COMPLETED STOPS IN THE ROUTE****************************//
    $check = $db->query("SELECT * FROM freight_grease_data_table WHERE route_id = $_POST[rid]");
    if(count($check)>0){// have you already completed any stops in the route?
         if(isset($_POST['drivers']) && strlen($_POST['drivers'])>0 && $_POST['drivers'] !="-"){
            $driver = new Person($_POST['drivers']);
            $headers = "From: " . strip_tags("iwpcommandcenter@iwpusa.com") . "\r\n";
            $headers .= "Reply-To: ". strip_tags("no-reply@iwpusa.com") . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            switch($driver->carrier){
                case "verizon":
                    $carrier = "vtext.com";
                    break;
                case "Tmobile":
                    $carrier = "tmomail.net";
                    break;
                default: 
                    $carrier = "vtext.com";
                    break;
            }
            $name_list="";
            foreach($add_these as $names){
                $name = new Grease_Stop($names);
                $name_list .= $name->account_name.",";
            }
            mail("$driver->area_code$driver->phone@$carrier","New Stops added!","$name_list added \r\nClick the following to view the route \r\n<a href='grease_route_gateway.php?route_id=$id'>$id</a>",$headers);
    //***************** TEXT DRIVER NEW STOPS IF THERE ARE COMPLETED STOPS IN THE ROUTE****************************//
        }
    }
     
}




$history = array(    
    "route_no"=>(int)$_POST['rid'],
    "what_day"=>(int)$_POST['mult_day_route'],
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
    "end_date"=>$_POST['completion_date'],
    "last_stop_mileage"=>$_POST['last_stop_mileage'],
    "type"=>'grease',  
    "time_end"=>$_POST['end_time'],  
    "time_start"=>$_POST['time_start'] 
);

$exist_ = $db->where('route_no',(int)$_POST['rid'])->where('what_day',$_POST['mult_day_route'])->get($dbprefix."_rout_history_grease");

if( count($exist_) == 0 ){
    echo "<br/>dne";
    $db->insert($dbprefix."_rout_history_grease",$history);
}  else {
    echo "<br/>exists";
    $db->where('route_no',$_POST['rid'])->where('what_day',$_POST['mult_day_route'])->update($dbprefix."_rout_history_grease",$history);
}

$track = array(
    "date"=>date("Y-m-d H:i:s"),
    "user"=>$person->user_id,
    "actionType"=>"Grease Route Updated",
    "descript"=>"Grease Route Updated <form action='grease_ikg.php' target='_blank' method='post' class='ikg_form' style='cursor:pointer;'><span style='color:blue;text-decoration:underline;'>$_POST[rid]</span><input type='hidden' value='$_POST[rid]' name='util_routes'/><input type='hidden' value='1' name='from_routed_grease_list'/></form>",
    "pertains"=>6,
    "type"=>14
);
$db->insert("xlogs.".$dbprefix."_activity",$track);
?>