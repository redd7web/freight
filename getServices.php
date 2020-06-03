<?php

$criteria = "";
if(isset($_POST['search_now'])){
    foreach($_POST as $name=>$value){
        switch($name){
            case "location_status":
                if($value=="both"){
                    $arrField[] = " freight_accounts.status in('ending','archive') ";
                } else {
                    $arrField[] = " freight_accounts.status ='$value' ";
                }
            break;
        }
    }
    
    if(!empty($arrField)){
        $criteria = " AND ".implode(" AND ", $arrField);
    }
    
    $format = "SELECT freight_accounts.account_ID, freight_accounts.name, freight_containers.account_no, freight_accounts.notes, freight_accounts.status, freight_containers.request_date, freight_containers.removal_date , freight_containers.container_no, freight_list_of_containers.amount_holds, freight_list_of_containers.container_id, SUM( freight_list_of_containers.amount_holds ) AS cap FROM freight_accounts INNER JOIN freight_containers ON freight_containers.account_no = freight_accounts.account_ID INNER JOIN freight_list_of_containers ON freight_containers.container_no = freight_list_of_containers.container_id WHERE freight_accounts.account_ID = freight_containers.account_no $criteria GROUP BY account_ID";
    //echo $format;
    $t = $db->query($format);
    
} else {
    $format = "SELECT freight_accounts.account_ID, freight_accounts.name, freight_containers.account_no, freight_accounts.notes, freight_accounts.status, freight_containers.request_date,freight_containers.removal_date ,freight_containers.container_no, freight_list_of_containers.amount_holds, freight_list_of_containers.container_id, SUM( freight_list_of_containers.amount_holds ) AS cap FROM freight_accounts INNER JOIN freight_containers ON freight_containers.account_no = freight_accounts.account_ID INNER JOIN freight_list_of_containers ON freight_containers.container_no = freight_list_of_containers.container_id WHERE freight_accounts.account_ID = freight_containers.account_no AND freight_accounts.status = 'ending' GROUP BY account_ID";
    //echo $format;
    $t = $db->query($format);
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

<table style="width: 100%;margin:auto;" id="myTable">
    <thead>
        <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
        
        <td class="cell_label">ID</a></td>
        
        <td class="cell_label">Name</td>
        <td class="cell_label">Issue</td>
        <td class="cell_label">Capacity</td>
        <td class="cell_label">Pickup</td>
        <td class="cell_label">Retrieval</td>
        <td class="cell_label"><span title="Cancellation note explaning why we lost them. Do we have one or not.">Why</span></td>
        
        <td class="cell_label">Flags</td>
        <td class="cell_label">Account Status</td>
        
        
        </tr>
    </thead>
    <tbody>
    <?php 
        if(count($t)>0){
            foreach($t as $ending){
                echo "<tr>";
                echo "<td>$ending[account_ID]</td>";
                echo "<td>".account_NumtoName($ending['account_ID'])."</td>";
                echo "<td>$ending[notes]</td>";
                echo "<td>$ending[cap]</td>";
                echo "<td>$ending[request_date]</td>";
                echo "<td>$ending[removal_date]</td>";
                echo "<td>"; 
                 if(file_exists("$ending[account_ID]/cancel/")){
                        if ($handle = opendir("$ending[account_ID]/cancel/")) {
                        while (false !== ($entry = readdir($handle))) {            
                            if ($entry != "." && $entry != "..") {        
                                  echo "<a href='$_GET[account]/cancel/$entry' target='_blank'><img src='img/48_document_green.png'/></a>&nbsp;&nbsp;<a href='upload_file.php?account=$_GET[account]&mode=$_GET[mode]&path=$_GET[account]/cancel/$entry'><img src='img/delete-icon.jpg' title='Delete this notice'/></a>";
                            }
                        }        
                        closedir($handle);
                    }
                         
                    
                } else {
                    echo "No Notice";
                }
                
                echo"</td>";
                echo "<td>flags</td>";
                echo "<td>$ending[status]</td>";                
                echo "</tr>";
            }
        }
    
    ?>
    </tbody>
</table>