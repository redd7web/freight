<?php

include "protected/global.php";
ini_set("display_errors",1);
if(strlen($_POST['selected_scheds'])>0 && $_POST['selected_scheds'] !=' ' && isset($_POST['selected_scheds'])){
    echo $_POST['selected_scheds'];
    
     $first_stop = explode("|",$_POST['selected_scheds']);
    array_pop($first_stop);
    print_r($first_stop);
    /**/ 
    if($db->query("DELETE FROM sludge_grease_traps WHERE grease_no IN(".implode(",",$first_stop).")" )){
        echo "Stops Deleted!";
    }  
    UNSET($_SESSION['temp_stops']);
}else{
    echo "No stops seleceted!";
}



?>