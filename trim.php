<?php
include "protected/global.php";
ini_set("display_errors",1);
$x = $db->query("SELECT account_ID ,name FROM freight_accounts WHERE status in ('active','new')");

foreach($x as $xo){
    $ko = $db->query("SELECT AVG(sum) as gpm FROM freight_data_table WHERE account_no = $xo[account_ID] AND YYEAR(date_of_pickup) =2015 group by MONTH(date_of_pickup) HAVING gpm <=30 ");
    if(count($ko)>0){
        foreach($ko as $avg){
            echo $xo['name']." ".$xo['account_ID']." ".$$avg['gpm']."<br/>";
        }
    }
    echo "<br/>------------------------------<br/>";
}



?>