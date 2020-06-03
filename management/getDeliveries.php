<?php

if(isset($_POST['search_now'])){
    foreach($_POST as $name=>$value){
        switch($name){
            case "from":
                if(strlen($value)>0 && isset($value)){
                    $arrFields[] = " state_date >= '$_POST[from]'";   
                }
                break;
            case "to":
                if(strlen($value)>0 && isset($value)){
                    $arrFields[] = " expires <= '$_POST[to]'";
                }
                break; 
        }
    }
}
else {
    //$db->query("SELECT DISTINCT account_no, COUNT(*) as num_of_cont, FROM freight_containers");
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
<tr><th colspan="4" class="gspan">&nbsp;</th><th align="center" style="border-top:1px solid #5555cc; border-left:1px solid #aaa;  background:#ededff; " colspan="2" class="gspan2">Scheduled Dates</th></tr>


<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">

<th width="27" nowrap="" class="cell_label">ID</th>

<th width="98" nowrap="" class="cell_label">Capacity</th>

<th width="198" nowrap="" class="cell_label">Title</th>

<th width="198" nowrap="" class="cell_label">Container Count</th>

<th width="98" nowrap="" class="cell_label">Earliest</th>

<th width="98" nowrap="" class="cell_label">Latest</th>


</tr>

</thead>
<tbody></tbody>
</table>