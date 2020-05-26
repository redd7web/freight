<?php
include "protected/global.php";// from enter util data page


$co =0;
$acnts ="";
$util = $db->query("SELECT schedule_id,account_no FROM sludge_utility_data_table WHERE route_id = $_POST[route_id]");
if(count($util)>0){//stops in data table mark complete
    foreach($util as $stops){
        $db->query("UPDATE sludge_utility SET ='completed' WHERE utility_sched_id =$stops[schedule_id]");
        $acnts .= $stops['account_no']."|";
        $co++;
    }
}


$buffer = array(
    "status"=>"$_POST[status]",
    'completed_date' =>date("Y-m-d H:i:s"),
    "stops"=>$co
);
$b3 = array(
    'completed_date' =>date("Y-m-d"),
    "account_numbers"=>$acnts
);
$db->where('route_id',$_POST['route_id'])->update($dbprefix."_list_of_utility",$buffer);
$db->where('route_id',$_POST['route_id'])->update($dbprefix."_ikg_utility",$b3);

//****************************************return uncompleted stops to pool*************************************/
$db->query("UPDATE sludge_utility SET route_status='scheduled',rout_no=null WHERE route_status IN('enroute','scheduled')");
//****************************************return uncompleted stops to pool*************************************/














?>