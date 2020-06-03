<?php
    ini_set("display_errors",1);
    $account_table = $dbprefix."_accounts";
    
    if(isset($_POST['search_now'])){
        foreach($_POST as $name=>$value){
            switch($name){
               case "friend":
                    if($value == 1){
                        $arrFields[] = " friendly IS NULL ";
                    } else if ($value ==2){
                        $arrFields[] = " (friendly IS NOT NULL  OR friendly !='' OR friendly !=' ')";
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
                case "from":
                    if(strlen($value)>0 || $value !=''){
                        $arrFields[] = " date_of_pickup >= '$_POST[from]'";   
                    }
                break;
                case "to":
                    if(strlen($value)>0 || $value !=''){
                        $arrFields[] = " date_of_pickup <= '$_POST[to]'";
                    }
                break;
                    
            }
        }
        
        if(!empty($arrFields)){
            $criteria = " AND ".implode(" AND ",$arrFields);
        }
         
        $format = "SELECT freight_accounts.notes, freight_accounts.expires, freight_accounts.city, freight_accounts.status, freight_accounts.account_ID, COALESCE( sum( inches_to_gallons ) , NULL , 0 ) AS gal, count( * ) AS pickups, COALESCE( AVG( inches_to_gallons ) , NULL , 0.00 ) AS gpp
FROM freight_accounts
LEFT JOIN freight_grease_data_table ON freight_accounts.account_ID = freight_grease_data_table.account_no
WHERE STATUS = 'Archive' $criteria GROUP BY freight_accounts.account_ID";
        
        //echo $format."<br/>";
        $result = $db->query($format);
    }
    else {
       
        
        $result =$db->query("SELECT freight_accounts.notes, freight_accounts.expires, freight_accounts.city, freight_accounts.status, freight_accounts.account_ID, COALESCE( sum( inches_to_gallons ),NULL,0) AS gal, count( * ) AS pickups, COALESCE(AVG( inches_to_gallons ) ,NULL,0.00) AS gpp FROM freight_accounts LEFT JOIN freight_grease_data_table ON freight_accounts.account_ID = freight_grease_data_table.account_no WHERE status = 'Archive' GROUP BY freight_accounts.account_ID");
        
        //where("status","archived)->query("SELECT * FROM ".$dbprefix."_accounts");    
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

<th class="cell_label">Status</th>

<th class="cell_label">Name</th>

<th class="cell_label">City</th>

<th class="cell_label">Date Closed</th>

<th class="cell_label">Gallons</th>

<th class="cell_label">Pickups</th>

<th class="cell_label"><span title="AVG Gallons per pickup">GPP</span></th>


<th class="cell_label">Note</th>


</tr>
</thead>


<tbody><?php
    foreach($result as $ended){
            $account = new Account();
            echo "<tr><td>$ended[account_ID]</td>
                <td>$ended[status]</td>
                <td>".$account->formatted_name($ended['account_ID'])."</td>
                <td>$ended[city]</td>
                <td>$ended[expires]</td>
                <td>".round($ended['gal'])."</td>
                <td>$ended[pickups]</td>
                <td>".round($ended['gpp'])."</td>
                <td>$ended[notes]</td>
            </tr>";
    }

?></tbody>
</table>
