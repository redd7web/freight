<?php
include "protected/global.php";
ini_set("display_errors",1);
    $db->query("UPDATE sludge_grease_traps SET multi_day_stop =$_POST[val] WHERE grease_no = $_POST[sched]");

?>