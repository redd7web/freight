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
    "recieving_facility"=>$_POST['reciev_fac'],
    "facility_address"=>$_POST['fac_address'],
    "driver"=>$_POST['drivers'],
    "ikg_transporter"=>"Co-West",
    "account_numbers"=>$_POST['accounts'],
    "customer_name"=>"Co-West",
    "scheduled_date"=>date("Y-m-d")                    
);


//var_dump($ikg);
$db->insert("freight_ikg_grease",$ikg);

$id = $db->getInsertId();

$ikg2 = array(
    "route_id"=>$id,
    "status"=>"enroute",
    "ikg_manifest_route_number"=>$_POST['ikg_manifest_route_number'],
    "facility"=>$_POST['reciev_fac'],
    "created_date"=>$created_date,
    "created_by"=>$person->user_id,
    "scheduled"=>date("Y-m-d") ,
    "driver"=>$_POST['drivers'],
    "stops"=>1,
    "inc"=>1
);
$db->insert($dbprefix.'_list_of_grease',$ikg2);



foreach($these_schedules as $numbers){
    //echo "UPDATE freight_grease_traps SET route_status ='enroute', grease_route_no = $id WHERE grease_no = $numbers<br/>";
    $db->query("UPDATE freight_grease_traps SET route_status ='enroute', grease_route_no = $id WHERE grease_no = $numbers");
}

    /*
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
    }*/








echo $id;
?>