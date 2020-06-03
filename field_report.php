<?php
include "protected/global.php";
ini_set("display_errors",0);
switch($_GET['type']){
    case "oil":
    $table = "freight_data_table";
    $ikg = new IKG($_GET['ikg']);
    break;
    case "grease":
    $table = "freight_grease_data_table";
    $ikg = new Grease_IKG($_GET['ikg']);
    break;
    
    case "util":
    $table = "freight_utility_data_table";
    $ikg = new Container_Route($_GET['ikg']);
}

$acc = new Account();
?>
<style>
body{
    padding:10px 10px 10px 10px;
    margin:5px 5px 5px 5px;
}
</style>

<table style="width: 100%;">
<tr><td>Stop #</td><td>Account</td><td> Field Report </td><td>Zero Material Reason</td><td>Notes</td></tr>
<?php
    if(count($ikg->scheduled_routes)>0){
        $count = 0;   
        foreach($ikg->scheduled_routes as $pickups){
            $count++;
            $schedule = new Grease_Stop($pickups);
            $request = $db->query("SELECT account_no,schedule_id,fieldreport,zero_gallon_reason,schedule_id FROM $table WHERE route_id=$_GET[ikg] AND zero_gallon_reason !=0 AND schedule_id = $pickups");
            echo "<tr><td>$count</td><td>".$schedule->account_name."</td>";
            
            if(count($request)>0){
                echo "<td>".$request[0]['fieldreport']."</td>
                      <td>";field_report_decode($request[0]['zero_gallon_reason']); echo"</td>";    
            }else {
                echo "<td>&nbsp;</td>
                      <td>&nbsp;</td>";
            }
            echo "<td>".$acc->singleField($schedule->account_number,"notes")."</td></tr>";
        }
    }

?>
</table>