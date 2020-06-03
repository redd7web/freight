#!/usr/bin/php5 -q
<?php


include "protected/global.php";


function date_dif($beg,$lat){
    return round(abs(strtotime("$beg")-strtotime("$lat"))/86400);
}

$count =1;
$accounts_pickedup = $db->query("SELECT account_ID,estimated_volume,pickup_frequency,created FROM freight_accounts WHERE status in ('active','new') AND pickup_frequency <30");

$atr = new Account();

//var_dump($accounts_pickedup);

if(count($accounts_pickedup)>0){
    foreach($accounts_pickedup as $nos){
        
        
       $total = 0;
       echo  "<br/><br/>$count ".account_NumToName($nos['account_ID'])."<br/>";
       $how_many_picks = $db->query("SELECT DISTINCT (schedule_id) date_of_pickup,sum  FROM freight_data_table WHERE account_no =$nos[account_ID] GROUP BY schedule_id ORDER BY date_of_pickup DESC ");
       //echo "fault after..<br/>";
       /**/
       if(count($how_many_picks)>=5) {//pickups 5 to 15
            
            
            
            if( $nos['pickup_frequency']  <30){// if frequency is less than 30 days , average out the last 5+n amount of pickups b
                $pickups = count($how_many_picks);
                
                if($pickups>15){
                    $pickups = 15;
                }    
                echo "Has 5 or more pickups and pickup frequency is less than 30 days<br/>";
                $check = $db->query("SELECT DISTINCT (schedule_id), date_of_pickup,sum  FROM freight_data_table WHERE account_no =$nos[account_ID] GROUP BY schedule_id ORDER BY date_of_pickup DESC  LIMIT 0,$pickups");
                $for_this  = 0;
                foreach($check as $pus){
                    echo $pus['date_of_pickup']." $pus[sum]"."<br/>";
                    $for_this = $for_this + $pus['sum'];
                }
                echo "total of last $pickups pickups: ".$for_this."<br/>";
                
                
                
                $ticks = $for_this/date_dif($check[$pickups-1]['date_of_pickup'],$check[0]['date_of_pickup']);
                echo "ticks per day: ".$ticks."<br/>";
                $next_sched = $db->query("SELECT schedule_id, scheduled_start_date FROM freight_scheduled_routes WHERE account_no =$nos[account_ID] AND route_status IN ('scheduled', 'enroute') ORDER BY scheduled_start_date DESC ");
                echo "next scheduled pickup ". $next_sched[0]['scheduled_start_date']."<br/>";
                echo "difference between todys date and last pickup: ". date_dif($check[0]['date_of_pickup'],date("Y-m-d"))."<br/>";
                 
                $new_oil_on_site = $ticks * date_dif($check[0]['date_of_pickup'],date("Y-m-d") ) ;
                echo round($new_oil_on_site,2) ." oil onsite after tick<br/>";
                echo "difference of next scheduled start and the last pickup". date_dif($check[0]['date_of_pickup'],$next_sched[0]['scheduled_start_date'])."<br/>";
                
                $avg = round($ticks * date_dif($check[0]['date_of_pickup'],$next_sched[0]['scheduled_start_date']),2);
                echo "expected : ".$avg;
                echo "<br/><br/>"; 
            }else {
                echo "Has 5 or more pickups and pickup frequency is 30 or greater<br/>";
                $check = $db->query("SELECT DISTINCT (schedule_id), date_of_pickup,sum  FROM freight_data_table WHERE account_no =$nos[account_ID] GROUP BY schedule_id ORDER BY date_of_pickup DESC  LIMIT 0,4");
                $for_this  = 0;
                foreach($check as $pus){
                    echo $pus['date_of_pickup']." $pus[sum]"."<br/>";
                    $for_this = $for_this + $pus['sum'];
                }
                
               
                echo "total of last 4 pickups: ".$for_this."<br/>";
                $ticks = round($for_this,2) / date_dif($check[3]['date_of_pickup'],$check[0]['date_of_pickup'])."<br/>";
                echo "ticks per day: ".$ticks."<br/>";
                $next_sched = $db->query("SELECT schedule_id, scheduled_start_date FROM freight_scheduled_routes WHERE account_no =$nos[account_ID] AND route_status IN ('scheduled', 'enroute') ORDER BY scheduled_start_date DESC ");
                echo "next scheduled pickup ". $next_sched[0]['scheduled_start_date']."<br/>";
                echo "difference between todys date and last pickup: ". date_dif($check[0]['date_of_pickup'],date("Y-m-d"))."<br/>";
                 
                $new_oil_on_site = $ticks * date_dif($check[0]['date_of_pickup'],date("Y-m-d") ) ;
                echo round($new_oil_on_site,2) ." oil onsite after tick<br/>";
                echo "difference of next scheduled start and the last pickup". date_dif($check[0]['date_of_pickup'],$next_sched[0]['scheduled_start_date'])."<br/>";
                
                $avg = round($ticks * date_dif($check[0]['date_of_pickup'],$next_sched[0]['scheduled_start_date']),2);
                echo "expected : ".$avg;
                echo "<br/><br/>"; 
                
            }
            
            
        
       } else if(count($how_many_picks) ==4) {         
            echo "Has 4 or more pickups<br/>";
            $check = $db->query("SELECT DISTINCT (schedule_id), date_of_pickup,sum  FROM freight_data_table WHERE account_no =$nos[account_ID] GROUP BY schedule_id ORDER BY date_of_pickup DESC  LIMIT 0,4");
            $for_this  = 0;
            foreach($check as $pus){
                echo $pus['date_of_pickup']." $pus[sum]"."<br/>";
                $for_this = $for_this + $pus['sum'];
            }
            
           
            echo "total of last 4 pickups: ".$for_this."<br/>";
            $ticks = round($for_this,2) / date_dif($check[3]['date_of_pickup'],$check[0]['date_of_pickup'])."<br/>";
            echo "ticks per day: ".$ticks."<br/>";
            $next_sched = $db->query("SELECT schedule_id, scheduled_start_date FROM freight_scheduled_routes WHERE account_no =$nos[account_ID] AND route_status IN ('scheduled', 'enroute') ORDER BY scheduled_start_date DESC ");
            echo "next scheduled pickup ". $next_sched[0]['scheduled_start_date']."<br/>";
             echo "difference between todys date and last pickup: ". date_dif($check[0]['date_of_pickup'],date("Y-m-d"))."<br/>";
             
             $new_oil_on_site = $ticks * date_dif($check[0]['date_of_pickup'],date("Y-m-d") ) ;
            echo round($new_oil_on_site,2) ." oil onsite after tick<br/>";
            echo "difference of next scheduled start and the last pickup". date_dif($check[0]['date_of_pickup'],$next_sched[0]['scheduled_start_date'])."<br/>";
            
            $avg = round($ticks * date_dif($check[0]['date_of_pickup'],$next_sched[0]['scheduled_start_date']),2);
            echo "expected : ".$avg;
            echo "<br/><br/>"; 
            
       }
       else if( count($how_many_picks)<=3 && count($how_many_picks)>0 ){
        
            $latest = $db->query("SELECT DISTINCT(date_of_pickup) FROM freight_data_table WHERE account_no =$nos[account_ID] ORDER BY date_of_pickup DESC LIMIT 0,1");
            
            echo "Has less  than 4 pickups but more than 0<br/>";
            echo " last: ".$latest[0]['date_of_pickup']."<br/>";
            echo "estimated: ".$nos['estimated_volume']."<br/>";            
            $diff = date_dif($latest[0]['date_of_pickup'],date("Y-m-d"));
            echo "difference :".$diff."<br/>";
            $avg = $nos['estimated_volume'];
            $averg = $nos['estimated_volume'] / 30;
            echo "ticker: ".$averg;
            $new_oil_on_site = $averg * $diff;
            $ticks = $averg;
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
            $ticks = $averg;
       }
       
       
       $package = array(
            "avg_gallons_per_Month"=>round($new_oil_on_site,2),//how much oil is onsite ?
            "estimated_volume"=>$avg, // expected (average of last 4 unique pickups)
            "ticks_per_day"=>$ticks
       );
       
       echo "<pre>";
       print_r($package);
       echo "</pre>";
       echo "********************************************************************************<br/><br/><br/>";
       
       
       //$nb = $db->query("SELECT route_status FROM freight_scheduled_routes WHERE account_no = $nos[account_ID] ORDER BY scheduled_start_date DESC");
       
       
       if(count($nb)>0){
           if($nb[0]['route_status'] !="enroute"){
                //echo "status: ".$db->where("account_ID",$nos['account_ID'])->update($dbprefix."_accounts",$package);
           }
       }
       $count++;   
    } 
}






