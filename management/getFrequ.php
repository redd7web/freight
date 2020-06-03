<?php


$result =$db->query("SELECT account_ID FROM ".$dbprefix."_accounts"); 

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
<table style="width: 100%;margin:auto;"  id="myTable">


<tr><th colspan="4">&nbsp;</th>

<th >Freq</th>

<th>Average</th>

<th >Optimal</th>

<th colspan="8">&nbsp;</th>
</tr>
</table>

<table style="width: 100%;" id="myTable">
<thead>
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
<th>&nbsp;</th>

<th class="cell_label">ID</th>
<th class="cell_label"><span title="Status">S</span></th>

<th class="cell_label">Location Name</th>

<th class="cell_label"><span title="Current Service Frequency">Freq</span></th>

<th class="cell_label"><span title="Average Service Frequency">Avg. Frq</span></th>

<th class="cell_label"><span title="Difference between Current Service Frequency and the actual pickup average ">AD</span></th>

<th class="cell_label"><span title="Optimal Service Frequency - number of days after pickup until container is 80% full">Opt. Frq</span></th>

<th class="cell_label"><span title="Difference between Current Service Frequency and the optimal frequency">OD</span></th>

<th class="cell_label">Pickups</th>

<th class="cell_label">Fires</th>

<th class="cell_label"><span title="Shortest number of days between pickups">Min Days</span></th>

<th class="cell_label"><span title="Longest number of days between pickups">Max Days</span></th>

<th class="cell_label"><span title="Average Gallons Per Pickup">Avg GPP</span></th>

<th class="cell_label"><span title="Average Gallons Per Pickup (last 8 pickups)">Avg GPP RCNT</span></th>

<th class="cell_label"><span title="Average Gallons Per Day (this one is for testing a better method)">Avg GPD</span></th>

<th class="cell_label"><span title="Estimated Galllons Per Day">Est GPD</span></th>

<th class="cell_label"><span title="On Site Capacity in Gallons">Cap</span></th>

<th class="cell_label"><span title="Average % of On Site Capacity per pickup">APC</span></th>

<th class="cell_label"><span title="Average % of On Site Capacity per pickup (last 8 pickups)">Recent APC</span></th>
</tr>
</thead>
<tbody></tbody>
</table>