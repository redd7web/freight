<?php
$utility_table = $dbprefix."_utility";
$account_table = $dbprefix."_accounts";
$requests = $db->query("SELECT $utility_table.*,$account_table.city,$account_table.state,$account_table.zip,$account_table.division,$account_table.name, $account_table.pickup_frequency,$account_table.account_ID FROM $utility_table INNER JOIN $account_table ON $utility_table.account_no = $account_table.account_ID WHERE $utility_table.route_status !='completed' ");
?>

<style type="text/css">
.tableNavigation {
    width:1000px;
    text-align:center;
    margin:auto;
}
.tableNavigation ul {
    display:inline;
    width:1000px;
}
.tableNavigation ul li {
    display:inline;
    margin-right:5px;
}

td{
    background:transparent;
    border:0px solid #bbb;  
    padding:0px 0px 0px 0px;  
}

tr.even{
    background:-moz-linear-gradient(center top , #F7F7F9, #E5E5E7);
}

tr.odd{
    background:transparent;
}
.setThisRoute{ 
    z-index:9999;
}
</style>
<script>

$(document).ready(function(){
   $('#myTable').dataTable({
        "lengthMenu": [ [10, 25, 50,100,150, -1], [10, 25, 50,100,150, "All"] ]
   }); 
});
</script>
<table style="width: 100%;margin:auto;" id="myTable">
<thead>
    <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
    
    <td class="underline_cell">#</td>
    <td class="cell_label">Info</td>
    <td class="cell_label">Since</span></td>
    <td class="cell_label">ETA</span></td>
    <td class="cell_label"><span title="Estimated monthly volume in gallons">Volume</span></td>
    <td class="cell_label">Location</td>
    <td class="cell_label">City</td>
    <td class="cell_label">State</td>
    <td class="cell_label">Zip</td>
    <td class="cell_label">Facility</td>
    
    </tr>
</thead>
<tbody>
<?php
 if(count($requests)>0){
    $counter =1;
    foreach($requests as $containerReq){
        $total = 0;
        $gals = $db->where("account_no",$containerReq['account_ID'])->get($dbprefix."_data_table","inches_to_gallons");
        
        if(count($gals)>0){
            foreach($gals as $amt){
                $total = $total + $amt['inches_to_gallons'];
            }
        }
        echo "<tr>";
        echo "<td>$counter</td>";
        echo "<td>$containerReq[dispatcher_note]<br/>$containerReq[driver_note]</td>";
        echo "<td>"; 
        
        if($containerReq['created_date'] !='0000-00-00'){
            echo date_different($containerReq['date_created'],date("Y-m-d"));
        } else {
            echo $containerReq['created_date'];
        }
        echo "</td>";//since
        echo "<td>$containerReq[date_of_service]</td>";//eta
        echo "<td>"; 
        echo round($total/$containerReq['pickup_frequency'],2);
        
         echo "</td>";//estimated monthly volume
        echo "<td>$containerReq[name]</td>";//name
        echo "<td>$containerReq[city]</td>";
        echo "<td>$containerReq[state]</td>";
        echo "<td>$containerReq[zip]</td>";
        echo "<td>".numberToFacility($containerReq['division'])."</td>";
        echo "</tr>";
        $counter++;
    }
 }

?>
</tbody>

</table>