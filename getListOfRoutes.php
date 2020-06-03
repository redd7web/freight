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

<table style="width: 100%;margin:auto;"  id="myTable" >
<thead>
     <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
        <th class="cell_label">ID</a></th>
        <th class="cell_label">Status</a></th>
        <th class="cell_label">Title</a></th>
        <th class="cell_label">Facility</a></th>
        <th class="cell_label">Created</a></th>
        <th class="cell_label">By </a></th>
        <th class="cell_label">Scheduled</a></th>
        <th class="cell_label">Driver</a></th>
        <th class="cell_label">Stops</a></th>
        <th class="cell_label"><span title="Number of incomplete oil pickups.">Inc.</span></a></th>
        
    </tr>
</thead>

<tbody>
<?php

$result = $db->get($dbprefix."_list_of_routes");
if(count($result) !=0){
    foreach($result as $route){
        $scheduled_routes = new Scheduled_Routes();
        echo "<tr>
                <td>$route[list_of_routes_id]</td>
                <td>".statusColors($route['status'])."</span></td>
                <td>$route[ikg_manifest_number]<form><input type='hidden' value='$route[ikg_manifest_number]' name=''/></form></td>
                <td>".numberToFacility($route['facility']) ."</td>
                <td>$route[created_date]</td>
                <td>".uNumToName($route['created_by'])."</td>
                <td>$route[scheduled]</td>
                <td>".uNumToName($route['driver'])."</td>
                <td>$route[stops]</td>
                <td>$route[stops]</td>
            </tr>";
    }
}
?>

</tbody>
</table>