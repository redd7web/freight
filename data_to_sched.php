<?php
include "protected/global.php";
$rev = $db->query("SELECT * FROM freight_data_table WHERE route_id =41273");

if(count($rev)>0){
    foreach($rev as $stops){
        $acc = new Account($stops['account_no']);
        $sched = new Scheduled_Routes($stops['schedule_id']);
        $prev_sched_date =subDayswithdate($sched->scheduled_start_date,$acc->pick_up_freq);
        echo "<pre>";
        $update_sched = array(
            "scheduled_start_date"=>$prev_sched_date,
            "route_id"=>41273
        );
        var_dump($update_sched);
        echo "</pre>";
        $account_nums .= $acc->acount_id."|"; 
        //$db->where("schedule_id",$sched->schedule_id)->update("freight_scheduled_routes",$update_sched);
        
        echo "<pre>";
        $new_sched = array(
            "route_id"=>NULL,
            "scheduled_start_date"=>$sched->scheduled_start_date,
            "facility_origin"=>$acc->division,
            "route_status"=>"scheduled",
            "created_by"=>99,
            "account_no"=>$acc->acount_id,
            "code_red"=>0,
            "date_created"=>"2015-01-26"
        );
        var_dump($new_sched);
        echo "</pre>";
        //$db->insert("freight_scheduled_routes",$new_sched);
    }
}
        
?>