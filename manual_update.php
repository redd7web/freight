<?php
  include "protected/global.php";
  $db->query("UPDATE freight_grease_traps SET manual_ok = $_POST[value] WHERE grease_no = $_POST[grease_no]");

?>