<?php
$account_table = $dbprefix."_accounts";
$data_table = $dbprefix."_data_table";



if(isset($_POST['search_now']) ){
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
                if(strlen($value)>0 && $value !="-" && $value !="null"){
                    $value = str_replace(" ","%",$value);
                    $arrField[] = " friendly like '%".$value."%'";
                }
                break;
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
            case "account_id":
                if(strlen($value)>0){
                    $arrField[] = " account_ID=".$value;
                }
                break;
            case "state":
                if(strlen($value)>0){
                    $arrField[] = " state='".$value."'";
                }
                break; 
        }
    }
    
  
    
    if(!empty($arrField)){
        $criteria1 = " AND ( ". implode (" AND ",$arrField)." )";             
    }
    
    //var_dump($arrField);
    
    
    
    
    $query = "SELECT $account_table.account_ID,$account_table.city,$account_table.state,$account_table.division,$data_table.inches_to_gallons,$data_table.entry_number,$data_table.date_of_pickup, $data_table.inches_to_gallons - ($data_table.inches_to_gallons *.25) as adj FROM $data_table INNER JOIN $account_table ON $account_table.account_ID=$data_table.account_no WHERE 1 " . $criteria1 . " order by date_of_pickup";
    echo $query;
    $result = $db->query($query);    
} /*else {
    $query ="SELECT $account_table.account_ID,$account_table.city,$account_table.state,$account_table.division,$data_table.inches_to_gallons,$data_table.entry_number,$data_table.date_of_pickup FROM $data_table INNER JOIN $account_table ON $account_table.account_ID=$data_table.account_no WHERE 1 " . $criteria1 . " order by date_of_pickup";
    $result = $db->query($query);
} */

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


<table style="width: 100%;margin:auto;">
  <td>Export Format &nbsp;&nbsp;
    
    
    <form target="_blank" action="exportForm.php" method="post" target="_blank">
        <select name="format"><option value="excel">Excel</option><option value="csv">CSV</option></select>
    <input type="submit" value="Export Now!" name="export"/>
    <input type="text" name="params" value="<?php if( isset($_POST['search_now']) ){ echo $criteria1;  }   ?>"/>
    </form></td>
        <tr><td colspan="9" style="text-align: center;padding:5px 5px 5px 5px;"></td></tr>

</table>
<table style="width: 100%;margin:auto;" id="myTable">
<thead>
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
<th class="cell_label">#</th>

<th class="cell_label">ID</th>


<th class="cell_label">Account Name</th>
<th class="cell_label">Date</th>

<th class="cell_label"><span title="Total Raw (Wet) Gallons">Raw</span></th>

<th class="cell_label"><span title="Total Adjusted (Dry) Gallons">Adj</span></th>

<th class="cell_label">City</th>

<th class="cell_label">State</th>


</tr>
</thead>
<tbody>
<?php

if(isset($_POST['search_now']) || isset($_GET['searched'])  ){
 if(count($result)>0){
    $count =0;
    foreach($result as $pickups){
        $count++;
        echo "<tr>";
            echo "<td>$count</td>";
            echo "<td>$pickups[entry_number]</td>";//pickup number
            echo "<td>".account_NumtoName($pickups['account_ID'])."</td>";
            echo "<td>$pickups[date_of_pickup]</td>";//date of pickup
            echo "<td>$pickups[inches_to_gallons]</td>";//raw
            echo "<td>".round($pickups['adj'],2)."</td>";//adj
            echo "<td>$pickups[city]";
            echo "<td>$pickups[state]</td>";
        echo "</tr>";
    }
 }
}
?>
</tbody>
</table>
<table style="width: 100%;margin:auto;">
  <td>Export Format &nbsp;&nbsp;
    
    
    <form target="_blank" action="exportForm.php" method="post">
        <select name="format"><option value="excel">Excel</option><option value="csv">CSV</option></select>
    <input type="submit" value="Export Now!" name="export"/>
    <input type="text" name="params" value="<?php if( isset($_POST['search_now']) ){ echo $criteria1;  }   ?>"/>
    </form></td>
        <tr><td colspan="9" style="text-align: center;padding:5px 5px 5px 5px;"></td></tr>

</table>