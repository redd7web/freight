<?php
  include "protected/global.php";
  $db->query("UPDATE sludge_grease_traps SET manual_ok = $_POST[value] WHERE grease_no = $_POST[grease_no]");

?>