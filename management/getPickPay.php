<?php
ini_set("display_errors",1);
$account_table = $dbprefix."_accounts";
$data_table = $dbprefix."_data_table";
$grease_trap_table = $dbprefix."grease_traps";
$criteria = "";
$paidx = "";
$group_var ="";
$picknpay= array();
$end = "";
$second_range="";
$amnt="freight_grease_data_table.inches_to_gallons  AS gals,";
$adj="(freight_grease_data_table.inches_to_gallons - (freight_grease_data_table.inches_to_gallons * freight_accounts.miu) ) as adj,";
$paiderx="freight_grease_data_table.paid,";
function get_index(){
    global $db;
    $xo =  $db->query("SELECT date,percentage FROM freight_jacobsen ORDER BY DATE DESC LIMIT 0,1 ");
    
    if(count($xo)>0){
        return $xo;
    } else {
        return 0;
    }
}

function pickups($account_no,$range = null){
    global $db;
    global $dbprefix;
    $infox = "0000-00-00|0000-00-00";
    $data_table = $dbprefix."_data_table";
    
    $info = $db->query("SELECT completed_date FROM $grease_trap_table WHERE account_no = $account_no $range ORDER BY completed_date DESC");
    if(count($info)>0){
        //latest|first
        $infox = $info[0]['completed_date']."|".$info[count($info)-1]['completed_date'];
    }
    
    return $infox;
}


