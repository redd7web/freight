<?php
ini_set("display_errors",1);
$account = new Account();
$schedule_table = $dbprefix."_scheduled_routes";
$account_table = $dbprefix."_accounts";
$data_table = $dbprefix."_data_table";
$ikg_single = $dbprefix."_ikg_manifest_info";
$addtn ='';
if($person->isFriendly()){
    $addtn = " AND freight_accounts.friendly like '%$person->first_name%'";
}


$criteria = "";
$have="";

if(isset($_POST['search_now'])){
    foreach($_POST as $name=>$value){
        switch($name){
            case "salesrep":
                if(strlen($value)>0 && isset($value)){
                    $arrFields[] = " $account_table.account_rep =".$value;
                }
            break;
            case "to":
                if(strlen($value)>0){
                    if($_POST['report_type'] == 2){
                        $arrFields[] = " $data_table.date_of_pickup <='$value'";
                    }    else {
                        $arrFields[] = "$schedule_table.scheduled_start_date <= '$value'";
                    }
                }
                
            break;
            
            case "from":
                if(strlen($value)>0){
                    if($_POST['report_type'] == 2){
                        $arrFields[] = " $data_table.date_of_pickup >='$value'";
                    } else {
                      $arrFields[] = "$schedule_table.scheduled_start_date >= '$value'"; 
                    }
                }
            break;
            
            case "min":
                if(isset($_POST['min']) && strlen($value)>0){
                    $end[] = " DATEDIFF($schedule_table.scheduled_start_date,NOW()) >= $value";
                }
            break;
            case "max":
                if(isset($_POST['max']) && strlen($value)>0){
                    $end[] = " DATEDIFF($schedule_table.scheduled_start_date,NOW()) <= $value";
                }
            break;
             case "facility":
                if($value !="ignore"){
                    if ( $value == 99){
                        $arrFields[] = " division IN(24,31,30,32,33)";
                    }else{
                        $arrFields[] = " division = $value";
                    }
                }  
            break;
        }
    }
    
    
    if(!empty($arrFields)){
        $criteria = " AND ".implode(" AND ",$arrFields);
    }
    
    if(!empty($end)){
        $have = " having ".implode (" AND ",$end);
    }
    
    
    switch($_POST['my_group']){
        case "-":
            $string = "SELECT  $schedule_table.*, $account_table.account_ID,$account_table.address, $account_table.city,$account_table.state,  $account_table.division,$account_table.account_rep,$account_table.avg_gallons_per_month as net,DATEDIFF($schedule_table.scheduled_start_date,NOW()) AS DiffDate FROM $schedule_table   LEFT JOIN $account_table   ON $schedule_table.account_no = $account_table.account_ID LEFT JOIN $ikg_single ON $ikg_single.route_id = $schedule_table.route_id WHERE $schedule_table.route_status in ('enroute','scheduled') AND $schedule_table.code_red =1 $criteria $addtn".$have; 
        break;
        case "account_rep":
             $string ="SELECT DISTINCT($account_table.account_rep),  $schedule_table.*, $account_table.account_ID,$account_table.address, $account_table.city,$account_table.state,  $account_table.division, $account_table.avg_gallons_per_month as net,DATEDIFF($schedule_table.scheduled_start_date,NOW()) AS DiffDate FROM $schedule_table   LEFT JOIN $account_table   ON $schedule_table.account_no = $account_table.account_ID LEFT JOIN $ikg_single ON $ikg_single.route_id = $schedule_table.route_id WHERE $schedule_table.route_status in ('enroute','scheduled') AND $schedule_table.code_red =1 $addtn $criteria group by $account_table.$_POST[my_group]".$have; 
        break;
        case "division":
            $string = "SELECT DISTINCT($account_table.division), $schedule_table.*, $account_table.account_ID,$account_table.address, $account_table.city,$account_table.state,  $account_table.division, $account_table.account_rep,$account_table.avg_gallons_per_month as net,DATEDIFF($schedule_table.scheduled_start_date,NOW()) AS DiffDate FROM $schedule_table   LEFT JOIN $account_table   ON $schedule_table.account_no = $account_table.account_ID LEFT JOIN $ikg_single ON $ikg_single.route_id = $schedule_table.route_id WHERE $schedule_table.route_status in ('enroute','scheduled') AND $schedule_table.code_red =1 $addtn $criteria group by $account_table.$_POST[my_group]".$have;
        break;
        case "driver":
            $string ="SELECT DISTINCT($schedule_table.driver), $schedule_table.*, $account_table.account_ID,$account_table.address, $account_table.city,$account_table.state,  e.scheduled_start_date,NOW()) AS DiffDate FROM $schedule_table   LEFT JOIN $account_table   ON $schedule_table.account_no = $account_table.account_ID LEFT JOIN $ikg_single ON $ikg_single.route_id = $schedule_table.route_id WHERE $schedule_table.route_status in ('enroute','scheduled') AND $schedule_table.code_red =1 $addtn  $criteria group by $schedule_table.$_POST[my_group]".$have;
        break;
        case "created_by":
            $string ="SELECT DISTINCT($schedule_table.created_by), $schedule_table.*, $account_table.account_ID,$account_table.address, $account_table.city,$account_table.state,  $account_table.division, $account_table.account_rep,$account_table.avg_gallons_per_month as net,DATEDIFF($schedule_table.scheduled_start_date,NOW()) AS DiffDate FROM $schedule_table   LEFT JOIN $account_table   ON $schedule_table.account_no = $account_table.account_ID LEFT JOIN $ikg_single ON $ikg_single.route_id = $schedule_table.route_id WHERE $schedule_table.route_status in ('enroute','scheduled') AND $schedule_table.code_red =1 $addtn $criteria group by $schedule_table.$_POST[my_group]".$have;
        break;
        
    }
     echo $string;
     $fires = $db->query($string);
} else {
    $string = "SELECT  $schedule_table.*, $account_table.account_ID,$account_table.address, $account_table.city,$account_table.state,  $account_table.division, $account_table.account_rep,$account_table.avg_gallons_per_month as net,DATEDIFF($schedule_table.scheduled_start_date,NOW()) AS DiffDate FROM $schedule_table   LEFT JOIN $account_table   ON $schedule_table.account_no = $account_table.account_ID LEFT JOIN $ikg_single ON $ikg_single.route_id = $schedule_table.route_id WHERE $schedule_table.route_status in ('enroute','scheduled') AND $schedule_table.code_red =1 $addtn";
    echo $string;
    $fires = $db->query($string);  
     
}

