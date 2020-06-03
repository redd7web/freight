<?php
error_reporting(E_WARNING | E_PARSE | E_NOTICE);
$account = new Account();
$schedule_table = $dbprefix."_scheduled_routes";
$account_table = $dbprefix."_accounts";
$data_table = $dbprefix."_data_table";

$criteria = "";
$addtn ='';
if($person->isFriendly()){
    $addtn = " AND freight_accounts.friendly like '%$person->first_name%'";
}

function total_galls_reps($id, $critera = ""){
    global $db;
    global $dbprefix;
    $account_table = $dbprefix."_accounts";
    $schedule_table = $dbprefix."_scheduled_routes";
    $data_table = $dbprefix."_data_table";
    $total = 0;
    $x = $db->query("SELECT $schedule_table.*, $account_table.account_ID,$account_table.address, $account_table.city, $account_table.previous_provider, $data_table.inches_to_gallons,$data_table.date_of_pickup,$data_table.schedule_id,$data_table.route_id,$account_table.account_rep FROM $schedule_table INNER JOIN $data_table ON $schedule_table.schedule_id = $data_table.schedule_id AND $schedule_table.route_id = $data_table.route_id  INNER JOIN $account_table   ON $schedule_table.account_no = $account_table.account_ID WHERE $schedule_table.route_status='completed' AND $schedule_table.code_red =1 AND $account_table.account_rep = $id ".$critera);
    
    if(count($x)>0){
        foreach($x as $completed_reps){
            $total = $total + $completed_reps['inches_to_gallons'];
        }
    }
    return $total;
    
}