if(isset($_POST['search_now'])){
       foreach($_POST as $name=>$value){
        switch($name){
            case "facility":
                if($value !="ignore"){
                    if ( $value == 99){
                        $arrFields[] = " division IN(24,31,30,32,33)";
                    }else{
                        $arrFields[] = " division = $value";
                    }
                }  
            break;
            case "to":
                if(isset($value) && strlen($value) >0){
                    $arrFields[] = "completed_date <='$value'";
                    $pickdate[] = "completed_date <='$value'";
                }
            break;
            case "from":
                if(isset($value) && strlen($value) >0){
                    $arrFields[] = "completed_date >= '$value'";
                    $pickdate[] = "completed_date >='$value'";
                }
            break;
            case "payment_threshold":
                if(isset($_POST['checks_mode'])){
                    $arrFields[] = " freight_grease_data_table.paid  > $value ";
                }
            break;
            
        }
        if( isset($_POST['archive'])  ){
            $arrFields[] = "freight_accounts.status = 'archive'"; 
        } else {
            $arrFields[] = "freight_accounts.status in('New','Active')";    
        }

    }
    
    if(!empty($pickdate)){
        $second_range = " AND ".implode(" AND ", $pickdate);
    }
    if(!empty($arrFields)){
        $criteria = " AND ".implode(" AND ",$arrFields);
    }
    
    
    
    if($_POST['my_group'] =="-"){ // default search
        $ask = "SELECT distinct(freight_grease_traps.completed_date),
            freight_accounts.contact_name, 
            freight_accounts.status,
            freight_accounts.account_ID, 
            freight_accounts.address,
            freight_accounts.city,
            freight_accounts.state,
            freight_accounts.billing_address,
            freight_accounts.division,
            freight_accounts.name,  
            freight_accounts.account_rep,
            freight_accounts.original_sales_person,
            freight_accounts.new_bos,
            freight_grease_data_table.account_no,  
             $amnt
             $adj
            freight_grease_data_table.payment_method,
            freight_grease_data_table.index_at_pickup,
            (freight_grease_data_table.inches_to_gallons - (freight_grease_data_table.inches_to_gallons * freight_accounts.miu) ) as adj, 
            freight_grease_traps.completed_date,
            $paiderx
            freight_grease_data_table.temp_miu,
            freight_grease_data_table.ppg,
            freight_grease_data_table.rate
            FROM freight_grease_data_table LEFT JOIN freight_accounts ON freight_accounts.account_ID = freight_grease_data_table.account_no WHERE                      freight_grease_data_table.route_id IN(SELECT route_id FROM freight_list_of_routes WHERE status = 'completed')  $criteria $end";
    } else {// group by accounts
        $ask = "SELECT DISTINCT freight_grease_data_table.account_no , freight_grease_data_table.payment_method,
freight_grease_data_table.index_at_pickup,
(freight_grease_data_table.inches_to_gallons - (freight_grease_data_table.inches_to_gallons * freight_accounts.miu) ) as adj, 
freight_grease_traps.completed_date,
freight_grease_data_table.temp_miu,
freight_grease_data_table.ppg,
freight_grease_data_table.rate,
inches_to_gallons(freight_grease_data_table.inches_to_gallons) AS gals,
inches_to_gallons(freight_grease_data_table.paid) as paid,
freight_grease_data_table.payment_method,
COUNT( DISTINCT (freight_grease_traps.completed_date) ) as num_pickups,
freight_accounts.contact_name, 
freight_accounts.status,
freight_accounts.account_ID, 
freight_accounts.address,
freight_accounts.city,
freight_accounts.state,
freight_accounts.billing_address,
freight_accounts.division,
freight_accounts.name,  
freight_accounts.account_rep,
freight_accounts.new_bos,
freight_accounts.original_sales_person FROM freight_grease_data_table LEFT JOIN freight_accounts ON freight_accounts.account_ID = freight_grease_data_table.account_no WHERE freight_grease_data_table.route_id IN(SELECT route_id FROM freight_list_of_routes WHERE status = 'completed') $criteria  GROUP BY freight_grease_data_table.account_no";   
    }
    
    
    

    
    
    echo $ask;
    $picknpay = $db->query($ask);
} else {
    /*$ask = "SELECT 
freight_accounts.contact_name, 
freight_accounts.status,
freight_accounts.account_ID, 
freight_accounts.address,
freight_accounts.city,
freight_accounts.state,
freight_accounts.billing_address,
freight_accounts.division,
freight_accounts.name,  
freight_grease_data_table.account_no,  
$amnt 
freight_grease_data_table.payment_method,
freight_grease_data_table.index_at_pickup,
$adj 
freight_grease_traps.completed_date,
$paiderx
freight_grease_data_table.temp_miu,
freight_grease_data_table.ppg,
freight_grease_data_table.rate
FROM freight_grease_data_table LEFT JOIN freight_accounts ON freight_accounts.account_ID = freight_grease_data_table.account_no WHERE freight_accounts.status IN('active','new') AND freight_grease_data_table.route_id IN(SELECT route_id FROM freight_list_of_routes WHERE status = 'completed')";*/

    //$picknpay = $db->query($ask);    
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

<table style="width: 100%;margin:auto;"><tr><td style="text-align: right;">Export Format</td><td><form action="management/export_pick.php" method="post" target="_blank"><select name="format"><option value="csv">CSV</option></select><input type="hidden" value="<?php echo $ask; ?>" name="param"/></td><td><input type="text" id="group" placeholder="group" name="group" value="<?php 
if(isset($_POST['search_now'])){
    echo $_POST['my_group'];
}
?>" readonly=""/><input type="submit" name="export" value="Export Now"/></form></td></tr>
<tr><td colspan="3" style="text-align: center;color:red;">Please search your criteria first then click export.</td></tr></table>

<table style="width: 100%;margin:auto;" id="myTable" >
<thead>
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size: contain;">
    <th class="cell_label">ID</th>    
    <th class="cell_label">Status</th>
    <th class="cell_label">Payor</th>
    <th class="cell_label">Name</th>
    <th class="cell_label"><span title="Net Payable">Payable</span></th>
    <th class="cell_label">PPG</th>
    <th class="cell_label"><span title="Total Raw Gallons">Raw Gallons</span></th>
    <th class="cell_label">MIU</th>
    <th class="cell_label"><span title="Total Adjusted Gallons">Adj Gallons</span></th>
    <th class="cell_label"><span title="Total Payments already made for oil collected in this period.">Paid</span></th>
    <?php
        if(isset($_POST['my_group'])){
            if($_POST['my_group'] !="-"){
                echo '<th class="cell_label">Latest Date</th>';
                echo '<th class="cell_label">First Date</th>';
            }else {
                echo '<th class="cell_label">Completion Date</th>';
            }
        }else {
            echo '<th class="cell_label">Completion Date</th>';        
        }
    ?>    
    
    
    <th class="cell_label">Payment Address</th>
    <th class="cell_label">Payment City</th>
    <th class="cell_label">Payment State</th>
    <th class="cell_label">Payment Type</th>
    <th  class="cell_label">Rate</th>
    <th  class="cell_label">Facility</th>
    <th  class="cell_label">Index at Pickup</th>
    <th  class="cell_label">Index Date</th>
    <?php
    if(isset($_POST['my_group'])){
        if($_POST['my_group'] !="-"){
            echo "<th class='cell_label'>Number of pickups</th>";
        }
    }
?>
    
</tr>
</thead>
<tbody>
<?php
$occurred_once = array();
$ko =get_index();
if(count($picknpay)>0){    
    $limit = 1;
    foreach($picknpay as $pick){
        $count = 0;
        $dates  = explode("|", pickups($pick['account_ID'],$second_range));//0 - latest , 1 - first
        echo "<tr>";
            echo "<td>$pick[account_ID]</td>";//account id
            echo "<td>$pick[status]</td>";//pickup status
            echo "<td>Biotane</td>";//Payor
            echo "<td>".account_NumtoName($pick['account_ID'])."</td>";//account name
            echo "<td>$pick[contact_name]</td>";//payable
            echo "<td>$pick[ppg]</td>";//ppg        
            echo "<td>".number_format($pick['gals'],2)."</td>";//Raw Gallons    
            echo "<td>% ".number_format($pick['temp_miu']*100)."</td>";//miu        
            echo "<td>".number_format(   ( $pick['gals'] - ( $pick['gals'] * $pick['temp_miu'] ) )  ,2)."</td>";//Adj Gallons
            echo "<td>$pick[paid]</td>";//Paid
            if(isset($_POST['my_group'])){
                if($_POST['my_group'] !="-"){
                    echo "<td>$dates[0]</td>";
                    echo "<td>$dates[1]</td>";
                }else{
                    echo "<td>$pick[completed_date]</td>";//first pickup
                }
            }else {
                echo "<td>$pick[completed_date]</td>";//first pickup
            }
            echo "<td>$pick[address]</td>";//payment Address
            echo "<td>$pick[city]</td>";
            echo "<td>$pick[state]</td>";
            echo "<td>$pick[payment_method]</td>";
            echo "<td>$pick[rate]</td>";//Jacobsen
            echo "<td>".numberToFacility($pick['division'])."</td>";//Jacobsen
            echo "<td>".number_format($pick['index_at_pickup'],2)."</td>";
            echo "<td>".$ko[0]['date']."</td>";
            if(isset($_POST['my_group'])){
                if($_POST['my_group'] !="-"){
                    echo "<td>";
                    echo $pick['num_pickups'];                
                    echo "</td>";
                }
            }
        echo "</tr>";
    }
}


?>
</tbody>
</table>
