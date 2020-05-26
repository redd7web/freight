<?php
ini_set("display_errors",1);

include "protected/global.php";
$db->query("DELETE FROM sludge_grease_traps WHERE grease_no = $_POST[grease_no]");
UNSET($_SESSION['temp_stops'][array_search($$_POST['grease_no'], $_SESSION['temp_stops'])]);
?>