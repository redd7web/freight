<?php
    include "protected/global.php";
    ini_set("display_errors",1);
    $db->query("UPDATE freight_accounts SET $_POST[field] = $_POST[value] WHERE account_ID = $_POST[account]");
?>