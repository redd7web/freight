<?php
include "protected/global.php";
session_start();
$person = new Person();
/*
$track = array(
    "date"=>date("Y-m-d H:i:s"),
    "user"=>$person->user_id,
    "actionType"=>"User logout",
    "descript"=>"User Logged out",
     "pertains"=>4,
    "type"=>10
   
);
$db->insert($dbprefix."_activity",$track);
*/
session_unset($_SESSION['freight_id']);
session_unset($_SESSION['freight_history']);
session_unset($_SESSION['freight_page_counter']);
session_unset($_SESSION['temp_stops']);
session_destroy();
header("Location:../");
?>