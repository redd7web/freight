<?php

include "protected/global.php";

$file = "biotane20141204/routes.txt";// Your Temp Uploaded file
if(($handle = fopen($file,"r"))!==FALSE){
    /*Skip the first row*/
    fgetcsv($handle, 0,chr(9));
    while(($data = fgetcsv($handle,0,chr(9)))!==FALSE){
        
        if(strlen($data[4])>0 && $data[4] != NULL){
            $scheduled = explode("-",$data[4]);
            $scheds =$scheduled[0]."-".$scheduled[1]."-".$scheduled[2];    
        }else {
            $scheds= "";
        }
        
        if(strlen($data[5])>0 && $data[5] != NULL){
            $completed = explode("-",$data[5]);
            $comps = $completed[0]."-".$completed[1]."-".$completed[2];
              
        }else {
            $comps = "";
        }
        
        if(strlen($data[3])>0 && $data[3] != NULL){
            $stopover = explode(" ",$data[3]);
            $created = explode("-",$stopover[0]);
             $creates = $created[0]."-".$created[1]."-".$created[2];    
        }else {
           $creates = "";
        }
        
        $route_package = array(
            "scheduled_date"=>$scheds,
            "completed_date"=>$comps,
        );
        $db->where("route_id",$data[0])->update("freight_ikg_manifest_info",$route_package);
        
        $list_of_routes = array(
            "status"=>$data[1],
            "created_date"=>$creates,
            "stops"=>$data[7],
            "expected"=>$data[8],
            "collected"=>$data[9],
            "created_date"=>$data[3]
        );
        $db->where("route_id",$data[0])->update("freight_list_of_routes",$list_of_routes);
        
        if($data[1] == "En-route"){
            $status = "enroute";
        } else {
            $status = $data[1];
        }
        
        $sched_status = array (
            "route_status"=>$status
        );
        $db->where("route_id",$data[0])->update("freight_scheduled_routes",$sched_status);
    }
}






?>