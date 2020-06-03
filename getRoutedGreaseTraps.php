<?php
    $list_of_grease =$dbprefix."_list_of_grease";
    $string ="";
    $rddtn ='';
    if($person->isFriendly()){
        $rddtn = " AND route_id IN ( SELECT freight_grease_traps.grease_route_no FROM freight_grease_traps LEFT JOIN freight_accounts ON freight_grease_traps.account_no  = freight_accounts.account_ID WHERE  1  AND freight_accounts.friendly like '%$person->first_name%' ) ";
    }else if( $person->isCoWest() ){
        $rddtn = " AND facility IN( 15,24,30,31,32,33)";
    }
    
    if(isset($_POST['search_now'])){
        foreach($_POST as $name=>$value){
            switch($name){
                 case "facility":
                    if($value !="ignore" && $value !=" " && $value !=""){
                        $arrFields[] = "facility = $value";    
                    }
                 break;
                 case "rtitle":
                if(strlen($_POST['rtitle']) && isset($_POST['rtitle'])){
                    $value = str_replace(' ','%',$value);
                    $arrFields[] = " ikg_manifest_route_number like '%$value%'";
                }
                break;
            case "rid":
                if(isset($_POST['rid']) AND strlen($_POST['rid'])>0){
                    $arrFields[] = " route_id = $value";
                }
                break;
                case "status_id":
                    if(isset($_POST['status_id']) && strlen($_POST['status_id'])>0 && $value !="all"){
                        $arrFields[] = " status = '$value'";
                    }
                    break;
                case "drivers":
                    if(isset($_POST['drivers']) && $_POST['drivers'] !="-"){
                        $arrFields[] = " driver = $value";
                    }
                    break;
            }
        }
        
        if(!empty($arrFields)){
            $string = implode (" AND ",$arrFields);
        }
        $string = "SELECT * FROM $list_of_grease WHERE 1 AND ".$string." $rddtn";
        echo $string;
        $check = $db->query($string);
        
    }
    else {
        echo "SELECT * FROM $list_of_grease WHERE status IN('enroute','scheduled') $rddtn";
        $check = $db->query("SELECT * FROM $list_of_grease WHERE status IN('enroute','scheduled')  $rddtn");
    }
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
        "order": [ 4, 'DESC' ],
        "lengthMenu": [ [10, 25, 50,100,150, -1], [10, 25, 50,100,150, "All"] ]
   }); 
});
</script>
<table style="width: 100%;margin:auto;" id="myTable">
    <thead>
        <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">  
            <td>&nbsp;</td>          
            <td class="cell_label">ID</td>
            <td class="cell_label">Status</td>
            <td class="cell_label">Title</td>
            <td class="cell_label">Created</td>
            <td class="cell_label">Scheduled</td>
            <td class="cell_label">Completed</td>
            <td class="cell_label">Driver</td>
            <td class="cell_label">Facility</td>
            <td class="cell_label">Services</td>
            <td class="cell_label"><span title="Number of incomplete service calls.">Inc.</span></td>
        </tr>
    </thead>
<tbody>
<?php

if(count($check)>0){
    foreach($check as $routed_grase){
        echo "<tr>
                ";
                echo "<td>"; 
                    if($person->isFacilityManager() || $person->isAdmin()){
                        echo "<img src='img/delete-icon.jpg' style='cursor:pointer;' rel='$routed_grase[route_id]' class='greaseroute'/>";
                    }
                echo "</td>";
            echo "<td>$routed_grase[route_id]</td>";
            
            echo "<td>$routed_grase[status]</td>";
            echo "<td><form action='grease_ikg.php' target='_blank' method='post' class='ikg_form' style='cursor:pointer;'>
            $routed_grase[ikg_manifest_route_number]
            
            <input type='hidden' value='$routed_grase[route_id]' name='util_routes'/>
            
            <input type='hidden' value='1' name='from_routed_grease_list'/></form></td>"; 
            echo "<td>$routed_grase[created_date]</td>";
            echo "<td>$routed_grase[scheduled]</td>";
            echo "<td>$routed_grase[completed_date]</td>";
            echo "<td>".uNumToName($routed_grase['driver'])."</td>";
            echo "<td>".numberToFacility($routed_grase['facility'])."</td>";
            echo "<td>"; 
            $counts = $db->query("SELECT count(service) AS num_services, service FROM freight_grease_traps WHERE grease_route_no = $routed_grase[route_id] GROUP BY service");
            if(count($counts)>0){
                foreach($counts as $service){
                    echo $service['num_services'].")".$service['service']."<br/>";
                }
            }
            echo "</td>";
            echo "<td>$routed_grase[inc]</td>";
        echo "</tr>";
    }
}

?>
</tbody>

</table>
<script>
$(".ikg_form").click(function(){
    $(this).submit();
});

$(".greaseroute").click(function(){
   if(confirm("Are you you wish to delete this grease trap route?")){
        $.get("adminDelGreaseRoute.php",{route_id: $(this).attr('rel')},function(data){
            alert("route Deleted! "+data);
            location.reload();
       }); 
   }
});
</script>