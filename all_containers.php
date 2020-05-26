<?php
include "protected/global.php";
$jk = $db->get($dbprefix."_list_of_containers","*");

if(count($jk)>0){
       
        foreach($jk as $value){
               
            echo "<option value='$value[container_id]'>$value[container_label]</option>";
        }
        
    }
 
?>