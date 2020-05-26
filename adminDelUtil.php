<?php
include "protected/global.php";

$db->query("DELETE FROM sludge_utility WHERE utility_sched_id = $_GET[util_id]");



?>