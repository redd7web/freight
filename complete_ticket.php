<?php
include "protected/global.php";

$db->query("UPDATE notes_reply SET status='completed' WHERE modifier='$_GET[mod]'");
$db->query("UPDATE notes SET status='completed' WHERE modifier='$_GET[mod]'");
?>