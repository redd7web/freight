<?php
include "protected/global.php";

$xp = $db->query("SELECT route_id,account_numbers,ikg_manifest_route_number FROM freight_ikg_manifest_info");



if(count($xp)>0){
    foreach($xp as $px){
        echo "route id: ".$px['route_id']." $px[ikg_manifest_route_number]<br/>";
        $uffer = explode("|",$px['account_numbers']);
        array_pop($uffer);//get current account list
         
        
         $ic = $db->query("SELECT account_no FROM freight_scheduled_routes WHERE route_id = $px[route_id]");
         
         if(count($ic)>0){
            foreach($ic as $ci){
                if( in_array($ci['account_no'],$uffer) == false  ){
                    $uffer[] = $ci['account_no'];
                } 
            }
         }
       
         $stopcounter =1;
         foreach($uffer as $list){
            echo "$stopcounter) ".account_NumToName($list)."<br/>";
            $stopcounter++;
         }
    }
    
}





?>