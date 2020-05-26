<?php
include "protected/global.php";
$container_table = $dbprefix."_list_of_containers";
$containers = $dbprefix."_containers";
$barrels = $db->query("SELECT container FROM $containers");

if(count($barrels)>0){
    foreach($barrels as $containerx){
        $update = $db->query("SELECT container_id FROM $container_table WHERE container_label like '%$containerx[container]%'");
        echo "New id ".$update[0]['container_id']." for ".$containerx['container']."<br/>";
        //$db->query("UPDATE $containers set container_no=".$update[0]['container_id']." WHERE container like '%$containerx[container]%'");
    }
}

echo "<br/><br/><br/><br/>";

$empty = $db->query("SELECT * FROM $container_table WHERE gpi= ''");
if(count($empty)>0){
    foreach($empty as $thisy){
        echo $thisy['container_label']."<br/>";
    }
}


?>