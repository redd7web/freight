<?php
include "protected/global.php";
ini_set("display_errors",1);

$db->query("UPDATE overhead_value SET value=$_POST[value] WHERE id =$_POST[id]");

?>