<?php

include "protected/global.php";

function date_dif($beg,$lat){    
     //Return the number of days between the two dates:

  return round(abs(strtotime("$beg")-strtotime("$lat"))/86400);

}

$count =1;
$accounts_pickedup = $db->query("SELECT account_ID,estimated_volume,pickup_frequency,created FROM sludge_accounts WHERE status in ('active','new')");
$atr = new Account();
 $total = 0;
       if(count($accounts_pickedup)>0){
    foreach($accounts_pickedup as $nos){
       $total = 0;
       
       $how_many_picks = $db->query("SELECT DISTINCT (schedule_id) date_of_pickup,sum  FROM sludge_data_table WHERE account_no =$nos[account_ID] GROUP BY schedule_id ORDER BY date_of_pickup DESC ");
       //echo "fault after..<br/>";
       /**/
       if(count($how_many_picks)>3) {     
           
            $check = $db->query("SELECT DISTINCT (schedule_id), date_of_pickup,sum  FROM sludge_data_table WHERE account_no =$nos[account_ID] GROUP BY schedule_id ORDER BY date_of_pickup DESC  LIMIT 0,4");
            $for_this  = 0;
            
            
           
          
            $next_sched = $db->query("SELECT schedule_id, scheduled_start_date,route_status FROM sludge_scheduled_routes WHERE account_no =$nos[account_ID] ORDER BY scheduled_start_date DESC LIMIT 0,1");
                if(count($next_sched)>0){
                        if($next_sched[0]['route_status'] == "completed" || $next_sched[0]['route_status'] == "complete"){
                            echo  "<br/><br/>$count ".account_NumToName($nos['account_ID'])."  account is picked up up, but route is not completed.<br/>";
                            foreach($check as $pus){
                                echo $pus['date_of_pickup']." $pus[sum]"."<br/>";
                                $for_this = $for_this + $pus['sum'];
                            }
                        
                          echo "Has 4 or more pickups<br/>";
                          echo "total of last 4 pickups: ".$for_this."<br/>";
                          $ticks = round($for_this,2) / date_dif($check[3]['date_of_pickup'],$check[0]['date_of_pickup'])."<br/>";
                          echo "ticks per day: ".$ticks."<br/>";
                        
                          echo "difference between todys date and last pickup: ". date_dif($check[0]['date_of_pickup'],date("Y-m-d"))."<br/>";                 
                          $new_oil_on_site = $ticks * date_dif($check[0]['date_of_pickup'],date("Y-m-d") ) ;
                          echo round($new_oil_on_site,2) ." oil onsite after tick<br/>";
                          echo "difference of between the last two pickups". date_dif($check[0]['date_of_pickup'],$check[1]['date_of_pickup'])."<br/>";
                    
                          $avg = round($ticks * date_dif($check[0]['date_of_pickup'],$check[1]['date_of_pickup']),2);
                          echo "expected : ".$avg;
                          
                          $package = array(
                                "avg_gallons_per_Month"=>round($new_oil_on_site,2),//how much oil is onsite ?
                                "estimated_volume"=>$avg // expected (average of last 4 unique pickups)
                           );
                           
                           echo "<pre>";
                           print_r($package);
                           echo "</pre>";
                           echo "********************************************************************************<br/><br/><br/>";
                           echo "status: ".$db->where("account_ID",$nos['account_ID'])->update($dbprefix."_accounts",$package);
                            
                          
                        }
                    
                }
            }
        }
    }



?>