<?php
include "protected/global.php";
ini_set("display_errors",1);

$ap_form = $db->query("SELECT * FROM Inetforms.ap_form_29942 WHERE ap_form_29942.element_1 = $_POST[account_no] AND ap_form_29942.element_2 = $_POST[route_id] AND ap_form_29942.element_3 = $_POST[schedule_id]");

if(count($ap_form)>0){
    echo "1|".$ap_form[0]['id'];
} else {
    echo 0;
}

?>