$count =1;
$accounts_pickedupx = $db->query("SELECT account_ID,estimated_volume,pickup_frequency,created FROM freight_accounts WHERE status in ('active','new')");
$atr = new Account();
 $total = 0;
       if(count($accounts_pickedupx)>0){
    foreach($accounts_pickedupx as $nos){
       $total = 0;
       
       $how_many_picks = $db->query("SELECT DISTINCT (schedule_id) date_of_pickup,sum  FROM freight_data_table WHERE account_no =$nos[account_ID] GROUP BY schedule_id ORDER BY date_of_pickup DESC ");
       //echo "fault after..<br/>";
       /**/
       if(count($how_many_picks)>3) {     
           
            $check = $db->query("SELECT DISTINCT (schedule_id), date_of_pickup,sum  FROM freight_data_table WHERE account_no =$nos[account_ID] GROUP BY schedule_id ORDER BY date_of_pickup DESC  LIMIT 0,4");
            $for_this  = 0;
            
            
           
          
            $next_sched = $db->query("SELECT schedule_id, scheduled_start_date,route_status FROM freight_scheduled_routes WHERE account_no =$nos[account_ID] ORDER BY scheduled_start_date DESC LIMIT 0,1");
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
                                "estimated_volume"=>$avg, // expected (average of last 4 unique pickups)
                                "ticks_per_day"=>$ticks
                           );
                           
                           echo "<pre>";
                           print_r($package);
                           echo "</pre>";
                           echo "********************************************************************************<br/><br/><br/>";
                           $nb = $db->query("SELECT route_status FROM freight_scheduled_routes WHERE account_no = $nos[account_ID] ORDER BY scheduled_start_date DESC");
       
       
                           if(count($nb)>0){
                               if($nb[0]['route_status'] !="enroute"){
                                    //echo "status: ".$db->where("account_ID",$nos['account_ID'])->update($dbprefix."_accounts",$package);
                               }
                           }
                            
                          
                        }
                    
                }
            }
        }
    }


$get = $db->query("SELECT account_ID FROM freight_accounts");

    if(count($get)>0){
        foreach ($get as $acnts){
            $account = new Account($acnts['account_ID']);
            //$db->query("UPDATE freight_accounts SET barrel_capacity = ".$account->total_barrel_capacity." WHERE account_ID=$acnts[account_ID]");
        }
    }

?>