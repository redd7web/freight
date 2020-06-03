<?php
error_reporting(1);
$criteria = "";
 if(isset($_POST['search_now'])){
    foreach($_POST as $name=>$value){
        switch($name){
            case "message_status":
                 if($value !="-"){
                    if($value == 1){
                        $arrField[] = " issue_status in ('new','active') ";
                     } else if ($value ==2){
                        $arrField[] = " issue_status like '%new%' ";
                     } else if ($value ==3){
                        $arrField[] = " issue_status like '%active%'";
                     } else if($value ==4){
                        $arrField[] = " issue_status =like '%resolved%'";
                     } else if($value == 5){
                        $arrField[] = " issue_status =like '%closed%'";
                     }
                 }
             break;
             case "my_message_category_id":
                if($value !="-"){
                    $arrField[] = " issue =".$value;
                }
             break;
             case "assigned_to_user_id":
                if($value !="-"){
                    $arrField[] = " assigned_to =".$value;
                }
             break;
        }
    }
    
    if(!empty($arrField)){
        $criteria = " AND ".implode(" AND ",$arrField);
    }
    
    $format = "SELECT * FROM freight_issues WHERE 1 $criteria";
    $issues = $db->query($format);
    echo $format;
 } else {
    $issues = $db->orderby("date_created","DESC")->get($dbprefix."_issues");   
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
        "order": [ 1, 'DESC' ],
        "lengthMenu": [ [10, 25, 50,100,150, -1], [10, 25, 50,100,150, "All"] ]
   }); 
});
</script>

<table style="width: 100%;margin:auto;" id="myTable">
    <thead>
        <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
            <td class="cell_label">#</td>
            <td class="cell_label">Created</td>
            <td class="cell_label">Status</td>
            <td class="cell_label">Type</span></td>
            <td class="cell_label">Category</td>
            <td class="cell_label">Info</td>
            <td class="cell_label">Wait</td>
            <td class="cell_label"><span title="Who added the message">Added By</span></td>
            <td class="cell_label">User Assigned</td>
        </tr>
    </thead>
<tbody>
<?php
if(count($issues) >0){
    foreach($issues as $content){
        echo"<tr>";
            echo "<td>$content[issue_no]</td>";
            echo "<td>$content[date_created]</td>"; 
            echo "<td>$content[issue_status]</td>";           
            echo "<td>";
            issueDecode("$content[priority_level]");
            echo"</td>";
             echo "<td>".account_issue("$content[issue]")."</td>";
            echo "<td>$content[message]</td>";
            echo "<td>".date_different($content['date_created'],date("Y-m-d H:i:s"))."</td>";
            echo "<td>".uNumToName("$content[reported_by]")."</td>";
            echo "<td>".uNumToName("$content[assigned_to]")."</td>";            
        echo"</tr>";
    }
}
?>
</tbody>
</table>