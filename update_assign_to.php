<?php
include "protected/global.php";
echo $db->query("UPDATE freight_issues SET assigned_to = $_GET[person] WHERE issue_no=$_GET[issue_no]");
?>