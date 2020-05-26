<?php
include "protected/global.php";
$count = 0;
$xac = $db->query("SELECT account_ID,division FROM sludge_accounts WHERE status = 'active' AND account_ID = 24847");
$act = new Account();

if(count($xac)>0){
    foreach($xac as $accounts){
        $count++;
        $freq = $act->singleField($accounts['account_ID'],"pickup_frequency");
        echo "$count: ".account_NumToName($accounts['account_ID'])." This accounts pickup frequency : $freq<br/>-------------------------<br/>";
        $date = $db->query("SELECT date_of_pickup FROM sludge_data_table WHERE account_no = $accounts[account_ID] ORDER BY date_of_pickup DESC LIMIT 0,1");
        
        echo "last pickup date in system :".$date[0]['date_of_pickup']."<br/>";
        
        
        $check_sched = $db->query("SELECT schedule_id,route_status,scheduled_start_date FROM sludge_scheduled_routes WHERE account_no=$accounts[account_ID] ORDER BY scheduled_start_date DESC");
        
        if(count($check_sched)>0){
            if( strtolower($check_sched[0]['route_status']) != "enroute"){
                echo "latest stop in system: ".$check_sched[0]['scheduled_start_date']." ".$check_sched[0]['route_status']."<br/>";
                if($check_sched[0]['scheduled_start_date'] < "2014-11-01"){
                    $new_date =addDayswithdate(date("Y-m-d"),$act->singleField($accounts['account_ID'],"pickup_frequency"));
                    echo $check_sched[0]['scheduled_start_date']." stop is before november 2014 <br/>";
                    echo "New stop date: ".$new_date."<br/>";
                }else {
                    $new_date = addDayswithdate($date[0]['date_of_pickup'],$act->singleField($accounts['account_ID'],"pickup_frequency"));
                    echo "New stop date: ".$new_date."<br/>";
                }
                
                $xc = $db->query("SELECT schedule_id FROM sludge_schedued_routes WHERE schedule_id= ".$check_sched[0]['scheduled_start_date']);
                
                if(count($x)>0){
                    $db->query("UPDATE sludge_scheduled_routes SET scheduled_start_date = '$new_date' WHERE schedule_id =".$check_sched[0]['scheduled_start_date']);    
                } else {
                    $new_info = array(
                        "scheduled_start_date"=>$new_date,
                        "facility_origin"=>$accounts['division'],
                        "route_status"=>"scheduled",
                        "created_by"=>99,
                        "account_no"=>$accounts['account_ID'],
                        "code_red"=>0,
                        "date_created"=>date("Y-m-d")
                    );
                    $db->insert("sludge_scheduled_routes",$new_info);
                }
            } else {
                echo "atest stop for account is enroute<br/>";
            }
        } else {
            echo "account has no stop in system<br/>";
        }
       
        echo "---------------------";
        echo "<br/><br/>";
    }
}



?>