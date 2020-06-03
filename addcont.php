<?php
include "protected/global.php";
$request = date("Y-m-d");
$label = $db->where("container_id",$_POST['container_id'])->get($dbprefix."_list_of_containers");

$buffer = array(
    "container_no"=>$_POST['container_id'],
    "account_no"=>$_POST['account_no'],
    "request_date"=>$request,
    "container"=>$label[0]['container_label']
);
$db->insert($dbprefix.'_containers',$buffer);


$routes  = $db->query("SELECT * FROM freight_data_table INNER JOIN freight_list_of_routes ON freight_data_table.route_id = freight_list_of_routes.route_id WHERE account_no = $_POST[account_no] AND status='enroute' ORDER BY date_of_pickup");

if(count($routes)>0){// 
    $new_data = array(
        "expected"=>$label[0]['amount_holds']
    );

    $db->update("account_no",$_POST['account_no'])->where("route_id",$routes[0]['route_id'])->update("freight_data_table",$new_data);
}