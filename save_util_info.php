
<?php
include "protected/global.php";
$list_of_utility_route = $dbprefix."_list_of_utility";
//ini_set("display_errors",1);
$dte = date("Y-m-d");

$person = new Person();
$ant = new Account($_POST['account_no']);
$util_sched = new Util_Stop($_POST['schedule_number']);


/**/



/**/
$data = Array(
    "route_id"=> $_POST['route_id'],
    "schedule_id"=>$_POST['schedule_number'],     
    "account_no"=>$_POST['account_no'],
    "completed"=>1,
    "fieldreport"=>$_POST['field_note'],
    "zero_gallon_reason"=>$_POST['zero_gallon_reason'],
    "driver"=>$_POST['driver'],
    "date_of_pickup"=>$_POST['dop']
);

//var_dump($data);
$db->insert($dbprefix.'_utility_data_table',$data);
echo "sched update status: ".$db->query("UPDATE sludge_utility SET route_status='completed' WHERE utility_sched_id = $_POST[schedule_number] AND rout_no=$_POST[route_id]")." ";  


//schedule stop 



?>