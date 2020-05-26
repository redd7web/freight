<?php
include "protected/global.php";
$k =date('Y-m-d h:i:s');
$account = new Account($_POST['account_no']);
$person = new Person();
$schedinfo = Array(//id will be automatically assigned in database , route_no will be updated upon route creation                      
    "scheduled_start_date"=>$_POST['date_sched_pickup_month'],
    "facility_origin"=>$account->division,
    "code_red"=>$_POST['fire'],
    "account_no"=>"$_POST[account_no]",
    "route_status"=>"Scheduled",
    "created_by"=>$person->user_id,
    "date_created"=>$k       
);


 if( $db->insert($dbprefix."_scheduled_routes",$schedinfo) ){
    //echo "Successfully scheduled ".account_NumtoName($_GET['account_no']) ." for later";
    echo $db->getInsertId();
 }



?>