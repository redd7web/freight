<?php
include "protected/global.php";
ini_set("display_errors",1);
$db->query("UPDATE freight_grease_traps SET zero_charge_pickup = $_POST[value] WHERE grease_no = $_POST[grease_no]");


if($_POST['value'] ==1){
    $db->query("UPDATE freight_grease_data_table SET paid = 0 WHERE schedule_id= $_POST[grease_no]");
}

if($_POST['value']==0){
    $paid =0;
    $yt = $db->query("SELECT payment_method,account_no FROM freight_grease_traps WHERE grease_no = $_POST[grease_no]");
    $ty = $db->query("SELECT * FROM freight_grease_data_table WHERE schedule_id = $_POST[grease_no] AND account_no = ".$yt[0]['account_no']);
    if(count($ty)>0){
        switch($yt[0]['payment_method']){
            case "Charge Per Pickup":case "Index":
                $paid = $ty[0]['ppg']; 
                break;
            case "Per Gallon":case "Normal":
                $paid = $ty[0]['ppg'] * $ty[0]['inches_to_gallons'];
                break;
            case "One Time Payment":
                $paid = $ty[0]['otp'];
                break;
            case "No Pay":
                $paid = 0;
                break;
            case "Cash On Delivery":
                $paid = $ty[0]['ppg'];
                break;
            case "O.T.P. PG":
                $paid = ($ty[0]['ppg'] * $ty[0]['inches_to_gallons']) + $ty[0]['otp'];
                break;
            default:
                $paid = 0;
                break;
        }
        $db->query("UPDATE freight_grease_data_table SET paid = $paid WHERE schedule_id= $_POST[grease_no] AND account_no = ".$ty[0]['account_no']);
    }
}

?>