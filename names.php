<?php
include "protected/global.php";
ini_set("display_errors",1);
$hyc = $db->query("SELECT COLUMN_NAME
  FROM INFORMATION_SCHEMA.COLUMNS
 WHERE table_name = 'sludge_account_notes'");

echo"<pre>";
print_r($hyc);
echo"</pre>";
?>