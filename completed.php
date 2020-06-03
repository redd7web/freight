<?php
include "protected/global.php";

$tff = $db->query("SELECT completed_date,route_id FROM freight_ikg_grease WHERE completed_date != '0000-00-00'");

if(count($tff)>0){
    foreach($tff as $tops){
        $db->query("UPDATE freight_grease_traps SET completed_date='$tops[completed_date]' WHERE grease_route_no='$tops[route_id]'");
    }
}





?>