//$fires = $db->where("code_red",1)->where("route_status","completed")->get($dbprefix."_scheduled_routes");


//var_dump($fires);
//echo "this page";
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

<div style="font-weigth:bold;margin:auto;margin-top:5px;"><span style="margin-left:100px;" width="200px;">Average Wait Days: <?php
    if(count($fires)>0){  
        $wait_days = array();
        foreach($fires as $wait){
            $wait_days[] = date_different($wait['scheduled_start_date'],date('Y-m-d'));
        }
        echo round(array_sum($wait_days) /count($wait_days) ,2);
    }

?></span><span style="margin-left:100px;" width="200px;">Maximum Wait Days: <?php if(!empty($wait_days)>0){ echo max($wait_days); } ?></span><span style="margin-left:100px;" width="200px;" title="Minimum wait can go negative because sometimes the pickup record is created after the fire has been picked up.">Minimum Wait Days: <?php if(!empty($wait_days)>0) echo min($wait_days); ?></span></div>

<table  style="width: 100%;margin:auto;" id="myTable" >
    <thead>
    <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
        <?php 
        if(isset($_POST['my_group'])){
            switch($_POST['my_group']){
                case "account_rep":
                   echo ' <td class="cell_label">Account Rep</td>';
                   break;
                 
            }    
        }
        
        ?>
        <td class="cell_label">Account Name</a></td>
        <td class="cell_label">Address</a></td>
        <td class="cell_label">City</a></td>
        <td class="cell_label">State</a></td>
        
        <td class="cell_label">Route</a></td>
        <td class="cell_label">Facility</td>
        
        <td class="cell_label">Date Reported</a></td>
        <td class="cell_label">Route Status</a></td>
        <td class="cell_label">Onsite Gallons</a></td>
        
        <td class="cell_label"><span title="Days between report and actual Pickup">Wait Days</span></a>
         
        </td>
        <td class="cell_label">Driver</td><td  class="cell_label">Added By</td>
    </tr>
</thead>
<tbody>
<?php

if(count($fires)>0){    
        $account = new Account();
        $counter = 1;
        foreach($fires as $completed_fire){        
                echo "<tr>";
                    if(isset($_POST['my_group'])){
                        switch($_POST['my_group']){
                            case "account_rep":
                               echo ' <td >'.uNumToName($completed_fire['account_rep']).'</td>';
                               break;
                             
                        } 
                    }
                    
                    if(isset($_POST['get_reps'])){
                        echo "<td>".uNumToName($completed_fire['account_rep'])."</td>";
                    }
                   
                    
                    echo "<td>".account_NumtoName($completed_fire['account_ID']) ."</td>";//account name
                    echo "<td>$completed_fire[address]</td>";//address
                    echo "<td>$completed_fire[city]</td>";//city
                    echo "<td>$completed_fire[state]</td>";//previous provider
                  
                    echo "<td style='cursor:pointer;text-decoration:underline;color:blue;'>"; 
                        if(strlen($completed_fire['route_id'])>0){
                            echo "<form action='oil_routing.php' method='post' target='_blank' class='oil_routing'><input type='hidden' value='$completed_fire[route_id]' name='manifest'/>$completed_fire[route_id]<input type='hidden' value='1' name='from_routed_oil_pickups'/></form>";
                        } else {
                            echo "0";
                        }
                    echo "</td>";//route id ->manifest page
                    echo "<td>".numberToFacility($completed_fire['division'])."</td>";//route status
                    echo "<td>$completed_fire[scheduled_start_date]</td>";//date reported
                    echo "<td>$completed_fire[route_status]</td>";//date picked up
                    
                     echo "<td>".round($completed_fire['net'],2)."</td>";//oil picked up
                  
                    echo "<td>";            
                                   
                           echo $completed_fire['DiffDate'];
                        
                    echo "</td>";
                    
                    echo "<td>"; 
                    if(strlen($completed_fire['driver'])>0){
                       echo  uNumToName($completed_fire['driver']);
                    } else{
                        echo "&nbsp;";
                    }
                    
                    echo "</td>";
                    
                    echo "<td>".uNumToName($completed_fire['created_by'])."</td>";                   
                echo "</tr>\r\n";
        }
    }

?>
</tbody>
</table>
<script>
$(".oil_routing").click(function(){
    $(this).submit();
});
</script>