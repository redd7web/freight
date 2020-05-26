<?php
include "protected/global.php";

$cs_form = $db->query("SELECT * FROM lnetforms.ap_form_28181 WHERE ap_form_28181.element_50 = $_POST[route_id] AND ap_form_28181.element_51 = $_POST[schedule_id] AND ap_form_28181.element_52 = $_POST[account_no]");

if(count($cs_form)>0){
    echo 1;
}else{
    echo 0;
}

?>