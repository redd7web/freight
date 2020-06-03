<?php
$utility_table = $dbprefix."_utility";
$account_table = $dbprefix."_accounts";
$ikg_table = $dbprefix."_ikg_utility";
$util_data = $dbprefix."_utility_data_table";
ini_set("display_errors",1);
$string = "";
$have = "";
if(isset($_POST['search_now'])){
   
    foreach($_POST as $name=>$value){
        switch($name){
            case "facility":
                if(isset($_POST['facility']) && $_POST['facility'] !="ignore"){
                    $arrFields[] = " $account_table.division = $value";
                }
            break;
            
            case "service_list":
                if(isset($_POST['service_list']) && strlen($_POST['service_list'])>0){
                    $arrFields[] = " type_of_service  = $value";
                }
                
            break; 
            case "location_status":
                if(isset($_POST['location_status']) && $_POST['location_status'] !="-" && strlen($_POST['location_status']) >0){
                    $arrFields[] = "$account_table.status = '$value'";
                }
            break;
            case "salesrep":
                if(strlen($value)>0 && isset($value)){
                    $arrFields[] = " $account_table.account_rep =".$value;
                }
            break;
            case "to":
                if(strlen($value)>0){
                    if($_POST['report_type'] == 2){
                        $arrFields[] = "date(date_of_pickup) <='$value'";
                    }    else {
                        $arrFields[] = "date_of_service <= '$value'";
                    }
                }
                
            break;
            
            case "from":
                if(strlen($value)>0){
                    if($_POST['report_type'] == 2){
                        $arrFields[] = " date(date_of_pickup) >='$value'";
                    } else {
                      $arrFields[] = "date_of_service >= '$value'"; 
                    }
                }
            break;
            
            case "min":
                if(isset($_POST['min']) && strlen($value)>0){
                    $end[] = " DATEDIFF($utility_table.date_of_service,$util_data.date_of_pickup) >= $value";
                }
            break;
            case "max":
                if(isset($_POST['max']) && strlen($value)>0){
                    $end[] = " DATEDIFF($utility_table.date_of_service,$util_data.date_of_pickup) <= $value";
                }
            break;
            case "r_status":
                if(isset($_POST['r_status']) && strlen($value)>0){
                    $arrFields[] = "route_status = '$value'";
                }
            
        }
    }
    
    if(!empty($end)){
        $have = " having ( ".implode (" AND ",$end)." ) ";
    }
    
    
    if(!empty($arrFields)){
        $string = " AND ".implode(" AND ", $arrFields);
    }
    
    
    switch($_POST['my_group']){
      
        case "account_rep":
              $string = "SELECT DISTINCT($account_table.account_rep),
            $utility_table.*,
            $account_table.name,
            $account_table.city,
            $account_table.status,
            $account_table.account_ID,
            $account_table.division,
            $account_table.address,
            $account_table.zip,
            $account_table.state,
            DATEDIFF($utility_table.date_of_service,$util_data.date_of_pickup) as created_date, 
            $ikg_table.driver,
            $util_data.date_of_pickup,
            $util_data.fieldreport, 
            $util_data.schedule_id,
            $util_data.route_id
            FROM $account_table 
            LEFT JOIN $utility_table ON $utility_table.account_no = $account_table.account_ID 
            LEFT JOIN $ikg_table ON $ikg_table.route_id = $utility_table.rout_no 
            LEFT JOIN $util_data ON $utility_table.utility_sched_id = $util_data.schedule_id WHERE $utility_table.code_red =1 && $utility_table.route_status  in ('enroute','scheduled') ".$string." group by account_rep $have";
        break;
        case "division":
            $string = "SELECT DISTINCT($account_table.division),
            $utility_table.*,
            $account_table.name,
            $account_table.city,
            $account_table.status,
            $account_table.account_ID,            
            $account_table.address,
            $account_table.zip,
            $account_table.state,
            DATEDIFF($utility_table.date_of_service,$util_data.date_of_pickup) as created_date, 
            $ikg_table.driver,
            $util_data.date_of_pickup,
            $util_data.fieldreport, 
            $util_data.schedule_id,
            $util_data.route_id
            FROM $account_table 
            LEFT JOIN $utility_table ON $utility_table.account_no = $account_table.account_ID 
            LEFT JOIN $ikg_table ON $ikg_table.route_id = $utility_table.rout_no 
            LEFT JOIN $util_data ON $utility_table.utility_sched_id = $util_data.schedule_id WHERE $utility_table.code_red =1 && $utility_table.route_status  in ('enroute','scheduled')".$string." group by $account_table.division $have";
        break;
        case "driver":
             $string = "SELECT DISTINCT($ikg_table.driver),
            $utility_table.*,
            $account_table.name,
            $account_table.city,
            $account_table.status,
            $account_table.account_ID,
            $account_table.division,
            $account_table.address,
            $account_table.zip,
            $account_table.state,
            DATEDIFF($utility_table.date_of_service,$util_data.date_of_pickup) as created_date, 
            
            $util_data.date_of_pickup,
            $util_data.fieldreport, 
            $util_data.schedule_id,
            $util_data.route_id
            FROM $account_table 
            LEFT JOIN $utility_table ON $utility_table.account_no = $account_table.account_ID 
            LEFT JOIN $ikg_table ON $ikg_table.route_id = $utility_table.rout_no 
            LEFT JOIN $util_data ON $utility_table.utility_sched_id = $util_data.schedule_id WHERE $utility_table.code_red =1 && $utility_table.route_status  in ('enroute','scheduled') ".$string." group by $ikg_table.driver $have";
        break;
        case "created_by":
             $string = "SELECT DISTINCT($utility_table.created_by),
            $utility_table.*,
            $account_table.name,
            $account_table.city,
            $account_table.status,
            $account_table.account_ID,
            $account_table.division,
            $account_table.address,
            $account_table.zip,
            $account_table.state,
            DATEDIFF($utility_table.date_of_service,$util_data.date_of_pickup) as created_date, 
            $ikg_table.driver,
            $util_data.date_of_pickup,
            $util_data.fieldreport, 
            $util_data.schedule_id,
            $util_data.route_id
            FROM $account_table 
            LEFT JOIN $utility_table ON $utility_table.account_no = $account_table.account_ID 
            LEFT JOIN $ikg_table ON $ikg_table.route_id = $utility_table.rout_no 
            LEFT JOIN $util_data ON $utility_table.utility_sched_id = $util_data.schedule_id WHERE $utility_table.code_red =1 && $utility_table.route_status in ('enroute','scheduled') ".$string." group by $utility_table.created_by $have";
        break;
        default:
              $string = "SELECT 
            $utility_table.*,
            $account_table.name,
            $account_table.city,
            $account_table.status,
            $account_table.account_ID,
            $account_table.division,
            $account_table.address,
            $account_table.zip,
            $account_table.state,
            DATEDIFF($utility_table.date_of_service,$util_data.date_of_pickup) as created_date, 
            $ikg_table.driver,
            $util_data.date_of_pickup,
            $util_data.fieldreport, 
            $util_data.schedule_id,
            $util_data.route_id
            FROM $account_table 
            LEFT JOIN $utility_table ON $utility_table.account_no = $account_table.account_ID 
            LEFT JOIN $ikg_table ON $ikg_table.route_id = $utility_table.rout_no 
            LEFT JOIN $util_data ON $utility_table.utility_sched_id = $util_data.schedule_id WHERE $utility_table.code_red =1 && $utility_table.route_status  in ('enroute','scheduled') ".$string." $have";
        break;
        
    }
    
  
    
    //echo $string;
    $utilalarmdone = $db->query($string);
    
    
    
} else {
    $string ="SELECT $utility_table.*,$account_table.name,$account_table.city,$account_table.status,$account_table.account_ID,$account_table.division,$account_table.address,$account_table.zip,$account_table.state,DATEDIFF($utility_table.date_of_service, $utility_table.completed_date) as created_date, $ikg_table.driver ,
            $util_data.date_of_pickup,
            $util_data.fieldreport, 
            $util_data.schedule_id,
            $util_data.route_id FROM $account_table LEFT JOIN $utility_table ON $utility_table.account_no = $account_table.account_ID LEFT JOIN $ikg_table ON $ikg_table.route_id = $utility_table.rout_no  LEFT JOIN $util_data ON $utility_table.utility_sched_id = $util_data.schedule_id WHERE $utility_table.code_red =1 && $utility_table.route_status in ('enroute','scheduled')";
    //echo $string;
    $utilalarmdone = $db->query($string);

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
        "lengthMenu": [ [10, 25, 50,100,150, -1], [10, 25, 50,100,150, "All"] ]
   }); 
});
</script>

