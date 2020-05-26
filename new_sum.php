<?php
include "protected/global.php";

$k = $db->query("SELECT DISTINCT schedule_id FROM sludge_data_table");

    foreach($k as $u){
        echo $u['schedule_id']."<br/>";        
        $ic = $db->query("SELECT SUM(inches_to_gallons)as sum FROM sludge_data_table WHERE schedule_id = $u[schedule_id]");
        
        echo "insert into sum column: ".$ic[0]['sum']."<br/>";
        $ko = $ic[0]['sum'];
        $db->query("UPDATE sludge_data_table SET sum =$ko WHERE schedule_id= $u[schedule_id]");
    }


?>