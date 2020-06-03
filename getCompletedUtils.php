<?php
ini_set("display_errors",1);

$rddtn="";
$search_string ="";
$utility_route_list_table = $dbprefix."_list_of_utility";
$utility_table = $dbprefix."_ikg_utility";
if(  $person->isCoWest()   ){
    $rddtn = " AND facility= 15 ";
} else if($person->isFriendly()){
    $rddtn = " AND $utility_table.rout_no IN ( SELECT freight_utility.rout_no FROM freight_utility LEFT JOIN freight_accounts ON freight_utility.account_no  = freight_accounts.account_ID WHERE 1  AND freight_accounts.friendly like '%$person->first_name%'  ) ";
}


if(isset($_POST['search_now'])){
     foreach($_POST as $name=>$value){
        switch($name){
                     case "drivers":            
                        if($value !="-"){
                           $arrFields[] = "$utility_table.driver = $value"; 
                        }
                    break;
                     case "rtitle":
                        if(strlen($_POST['rtitle']) && isset($_POST['rtitle'])){
                            $value = str_replace(' ','-',$value);
                            $arrFields[] = " $utility_table.ikg_manifest_route_number like '%$value%'";
                        }
                        break;
                    case "rid":
                        if(isset($_POST['rid']) && strlen($_POST['rid'])>0){
                            $arrFields[] = " $utility_table.rout_no = $value";
                        }
                        break;
                    case "to":
                        if(strlen($value)>0){
                            if($_POST['report_type'] == 2){
                                $arrFields[] = " $utility_table.completed_date <='$value'";
                            }    else {
                                $arrFields[] = "$utility_table.scheduled_date <= '$value'";
                            }
                        }
                        
                    break;
                    
                    case "from":
                        if(strlen($value)>0){
                            if($_POST['report_type'] == 2){
                                $arrFields[] = " $utility_table.completed_date >='$value'";
                            } else {
                              $arrFields[] = "$utility_table.scheduled_date >= '$value'"; 
                            }
                        }
                    break;
                    
                    case "min":
                        if(isset($_POST['min']) && strlen($value)>0){
                            $end[] = " DATEDIFF($utility_table.scheduled_date,$utility_table.completed_date) >= $value";
                        }
                    break;
                    case "max":
                        if(isset($_POST['max']) && strlen($value)>0){
                            $end[] = " DATEDIFF($utility_table.scheduled_date,$utility_table.completed_date) <= $value";
                        }
                    break;           
            }
     }
     if(!empty($arrFields)){
        $criteria = " AND ". implode (" AND ", $arrFields);
     }
     if(!empty($end)){
            $have = " having ".implode (" AND ",$end);
        }
     switch($_POST['my_group']){
            case "-": 
                $string = "SELECT $utility_route_list_table.*,$utility_table.* FROM $utility_route_list_table INNER JOIN $utility_table ON $utility_route_list_table.route_id = $utility_table.route_id WHERE $utility_route_list_table.status='completed' ".$criteria.$have;
                
            break;
            case "recieving_facility":
                $string ="SELECT DISTINCT($utility_table.recieving_facility),$utility_table.*,DATEDIFF($utility_table.scheduled_date,$utility_table.completed_date) as diff, $utility_route_list_table.status,$utility_route_list_table.inc,$utility_route_list_table.stops,$utility_route_list_table.expected,$utility_route_list_table.created_date,$utility_route_list_table.created_by FROM $utility_table INNER JOIN $utility_route_list_table ON $utility_table.route_id = $utility_route_list_table.route_id WHERE status='completed' $criteria $rddtn GROUP BY recieving_facility ".$have;
            break;
            case "driver":
                $string ="SELECT DISTINCT($utility_table.driver),$utility_table.*,DATEDIFF($utility_table.scheduled_date,$utility_table.completed_date) as diff , $utility_route_list_table.status,$utility_route_list_table.inc,$utility_route_list_table.stops,$utility_route_list_table.expected,$utility_route_list_table.created_date,$utility_route_list_table.created_by FROM $utility_table INNER JOIN $utility_route_list_table ON $utility_table.route_id = $utility_route_list_table.route_id WHERE status='completed' $criteria $rddtn group by $utility_table.driver ".$have;
            break;
            case "created_by":
                $string ="SELECT DISTINCT($utility_table.created_by), $utility_table.*,DATEDIFF($utility_table.scheduled_date,$utility_table.completed_date) as diff, $utility_route_list_table.status,$utility_route_list_table.inc,$utility_route_list_table.stops,$utility_route_list_table.expected,$utility_route_list_table.created_date,$utility_route_list_table.created_by FROM $utility_table INNER JOIN $utility_route_list_table ON $utility_table.route_id = $utility_route_list_table.route_id WHERE status='completed' $criteria group by $utility_route_list_table.created_by ".$have;
            break;
            default:
                $string = "SELECT $utility_table.*,DATEDIFF($utility_table.scheduled_date,$utility_table.completed_date) as diff , $utility_route_list_table.status,$utility_route_list_table.inc,$utility_route_list_table.stops,$utility_route_list_table.expected,$utility_route_list_table.created_date,$utility_route_list_table.created_by FROM $utility_table INNER JOIN $utility_route_list_table ON $utility_table.route_id = $utility_route_list_table.route_id WHERE status ='completed' $criteria $rddtn  order by $utility_route_list_table.created_date DESC";
            break;
        }
     
     
     $check = $db->query($string);   
} else {
    $string = "SELECT * FROM $utility_route_list_table WHERE status='completed' $rddtn" ;
    $check  = $db->query($string);
}

echo $string;
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
        "lengthMenu": [ [50,10, 25, 100,150, -1], [50,10, 25, 100,150, "All"] ]
   }); 
});
</script>
<table style="width: 100%;margin:auto;"  id="myTable">
<thead>
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">

    <td class="cell_label">ID</td>
    
    <td class="cell_label">Status</td>
    
    <td class="cell_label">Title</td>
    
    <td class="cell_label">Created</td>
    
    <td class="cell_label">Scheduled</td>
    
    <td class="cell_label">Completed</td>
    
   <td class="cell_label">Driver</td>
    
    <td class="cell_label">Facility</td>
    
    


 

</tr>
</thead>
<tbody>
<?php
if(count($check)>0){
    foreach($check as $completed){
        $schedule_service_types = $db->where('rout_no',$completed['route_id'])->get($dbprefix."_utility","type_of_service");
        echo "<tr>";
         echo "<td>"; 
        echo "$completed[route_id]" ; 
        echo "</td>";
        echo "<td>$completed[status]</td>";
        echo "<td style='cursor:pointer;'><form action='ikg_routing.php' target='_blank' method='post' class='ikg_form'>$completed[ikg_manifest_route_number]<input type='hidden' value='$completed[ikg_manifest_route_number]' name='util_number'/><input type='hidden' value='1' name='from_routed_util_list'/></form></td>";
        echo "<td>$completed[created_date]</td>";
        echo "<td>$completed[scheduled]</td>";
        echo "<td>$completed[completed_date]</td>";
        echo "<td>"; 
        if($completed['driver'] == 0){
            echo "No Driver set";
        } else {
           echo uNumToName($completed['driver']);
        }
        
        echo  "</td>";
        echo "<td>"; 
        if ($completed['facility'] >0 ) {
            echo numberToFacility($completed['facility']);
        } else {
            echo "No Facility Set";
        }
        echo"</td>";
        
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

$(".delroute").click(function(){
    $.get("adminDelUtilRoute.php",{util_route_no: $(this).attr('rel')},function(data){
        alert("route Deleted");
        location.reload(); 
    });
});
</script>