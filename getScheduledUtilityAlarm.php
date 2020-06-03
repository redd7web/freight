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
        <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;">
        
        <td class="underline_cell">#</td>
        <td class="cell_label">Info</td>
        <td class="cell_label"><span title="Location status">Loc Status</span></td>
        <td class="cell_label"><span title="When was this reported as a fire">Since</span></td>
        <td class="cell_label"><span title="Estimated date of service">ETA</span></td><td class="cell_label">Location</td>
        <td class="cell_label">City</td>
        <td class="cell_label"><span title="Who took the call">Added By</span></td>
        <td class="cell_label"><span title="The named driver has been informed of this request.">Driver Informed</span></td></tr>
    </thead>
    <tbody>
    
    </tbody>
</table>