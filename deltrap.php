<?php
include "protected/global.php";

$db->where("grease_no",$_POST['trapnum'])->delete($dbprefix."_grease_traps");

?>