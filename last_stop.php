<?php
include "protected/global.php";

$hc = $db->query("SELECT freight_ikg_grease.customer_name,freight_accounts.Name,freight_grease_data_table.date_of_pickup,freight_grease_data_table.arrival,freight_grease_data_table.departure,freight_grease_data_table.drop_removal,freight_grease_data_table.route_id,freight_ikg_grease.ikg_manifest_route_number,freight_grease_data_table.facility_origin,freight_grease_data_table.account_no FROM freight_accounts LEFT JOIN freight_grease_data_table ON freight_accounts.account_ID = freight_grease_data_table.account_no LEFT JOIN freight_ikg_grease ON freight_grease_data_table.route_id = freight_ikg_grease.route_id  WHERE freight_grease_data_table.facility_origin = $_GET[tank]  OR freight_grease_data_table.account_no = $_GET[tank]  ORDER BY freight_grease_data_table.arrival ASC LIMIT 0,1");


if(count($hc)>0){
    
    if($hc[0]['account_no']== $_GET['tank']){
        $drop_remove = " Removal<br/>";
    }else if($hc[0]['facility_origin'] == $_GET['tank']){
        $drop_remove = " Arrival<br/>";
    }
   echo "<form target='_blank' class='ikg_grease' method='post' action='grease_ikg.php' style='cursor:pointer;width:280px;'>Customer Name: ".$hc[0]['customer_name']."<br/>".$hc[0]['Name']."<br/> Date of service: ".$hc[0]['date_of_pickup']." <br/> Arrival - ".$hc[0]['arrival']."  <br/>Departure - ".$hc[0]['departure']." <br/>Route: ".$hc[0]['ikg_manifest_route_number']."<br/>$drop_remove<input type='hidden' name='from_routed_grease_list' value='1' /><input type='hidden' value='".$hc[0]['route_id']."' name='util_routes'/></form>"; 
}else{
    echo "NO STOP";
}


?>