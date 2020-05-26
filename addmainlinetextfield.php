<?php
include "protected/global.php";
//ini_set("display_errors",1);
$sched = $db->where("rout_no",$_GET['route_id'])->where("utility_sched_id","$_GET[sched_id]")->get($dbprefix."_utility","account_no,rout_no,utility_sched_id,rout_no");//schedule info

if(count($sched)>0){
    echo "<tbody id='workid'>";
    echo "<tr><td>"; 
    $cs_form = $db->query("SELECT * FROM Inetforms.ap_form_28181 WHERE ap_form_28181.element_50 = $_GET[route_id] AND ap_form_28181.element_51 = $_GET[sched_id] AND ap_form_28181.element_52 = ".$sched[0]['account_no']." AND ap_form_28181.element_35 = 1");
    
    if(count($cs_form)>0){
        echo "&nbsp;<span class='view_form' rel=".$cs_form[0]['id']." style='cursor:pointer;text-decoration:underline;'>View Mainline Jetting Work Form</span>";
    }else{
        echo "&nbsp;<span class='work_form' style='cursor:pointer;color:blue;font-weight:bold;text-decoration:underline;' schedule_id='".$sched[0]['utility_sched_id']."' account='".$sched[0]['account_no']."' route_id='".$sched[0]['rout_no']."'   >Mainline Jetting Work Form</a>";
    }
    
    
    echo "</td></tr>";
    
}else {
    echo "<tbody id='workid'>";
    echo "<tr><td>missing info";
    echo "</td></tr>";
}


?>