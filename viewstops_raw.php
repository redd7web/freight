<?php
include "protected/global.php";

$s = $db->query("SELECT * FROM freight_scheduled_routes WHERE route_id IS NULL AND route_status='scheduled' ORDER BY date_created DESC ,scheduled_start_date DESC");

if(count($s)>0){
    foreach($s as $stops){
        echo  account_NumtoName($stops['account_no'])." # $stops[schedule_id] | created: $stops[date_created] | scheduled: $stops[scheduled_start_date] | facility:  ". numberToFacility($stops['facility_origin'])."<br/><br/>";
    }
}

?>