<style>
body{
    padding:10px 10px 10px 10px;
    margin:10px 10px 10px 10px;
}
</style>
<?php
include "protected/global.php";
$head  = 'MIME-Version: 1.0' . "\r\n";
$head .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$head .= 'From: No-reply@iwpusa.com'."\r\n".'Reply-To: No-reply@iwpusa.com'."\r\n" .'X-Mailer: PHP/' . phpversion();


    $grease_info = new Grease_Stop($_GET['sched']);
    $account = new Account($grease_info->account_number);
    $cost_for_jet = $account->grease_volume * number_format($account->grease_ppg,2);
    
    
 if(isset($_POST['remind']) && isset($_POST['email'])){
    if  (mail("$_POST[email]","Your Grease Trap service is scheduled soon.","Please review the following details then continue to the link to complete your invoice for $".number_format($cost_for_jet,2).'Please go to this link to complete your Grease Trap invoice. 
    <form action="https://inet.iwpusa.com/grease/paytracesecurecheckoutvalidate.php" method="post" target="_blank">                          
    <input type="hidden" name="cost" value="'.number_format($cost_for_jet,2).'"/>
    <input type="hidden" name="account_no" value="'.$account->acount_id.'" />
    <input type="hidden" name="schedule_id" value="'.$grease_info->grease_no.'" />
    <input type="hidden" name="route_id" value="'.$grease_info->grease_route_no.'" />
    <input type="submit" value="Click here to review your information" name="payertrace"/>
    </form>',$head ) ){
        echo "Reminder Sent! to $_POST[email]!";
    }else{
        echo "Please enter a valid email address";
    }
 }

?>

<form action="invoice_reminder.php" method="post">
<input placeholder="Email Here" value="<?php echo $account->email_address; ?>"  name="email" type="text"/>
<input type="submit" value="Remind Now!" name="remind"/>
</form>