<?php
include "protected/global.php";
//ini_set("display_errors",1);
$head ='From: account-creation@iwpusa.com' . "\r\n" .
            'Reply-To: no-replyr@iwpusa.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion()."\r\n";
            $head .= "MIME-Version: 1.0\r\n";
            $head .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';
$account = new Account($_POST['id']);

$db->query("UPDATE sludge_accounts SET status='Active' WHERE account_ID = '$_POST[id]'");


  mail("KMickle@iwpusa.com,Kjankowski@iwpusa.com, AParsons@iwpusa.com,GRuff@iwpusa.com,ABurkett@iwpusa.com","Account $account->name_plain set to pending","Name: $account->name_plain  \r\n Account id:$account->acount_id \r\n Address: $account->address, $account->city, $account->state $account->zip \r\n Phone: $account->area_code - $account->phone \r\n Contact Name: $account->contact_name \r\n  Account has been activated by Kevin: <a href='https://inet.iwpusa.com/grease/viewAccount.php?id=$account->acount_id'>$account->name_plain</a> \r\n Scheduled date: ".$account->schedule['scheduled_start_date'],$head);    

?>