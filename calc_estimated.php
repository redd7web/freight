<?php
include "protected/global.php";
ini_set("error_reporting", E_ALL);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
$acnt = new Account();
$x = $db->query("SELECT freight_list_of_routes.route_id,freight_list_of_routes.ikg_manifest_route_number, freight_ikg_manifest_info.account_numbers FROM freight_list_of_routes INNER JOIN freight_ikg_manifest_info ON freight_list_of_routes.route_id = freight_ikg_manifest_info.route_id WHERE freight_ikg_manifest_info.route_id = 41463");

if(count($x)>0){
    foreach($x as $ko){
        
        $buffer = explode("|",$ko['account_numbers']);
        array_pop($buffer);
        $i = 0;
        
        foreach($buffer as $up){
            $i += $acnt->singleField($up,"estimated_volume");
        }
        echo "route id:".$ko['route_id']." $ko[ikg_manifest_route_number]<br/>";
        echo $i."<br/><br/>";
        $db->query("UPDATE freight_list_of_routes SET expected = $i WHERE route_id= $ko[route_id]");
        
    }
}

?>