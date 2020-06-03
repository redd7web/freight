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
<table style="width: 100%;margin:auto;margin-top:10px;"  id="myTable" >

<tr><td colspan="15" style="text-align: left;"><a href="management/addFriend.php" rel="shadowbox;width=400px;height=310;"><img src="img/add_item.big.gif" />&nbsp;<span style="font-size: 12px;">Add Friendly</a></td></tr>
</table>

<table style="width: 100%;" id="myTable">
<thead>
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">

<th class="cell_label">ID</th>

<th class="cell_label">Friendly Name</th>

<th class="cell_label">Description</td></th>


</thead>
<tbody>
<?php

$fie = $db->get("freight_friendly","*");

if( count($fie) >0  ){
    foreach($fie as $friendly){
       echo  "<tr><td>$friendly[comp_id]</td><td>$friendly[comp_name]</td><td>$friendly[comp_rep_fname] $friendly[com_rep_lname] $friendly[com_rep] $friendly[comp_rep_email]</td></tr>";        
    }
}
?>
</tbody>
</table>
<script>


</script>