<?php
include "protected/global.php";
ini_set("display_errors",0);
$account = new Account($_GET['id']);
$head ='From: account-creation@iwpusa.com' . "\r\n" .
            'Reply-To: no-replyr@iwpusa.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion()."\r\n";
            $head .= "MIME-Version: 1.0\r\n";
            $head .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';

    if(isset($_POST['decline_now'])){
        

        $db->query("UPDATE freight_accounts SET status='Archive',decline_note='$_POST[decline_reason]' WHERE account_ID = '$_GET[id]'");
    
    
        mail("KMickle@iwpusa.com,Kjankowski@iwpusa.com, AParsons@iwpusa.com,GRuff@iwpusa.com,ABurkett@iwpusa.com","Account $account->name_plain set to pending","Name: $account->name_plain  \r\n Account id:$account->acount_id \r\n Address: $account->address, $account->city, $account->state $account->zip \r\n Phone: $account->area_code - $account->phone \r\n Contact Name: $account->contact_name \r\n  Account has been de-activated by Kevin: <a href='https://inet.iwpusa.com/grease/viewAccount.php?id=$account->acount_id'>$account->name_plain</a> \r\n Decline Reason: $_POST[decline_reason]\r\n Scheduled date: ".$account->schedule['scheduled_start_date'],$head);    

    }
?>
<html>
<head>
<style type="text/css">

body{
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
}
</style>
</head>
<body>

<table style="width: 260px;margin:auto;">
<tr><td style="text-align:center;vertical-align:bottom;"><form action="decline.php?id=<?php echo $account->acount_id; ?>" method="post"><input type="hidden" name="id" value="<?php $account->acount_id; ?>"/><textarea id="decline_reason" placeholder="Reason for decline" name="decline_reason"><?php echo $account->decline_note; ?></textarea>&nbsp;<input type="submit" value="Decline Account" name="decline_now"/></form></td></tr>
</table>
</body>
</html>