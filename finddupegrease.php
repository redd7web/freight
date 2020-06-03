<?php
include "protected/global.php";

$k = $db->query("SELECT freight_list_of_grease.status, freight_grease_traps.grease_no, freight_data_table.schedule_id FROM freight_scheduled_routes INNER JOIN freight_data_table ON freight_scheduled_routes.schedule_id = freight_data_table.schedule_id INNER JOIN freight_list_of_routes. WHERE freight_scheduled_routes.route_status = 'scheduled' ORDER BY freight_scheduled_routes.account_no");

foreach($k as $stops_should_be_completed){
    echo  account_NumToName($stops_should_be_completed['account_no'])." ".$stops_should_be_completed['schedule_id']." ".$stops_should_be_completed['date_of_pickup']."<br/>";
    //$db->query("UPDATE freight_scheduled_routes set route_status='completed' WHERE schedule_id = $stops_should_be_completed[schedule_id]");
}


?>