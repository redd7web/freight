<?php
include "protected/global.php";
$account_table =  $dbprefix."_accounts";


$i = 0;
$file = "biotane20141204/containers.txt";// Your Temp Uploaded file
if(($handle = fopen($file,"r"))!==FALSE){
    /*Skip the first row*/
    fgetcsv($handle, 0,chr(9));
    while(($data = fgetcsv($handle,0,chr(9)))!==FALSE){
            $x = trim($data[4]);
            if($x == "100 Gal Tote"){
                $x = "Tote-100";
            } else if ($x == "Barrel - 55 Gal"){
                $x = "Barrel-55";
            }
            echo "SELECT container_id FROM freight_list_of_containers WHERE container_label like '%$x%'<br/>";
            $barrrel_look = $db->query("SELECT container_id FROM iwp_list_of_containers WHERE container_label like '%$x%'");
            if(count($barrrel_look)>0){
                
                $data= array (
                    "account_no"=>$data[7],
                    "container_no"=>$barrrel_look[0]['container_id'],
                    "container"=>$x
                );
                var_dump($data);
                
                $db->insert($dbprefix."_containers",$data);
            }
            
    }
}
?>