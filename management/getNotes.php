<?php

$criteria = "";
if(isset($_POST['search_now'])){
    foreach($_POST as $name=>$value){
        switch($name){
            case "facility":
               if($value !="ignore"){
                    if ($value == 99){
                        $running[] = "facility_restriction in(24,30,31,32,33)";
                    }
                    else{
                        $running[] = "facility_restriction = ".$value;
                    }    
                 }
            break;
            case "from":
                if(strlen($value)>0){
                    $running[] = " date >= '".$value."'";
                }
                break;
            case "to":
                if(strlen($value)>0){
                    $running[] = " date <= '".$value."'";
                }
                break; 
            case "account":
                if(isset($_POST['account']) && $value >0 ){
                    $running[] = "account_no = $value";
                    $static[] = "account_ID = $value";
                }
                break;   
            case "run_key":
                if(strlen($value)>0 && isset($_POST['run_key'])){
                    $value = str_replace(" ","%",$value);
                    $running[] = "note like '%$value%'";
                    $static[] = "notes like '%$value%'";
                }
                break;
           
            case "author":
                if(strlen($value)>0 && isset($_POST['author'])){
                    $running[] = "freight_account_notes.author = $value";    
                }
                break;
        }
    }
    
    if(!empty($running)){
        $running_crit = " AND ".implode(" AND ", $running);
    }
    
    if(!empty($static)){
        $static_crit = " AND ".implode(" AND ", $static);
     }
    
    $query = "SELECT freight_account_notes.*,freight_accounts.division FROM freight_account_notes INNER JOIN freight_accounts ON freight_account_notes.account_no = freight_accounts.account_ID $running_crit

UNION SELECT NULL as id,account_ID as account_no, notes as note,null as date,null as author,division FROM freight_accounts WHERE 1 $static_crit";
    echo $query."<br/>";
    $iop = $db->query($query);    
}else {
    $iop = $db->query("SELECT freight_account_notes.*,freight_accounts.division FROM freight_account_notes LEFT JOIN freight_accounts ON freight_account_notes.account_no = freight_accounts.account_ID 

UNION SELECT NULL as id,account_ID as account_no, notes as note,null as date,null as author,division FROM freight_accounts WHERE notes IS NOT NULL AND length(notes)>0");    
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


<th class="cell_label">ID</th>
<th class="cell_label">Date</th>
<th class="cell_label">Running / Static Note</th>
<th class="cell_label">Made By</th>
<th class="cell_label">Account</th>
<th class="cell_label">Facility</th>
</tr>
</thead>
<tbody>
<?php

if(count($iop) >0){
    foreach($iop as $t){
        echo "<tr>
            <td>$t[account_no]</td>
            <td>$t[date]</td>
            <td>$t[note]"; 
            if($t['author'] == NULL){
                echo " <b>(static)<b/>";
            }
            echo "</td>
            <td>".uNumToName($t['author'])."</td>
            <td>".account_NumtoName($t['account_no'])."</td>            
            <td>".numberToFacility($t['division']) ."</td>
        </tr>";
    }
}

?>
</tbody>
</table>