<?php 
include "protected/global.php";
$person = new Person();
$k = date("Y-m-d H:i:s");

$x = $db->query("SELECT account_no FROM freight_scheduled_routes WHERE account_no=$_POST[account_no] AND route_status='scheduled'");
if(count($x)>0){
    $db->query("DELETE FROM freight_scheduled_routes WHERE account_no=$_POST[account_no] AND route_id IS NULL AND route_status='scheduled'");
    $db->query("DELETE FROM freight_notes WHERE schedule_id=$_POST[scedule_id_] AND account_no=$_POST[account_no]");
}


$schedinfo = Array(//id will be automatically assigned in database , route_no will be updated upon route creation                  
    "scheduled_start_date"=>$_POST['start_date'],
    "facility_origin"=>23,
    "code_red"=>$_POST['codered'],
    "account_no"=>"$_POST[account_no]",
    "route_status"=>"scheduled",
    "created_by"=>$person->user_id,
    "date_created"=>$k       
);



if( $db->insert($dbprefix."_scheduled_routes",$schedinfo) ){
    $sched_num = $db->getInsertId(); 
    if(   isset($_POST['notes']) && strlen($_POST['notes'])>0  ){
        if(isset($_POST['dispatcher_note']) && strlen($_POST['dispatcher_note'])>0){
            $notex = $_POST['dispatcher_note'];
        }
        if(isset($_POST['special_instructions']) && strlen($_POST['special_instructions']) ){
            $notex .= "|".$_POST['special_instructions'];
        }
        $schednotes = Array(  //route_no will be updated upon route creation                            
            "schedule_id"=>$sched_num,          
            "author_id"=>$person->user_id,
            "date"=>date('Y-m-d h:i:s'),
            "notes"=>$notex,                
            "created_by"=>$person->user_id,
            "account_no"=>$_POST['account_no']
        );
        $db->insert($dbprefix."_notes",$schednotes);
    }
 }

 $track = array(
    "date"=>date("Y-m-d H:i:s"),
    "user"=>$person->user_id,
    "actionType"=>"Scheduled Created For Route",
    "descript"=>"Schedule $id",
    "account"=>$_POST['account_no'],
    "pertains"=>6,
    "type"=>7
);
$db->insert($dbprefix."_activity",$track);

?>