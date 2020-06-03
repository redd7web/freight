<?php
include "protected/global.php";
$person = new Person();
$created_date = date("Y-m-d");

ini_set("display_errors",0);



$accounts = explode("|",$_POST['accounts']);
array_pop($accounts);
$these_schedules = explode("|",$_POST['schedules']);
array_pop($these_schedules);



$jix = preg_replace('/[^A-Za-z0-9\-]/','', trim($_POST['ikg_manifest_route_number']));

$ikg = Array(
    "ikg_manifest_route_number"=>$jix,
    "trailer_lp"=>$_POST['trailer_lp'],
    "trailer_decal"=>$_POST['trailer_decal'],
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


//var_dump($ikg);
 $db->insert($dbprefix."_ikg_grease",$ikg);

$id = $db->getInsertId();

$ikg2 = array(
    "route_id"=>$id,
    "status"=>"enroute",
    "ikg_manifest_route_number"=>$_POST['ikg_manifest_route_number'],
    "facility"=>$_POST['reciev_fac'],
    "created_date"=>$created_date,
    "created_by"=>$person->user_id,
    "scheduled"=>$_POST['sched_route_start'],
    "driver"=>$_POST['drivers'],
    "stops"=>count($accounts),
    "inc"=>count($accounts)
);
$db->insert($dbprefix.'_list_of_grease',$ikg2);




$status = array(
    "route_status"=>"enroute",
    "grease_route_no"=>$id,
    "service_date"=>$_POST['sched_route_start']
);

foreach($these_schedules as $numbers){
    if(in_array($numbers,$_SESSION['temp_stops'])){
        unset($_SESSION['temp_stops'][array_search($numbers, $_SESSION['temp_stops'])]);// when finalizing your route remove stops from temp
        
    }
    $stop = new Grease_Stop($numbers);
    $ant = new Account($stop->account_number);
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
    
    
    $db->where("grease_no",$numbers)->update($dbprefix."_grease_traps",$status);
}

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
        mail("$driver->area_code$driver->phone@$carrier","New Route $id Created!","Click the following to view the route \r\n<a href='grease_route_gateway.php?route_id=$id'>$id</a>",$headers);
    }


$track = array(
    "date"=>date("Y-m-d H:i:s"),
    "user"=>$person->user_id,
    "actionType"=>"Grease Route Created",
    "descript"=>"Grease Route <form action='grease_ikg.php' target='_blank' method='post' class='ikg_form' style='cursor:pointer;'><span style='color:blue;text-decoration:underline;'$id</span><input type='hidden' value='$id' name='util_routes'/><input type='hidden' value='1' name='from_routed_grease_list'/></form> Created",
    "pertains"=>6,
    "type"=>14
);
$db->insert("xlogs.".$dbprefix."_activity",$track);
unset($_SESSION['temp_stops']);







echo $id;
?>