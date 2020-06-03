
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

<tr>

<th colspan="4"></th>

<th class="head_blue" colspan="3">Biotane Acquired</th>

<th class="head_pink" colspan="3">Biotane Additional</th>

<th class="head_blgr" colspan="3">Biotane</th>

<th class="head_grey" colspan="3">Other</th>

<th class="head_green" colspan="3">Total</th><th colspan="1"></th>
</tr>


<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
<th class="cell_label">#</th>

<th class="cell_label">ID</a></th>

<th class="cell_label">Route</a></th>

<th class="cell_label">Date</a></th>

<th class="cell_label"><span title="Total Raw (Wet) Gallons">Gal</span></a></th>

<th class="cell_label"><span title="Number of pickups in this route">Stops</span></a></th>


<th class="cell_label"><span title="Number of locations in this route">Loc</span></a></th>


<th class="cell_label"><span title="Total Raw (Wet) Gallons">Gal</span></a></th>

<th class="cell_label"><span title="Number of pickups in this route">Stops</span></a></th>

<th class="cell_label"><a href="index.php?task=export_freight_oil_breakout_per_route&amp;st_year=2014&amp;st_month=06&amp;end_year=2014&amp;end_month=06&amp;sort_direction=ASC&amp;sort_field=sad_locations"><span title="Number of locations in this route">Loc</span></a></th>


<th class="cell_label"><span title="Total Raw (Wet) Gallons">Gal</span></a></th>

<th class="cell_label"><span title="Number of pickups in this route">Stops</span></a></th>


<th class="cell_label"><span title="Number of locations in this route">Loc</span></a></th>

<th class="cell_label"><span title="Total Raw (Wet) Gallons">Gal</span></a></th>

<th class="cell_label"><span title="Number of pickups in this route">Stops</span></a></th>

<th class="cell_label"><span title="Number of locations in this route">Loc</span></a></th>


<th class="cell_label"><span title="Total Raw (Wet) Gallons">Gal</span></a></th>

<th class="cell_label"><span title="Number of pickups in this route">Stops</span></a></th>

<th class="cell_label"><span title="Number of locations in this route">Loc</span></a></th>

<th class="cell_label">Facility</a></th></tr>
</thead>
<tbody></tbody>
</table>