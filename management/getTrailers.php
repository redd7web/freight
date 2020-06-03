<?php


$vcle = $db->query("SELECT * FROM assets.trailers WHERE is_grease =1 and enabled=1 AND sold =0");
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
<tr><td colspan="15" style="text-align: left;">

<a href="viewVehicles.php"  target="_blank"><img src="img/add_item.big.gif" />&nbsp;<span style="font-size: 12px;">Add Trailer</a>

</td></tr>
</table>
 
<table style="width: 100%;margin:auto;"  id="myTable" >

<thead>

<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;">
<th>&nbsp;</th>
<th class="cell_label">ID</th>

<th class="cell_label">Status</th>

<th class="cell_label">Name</th>

<th class="cell_label">VIN</th>

<th class="cell_label">Plates</th>

<th class="cell_label">Type</th>

<th class="cell_label">Make</th>
<th class="cell_label">Model</th>

<th class="cell_label">Capacity</th>


<th class="cell_label">Facility</th>

<th class="cell_label">Acquired</th>

<th class="cell_label">Lic. Renewed</th>

<th class="cell_label">Lic. Expires</th>
</tr>
</thead>

<tbody>
<?php


if( count($vcle) >0 ){
    
    foreach($vcle as $vehicle){
        echo "<tr>
                <td><img src='img/delete-icon.jpg' style='cursor:pointer;width:10px;height:10px;' class='deltrailer' rel='$vehicle[truck_id]'/></td>
                <td>$vehicle[truck_id]</td>
                <td>$vehicle[status]</td>
                <td><a href='viewVehicles.php?task=vv&id=$vehicle[truck_id]&type=trailer'>$vehicle[name]</a></td>
                <td>$vehicle[vin]</td>
                <td>$vehicle[plates]</td>
                <td>$vehicle[type]</td>
                <td>$vehicle[truck_make]</td>
                <td>$vehicle[truck_model]</td>
                <td>$vehicle[max_capacity]</td>
                <td>$vehicle[division]</td>
                <td>$vehicle[acquired]</td>
                <td>$vehicle[renewed]</td>
                <td>$vehicle[expires]</td>
            </tr>";
    }
    
}


?>
</tbody>

</table>
<script>
$(".deltrailer").click(function(){
    $.post("delete_trailer.php",{tid:$(this).attr('rel')},function(data){
       alert("Trailer Deleted from System "+data) ;
       window.location.reload();
    });
});

</script>