function total_galls_friendly($friendly, $criera = ""){
    $x = $db->query("SELECT $schedule_table.*, $account_table.account_ID,$account_table.address, $account_table.city, $account_table.friendly, $data_table.inches_to_gallons,$data_table.date_of_pickup,$data_table.schedule_id,$data_table.route_id,$account_table.friendly FROM $schedule_table INNER JOIN $data_table ON $schedule_table.schedule_id = $data_table.schedule_id AND $schedule_table.route_id = $data_table.route_id  INNER JOIN $account_table   ON $schedule_table.account_no = $account_table.account_ID WHERE $schedule_table.route_status='completed' && $schedule_table.code_red =1 && $account_table.friendly = $friendly ".$critera);
    
    if(count($x)>0){
        foreach($x as $completed_reps){
            $total = $total + $completed_repsp['inches_to_gallons'];
        }
    }
    return $total;
}

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
                    $arrFields[] = " $data_table.date_of_pickup <='$value'";    
                }
                
            break;
            
            case "from":
                if(strlen($value)>0){
                    $arrFields[] = " $data_table.date_of_pickup >='$value'";
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
    
    if($_POST['my_group'] =="-"){
        echo "SELECT $schedule_table.*, $account_table.account_ID,$account_table.address, $account_table.city, $account_table.previous_provider, $data_table.inches_to_gallons,$data_table.date_of_pickup,$data_table.schedule_id,$data_table.route_id,$account_table.account_rep FROM $schedule_table INNER JOIN $data_table ON $schedule_table.schedule_id = $data_table.schedule_id AND $schedule_table.route_id = $data_table.route_id  INNER JOIN $account_table   ON $schedule_table.account_no = $account_table.account_ID WHERE $schedule_table.route_status='completed' AND $schedule_table.code_red =1".$criteria." ".$addtn;
    $fires = $db->query("SELECT $schedule_table.*, $account_table.account_ID,$account_table.address, $account_table.city, $account_table.previous_provider, $data_table.inches_to_gallons,$data_table.date_of_pickup,$data_table.schedule_id,$data_table.route_id,$account_table.account_rep FROM $schedule_table INNER JOIN $data_table ON $schedule_table.schedule_id = $data_table.schedule_id AND $schedule_table.route_id = $data_table.route_id  INNER JOIN $account_table   ON $schedule_table.account_no = $account_table.account_ID WHERE $schedule_table.route_status='completed' AND $schedule_table.code_red =1".$criteria." ".$addtn); 
    } 
    
} else {
    //echo "SELECT $schedule_table.*, $account_table.account_ID,$account_table.address, $account_table.city, $account_table.previous_provider, $data_table.inches_to_gallons,$data_table.date_of_pickup,$data_table.schedule_id,$data_table.route_id,$account_table.account_rep FROM $schedule_table INNER JOIN $data_table ON $schedule_table.schedule_id = $data_table.schedule_id AND $schedule_table.route_id = $data_table.route_id  INNER JOIN $account_table   ON $schedule_table.account_no = $account_table.account_ID WHERE $schedule_table.route_status='completed' AND $schedule_table.code_red =1";
    $fires = $db->query("SELECT $schedule_table.*, $account_table.account_ID,$account_table.address, $account_table.city, $account_table.previous_provider, $data_table.inches_to_gallons,$data_table.date_of_pickup,$data_table.schedule_id,$data_table.route_id,$account_table.account_rep FROM $schedule_table INNER JOIN $data_table ON $schedule_table.schedule_id = $data_table.schedule_id AND $schedule_table.route_id = $data_table.route_id  INNER JOIN $account_table   ON $schedule_table.account_no = $account_table.account_ID WHERE $schedule_table.route_status='completed' AND $schedule_table.code_red =1 $addtn");    
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

<div style="font-weigth:bold;margin:auto;margin-top:5px;"><span style="margin-left:100px;" width="200px;">Average Wait Days: 3</span><span style="margin-left:100px;" width="200px;">Maximum Wait Days: 27</span><span style="margin-left:100px;" width="200px;" title="Minimum wait can go negative because sometimes the pickup record is created after the fire has been picked up.">Minimum Wait Days: -1</span></div>

<table  style="width: 100%;margin:auto;" id="myTable" >
<thead>
<?php

if( isset($_POST['search_now']) ){
    if($_POST['my_group'] == "-"){
        ?>
        <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
        <?php if(isset($_POST['get_reps'])){ echo "<td class='cell_label'>Account Rep</td>";  } ?>
        <td class="underline_cell">Pickup ID</td>
        <td class="cell_label">ID</a></td>
        <td class="cell_label">Account Name</a></td>
        <td class="cell_label">Address</a></td>
        <td class="cell_label">City</a></td>
        <td class="cell_label">Friendly</a></td>
        
        <td class="cell_label">Route</a></td>
        <td class="cell_label">Pickup Status</td>
        
        <td class="cell_label">Date Reported</a></td>
        <td class="cell_label">Date Collected</a></td>
        <td class="cell_label">Gals</a></td>
        <td class="cell_label"><span title="Days between report and actual Pickup">Wait Days</span></a>
        
        </td></tr>
        <?php
    }else {
        echo '<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">';
        echo "<td class='cell_label'>$_POST[my_group]</td>";
        echo "<td class='cell_label'>gals</td>";
        echo "</tr>";
    }
} else {
  ?>
    <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
        <?php if(isset($_POST['get_reps'])){ echo "<td class='cell_label'>Account Rep</td>";  } ?>
        <td class="underline_cell">Pickup ID</td>
        <td class="cell_label">ID</a></td>
        <td class="cell_label">Account Name</a></td>
        <td class="cell_label">Address</a></td>
        <td class="cell_label">City</a></td>
        <td class="cell_label">Friendly</a></td>
        
        <td class="cell_label">Route</a></td>
        <td class="cell_label">Pickup Status</td>
        
        <td class="cell_label">Date Reported</a></td>
        <td class="cell_label">Date Collected</a></td>
        <td class="cell_label">Gals</a></td>
        <td class="cell_label"><span title="Days between report and actual Pickup">Wait Days</span></a>
    </td>
    </tr>
  <?php
}
?>    
</thead>
<tbody>
<?php

if( isset($_POST['search_now']) ){    
  if($_POST['my_group'] !="-"){
    
     switch($_POST['my_group']){
        case "account_rep":
            echo "grouped rep!<br/>";    
            $reps = $db->query("SELECT user_id FROM freight_users WHERE roles LIKE '%Sales%Representative%'");
            if(count($reps)>0){
                foreach($reps as $users){
                    echo "<tr>";
                    echo "<td>".uNumToName($users['user_id'])."</td>";
                    echo "<td>".total_galls_reps($users['user_id'],$criteria)."</td>";
                    echo "</tr>";
                    
                }    
            }
            
        break;
        case "friendly":
            $friend = $db->query("SELECT comp_id,comp_name FROM freight_friendly");
            if(count($friend)>0){
                foreach($friend as $friendly){
                    echo "<tr>";
                    echo "<td>$friendly[comp_name]</td>";
                    echo "<td>".total_galls_friendly($friendly['comp_id'],$criteria)."</td>";
                    echo "</tr>";
                }
            }    
        break;
    }
  } else {
    if(count($fires)>0){    
        $account = new Account();
        $counter = 1;
        foreach($fires as $completed_fire){        
                echo "<tr>";
                    if(isset($_POST['get_reps'])){
                        echo "<td>".uNumToName($completed_fire['account_rep'])."</td>";
                    }
                    echo "<td>$completed_fire[schedule_id]</td>";//schedule id
                    echo "<td title='".$account->singleField($completed_fire['account_no'],'name')."'>$completed_fire[account_no]</td>";//account number
                    echo "<td>".account_NumtoName($completed_fire['account_ID']) ."</td>";//account name
                    echo "<td>$completed_fire[address]</td>";//address
                    echo "<td>$completed_fire[city]</td>";//city
                    echo "<td>$completed_fire[previous_provider]</td>";//previous provider
                  
                    echo "<td style='cursor:pointer;text-decoration:underline;color:blue;'><form action='oil_routing.php' method='post' target='_blank' class='oil_routing'><input type='hidden' value='$completed_fire[route_id]' name='manifest'/>$completed_fire[route_id]<input type='hidden' value='1' name='from_routed_oil_pickups'/></form></td>";//route id ->manifest page
                    echo "<td>$completed_fire[route_status]</td>";//route status
                    echo "<td>$completed_fire[scheduled_start_date]</td>";//date reported
                    echo "<td>$completed_fire[date_of_pickup]</td>";//date picked up
                    echo "<td>".round($completed_fire['inches_to_gallons'],2)."</td>";//oil picked up
                  
                    echo "<td>";            
                        if( $completed_fire['scheduled_start_date'] !='0000-00-00' && $new_jh[1] !="0000-00-00" ){                     
                           echo date_different($completed_fire['scheduled_start_date'],$completed_fire['date_of_pickup']);
                        }
                        else {
                            echo "0";
                        }
                    echo "</td>";
                echo "</tr>";
                $counter++;
            
        }
    }
  }
}
else {
     if(count($fires)>0){    
        $account = new Account();
        $counter = 1;
        foreach($fires as $completed_fire){        
                echo "<tr>";
                    if(isset($_POST['get_reps'])){
                        echo "<td>".uNumToName($completed_fire['account_rep'])."</td>";
                    }
                    echo "<td>$completed_fire[schedule_id]</td>";//schedule id
                    echo "<td title='".$account->singleField($completed_fire['account_no'],'name')."'>$completed_fire[account_no]</td>";//account number
                    echo "<td>".account_NumtoName($completed_fire['account_ID']) ."</td>";//account name
                    echo "<td>$completed_fire[address]</td>";//address
                    echo "<td>$completed_fire[city]</td>";//city
                    echo "<td>$completed_fire[previous_provider]</td>";//previous provider
                  
                    echo "<td style='cursor:pointer;text-decoration:underline;color:blue;'><form action='oil_routing.php' method='post' target='_blank' class='oil_routing'><input type='hidden' value='$completed_fire[route_id]' name='manifest'/>$completed_fire[route_id]<input type='hidden' value='1' name='from_routed_oil_pickups'/></form></td>";//route id ->manifest page
                    echo "<td>$completed_fire[route_status]</td>";//route status
                    echo "<td>$completed_fire[scheduled_start_date]</td>";//date reported
                    echo "<td>$completed_fire[date_of_pickup]</td>";//date picked up
                    echo "<td>".round($completed_fire['inches_to_gallons'],2)."</td>";//oil picked up
                  
                    echo "<td>";            
                        if( $completed_fire['scheduled_start_date'] !='0000-00-00' && $completed_fire['date_of_pickup'] !="0000-00-00" ){                     
                           echo date_different($completed_fire['scheduled_start_date'],$completed_fire['date_of_pickup']);
                        }
                        else {
                            echo "0";
                        }
                    echo "</td>";
                echo "</tr>";
        }
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