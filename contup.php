<?php
include "protected/global.php";
$file = "biotane20141204/containers.txt";// Your Temp Uploaded file
if(($handle = fopen($file,"r"))!==FALSE){
    /*Skip the first row*/
    fgetcsv($handle, 0,chr(9));
    while(($data = fgetcsv($handle,0,chr(9)))!==FALSE){
            $lookup = $db->query("SELECT container_id FROM freight_list_of_containers WHERE container_label like '%$data[4]%'");
            echo account_NumToName($data[7])." - ".$data[4]."# ".$lookup[0]['container_id']."<br/><br/>";
            $continfo = array (
                "container_no"=>$lookup[0]['container_id'],
                "account_no"=>$data[7],
                "container"=>$data[4],
                "delivery_date"=>$data[2]
            );
            $db->insert("freight_containers",$continfo);
    }
}

?>