<table style="width: 100%;margin:auto;" id="myTable">
<thead>
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
<?php
if(isset($_POST['my_group'])){
            if($_POST['my_group'] == "account_rep"){
                echo "<td class='cell_label'>Account_rep</td>";
            }
        }
?>
<td class="underline_cell">Route Id</td>
<td class="cell_label">Location</a></td>
<td class="cell_label">Address</a></td>
<td class="cell_label">City</a></td>
<td class="cell_label">State</a></td>
<td class="cell_label">Zip</a></td>
<td class="cell_label"><span title="Location status">Loc Status</span></a></td>
<td class="cell_label">Route Status</td>
<td class="cell_label">Facility</td>
<td class="cell_label"><span title="Estimated date of service">ETA</span></a></td>

<td class="cell_label"><span title="When was this reported as a fire">Since</span></a></td>
<td class="cell_label">Service Type</td>
<td class="cell_label">Tote</td>
<td class="cell_label">Info</td>
<td class="cell_label"><span title="Who took the call">Added By</span></a></td>
<td class="cell_label">Driver</span></a></td>
</tr>
</thead>

<tbody>
<?php

if(count($utilalarmdone)>0){
    $counter = 1;
    foreach($utilalarmdone as $alarmdone){
        echo "<tr>";
        if(isset($_POST['my_group'])){
            if($_POST['my_group'] == "account_rep"){
                echo "<td>".uNumToName($alarmdone['account_rep'])."</td>";
            }
        }
        
        echo "<td>$alarmdone[rout_no]</td>";
        echo "<td>".account_NumtoName($alarmdone['account_no'])."</td>";
        echo "<td>$alarmdone[address]</td>";
        echo "<td>$alarmdone[city]";
        echo "<td>$alarmdone[state]";
        echo "<td>$alarmdone[zip]";
        echo "<td>$alarmdone[status]</td>";//loc status
        echo "<td>$alarmdone[route_status]</td>";
        echo "<td>".numberToFacility($alarmdone['division'])."</td>";
        echo "<td>$alarmdone[date_of_service]</td>";
        
          if($alarmdone['created_date'] == NULL && strlen($alarmdone['created_date'])>0){
          echo "<td>$alarmdone[created_date]</td>";
          } else {
            echo "<td>".date_different($alarmdone['date_of_service'],date("Y-m-d"))."</td>";
          }
           echo "<td>"; service_call_decode($alarmdone['type_of_service']); echo "</td>";
           echo "<td>".containerNumToName($alarmdone['container_label'])."</td>";
        
        echo "<td>".$alarmdone['fieldreport']."</td>";
        
        
      
        
        
        
        
        echo "<td>".uNumToName($alarmdone['created_by'])."</td>";
        echo "<td>".uNumToName($alarmdone['driver'])."</td>";
        
        $counter++;
    }

}


?>
</tbody>
</table>