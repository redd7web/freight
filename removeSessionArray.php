<?php

ini_set("display_errors",1);
include "protected/global.php";
if(isset($_SESSION['freight_id'])){
    if(in_array($_POST['schedule_id'],$_SESSION['temp_stops'])){
        unset($_SESSION['temp_stops'][array_search($_POST['schedule_id'], $_SESSION['temp_stops'])]);
        echo "Stop removed from temp";
    }
}
array_unique($_SESSION['temp_stops']);
?>