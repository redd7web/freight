<?php
include "protected/global.php";
ini_set("display_errors",1);


$db->query("UPDATE freight_grease_traps SET percent_split =$_POST[percent_split] WHERE  account_no = $_POST[account] AND grease_no= $_POST[schedule_id]");



?>