<?php
include "protected/global.php";

$total_cap=  20000;
$check_complete = $db->query("SELECT route_id FROM freight_list_of_grease WHERE status='completed' AND facility =$_GET[tank] ");

if(count($check_complete)>0){
    foreach($check_complete as $check){
        $these[] = $check['route_id'];
    }
    $hb = $db->query("SELECT SUM(inches_to_gallons) as current_level FROM freight_grease_data_table WHERE facility_origin = $account->acount_id AND  route_id IN(".implode(",",$these).")  ORDER BY date_of_pickup DESC LIMIT 0,1");
    if(count($hb)>0){
        $onsite = $hb[0]['current_level'];
    }else{
        $onsite = 0;
    }
    
}else{
    $onsite = 0;
}



echo "<sup id='subscript'><?php echo $onsite; ?></sup>/<sub>20000</sub>";
?>
