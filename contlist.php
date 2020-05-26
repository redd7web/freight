<?php
include "protected/global.php";
 $i = 0;
$file = "Book1.csv";// Your Temp Uploaded file
if(($handle = fopen($file,"r"))!==FALSE){
    /*Skip the first row*/
    fgetcsv($handle, 0,chr(9));
    while(($data = fgetcsv($handle,0,chr(9)))!==FALSE){
            $line = explode(",",$data[0]);
            var_dump($line);        
            
            $list = array(
                "container_label"=>$line[0],
                "amount_holds"=>$line[1],
                "gpi"=>$line[2]
            );
            $db->insert($dbprefix."_list_of_containers",$list);
    }
}

?>