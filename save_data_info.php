<?php
error_reporting(E_WARNING | E_PARSE | E_NOTICE);
include "protected/global.php";
$person = new Person();
$ant = new Account($_POST['account_no']);
$schedx = new Scheduled_Routes($_POST['schedule_number']);
$scheduled_list = $dbprefix."_scheduled_routes";
$account_table = $dbprefix."_accounts";
$list_of_routes = $dbprefix."_list_of_routes";
//echo 'pick up complete <br/><br/>';










if($_POST['entry'] ==0){//does the entry already exist?
    $data = Array(
        "route_id"=> $_POST['route_id'],
        "schedule_id"=>$_POST['schedule_number'],
        "inches_entered"=>$_POST['inches_entered'],
        "inches_to_gallons"=>$_POST['picked_up'],
        "inches_leftover"=>$_POST['inches_left'],
        "inches_to_gallons_leftover"=>$_POST['inches_to_gallons_leftover'],
        "expected_gallons"=>$_POST['gallons_expected'],
        "avg_exp"=>$schedx->onsite,
        "container_label"=>$_POST['label'],
        "issue_number"=>'',
        "account_no"=>$_POST['account_no'],
        "completed"=>1,
        "fieldreport"=>$_POST['field_note'],
        "zero_gallon_reason"=>$_POST['zero_gallon_reason'],
        "driver"=>$_POST['driver'],
        "date_of_pickup"=>$_POST['dop'],
        "sum"=>$_POST['sum'],
        "mileage"=>$_POST['mileage']
    );
    echo  "<img src='img/check_green_2s.png'/>&nbsp;".$ant->name." inserted with $_POST[picked_up] gallons"; 
    $db->insert('sludge_data_table',$data)."<br/>";
}else {
    $data = Array(
        "route_id"=> $_POST['route_id'],
        "schedule_id"=>$_POST['schedule_number'],
        "inches_entered"=>$_POST['inches_entered'],
        "inches_to_gallons"=>$_POST['picked_up'],
        "inches_leftover"=>$_POST['inches_left'],
        "inches_to_gallons_leftover"=>$_POST['inches_to_gallons_leftover'],
        "expected_gallons"=>$_POST['gallons_expected'],
        "avg_exp"=>$schedx->onsite,
        "container_label"=>$_POST['label'],
        "issue_number"=>'',
        "account_no"=>$_POST['account_no'],
        "completed"=>1,
        "fieldreport"=>$_POST['field_note'],
        "zero_gallon_reason"=>$_POST['zero_gallon_reason'],
        "driver"=>$_POST['driver'],
        "sum"=>$_POST['sum'],
        "mileage"=>$_POST['mileage']
    );
    echo "<img src='img/check_green_2s.png'/>&nbsp;".$ant->name." updated with $_POST[picked_up] gallons"; 
    echo $db->where('entry_number',$_POST['entry'])->update($dbprefix."_data_table",$data)."<br/>";
}

//mark current schedule complete
$db->query("UPDATE $scheduled_list SET route_status ='completed' WHERE schedule_id = $_POST[schedule_number]");

/*
echo "<pre>";
print_r($data);
echo "</pre>";
*/


$y = $db->query("SELECT SUM(inches_to_gallons) as cur_tot FROM sludge_data_table WHERE route_id=$_POST[route_id]");
$db->query("UPDATE sludge_list_of_routes set collected = ".$y[0]['cur_tot']." WHERE route_id=$_POST[route_id]");


$db->query("UPDATE sludge_scheduled_routes SET route_status='completed' WHERE schedule_id=$_POST[schedule_number] AND route_id=$_POST[route_id]");



$nb =$db->query("SELECT schedule_id FROM sludge_scheduled_routes WHERE route_id= $_POST[route_id] AND route_status='enroute'");

$incs = count($nb);

$db->query("UPDATE sludge_list_of_routes SET inc = $incs WHERE route_id=$_POST[route_id]");




/**/

?>