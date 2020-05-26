<?php
ini_set("display_errors",1);
include "protected/global.php";


$ytt = $db->query("SELECT sludge_grease_traps.account_no,sludge_grease_traps.grease_no,sludge_accounts.payment_method as pm,sludge_accounts.account_ID FROM `sludge_grease_traps` LEFT JOIN sludge_accounts on sludge_accounts.account_ID = sludge_grease_traps.account_no WHERE  sludge_accounts.account_ID IS NOT NULL");

if(count($ytt)>0){
    foreach($ytt as $c){
        $db->query("UPDATE sludge_grease_traps SET payment_method = '$c[pm]' WHERE account_no = $c[account_ID] AND grease_no = $c[grease_no]");
        echo "UPDATE sludge_grease_traps SET payment_method = '$c[pm]' WHERE account_no = $c[account_ID] AND grease_no = $c[grease_no]<br/>";
    }
}
/*
$i = $db->query("SELECT sludge_grease_data_table.paid, sludge_grease_data_table.inches_to_gallons,sludge_grease_data_table.account_no,route_id,schedule_id,date_of_pickup,ppg,sludge_grease_traps.payment_method FROM sludge_grease_data_table LEFT JOIN sludge_grease_traps ON sludge_grease_traps.account_no = sludge_grease_data_table.account_no AND sludge_grease_traps.grease_no = sludge_grease_data_table.schedule_id AND sludge_grease_traps.grease_route_no = sludge_grease_data_table.route_id");

if(count($i)>0){
    foreach($i as $k){
        switch($k['payment_method']){
            case "Per Gallon":
                $db->query("UPDATE sludge_grease_data_table SET paid = $k[inches_to_gallons] * $k[ppg]  WHERE route_id = $k[route_id]");
            break;
            case "No Pay":
                 $db->query("UPDATE sludge_grease_data_table SET paid = 0.00 WHERE route_id = $k[route_id]");
            break;
            case "Charge Per Pickup":
                $db->query("UPDATE sludge_grease_data_table SET paid = $k[ppg] WHERE route_id = $k[route_id]");
            break;
        }
    }
}*/
?>