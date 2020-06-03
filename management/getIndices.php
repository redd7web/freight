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
   $('#myTable').dataTable(); 
});
</script>
<table style="width: 100%;">
<tr><th colspan="3" style="height: 20px;text-align:left;"><a href="management/addIndex.php" rel="shadowbox;width=400px;height=50;"><img src="img/add_item.big.gif" />&nbsp;<span style="font-size: 12px;">Add Index</span><br /></th></tr>
</table>

<table style="width: 100%;margin:auto;" id="myTable" >
 

<thead>
 
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">

<th class="cell_label">#</th>
<th class="cell_label">Month</th>
<th class="cell_label">Price</th>
<th class="cell_label">User</th>
<th class="cell_label">Modified</th>
</tr>
</thead>
<tbody>

<?php
    $index = $db->get($dbprefix."_jacobsen");
    
    foreach($index as $value){
        $month = explode ("-",$value['date']);
        switch($month[1]){
            case "01":
                $mname = "January";
                break;
            case "02":
                $mname = "Febuary";
                break;
            case "03":
                $mname = "March";
                break;
            case "04":
                $mname = "April";
                break;
            case "05":
                $mname = "May";
                break;
            case "06":
                $mname = "June";
                break;
            case "07":
                $mname= "July";
                break;
            case "08":
                $mname= "August";
                break;
            case "09":
                $mname = "September";
                break;
            case "10":
                $mname = "October";
                break;
            case "11":
                $mname = "November";
                break;
            case "12":
                $mname = "December";
                break;
                
        }
        
        echo "<tr><td>$value[id]</td><td>$mname</td>  <td> ". number_format ($value['percentage'],2)."</td><td>". uNumToName($value['user']). "</td><td>$value[modified]</td><td><a href='management/updateJake.php?jacobnumber=$value[id]' rel='shadowbox;width=400px;height=50;'><img src='img/edit-icon.jpg' /></a></td></tr>";
    }

?>


</tbody>
</table>