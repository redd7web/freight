<?php
include "protected/global.php";
ini_set("display_errors",0);

$schnums = $db->query("SELECT schedule_id FROM freight_scheduled_routes WHERE schedule_id = $_GET[schedule_id]");

if(count($schnums)>0){
    foreach($schnums as $value){
        $schedule = new Scheduled_Routes($value['schedule_id']);
        $alter++;
        if($alter%2 == 0){
            $bg = '-moz-linear-gradient(center top , #F7F7F9, #E5E5E7) repeat scroll 0 0 rgba(0, 0, 0, 0)';
        }
        else { 
            $bg = 'transparent';
        }
        
        echo"<tr id='row$value[schedule_id]' style='cursor:pointer;background:$bg;' class='accnt_row'  xlr='$schedule->account_number' title='$schedule->account_number'>";
        
        echo"<td><img src='img/delete-icon.jpg' title='remove pickup $value[schedule_id]' xlr='$schedule->account_number' class='deletesched' rel='$value[schedule_id]'/>&nbsp;&nbsp;</td>";
        echo "<td>$count</td>";                
        echo "<td  style='width:50px;'>$schedule->code_red</td>
        <td>"; 
            if(strlen($schedule->account_friendly)>0){
                echo "F";
            }
        echo "</td><td>$schedule->route_status</td>";
        echo "<td>"; 
            $datex = explode(" ",$schedule->scheduled_start_date);
            echo $datex[0];
        echo "</td>";
        echo "<td>$schedule->account_name</td>";
        echo "<td>$schedule->account_city</td>";
        echo "<td>$schedule->account_address</td>";
        echo "<td>$schedule->cs_reason</td>";
        
        echo"</tr>";
    }
}


?>