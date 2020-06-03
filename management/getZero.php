<?php
    $data_table = $dbprefix."_data_table";
    $criteria="";
    if(isset($_POST['search_now'])){
        foreach($_POST as $name=>$value){
            switch($name){
                case "reason_for_skip_id":
                    if(strlen($value)>0){
                        $arrField[]= " zero_gallon_reason  = $value ";
                    }
                    break;
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
            }
        }
        if(!empty($arrField)){
            $criteria = implode(" AND ", $arrField);
        }
        $string = "SELECT DISTINCT account_no,date_of_pickup,zero_gallon_reason,fieldreport FROM $data_table WHERE inches_to_gallons=0  AND". $criteria;
        //echo $string;
        $result = $db->query($string);
    } else {
        //$result =$db->query("SELECT distinct account_no,date_of_pickup,zero_gallon_reason,fieldreport FROM $data_table where inches_to_gallons=0");    
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
    <th class="cell_label">#</th>

    <th class="cell_label" style="width:400px;">
        <span title="Date of Pickup">Date</span>
    </th>
    <th class="cell_label">Name</th>

    <th class="cell_label">Reason For Zero</th>

    <th class="cell_label">Field Report</th>

    <th class="cell_label"><span title="Number of other pickups with zero gallons at this same location within the chosen time interval">Other Zeroes</span></th>


</tr>
</thead>
<tbody><?php

 if(count($result)>0){
 foreach($result as $zero){
    $data = new Dtable();
    echo "<tr>";
    echo "<td>$zero[account_no]</td>";
    echo "<td>$zero[date_of_pickup]</td>";
    echo "<td>".$data->getAccount_Name($zero['account_no'])."</td>";
    echo "<td>"; 
    field_report_decode($zero['zero_gallon_reason']);
    echo "</td>";
    echo "<td>$zero[fieldreport]</td>";
    echo "<td>"; 
    $other = $db->query("SELECT count( account_no ) AS other_zeroes FROM $data_table WHERE account_no =$zero[account_no] AND inches_to_gallons =0");
    if(count($other)>0){
        echo $other[0]['other_zeroes'];
    } else {
        echo "0";
    }
    
    echo"</td>";
    
    echo "</tr>";
 }

}
?>
</tbody>
</table>


