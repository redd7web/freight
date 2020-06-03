<?php
$account_table = $dbprefix."_accounts";
$addition = "";
if(isset($_POST['search_now'])  ){
     foreach($_POST as $name=>$value){
        switch($name){
            case "facility":
                 if($value !="ignore"){
                    if ($value == 99){
                        $arrFields[] = "division in(24,30,31,32,33)";
                    }
                    else{
                        $arrFields[] = "division = ".$value;
                    }    
                 } 
            break;
            case "from":
                if(strlen($value)>0 && isset($value)){
                    $arrFields[] = " state_date >='$value'";   
                }
            break;
            case "to":
                if(strlen($value)>0 && isset($value)){
                    $arrFields[] = " expires  <='$value'";
                }
            break; 
            case "salesrep":
                if(strlen($value)>0){
                    $arrFields[] = " original_sales_person = $value ";
                }
                break;           
        }  
     }   
     
     if(!empty($arrFields)){
        $addition = " AND ".implode(" AND ",$arrFields);
     }
     $format = "SELECT * FROM $account_table WHERE status='new' ".$addition;
     //echo $format."<br/>";
     $new = $db->query($format);
} else  {
    $new = $db->query("SELECT account_ID,city,address,state,original_sales_person,created,state_date,previous_provider,estimated_volume FROM $account_table WHERE status='new'");
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

<table style="width: 100%;margin:auto;" id="myTable" >

<thead>

<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">

<th class="cell_label">ID</th>

<th class="cell_label">Acct Name</th>



<th class="cell_label">Loc Address</th>

<th class="cell_label">City</th>

<th class="cell_label">State</th>

<th class="cell_label">Sales Rep</th>

<th class="cell_label">Created</th>

<th class="cell_label"><span title="Date Contract Signed">Starts</span></th>
<th class="cell_label">Previous Provider</th>

<th class="cell_label"><span title="Estimated Gallons Per Month">GPM</span></th>
<?php
if( isset($_POST['get_reps']) ){ echo "<th class='cell_label'>Account Rep</th>";}
?>

</tr>
</thead>
<tbody>
<?php
if(count($new)>0){
    $alter = 0;
    foreach($new as $newloc ){
        $account = new Account();
       
        
        echo "<tr>";
        echo "<td>$newloc[account_ID]</td>";
        echo "<td>".account_NumToName($newloc['account_ID'])."</td>";        
        echo "<td>".$newloc['address']."</td>";
        echo "<td>".$newloc['city']."</td>";
        echo "<td>".$newloc['state']."</td>";
        echo "<td>".$newloc['original_sales_person']."</td>";
        echo "<td>".$newloc['created']."</td>";
        echo "<td>".$newloc['state_date']."</td>";
        echo "<td>".$newloc['previous_provider']."</td>";
        echo "<td>".$newloc['estimated_volume']."</td>";
        if( isset($_POST['get_reps']) ){ echo "<td>".uNumToName($newloc['account_rep'])."</td>";}
        echo "</tr>";
    }
}
?>
</tbody>
</table>
