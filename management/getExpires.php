<?php
    $account_table = $dbprefix."_accounts";
    $string = "";
    $criteria = "";
    if(isset($_POST['search_now'])){
        foreach($_POST as $name=>$value){
            switch($name){
                case "friend":
                    if($value == 1){
                        $arrFields[] = " friendly IS NULL ";
                    } else if ($value ==2){
                        $arrFields[] = " (friendly IS NOT NULL OR friendly !='' OR friendly !=' ') ";
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
                case "account_status":
                   if(strlen($value)>0){
                        $arrFields[] = " status='$value' ";
                   } else {
                        $arrFields[] = " status='Archive' ";
                   }
                    break;
                case "salesrep":
                    if(strlen($value)>0 || $value !=''){
                        $arrFields[] = " account_rep = $value";
                    }
                    break;
                case "from":
                    if(strlen($value)>0 || $value !=''){
                        $arrFields[] = " state_date >= '$_POST[from]'";   
                    }
                break;
                case "to":
                    if(strlen($value)>0 || $value !=''){
                        $arrFields[] = " expires <= '$_POST[to]'";
                    }
                break;
                
                case "friendly":
                    if($value !="null"){
                        $arrFields[] = " friendly = $value";
                    }
                break;                
            }
        }
        
        if(!empty($arrFields)){
            $string = " AND ".implode(" AND ",$arrFields);
        }
        
        $format = "SELECT account_ID,address,city,state,expires,created,status,account_rep,original_sales_person,friendly,new_bos,TIMESTAMPDIFF(day,created, expires ) as duration,state_date FROM $account_table WHERE 1 $string";
        echo $format;
        $request = $db->query($format);
    } else {
        $request =$db->query("SELECT account_ID,address,city,state,expires,created,status,account_rep,original_sales_person,friendly,new_bos,TIMESTAMPDIFF(day,created, expires ) as duration,state_date FROM $account_table WHERE status='Archive'");    
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
<th class="underline_cell">#</th>

<th class="cell_label">New Bos</th>

<th class="cell_label">Company</th>

<th class="cell_label">Address</th>

<th class="cell_label">City</th>

<th class="cell_label">State</th>
<th class="cell_label">Created</th>
<th class="cell_label">Start Date</th>
<th class="cell_label">Expires</th>



<th class="cell_label">Period (days)</th>
<?php
    
    if(isset($_POST['get_sales_reps'])){
        echo '<th class="cell_label">Original Sales Rep</th>';
    }
    
    if(isset($_POST['get_acct_reps'])){
        echo '<th class="cell_label">Account Rep</th>';
    }
    
    if(isset($_POST['get_affs'])){
        echo '<th class="cell_label">Friendly</th>';
    }
?>
</tr>
</thead>
<tbody>
<?php
$account  = new Account();
if(count($request)>0){
    foreach($request as $acnt_expire){
        echo"<tr>";
        echo"<td>$acnt_expire[account_ID]</td>";
        echo "<td>$acnt_expire[new_bos]</td>";
        echo "<td>".account_NumtoName($acnt_expire['account_ID'])."</td>";
        echo "<td>$acnt_expire[address]</td>";
        echo "<td>$acnt_expire[city]</td>";
        echo "<td>$acnt_expire[state]</td>";
         echo "<td>$acnt_expire[state_date]</td>";
         echo "<td>$acnt_expire[created]</td>";
        echo "<td>$acnt_expire[expires]</td>";       
        echo "<td>$acnt_expire[duration]</td>";
        
        
        if(isset($_POST['get_sales_reps'])){
            echo '<th class="cell_label">'.uNumToName($acnt_expire['original_sales_person']).'</th>';
        }
        
        if(isset($_POST['get_acct_reps'])){
            echo '<th class="cell_label">'.uNumToName($acnt_expire['account_rep']).'</th>';
        }
        
        if(isset($_POST['get_affs'])){
            echo '<th class="cell_label">'.$acnt_expire['friendly'].'</th>';
        }
        
    echo"</tr>";
    }
}


?>
</tbody>
</table>

