<?php
include "protected/global.php";

$check_complete = $db->query("SELECT DISTINCT(sludge_list_of_grease.route_id),sludge_list_of_grease.status FROM sludge_list_of_grease LEFT JOIN sludge_grease_data_table ON sludge_grease_data_table.route_id = sludge_list_of_grease.route_id WHERE sludge_list_of_grease.status='completed' AND (sludge_grease_data_table.account_no = $_GET[tank] OR sludge_grease_data_table.facility_origin =$_GET[tank] ) GROUP BY sludge_list_of_grease.route_id");



if(count($check_complete)>0){
    echo count($check_complete);
}else{
    echo "-1";
}





?>