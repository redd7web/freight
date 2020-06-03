<?php

$account_table = $dbprefix."_accounts";

$oil_manifest = $dbprefix."_ikg_manifest_info";
//$exoirts = $db->get($dbprefix."_accounts");
$query = "SELECT DISTINCT $account_table.account_ID ,$account_table.status,$oil_manifest.account_numbers FROM $account_table INNER JOIN $oil_manifest ON $oil_manifest.account_numbers like  '%$account_table.account_ID%' WHERE $account_table.status='active'";
$exoirts = $db->query($query);
echo $query;
var_dump($exoirts);
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
            <td class="cell_label">#</td>
            <td class="cell_label">Location</td>
            <td class="cell_label">Date Pickup Scheduled</td>
            <td class="cell_label">Date of Route</td>
            <td class="cell_label">Route ID</td>
            <td class="cell_label">Route Name</td>
            <td class="cell_label">City</td>
            <td class="cell_label">State</td>
            <td class="cell_label">Fire</td>
            <td class="cell_label">fire type</td>
        </tr>
    </thead>
    <tbody>
    <?php
        if(count($exoirts)>0){
            $counter =1;
            foreach($exoirts as $xport){
                echo "<td>$counter</td>";
                echo "<td>".account_NumtoName($xport['account_ID'])."</td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<tr/>";
                $counter++;
            }
        }
    ?>
    </tbody>
</table>