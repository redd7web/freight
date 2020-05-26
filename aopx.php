<?php
include "protected/global.php";
$person = new Person();
$ant = new Account($_POST['account_no']);
$scheduled_list = $dbprefix."_scheduled_routes";
$account_table = $dbprefix."_accounts";
$list_of_routes = $dbprefix."_list_of_routes";



//***************************** UPDATE LIST OF ROUTES TABLE*************************//

$check_how_much = $db->where("route_id",$_POST['route_id'])->get("sludge_list_of_routes","collected");

if(count($check_how_much)>0){
    $collected = $_POST['picked_up'] + $check_how_much[0]['collected'];    
} else {
    $collected = $_POST['picked_up'];
}

 



//$db->query("UPDATE sludge_list_of_routes SET collected = $collected WHERE route_id=$_POST[route_id] ");
//$db->query("UPDATE sludge_list_of_routes SET  inc  = inc-1 WHERE route_id=$_POST[route_id] AND inc >0 ");


//******* UPDATE LIST OF ROUTES TABLE*************************//



if(isset($_POST['field_note']) && strlen($_POST['field_note'])>0 ){
    $note = array(
        "schedule_id"=>$_POST['schedule_number'],
        "route_id"=>$_POST['route_id'],
        "author_id"=>$person->user_id,
        "date"=>date("Y-m-d"),
        "notes"=>$_POST['field_note'],
        "account_no"=>$_POST['account_no']
    );
    
    $db->insert($dbprefix."_notes",$note);
}


$track = array(
    "date"=>date("Y-m-d H:i:s"),
    "user"=> $person->user_id,
    "actionType"=>"Oil Picked up",
    "descript"=>"Oil Picked up For ".account_NumToName($_POST['account_no'])." Route <form action='oil_routing.php' target='_blank' method='post' class='ikg_form'><span style='font-decoration:underline;color:blue;cursor:pointer;'>$_POST[route_id]</span><input type='hidden' value='$_POST[route_id]' name='manifest'/><input type='hidden' value='1' name='from_routed_oil_pickups'/></form> Driver :". uNumTonName($_POST['driver']),
    "account"=>$_POST['account_no'],
    "pertains"=>6,
    "type"=>14
);
$db->insert($dbprefix."_activity",$track);
?>