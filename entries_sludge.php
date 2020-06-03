<?php
include "protected/global.php";

$check_complete = $db->query("SELECT DISTINCT(freight_list_of_grease.route_id),freight_list_of_grease.status FROM freight_list_of_grease LEFT JOIN freight_grease_data_table ON freight_grease_data_table.route_id = freight_list_of_grease.route_id WHERE freight_list_of_grease.status='completed' AND (freight_grease_data_table.account_no = $_GET[tank] OR freight_grease_data_table.facility_origin =$_GET[tank] ) GROUP BY freight_list_of_grease.route_id");



if(count($check_complete)>0){
    echo count($check_complete);
}else{
    echo "-1";
}





?>