<?php
include "protected/global.php";
ini_set("display_errors",1);
$account = new Account($_POST['account']);
$c ="";
     if(strlen(trim($_POST['mult_stop'])) && isset($_POST['mult_stop']) ){//update multiple stops payment option 
        $stops = explode("~",$_POST['mult_stop']);
        array_pop($stops);
        $c =" WHERE grease_no IN($account->current_stop,".implode(",",$stops).")";
        //echo "multi stop ? $c?<br/>";
     }else{
        $c = "WHERE grease_no = $account->current_stop";
     }

    switch($_POST['payment_method']){
        case "No Pay":
            $db->query("UPDATE sludge_accounts SET grease_ppg = 0 WHERE account_ID =$_POST[account]");
            //echo "UPDATE sludge_grease_traps SET price_per_gallon = 0,payment_method = '$_POST[payment_method]' $c";
            $db->query("UPDATE sludge_grease_traps SET price_per_gallon = 0,payment_method = '$_POST[payment_method]' $c");
        break;
        default:
            //echo "UPDATE sludge_grease_traps SET price_per_gallon = $_POST[amount],payment_method = '$_POST[payment_method]' $c";
            $db->query("UPDATE sludge_accounts SET grease_ppg = $_POST[amount] WHERE account_ID =$_POST[account]");
            $db->query("UPDATE sludge_grease_traps SET price_per_gallon = $_POST[amount],payment_method = '$_POST[payment_method]' $c");
        break;
    }





?>