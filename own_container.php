<?php
include "protected/global.php";

$hg = $db->query("SELECT * FROM freight_containers WHERE account_no = $_GET[account_no]");

if(count($hg)>0){
    foreach($hg as $gh){
        echo "<option value='$gh[entry]'>$gh[container]</option>";
    }
}

?>