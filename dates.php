<?php

include "protected/global.php";
set_time_limit (0);

$i=0;

$file = "biotane20141204/pickups.txt";// Your Temp Uploaded file
if(($handle = fopen($file,"r"))!==FALSE ){
    /*Skip the first row*/
    fgetcsv($handle, 0,chr(9));
    while(($data = fgetcsv($handle,0,chr(9)))!==FALSE){
        $i++;      
        
        $test =date('Y-m-d', strtotime(str_replace('/', '-',$h[0])));
        
        //$ask = "UPDATE iwp_data_table set date_of_pickup = '$test' WHERE schedule_id=$data[0] && date_of_pickup ='0000-00-00'";
        //echo $ask."<br/><br/>";
        
        $package = array(
            "date_of_pickup"=>$data[6]
        );
        
        $db->where("schedule_id",(int)$data[0])->where("date_of_pickup","0000-00-00 00:00:00")->update("iwp_data_table",$package);
        
    }
} 



?>