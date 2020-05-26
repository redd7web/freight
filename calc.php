<?php
include "protected/global.php";
$current_month = date("m");
$current_year = date("y");
echo $current_month." ".$current_year."<br/>";

for($i=1; $i<13;$i++){
   $current_month = $current_month -1;
   
    if($current_month ==0 ){
        $current_month=12;
        $current_year = $current_year -1;
    } 
    echo $current_month." $current_year<br/>";
    
}

?>