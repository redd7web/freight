<?php
include "protected/global.php";
$atr = new Account();

$account = 34697;
$advance_days = 8;
echo account_NumToName($account)."<br/>";
$check = $db->query("SELECT DISTINCT (date_of_pickup), count( date_of_pickup ) AS num, SUM( inches_to_gallons ) AS tot_for_pickup, account_no, inches_to_gallons FROM freight_data_table WHERE account_no =$account GROUP BY schedule_id ORDER BY date_of_pickup DESC LIMIT 0 , 4");

 $oil_on_site =$atr->singleField($account,"avg_gallons_per_Month");
            
$for_this= 0;
foreach($check as $individ){
    $for_this = $for_this + $individ['tot_for_pickup'];
}


$avg = round($for_this / count($check),2);


$ticks = round($avg,2) / $atr->singleField($account,"pickup_frequency")."<br/>";
          
echo "ticks per day: ". round($ticks,2)."<br/>" ;
$format_ticks = round($ticks,2);            
echo "current oil on site: $oil_on_site<br/>";
echo "Advanced $advance_days  days<br/>";


$oil_on_site = $atr->singleField($account,"avg_gallons_per_Month");
$new_oil_on_site = $oil_on_site + ($format_ticks*$advance_days);
$new_oil_on_site  = $new_oil_on_site ;
echo "Amount advanced:".$format_ticks*$advance_days;

echo $new_oil_on_site  ." oil onsite after tick<br/>";

$package = array(
    "avg_gallons_per_Month"=>$new_oil_on_site
);
       
       
echo "status: ".$db->where("account_ID",$account)->update($dbprefix."_accounts",$package);

?>