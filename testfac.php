<style>



td{
    border: 1px solid #ccc;
}

tr:first-child td {
    background:#ccc;
    color:black;
    vertical-align: top;
}
</style>
<?php
include "protected/global.php";
ini_set("display_errors",1);

 $ask = " 
 SELECT iwp_accounts.account,iwp_accounts.Name,iwp_scheduled_routes.* FROM iwp_accounts INNER JOIN iwp_scheduled_routes ON iwp_accounts.account_ID = iwp_scheduled_routes.account_no WHERE iwp_accounts.status like '%archive%' AND iwp_scheduled_routes.route_status='scheduled'";

echo $ask."<br/><br/>-------------------------------------------------------------<br/><br/>";

$uyu = $db->query($ask);


if(count($uyu)>0){
    foreach($uyu as $r){
        echo $r['account_no']." $r[Name] ".$r['schedule_id'];
    }
}
?>