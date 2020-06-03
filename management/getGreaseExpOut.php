<?php
ini_set("display_errors",1);
$data_table = $dbprefix."_grease_data_table";
$account_table = $dbprefix."_accounts";
$criteria = "";

if(isset($_POST['search_now'])){
    
    foreach($_POST as $name=>$value){
         switch($name){
            case "inbound":
                if($value !="ignore"){
                    $arrField[]= " freight_grease_data_table.account_no = $value ";
                }
            break;
            case "outbound":
                if($value !="ignore"){
                    $arrField[]= " freight_grease_data_table.facility_origin = $value ";
                }
            break;
            case "ikg":
                if(strlen(trim($value))>0){
                    $arrField[] = " freight_ikg_grease.wtn like '%$value%' ";
                }
            break;
            case "conductivity":
                if(strlen(trim($value))>0){
                   $arrField[]=" freight_ikg_grease.conductivity >= $value "; 
                }
            break;
            case "c_to":
                if(strlen(trim($value))>0){
                    $arrField[]=" freight_ikg_grease.conductivity <= $value ";
                }
                
            break;
            case "p_to":
                if(strlen(trim($value))>0){
                    $arrField[]= "freight_ikg_grease.percent_fluid <=$value";
                }
            break;
            case "percent_solids":
                if(strlen(trim($value))>0){
                    $arrField[] = "freight_ikg_grease.percent_fluid >=$value";
                }
            break;
        }
    }
    $string = "";
    if(!empty($arrField)){
        $string = " AND ".implode(" AND ", $arrField);
    }
   
    
    $format = "SELECT freight_grease_data_table.facility_origin, freight_grease_data_table.date_of_pickup,freight_ikg_grease.	ikg_manifest_route_number,freight_grease_data_table.account_no,freight_ikg_grease.truck,freight_ikg_grease.net_weight,freight_ikg_grease.gross_weight,freight_ikg_grease.tare_weight,freight_ikg_grease.driver,freight_grease_data_table.percent_split,freight_ikg_grease.wtn,freight_ikg_grease.conductivity,freight_ikg_grease.percent_fluid FROM freight_grease_data_table LEFT JOIN freight_ikg_grease ON freight_grease_data_table.route_id = freight_ikg_grease.route_id where 1 $string";
    echo $format;
    $request = $db->query($format);
}else {    
    
     $format = "SELECT freight_grease_data_table.facility_origin, freight_grease_data_table.date_of_pickup,freight_ikg_grease.	ikg_manifest_route_number,freight_grease_data_table.account_no,freight_ikg_grease.truck,freight_ikg_grease.net_weight,freight_ikg_grease.gross_weight,freight_ikg_grease.tare_weight,freight_ikg_grease.driver,freight_grease_data_table.percent_split,freight_ikg_grease.wtn,freight_ikg_grease.conductivity,freight_ikg_grease.percent_fluid FROM freight_grease_data_table LEFT JOIN freight_ikg_grease ON freight_grease_data_table.route_id = freight_ikg_grease.route_id";
    //echo $format;
    $request = $db->query($format);
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

<table style="width: 400px;margin:auto;"><tr><td><form target="_blank" method="post" action="management/export_grease.php?mode=<?php echo $_GET['mode']; ?>">Export</td>

<td><select name="format"><option value="csv">CSV</option><option value="xls"> XLS</option></select><input type="hidden"  name="param" value="<?php echo $format ?>" readonly=""/></td>

<td><input type="hidden" name="my_group" value="<?php echo $_POST['my_group']; ?>" readonly=""/><input type="submit" name="export_now"/><input type="hidden"  name="param" value="<?php echo $format ?>" readonly=""/></form></td></tr>

<tr><td><form action="export_import.php&mode=<?php echo $_GET['mode']; ?>" target="_blank" method="post">Export Import</td><td>CSV</td><td><input type="hidden" name="my_group" value="<?php echo $_POST['my_group']; ?>" readonly=""/><input type="hidden"  name="param" value="<?php echo $format ?>" readonly=""/><input type="submit" name="export_now"/></form></td></tr>
</table>
        
<table  style="width: 100%;margin:auto;margin-top:10px;margin-bottom:10px;"><tr><td style="height: 10px;">


</td></tr><tr><td style="text-align: center;">
<?php


$allxx = "SELECT  SUM(inches_to_gallons)/8.34 as bigbucket,COUNT(schedule_id) as num  FROM freight_grease_data_table WHERE 1 $criteria";


$all = $db->query($allxx);
?>
</td></tr><tr><td style="text-align: center;">
<?php


 echo "<span style='font-weight:bold;font-size:24px;'>Total Lbs :".round($all[0]['bigbucket'],2)."</span><br/><br/>";
        echo "<span style='font-weight:bold;font-size:24px;'>Total Pickups :".$all[0]['num']."</span>";

  


  
    

?>
</td></tr></table>        
        
<table style="width: 100%;margin:auto;" id="myTable" >
<thead>
<tr>
    <td>Tank</td>
    <td>Date</td>
    <td>IKG Manifest Number</td>
    <td>% Split</td>
    <td>Truck</td>
    <td>Gross</td>
    <td>Tare</td>
    <td>Net</td>
    <td>Conductivity</td>
    <td>Percent Solid</td>
    <td>Weight Certificate </td>
    <td>Driver Name</td>
    <td>Destination</td>
</tr>
</thead>
<tbody>
<?php 
if(count($request)>0){
    foreach($request as $summary){
         echo "<tr>";
         echo "<td>".account_NumToName($summary['account_no'])."</td>";
         echo "<td>$summary[date_of_pickup]</td>";
         echo "<td>$summary[ikg_manifest_route_number]</td>";
         echo "<td>$summary[percent_split]</td>";
         echo "<td>$summary[truck]</td>";
         echo "<td>$summary[gross_weight]</td>";
         echo "<td>$summary[tare_weight]</td>";
         echo "<td>$summary[net_weight]</td>";
         echo "<td>$summary[conductivity]</td>";
         echo "<td>$summary[percent_fluid]</td>";
         echo "<td>$summary[wtn]</td>";
         echo "<td>".uNumToName($summary['driver'])."</td>";
         echo "<td>".numberToFacility($summary['facility_origin'])."</td>";
         echo "</tr>";
            		
    }
}    

?>
</tbody>
</table>
<script>

$(".emergency").click(function(){    
    if($(this).is(":checked")){
        $.post("emergency.php",{grease_id:$(this).attr('rel'),value:1},function(data){
            alert(data);
        });
    }else{
        $.post("emergency.php",{grease_id:$(this).attr('rel'),value:0},function(data){
            alert(data);
        });
    }
    
    
    if(confirm("Refresh page to see changes?")){
        window.location.reload();
    } 
}); 

$(".zcp").click(function(){
    if($(this).is(":checked")){
        $.post("zero_charge.php",{grease_no:$(this).attr('rel'),value:1},function(data){
            alert("Set to Zero Charge Pickup");
            //window.location.reload();
        });
    }else{
        $.post("zero_charge.php",{grease_no:$(this).attr('rel'),value:0},function(data){
            alert("Unset Zero Charge Pickup");
            //window.location.reload();
        });
    }
});


$(".invoice").click(function(){
    if($(this).is(":checked") ){
       $.post("update_exp_info.php",{value:1,mode:"invoice",entry_number:$(this).attr('rel')},function(data){
            alert("Payment Method Updated!");
            //window.location.reload();
        }); 
    }else{
       $.post("update_exp_info.php",{value:0,mode:"invoice",entry_number:$(this).attr('rel')},function(data){
            alert("Payment Method Updated!");
            //window.location.reload();
        }); 
    }
});

$(".cc").click(function(){
    if($(this).is(":checked") ){
        $.post("updateCN.php",{account:$(this).attr('rel'),value:1,mode:4},function(data){
            alert("Credit Card Updated!");
            //window.location.reload();
        }); 
    }else {
        $.post("updateCN.php",{account:$(this).attr('rel'),value:0,mode:4},function(data){
            alert("Credit Card  Updated!");
            //window.location.reload();
        }); 
    }
});


$(".paid").change(function(){
    $.post("update_exp_info.php",{value:$(this).val(),mode:"paid",entry_number:$(this).attr('rel')},function(data){
        alert("Paid Updated!");
    });
});

$(".ppg").change(function(){
    $.post("update_exp_info.php",{value:$(this).val(),mode:"ppg",entry_number:$(this).attr('rel')},function(data){
        alert("PPG Updated!");
    });
});

$(".payment_method").change(function(){
    $.post("update_exp_info.php",{value:$(this).val(),mode:"payment_method",entry_number:$(this).attr('rel')},function(data){
        alert("Payment Method Updated!");
    });
});


$(".pickedup").change(function(){
    $.post("update_exp_info.php",{value:$(this).val(),mode:"inches_to_gallons",entry_number:$(this).attr('rel')},function(data){
        alert("Payment Method Updated!");
    });
});

$(".ppg").change(function(){
    $.post("update_exp_info.php",{value:$(this).val(),mode:"ppg",entry_number:$(this).attr('rel')},function(data){
        alert("Payment Method Updated!");
    });
});

$(".volume").change(function(){
    $.post("update_exp_info.php",{value:$(this).val(),mode:"volume",entry_number:$(this).attr('rel')},function(data){
        alert("Payment Method Updated!");
    });
});
</script>