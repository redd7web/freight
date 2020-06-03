<?php
error_reporting(E_WARNING | E_PARSE | E_NOTICE);
$data_table = $dbprefix."_data_table";
$account_table = $dbprefix."_accounts";
$criteria = "";

if(isset($_POST['search_now'])){
   foreach($_POST as $name=>$value){
        switch($name){
            case "from":
                if(strlen($value)>0){
                    $arrFields[] = " freight_data_table.date_of_pickup >= '".$_POST['from']."'";
                }
                break;
            case "to":
                if(strlen($value)>0){
                    $arrFields[] = " freight_data_table.date_of_pickup  <= '".$_POST['to']."'";
                }
                break;
            case "account_rep":
                if(strlen($value)>0){
                    $arrFields[]= " account_rep = $value";
                    //$names = " account_rep = $value";
                } 
                break; 
            case "salesrep":
                if(strlen($value)>0){
                    $arrFields[]= " original_sales_person = $value";
                    //$names .= " original_sales_person = $value";
                }
                break;
            case "friendly":
                if($value !="null"){
                    $arrFields[] = "friendly = '$value'";   
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
        $criteria = " AND ".implode (" AND ",$arrFields);
   }
   
   if($_POST['my_group'] !="-"){
     switch($_POST['my_group']){
        case "account_rep":
            $format = "SELECT freight_users.user_id, freight_data_table.account_no, COUNT( * ) AS count, SUM( freight_data_table.inches_to_gallons ) AS gals, AVG( freight_data_table.inches_to_gallons ) AS avg, freight_accounts.account_ID, freight_accounts.address, freight_accounts.city, freight_accounts.state, freight_accounts.previous_provider, freight_accounts.division,freight_accounts.original_sales_person,freight_accounts.account_rep FROM freight_data_table INNER JOIN freight_accounts ON freight_data_table.account_no = freight_accounts.account_ID INNER JOIN freight_users ON freight_accounts.account_rep = freight_users.user_id  $criteria GROUP BY freight_accounts.account_rep ORDER BY count DESC";
            //echo $format;    
        break;
        case "division":
            $format =  "SELECT freight_data_table.account_no, COUNT( * ) AS count, SUM( freight_data_table.inches_to_gallons ) AS gals, AVG( freight_data_table.inches_to_gallons ) AS avg, freight_accounts.account_ID, freight_accounts.address, freight_accounts.city, freight_accounts.state, freight_accounts.previous_provider, freight_accounts.division,freight_accounts.original_sales_person,freight_accounts.account_rep FROM freight_data_table INNER JOIN freight_accounts ON freight_data_table.account_no = freight_accounts.account_ID INNER JOIN freight_users ON freight_accounts.account_rep = freight_users.user_id  WHERE 1 $criteria  GROUP BY freight_accounts.division ORDER BY count DESC";
        break;
        case "original_sales_person":
            $format =  "SELECT freight_data_table.account_no, COUNT( * ) AS count, SUM( freight_data_table.inches_to_gallons ) AS gals, AVG( freight_data_table.inches_to_gallons ) AS avg, freight_accounts.account_ID, freight_accounts.address, freight_accounts.city, freight_accounts.state, freight_accounts.previous_provider, freight_accounts.division,freight_accounts.original_sales_person,freight_accounts.account_rep FROM freight_data_table INNER JOIN freight_accounts ON freight_data_table.account_no = freight_accounts.account_ID INNER JOIN freight_users ON freight_accounts.account_rep = freight_users.user_id  WHERE 1 $criteria  GROUP BY freight_accounts.original_sales_person ORDER BY count DESC";
        break;
        
     }
   } else {
        $format = "SELECT freight_data_table.account_no, COUNT( * ) AS count, SUM( freight_data_table.inches_to_gallons ) AS gals, AVG( freight_data_table.inches_to_gallons ) AS avg, freight_accounts.account_ID, freight_accounts.address, freight_accounts.city, freight_accounts.state, freight_accounts.previous_provider, freight_accounts.division,freight_accounts.original_sales_person,freight_accounts.account_rep FROM freight_data_table INNER JOIN freight_accounts ON freight_data_table.account_no = freight_accounts.account_ID WHERE 1 $criteria GROUP BY account_no ORDER BY count DESC ";
   }
   
   echo $format."<br>";
   
   $request = $db->query($format);
    
}else {    
    $format = "SELECT freight_data_table.account_no, COUNT( * ) AS count, SUM( freight_data_table.inches_to_gallons ) AS gals, AVG( freight_data_table.inches_to_gallons ) AS avg, freight_accounts.account_ID, freight_accounts.address, freight_accounts.city, freight_accounts.state, freight_accounts.previous_provider, freight_accounts.division FROM freight_data_table INNER JOIN freight_accounts ON freight_data_table.account_no = freight_accounts.account_ID GROUP BY account_no ORDER BY count DESC";
    //echo $format;
    $request = $db->query($format);
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
        
<table  style="width: 100%;margin:auto;margin-top:10px;margin-bottom:10px;"><tr><td style="height: 10px;">
<?php 

$allxx = "SELECT SUM(freight_data_table.inches_to_gallons) as bigbucket, COUNT(freight_data_table.inches_to_gallons) as num ,freight_accounts.account_rep,freight_accounts.account_ID FROM $data_table INNER JOIN freight_accounts ON freight_data_table.account_no = freight_accounts.account_ID WHERE 1 " .$criteria;
//echo "<br/>".$allxx."<br/>";

$all = $db->query($allxx);
?>
</td></tr><tr><td style="text-align: center;">
<?php


if(count($all)>0){
    foreach($all as $answer){
        echo "<span style='font-weight:bold;font-size:24px;'>Total Gallons :".round($answer['bigbucket'],2)."</span><br/><br/>";
        echo "<span style='font-weight:bold;font-size:24px;'>Total Pickups :".$answer['num']."</span>";
    }
}

  
    

?>
</td></tr></table>        
        
<table style="width: 100%;margin:auto;" id="myTable" >
<thead>
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
<?php
if(isset($_POST['search_now'])){
    if($_POST['my_group'] !="-" && isset($_POST['my_group']) ){
         switch($_POST['my_group']){
            case "account_rep":
                echo "<th class='cell_label'>Account Rep</th>";
            break;
            case "facility":
                echo "<th class='cell_label'>Facility</th>";
            break;
            case "original_sales_person":
                echo "<th class='cell_label'>Original Sales Person</th>";
            break;
            default:
            
            break;
         }
   } 
}

if( $_POST['my_group']  == "-" || !isset($_POST['my_group'])){
    
?>
<th class="cell_label">Act ID</th>

<th class="cell_label">Acct Name</th>


<th class="cell_label">Loc Address</th>

<th class="cell_label">City</th>

<th class="cell_label">State</th>

<th class="cell_label">Previous Provider</th>
<?php } ?>

<th class="cell_label">Pickups</th>

<th class="cell_label">Gallons</th>

<th class="cell_label"><span title="Average Gallons per Pickup">GPP</span></th>




<th class="cell_label">Facility</th>

</tr>
</thead>
<tbody>
<?php 
if(count($request)>0){
    foreach($request as $summary){
        
        echo"<tr>";
            if(isset($_POST['search_now'])){
                if($_POST['my_group'] !="-"){
                     switch($_POST['my_group']){
                        case "account_rep":
                            echo "<th class='cell_label'>".uNumToName($summary['account_rep'])."</th>";
                        break;
                        case "facility":
                            echo "<th class='cell_label'>".numberToFacility($summary['division'])."</th>";
                        break;
                        case "original_sales_person":
                            echo "<th class='cell_label'>".uNumToName($summary['original_sales_person'])."</th>";
                        break;
                     }
               } 
            }

            
            if( $_POST['my_group'] =="-"  || !isset($_POST['my_group'] )){
                echo "<td>$summary[account_no]</td>";
                echo "<td>".account_NumToName($summary['account_no'])."</td>";           
                echo "<td>$summary[address]</td>";
                echo "<td>$summary[city]</td>";
                echo "<td>$summary[state]</td>";
                echo "<td>$summary[previous_provider]</td>";
            }
            
            
            echo "<td>$summary[count]</td>";
            echo "<td>".round("$summary[gals]",2)."</td>";
            echo "<td>".round("$summary[avg]",2)."</td>";
            
           
            echo "<td>".numberToFacility("$summary[division]")."</td>";
           
            
           
        echo "</tr>";
    }
}    

?>
</tbody>
</table>
