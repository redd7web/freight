<?php
include "protected/global.php";
ini_set("display_erorrs",1);
echo "UPDATE freight_grease_traps SET jetting =$_POST[val] WHERE grease_no = $_POST[grease_no]";
$db->query("UPDATE freight_grease_traps SET jetting =$_POST[val] WHERE grease_no = $_POST[grease_no]");


?>