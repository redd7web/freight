<?php

//from enter data page
include "protected/global.php";


$dte = date("Y-m-d");
$buffer = array(
    "status"=>"completed",
    'completed_date' =>$dte
);

$b3 = array(
    'completed_date' =>$dte
);


$db->where('route_id',$_POST['route_id'])->update($dbprefix."_list_of_utility",$buffer);
$db->where('route_id',$_POST['route_id'])->update($dbprefix."_ikg_utility",$b3);
$db->where('route_status','completed')->where('rout_no',$_POST['route_id'])->update('sludge_utility',$b3);

$po = $db->query("UPDATE sludge_utility SET rout_no = null,route_status='scheduled' WHERE route_status IN('scheduled','enroute') AND rout_no=$_POST[route_id]");


echo "debug<br/>";
?>







