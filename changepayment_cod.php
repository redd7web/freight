<?php
include "protected/global.php";

$db->query("UPDATE sludge_accounts SET payment_method='$_POST[payment_type]' WHERE account_ID = $_POST[account]");

?>