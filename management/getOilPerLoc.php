<?php
$account_table = $dbprefix."_accounts";
$data_table = $dbprefix."_data_table";
//ini_set('display_errors',1); 
//error_reporting(E_ALL);

function getDates($id){
    global $db;
    global $dbprefix;
    $first_last = $db->query("SELECT (SELECT date_of_pickup FROM freight_data_table WHERE account_no=$id ORDER BY date_of_pickup LIMIT 1) as 'first', (SELECT date_of_pickup FROM freight_data_table WHERE account_no=$id ORDER BY date_of_pickup DESC LIMIT 1) as 'last'");
    return $first_last;
}


if(isset($_POST['search_now'])){
     foreach($_POST as $name=>$value){
        switch($name){
            case "from":
                if(strlen($value)>0){
                    $arrField[] = " date_of_pickup >= '".$value."'";
                }
                break;
            case "to":
                if(strlen($value)>0){
                    $arrField[] = " date_of_pickup <= '".$value."'";
                }
                break;    
            case "account_name":
                if(strlen($value)>0){
                    $value = str_replace(" ","%",$value);
                    $arrField[] = " name like '%".$value."%'";
                }        
                break;
            case "friendly":
                if(strlen($value)>0 && $value !="-"){
                    $value = str_replace(" ","%",$value);
                    $arrField[] = " previous_provider like '%".$value."'%";
                }
                break;
            case "account_id":
                if(strlen($value)>0){
                    $arrField[] = "account_ID = ". $value;
                }
                break;
            case "state":
                if(strlen($value)>0){
                    $arrField[] = " state = '$value'";
                }     
                break;
        }
    }
    
    if(!empty($arrField)){
        $criteria1 = " AND ( ". implode (" AND ",$arrField)." )";             
    }
    
    $query = "SELECT freight_accounts.account_ID, SUM( inches_to_gallons ) AS total_gallons, SUM( inches_to_gallons - (inches_to_gallons * .25) ) AS adj, freight_accounts.status, freight_accounts.friendly, COUNT( inches_to_gallons ) AS pickups, freight_accounts.address, freight_accounts.city, freight_accounts.state FROM freight_accounts INNER JOIN freight_data_table ON  freight_accounts.account_ID = freight_data_table.account_no WHERE 1 $criteria1 GROUP BY freight_accounts.account_ID";
    $result = $db->query($query);    
    
}else {
    $query = "SELECT freight_accounts.account_ID, SUM( inches_to_gallons ) AS total_gallons, SUM( inches_to_gallons - (inches_to_gallons * .25) ) AS adj, freight_accounts.status, freight_accounts.friendly, COUNT( inches_to_gallons ) AS pickups, freight_accounts.address, freight_accounts.city, freight_accounts.state FROM freight_accounts INNER JOIN freight_data_table ON freight_accounts.account_ID = freight_data_table.account_no GROUP BY freight_accounts.account_ID";    
    //$result = $db->query($query);
}
//echo $query;
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

.head_green{
    color:green;
}
</style>
<script>

$(document).ready(function(){
   $('#myTable').dataTable({
        "lengthMenu": [ [10, 25, 50,100,150, -1], [10, 25, 50,100,150, "All"] ]
   }); 
});
</script>   

<table style="width: 100%;margin:auto;">
<tr><td colspan="2"><form action="export_loc_oil.php" method="post" target="_blank">Export Format: </td><td colspan="2"><select name="format"><option value="csv">CSV</option><option value="excel">Excel</option></select></td><td style="text-align: right;"><input type="text" name="param" value="
<?php if(isset($_POST['search_now'])){
    echo $criteria1;
} ?>
"/><input type="submit" value="Export Now" name="export"/></form></td></tr>


<tr><th colspan="4"></th><th class="head_blue" colspan="2">Gallons</th>
<th class="head_green" colspan="3">Pickups</th><th colspan="3"></th></tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>

</table>

<table style="width: 100%;margin:auto;" id="myTable" >
<thead>

<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
<th class="cell_label">ID</th>

<th class="cell_label">Status</th>

<th class="cell_label">Affiliate</th>

<th class="cell_label">Name</th>

<th class="cell_label"><span title="Total Raw (Wet) Gallons">Raw</span></th>

<th class="cell_label"><span title="Total Adjusted (Dry) Gallons">Adj</span></th>

<th class="cell_label"><span title="Number of pickups in this period">Num</span></th>

<th class="cell_label"><span title="Date of first pickup in this period">First</span></th>

<th class="cell_label"><span title="Date of last pickup in this period">Last</span></th>

<th class="cell_label">Address</th>

<th class="cell_label">City</th>

<th class="cell_label">State</th>
</tr></thead>
<tbody>
<?php
if(count($result)>0){
    foreach($result as $answer){
        $dates = getDates($answer['account_ID']);
        echo "<tr>";
        echo "<td>$answer[account_ID]</td>";
        echo "<td>$answer[status]</td>";
        echo "<td>$answer[friendly]</td>";
        echo "<td>".account_NumToName($answer['account_ID'])."</td>";
        echo "<td>".round("$answer[total_gallons]",2)."</td>";
        echo "<td>".round($answer['adj'],2)."</td>";
        echo "<td>$answer[pickups]</td>";
        echo "<td>".$dates[0]['first']."</td>";
        echo "<td>".$dates[0]['last']."</td>";
        echo "<td>$answer[address]</td>";
        echo "<td>$answer[city]</td>";
        echo "<td>$answer[state]</td>";
        echo"</tr>";
    }
}

?>
</tbody>
</table>