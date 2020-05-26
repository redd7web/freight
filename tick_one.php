<?php
include "protected/global.php";
$data_table = $dbprefix."_data_table";
error_reporting(1);
ini_set("max_execution_time",300);
//********************************* THIS SCRIPT TO BE RUN DAILY (CRON JOB)*******************************************//s

function date_dif($beg,$lat){
    $now = strtotime("$lat"); // or your date as well
    $your_date = strtotime("$beg");
    $datediff = abs($now - $your_date);
    return floor($datediff/(60*60*24));
}

$count =1;
$accounts_pickedup = $db->query("SELECT account_ID,estimated_volume,pickup_frequency,created FROM sludge_accounts WHERE status in ('active','new') AND account_ID = 40708");

$atr = new Account();

//var_dump($accounts_pickedup);

if(count($accounts_pickedup)>0){
    foreach($accounts_pickedup as $nos){
       $total = 0;
       echo  "<br/><br/>$count ".account_NumToName($nos['account_ID'])."<br/>";
       $how_many_picks = $db->query("SELECT date_of_pickup,inches_to_gallons  FROM sludge_data_table WHERE account_no =$nos[account_ID]  ORDER BY date_of_pickup DESC");
       //echo "fault after..<br/>";
       
       
       
       /**/
       if(count($how_many_picks)>3) {     
            echo "Has 4 or more pickups<br/>";
            $check = $db->query("SELECT date_of_pickup,inches_to_gallons FROM sludge_data_table WHERE account_no =$nos[account_ID]  ORDER BY date_of_pickup DESC LIMIT 0,4");
            $for_this  = 0;
            foreach($check as $pus){
                echo $pus['date_of_pickup']." $pus[inches_to_gallons]"."<br/>";
                $for_this = $for_this + $pus['inches_to_gallons'];
            }
            
            echo "total of last 4 pickups: ".$for_this."<br/>";
            $ticks = round($for_this,2) / date_different($check[3]['date_of_pickup'],$check[0]['date_of_pickup'])."<br/>";
            echo "ticks per day: ".$ticks."<br/>";
            $next_sched = $db->query("SELECT schedule_id, scheduled_start_date FROM sludge_scheduled_routes WHERE account_no =$nos[account_ID] AND route_status IN ('scheduled', 'enroute') ORDER BY scheduled_start_date DESC ");
            echo "next scheduled pickup ". $next_sched[0]['scheduled_start_date']."<br/>";
             echo "difference between todys date and last pickup: ". date_different($check[0]['date_of_pickup'],date("Y-m-d"))."<br/>";
             
             $new_oil_on_site = $ticks * date_different($check[0]['date_of_pickup'],date("Y-m-d") ) ;
            echo round($new_oil_on_site,2) ." oil onsite after tick<br/>";
            echo "difference of next scheduled start and the last pickup". date_different($check[0]['date_of_pickup'],$next_sched[0]['scheduled_start_date'])."<br/>";
            
            $avg = round($ticks * date_different($check[0]['date_of_pickup'],$next_sched[0]['scheduled_start_date']),2);
            echo "expected : ".$avg;
            
            echo "<br/><br/>"; 
            
       }
       else if( count($how_many_picks)<4 && count($how_many_picks)>0 ){
        
            $latest = $db->query("SELECT DISTINCT(date_of_pickup) FROM sludge_data_table WHERE account_no =$nos[account_ID] ORDER BY date_of_pickup DESC LIMIT 0,1");
            
            echo "Has less  than 4 pickups but more than 0<br/>";
            echo " last: ".$latest[0]['date_of_pickup']."<br/>";
            echo "estimated: ".$nos['estimated_volume']."<br/>";            
            $diff = date_dif($latest[0]['date_of_pickup'],date("Y-m-d"));
            echo "difference :".$diff."<br/>";
            $avg = $nos['estimated_volume'];
            $averg = $nos['estimated_volume'] / 30;
            echo "ticker: ".$averg;
            $new_oil_on_site = $averg * $diff;
            
       } else {
            echo " created date: ".$nos['created']."<br/>";
            echo "Has less  0 pickups<br/>";
            echo "estimated: ".$nos['estimated_volume']."<br/>";
            echo "Date Difference: $diff<br/>";
            $diff = date_dif($nos['created'],date("Y-m-d"));
            echo "difference :".$diff;
            $avg = $nos['estimated_volume'];
            $averg = $nos['estimated_volume'] / 30;
            echo "ticker: ".$averg;
            $new_oil_on_site = $averg * $diff;
       }
       
       
       $package = array(
            "avg_gallons_per_Month"=>round($new_oil_on_site,2),//how much oil is onsite ?
            "estimated_volume"=>$avg // expected (average of last 4 unique pickups)
       );
       
       echo "<pre>";
       print_r($package);
       echo "</pre>";
       echo "********************************************************************************<br/><br/><br/>";
       //echo "status: ".$db->where("account_ID",$nos['account_ID'])->update($dbprefix."_accounts",$package);
        $count++;   
    } 
}








?>