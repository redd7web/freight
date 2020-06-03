<?php
$alter =0;
$schedule_table = $dbprefix."_scheduled_routes";
$data_table = $dbprefix."_data_table";
$account_table = $dbprefix."_accounts";
$user_table = $dbprefix."_users";

$criteria = "";
if(isset($_POST['search_now'])){
        foreach($_POST as $name=>$value){
             switch($name){
                case "facility";
                    if($value !="ignore"){
                        if ($value == 99){
                             $arrFields[] = "division in(24,30,31,32,33)";
                        }
                        else{
                            $arrFields[] = "division = ".$value;
                        } 
                    }
                break;
                case "salesrep":
                    if(strlen($value) != 0 && $value !=""){
                        $arrFields[] = " account_rep =".$value;
                    }
                break;
                case "from":
                    if(strlen($value)>0 && isset($value)){
                        $arrFields[] = " date_of_pickup >= '$_POST[from]'";   
                    }
                break;
                case "to":
                    if(strlen($value)>0 && isset($value)){
                        $arrFields[] = " date_of_pickup <= '$_POST[to]'";
                    }
                break; 
        }
    }
  
    if(!empty($arrFields)){
        $criteria = " AND ".implode(" AND ", $arrFields);
    }
    
    
    if($_POST['my_group'] == "-"){
        $ask = "SELECT freight_scheduled_routes.*, freight_data_table.inches_to_gallons, freight_data_table.date_of_pickup, freight_data_table.route_id, freight_data_table.schedule_id, freight_data_table.account_no,freight_accounts.account_rep FROM freight_scheduled_routes INNER JOIN freight_data_table ON freight_scheduled_routes.route_id = freight_data_table.route_id INNER JOIN freight_accounts ON freight_scheduled_routes.account_no = freight_accounts.account_ID AND freight_scheduled_routes.schedule_id = freight_data_table.schedule_id AND freight_scheduled_routes.account_no = freight_data_table.account_no WHERE freight_scheduled_routes.code_red =1 AND freight_scheduled_routes.route_status like '%completed%'". $criteria;
        //echo $ask;
        $result =$db->query($ask);    
    }else {
        $ask = "SELECT freight_scheduled_routes.*, freight_data_table.inches_to_gallons, freight_data_table.date_of_pickup, freight_data_table.route_id, freight_data_table.schedule_id, freight_data_table.account_no,freight_accounts.account_rep,freight_accounts.division,freight_accounts.friendly,freight_accounts.previous_provider FROM freight_scheduled_routes INNER JOIN freight_data_table ON freight_scheduled_routes.route_id = freight_data_table.route_id INNER JOIN freight_accounts ON freight_scheduled_routes.account_no = freight_accounts.account_ID AND freight_scheduled_routes.schedule_id = freight_data_table.schedule_id AND freight_scheduled_routes.account_no = freight_data_table.account_no WHERE freight_scheduled_routes.code_red =1 AND freight_scheduled_routes.route_status like '%completed%' $criteria GROUP BY $_POST[my_group]";
        //echo $ask;
        $result =$db->query($ask);
    }
}else {
    $ask = "SELECT freight_scheduled_routes.*, freight_data_table.inches_to_gallons, freight_data_table.date_of_pickup, freight_data_table.route_id, freight_data_table.schedule_id, freight_data_table.account_no,freight_accounts.account_rep FROM freight_scheduled_routes INNER JOIN  freight_data_table ON freight_scheduled_routes.route_id = freight_data_table.route_id AND freight_scheduled_routes.schedule_id = freight_data_table.schedule_id AND freight_scheduled_routes.account_no = freight_data_table.account_no INNER JOIN freight_accounts ON freight_scheduled_routes.account_no = freight_accounts.account_ID WHERE freight_scheduled_routes.code_red =1 AND freight_scheduled_routes.route_status like '%completed%'";
    //echo $ask;
    $result =$db->query($ask);
      
}
    //where("status","archived)->query("SELECT * FROM ".$dbprefix."_accounts"); 

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

<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;">
<?php 
if($_POST['my_group'] !="-" ){
    switch($_POST['my_group']){
        case "account_rep":
            echo "<th class='cell_label'>Account Rep</th>";
        break;
        case "friendly":
            echo "<th class='cell_label'>Friendly</th>";
        break;
        case "division":
            echo "<th class='cell_label'>Division</th>";
        break;
        
        case "previous_provider":
            echo "<th class='cell_label'>Previous Provider</th>";
        break;
    }
}
if($_POST['my_group'] =="-" || !isset($_POST['my_group'])){
    if(isset($_POST['get_reps'])){echo "<th class=\"cell_label\">Account Rep</td>";}
?>
    <th class="cell_label">#</th>
    <th class="cell_label">ID</th>
    <th class="cell_label">Account</th>
    <th class="cell_label">Address</th>
    <th class="cell_label">City</th>
    <th class="cell_label">Route</th>
    <th class="cell_label">Pickup Status</th>
    <th class="cell_label">Date Reported</th>
    <th class="cell_label">Date Collected</th>
<?php } ?>
    <th class="cell_label">Gals</th>
<?php if($_POST['my_group'] =="-" || !isset($_POST['my_group'])){ ?>
    <th class="cell_label"><span title="Days between report and actual Pickup">Wait Days</span></th>
<?php } ?>
</tr>
</thead>
<tbody>
<?php

if(count($result)>0){
     $count= 0;
     $account = new Account();
     $scheduled = new Scheduled_Routes("");
     foreach($result as $collected){
        $count++;
        echo "<tr>";
        if($_POST['my_group'] =="-" || !isset($_POST['my_group'])){
            if(isset($_POST['get_reps'])){
                echo "<td>".uNumToName($collected['account_rep'])."</td>";
            } 
            echo "<td>$count</td>";
            echo "<td>$collected[schedule_id]</td>";
            echo "<td>".account_NumtoName($collected['account_no'])."</td>";
            echo "<td>". $account->singleField($collected['account_no'],"address")."</td>";
            echo "<td>". $account->singleField($collected['account_no'],"city")."</td>";
            echo "<td>".$collected['route_id']."</td>";
            echo "<td>".$collected['route_status']."</td>";
            $ku = explode(" ",$collected['date_created']);
            
            echo "<td>".$ku[0]."</td>";
        
            echo "<td>$collected[date_of_pickup]</td>";
        } else {
            echo "<td>".uNumToName($collected['account_rep'])."</td>";
        }
        
        echo "<td>$collected[inches_to_gallons]</td>";
        
        if($_POST['my_group'] =="-" || !isset($_POST['my_group'])){
            echo "<td>".date_different($ku[0],$collected['date_of_pickup'])."</td>";
        }
        echo "</tr>";   
     }
}
?>
</tbody>
</table>
