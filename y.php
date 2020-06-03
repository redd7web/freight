<?php
ini_set("display_errors",1);
include "protected/global.php";


$ytt = $db->query("SELECT freight_grease_traps.account_no,freight_grease_traps.grease_no,freight_accounts.payment_method as pm,freight_accounts.account_ID FROM `freight_grease_traps` LEFT JOIN freight_accounts on freight_accounts.account_ID = freight_grease_traps.account_no WHERE  freight_accounts.account_ID IS NOT NULL");

if(count($ytt)>0){
    foreach($ytt as $c){
        $db->query("UPDATE freight_grease_traps SET payment_method = '$c[pm]' WHERE account_no = $c[account_ID] AND grease_no = $c[grease_no]");
        echo "UPDATE freight_grease_traps SET payment_method = '$c[pm]' WHERE account_no = $c[account_ID] AND grease_no = $c[grease_no]<br/>";
    }
}
/*
$i = $db->query("SELECT freight_grease_data_table.paid, freight_grease_data_table.inches_to_gallons,freight_grease_data_table.account_no,route_id,schedule_id,date_of_pickup,ppg,freight_grease_traps.payment_method FROM freight_grease_data_table LEFT JOIN freight_grease_traps ON freight_grease_traps.account_no = freight_grease_data_table.account_no AND freight_grease_traps.grease_no = freight_grease_data_table.schedule_id AND freight_grease_traps.grease_route_no = freight_grease_data_table.route_id");

if(count($i)>0){
    foreach($i as $k){
        switch($k['payment_method']){
            case "Per Gallon":
                $db->query("UPDATE freight_grease_data_table SET paid = $k[inches_to_gallons] * $k[ppg]  WHERE route_id = $k[route_id]");
            break;
            case "No Pay":
                 $db->query("UPDATE freight_grease_data_table SET paid = 0.00 WHERE route_id = $k[route_id]");
            break;
            case "Charge Per Pickup":
                $db->query("UPDATE freight_grease_data_table SET paid = $k[ppg] WHERE route_id = $k[route_id]");
            break;
        }
    }
}*/
?>