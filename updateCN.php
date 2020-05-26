<?php
include "protected/global.php";
ini_set("display_errors",1);
switch($_POST['mode']){
    case 1:
        $db->query("UPDATE sludge_accounts SET credit_note ='$_POST[value]' WHERE account_ID = $_POST[account]");
    break;
    case 2:
        $db->query("UPDATE sludge_accounts SET credits =$_POST[value] WHERE account_ID = $_POST[account]");
    break;
    case 3:
        $db->query("UPDATE sludge_accounts SET credit_terms ='$_POST[value]' WHERE account_ID = $_POST[account]");
    break;
    case 4:
        $db->query("UPDATE sludge_accounts SET 	cc_on_file =$_POST[value] WHERE account_ID = $_POST[account]");
    break;
    case 5:
        $db->query("UPDATE sludge_accounts SET 	locked =$_POST[value] WHERE account_ID = $_POST[account]");
    break;
    case 6:
        $db->query("UPDATE sludge_accounts SET 	credit_email =$_POST[value] WHERE account_ID = $_POST[account]");
    break;
}   



?>