<?php    
$criteria ="";
    if(isset($_POST['search_now'])){
        foreach($_POST as $name=>$value){
            switch($name){
                case "location_status":
                    if($value ==3){
                        $arrfield[] = " freight_accounts.status in ('new','active')";
                    } else {
                        $arrfield[] = " freight_accounts.status = '$value'";
                    }
                    break;
                case "salesrep":
                    if(strlen($value)>0){
                        $arrfield[] = "freight_accounts.original_sales_person = $value";
                    }
                    break;
                case "flag_id":
                    if($value == 1){
                        $arrfield[] = "freight_accounts.contract IS NULL ";
                    } else if($value ==6){
                        $arrfield[] = "freight_accounts.billing_address IS NULL ";
                    } else if ($value == 7){
                        $arrfield[] = "freight_accounts.address IS NULL ";
                    } else if ($value == 8){
                        $arrfield[] = " freight_accounts.account_ID NOT IN (SELECT account_no FROM freight_containers)";
                    }
                    break;
            }
        }
        
        if(!empty($arrfield)){
            $criteria = " AND ".implode(" AND ", $arrfield);
        }
        
        echo "SELECT freight_accounts.created,freight_accounts.status,freight_accounts.account_ID,freight_accounts.contract,freight_accounts.notes, freight_accounts.contact_name,freight_accounts.phone,freight_accounts.area_code,freight_accounts.original_sales_person  FROM freight_accounts WHERE 1 $criteria";
        $track_start = $db->query("SELECT freight_accounts.created,freight_accounts.status,freight_accounts.account_ID,freight_accounts.contract,freight_accounts.notes, freight_accounts.contact_name,freight_accounts.phone,freight_accounts.area_code,freight_accounts.original_sales_person  FROM freight_accounts WHERE 1 $criteria");
    } else {
        $track_start = $db->query("SELECT freight_accounts.created,freight_accounts.status,freight_accounts.account_ID,freight_accounts.contract,freight_accounts.notes, freight_accounts.contact_name, freight_accounts.phone,freight_accounts.area_code,freight_accounts.original_sales_person  FROM freight_accounts WHERE freight_accounts.status in('active','new')");
            
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
        <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size: contain;">
            <td class="cell_label">ID</td>
            <td class="cell_label">Name</td>
            <td class="cell_label">Created</td>
            <td class="cell_label">Age</td>
            <td class="cell_label" style="width: 50px;">Issues</td>
            <td class="cell_label">Status</td>
            <td class="cell_label" style="width: 300px;">
                <span title="This will be icons that control the sort!">Milestones</span>
                    
                    
            </td>
        
            <td class="cell_label">Info</td><td class="cell_label">Account</a></td>
        </tr>
    </thead>
    <tbody>
    <?php
        if(count($track_start)>0){
            foreach($track_start as $loc){
                
                echo "<tr>";
                    $spec_instr = explode("|","$loc[notes]");
                    echo "<td>$loc[account_ID]</td>";
                    echo "<td>".account_NumtoName($loc['account_ID'])."</td>";
                    echo "<td>$loc[created]</td>";
                    echo "<td>".date_different($loc['created'],date("Y-m-d"))."</td>";
                    echo "<td>$spec_instr[0]<br/>$spec_instr[1]</td>";
                    echo "<td>$loc[status]</td>";
                    echo "<td style='text-align:left;'>"; 
                    if(strlen($loc['contact_name'])>0){
                        echo "<img src='img/contact_green.png' title='$loc[contact_name]'/>&nbsp;";
                    }
                    else {
                        echo "<img src='img/contact_gold.png' />&nbsp;";
                    }
                    $check_tote = $db->query("SELECT container,delivery_date FROM freight_containers WHERE account_no = $loc[account_ID]");
                    if(count($check_tote)>0){
                        echo "<img src='img/oil_barrel_small_green.png' title='";  
                        foreach($check_tote as $dates){
                            echo "$dates[container] - $dates[delivery_date]&nbsp;|&nbsp;";
                        }
                        echo "' style='width:32px;heght:32px;'/>&nbsp;";
                    } else {
                        echo "<img src='img/oil_barrel_small_red.png'  style='width:32px;heght:32px;'/>&nbsp;";
                    }
                    
                    if(strlen($loc['phone'])>0){
                        echo "<img src='img/telephone.png' title='$loc[area_code] $loc[phone]'/>&nbsp;";
                    }
                    
                    if(file_exists("$loc[account_ID]/contract")){
                        if ($handle = opendir("$loc[account_ID]/contract/")) {
                            while (false !== ($entry = readdir($handle))) {            
                                if ($entry != "." && $entry != "..") {   
                                    $string = "contract-$loc[account_ID]";
                                    if(preg_match('/'.$string.'/',$entry)  ) {
                                        echo "<img src='img/48_document_green.png' style='width:32px;height:32px;'/>&nbsp;";
                                    } else {
                                        echo "<img src='img/image002.png' style='width:32px;height:32px;' />&nbsp;";
                                    } 
                                }
                            }        
                            closedir($handle);
                        }    
                    } else {
                        echo "<img src='img/image002.png' style='width:32px;height:32px;' />&nbsp;";
                    } 
                    
                    
                    
                    echo "</td>";
                    echo "<td>"; 
                    $oi = $db->query("SELECT notes FROM freight_notes WHERE account_no = $loc[account_ID]");
                    
                    if(count($oi)>0){
                        if($oi[0]['notes'] !=null || $oi[0]['notes'] !="|"){
                            echo $oi[0]['notes'];
                        }
                    }
                    
                    echo "</td>";
                    echo "<td>$loc[account_ID]</td>";
                echo "</tr>";
            }
        }
    
    ?>
    </tbody>
</table>