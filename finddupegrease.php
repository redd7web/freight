<?php
include "protected/global.php";

$k = $db->query("SELECT sludge_list_of_grease.status, sludge_grease_traps.grease_no, sludge_data_table.schedule_id FROM sludge_scheduled_routes INNER JOIN sludge_data_table ON sludge_scheduled_routes.schedule_id = sludge_data_table.schedule_id INNER JOIN sludge_list_of_routes. WHERE sludge_scheduled_routes.route_status = 'scheduled' ORDER BY sludge_scheduled_routes.account_no");

foreach($k as $stops_should_be_completed){
    echo  account_NumToName($stops_should_be_completed['account_no'])." ".$stops_should_be_completed['schedule_id']." ".$stops_should_be_completed['date_of_pickup']."<br/>";
    //$db->query("UPDATE sludge_scheduled_routes set route_status='completed' WHERE schedule_id = $stops_should_be_completed[schedule_id]");
}


?>