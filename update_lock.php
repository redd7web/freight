<?php
include "protected/global.php";

$person = new Person();
$head ='From: account-creation@iwpusa.com' . "\r\n" .
            'Reply-To: no-replyr@iwpusa.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion()."\r\n";
            $head .= "MIME-Version: 1.0\r\n";
            $head .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';
$account = new Account($_POST['account']);


switch($_POST['mlock']){
    case 1:
       $second = "Master";
        $db->query("UPDATE freight_accounts SET master_lock = $_POST[state] WHERE account_ID = $_POST[account]");
    break;
    case 0:
        $second = "Normal";
        $db->query("UPDATE freight_accounts SET locked = $_POST[state] WHERE account_ID = $_POST[account]");
    break;
}



switch($_POST['state']){
    case 1:
        $phrase = "locked";
    break;
    case 0:
        $phrase = "unlocked";
    break;
}

$lock_info = array(
    "account_no"=>$_POST['account'],
    "reason"=>$_POST['reason'],
    "who_locked"=>$person->user_id,
    "date"=>date("Y-m-d H:i:s"),
    "type_of_lock"=>$second,
    "on_off"=>$_POST['state']
);


$db->insert("freight_lock_reason",$lock_info);
mail("aburkett@iwpusa.com,bgastelum@Iwpusa.com,aparsons@iwpusa.com,gruff@iwpusa.com,lbriseno@iwpusa.com,sgastelum@iwpusa.com","Account $account->name_plain $second locked","Account $account->name_plain $second $phrase by  $person->first_name $person->last_name @ ".date("Y-m-d H:i:s")." \r\n Reason: $_POST[reason]");
?>
