<?php
include "protected/global.php";
$history_table = $dbprefix."_rout_history";
echo $_POST['route_id']." ".$_POST['what_day'];

$exist_ = $db->where('route_no',$_POST['route_id'])->where('what_day',$_POST['what_day'])->get($dbprefix."_rout_history");

if( count($exist_) == 0 ){
    $string ="";
    $db->query("INSERT INTO $history_table (route_no,what_day,destination_account_no) values($_POST[route_id],$_POST[what_day],'$_POST[account_no]|')");
    echo "entrey did not exist just created it";
}  else {
    $io= $db->where('route_no',$_POST['route_id'])->where('what_day',$_POST['what_day'])->get($dbprefix."_rout_history","destination_account_no");
    if(count($io)>0){
        $string = $io[0]['destination_account_no'].$_POST['account_no']."|";
    } else {
        $string  = $_POST['account_no']."|";
    }
    
    $db->query("UPDATE $history_table SET destination_account_no = '$string' WHERE route_no = $_POST[route_id] && what_day = $_POST[what_day]");
}
?>