<?php
include "protected/global.php";
$db->query("DELETE FROM freight_containers WHERE account_no = $_POST[account] AND entry = $_POST[entry]");

?>