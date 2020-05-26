<?php
include "protected/global.php";
//ini_set("display_errrors",1);
$history_table = "sludge_rout_history_grease";
switch($_POST['mode']){
    case 1: // time start
        $history = $db->query("SELECT TIME(start_date) as tt FROM $history_table WHERE what_day =$_POST[what_day] && route_no = $_POST[route_id]");
        if(count($history)>0){
            echo $history[0]["tt"];
        }else {
            echo "";
        }
        break;
    case 2: //start mileage
        $crit = "start_mileage";
        $history = $db->where("route_no",$_POST['route_id'])->where("what_day",$_POST['what_day'])->get("sludge_rout_history_grease",$crit);
        if(count($history)>0){
            echo $history[0][$crit];
        }else {
            echo "";
        }
        break;
    case 3: // first stop
        $crit = "first_stop";
        $history = $db->where("route_no",$_POST['route_id'])->where("what_day",$_POST['what_day'])->get("sludge_rout_history_grease",$crit);
        if(count($history)>0){
            echo $history[0][$crit];
        }else {
            echo "";
        }
        
        break;
    case 4: //first stop mileage
        $crit = "first_stop_mileage";
        $history = $db->where("route_no",$_POST['route_id'])->where("what_day",$_POST['what_day'])->get("sludge_rout_history_grease",$crit);
        if(count($history)>0){
            echo $history[0][$crit];
        }else {
            echo "";
        }
        break;
    case 5: //last stop
        $crit = "last_stop";
        $history = $db->where("route_no",$_POST['route_id'])->where("what_day",$_POST['what_day'])->get("sludge_rout_history_grease",$crit);
        if(count($history)>0){
            echo $history[0][$crit];
        }else {
            echo "";
        }
        break;
    case 6: // last stop mileage
        $crit = "last_stop_mileage";
        $history = $db->where("route_no",$_POST['route_id'])->where("what_day",$_POST['what_day'])->get("sludge_rout_history_grease",$crit);
        if(count($history)>0){
            echo $history[0][$crit];
        }else {
            echo "";
        }
        break;
    case 7: // end time
        $history = $db->query("time_end FROM $history_table WHERE what_day =$_POST[what_day] && route_no = $_POST[route_id]");
        if(count($history)>0){
            echo $history[0]["time_end"];
        }else {
            echo "";
        }
        break;
    case 8:// end mileage
        $crit = "end_mileage";
        $history = $db->where("route_no",$_POST['route_id'])->where("what_day",$_POST['what_day'])->get("sludge_rout_history_grease",$crit);
        if(count($history)>0){
            echo $history[0][$crit];
        }else {
            echo "";
        }
        break;    
}



?>