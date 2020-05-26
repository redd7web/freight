<?php
include "protected/global.php";




$file = "biotane20141204/ariz.txt";// Your Temp Uploaded file
if(($handle = fopen($file,"r"))!==FALSE){
    /*Skip the first row*/
    fgetcsv($handle, 0,chr(9));
    while(($data = fgetcsv($handle,0,chr(9)))!==FALSE){
        
            echo $data[5]." ".$data[6]."<br/><br/>";
            
            
            // data_table info
            $check = $db->where("schedule_id",$data[0])->get($dbprefix."_scheduled_routes");
            if(count($check)>0){//if it already exists just update date created
                $data_pack = array(
                    "date_of_pickup"=>$data[6],
                    "inches_to_gallons"=>$data[7],
                    "fieldreport"=>$data[8]
                );
                $db->where("schedule_id",$data[0])->where("account_no",$data[1])->update("iwp_data_table",$data_pack);
                
                
                $sched_pack = array(
                    "date_created"=>$data[10]
                );
                $db->where("schedule_id",$data[0])->where("account_no",$data[1])->update("iwp_scheduled_routes",$sched_pack);
                
                
            } else {
               $data_pack = array (
                    "date_of_pickup"=>$data[6],
                    "schedule_id"=>$data[0],
                    "account_no"=>$data[1],
                    "inches_to_gallons"=>$data[7],
                    "fieldreport"=>$data[8]
               );
               $db->insert("iwp_data_table",$data_pack);
               
               
               $sched_pack = array(
                    "date_created"=>$data[10],
                    "schedule_id"=>$data[0],
                    "account_no"=>$data[1],
                    "code_red"=>$data[4]
               );
               
            }
            //*****************************************
            
            
            
            
    }
    
    
}

?>