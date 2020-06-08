<?php
ini_set("display_errors",1);
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
        "account_no"=>$_POST['account_no'],
        "completed"=>1,
        "fieldreport"=>$_POST['field_note'],
        "driver"=>$_POST['driver'],
        "date_of_pickup"=>$_POST['dop']
    );
    echo  "<img src='img/check_green_2s.png'/>&nbsp;".$ant->name." inserted with $_POST[picked_up] gallons"; 
    $db->insert('freight_data_table',$data)."<br/>";
}else {
    $data = Array(
        "route_id"=> $_POST['route_id'],
        "schedule_id"=>$_POST['schedule_number'],
        "account_no"=>$_POST['account_no'],
        "completed"=>1,
        "fieldreport"=>$_POST['field_note'],
    );
    echo "<img src='img/check_green_2s.png'/>&nbsp;".$ant->name." updated with $_POST[picked_up] gallons"; 
    echo $db->where('entry_number',$_POST['entry'])->update($dbprefix."_data_table",$data)."<br/>";
}

//mark current schedule complete

/*
echo "<pre>";
print_r($data);
echo "</pre>";
*/


$db->query("UPDATE freight_scheduled_routes SET route_status='completed' WHERE schedule_id=$_POST[schedule_number] AND route_id=$_POST[route_id]");



$nb =$db->query("SELECT schedule_id FROM freight_scheduled_routes WHERE route_id= $_POST[route_id] AND route_status='enroute'");

$incs = count($nb);

$db->query("UPDATE freight_list_of_routes SET inc = $incs WHERE route_id=$_POST[route_id]");




/**/

?>