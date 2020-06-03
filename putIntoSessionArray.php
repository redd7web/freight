<?php
ini_set("display_errors",1);

include "protected/global.php";
if(isset($_SESSION['freight_id'])){
    if(!in_array($_SESSION['temp_stops'] && strlen($_POST['schedule_id']) >0)){
        $_SESSION['temp_stops'][] = $_POST['schedule_id'];
        array_unique($_SESSION['temp_stops']);
        echo "Stop saved!<br/>";
    }
}
?>