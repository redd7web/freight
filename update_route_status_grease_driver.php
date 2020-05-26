<?php
//from etner grease data

include "protected/global.php";


$tot =0;
$aco_nums ="";
$count = 0;
$op = $db->query("SELECT * FROM sludge_grease_data_table WHERE route_id=$_POST[route_id]");
if(count($op)>0){
    foreach($op as $compl){
        $tot = $compl['inches_to_gallons'] + $tot;
        $aco_nums .= $compl['account_no']."|";
        $count++;
        //$db->query("UPDATE sludge_grease_traps SET route_status='completed' WHERE grease_route_no = $_POST[route_id]");
    }
}



$buffer = array(    
    'driver_completed_date'=>date("Y-m-d H:i:s"),
    "collected"=>$tot,
    "stops"=>$count
);

$b3 = array(
    'driver_completed_date'=>date("Y-m-d H:i:s"),
    "account_numbers"=>$aco_nums
);
$db->where('route_id',$_POST['route_id'])->update($dbprefix."_list_of_grease",$buffer);
$db->where('route_id',$_POST['route_id'])->update($dbprefix."_ikg_grease",$b3);




//$db->query("UPDATE sludge_grease_traps SET grease_route_no=null, route_status='scheduled' WHERE grease_route_no = $_POST[route_id] AND route_status IN('enroute','scheduled')");







?>







