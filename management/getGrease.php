<?php
ini_set("display_errors",1);
$data_table = $dbprefix."_grease_data_table";
$account_table = $dbprefix."_accounts";
$criteria = "";

if(isset($_POST['search_now'])){
   foreach($_POST as $name=>$value){
        switch($name){
            case "friend":
                if($value == 1){
                    $arrFields[] = " friendly IS NULL ";
                } else if ($value ==2){
                    $arrFields[] = " (friendly IS NOT NULL OR friendly !='' OR friendly !=' ') ";
                }
                break;
            case "from":
                if(strlen($value)>0){
                    $arrFields[] = " freight_ikg_grease.scheduled_date >= '".$_POST['from']."'";
                }
                break;
            case "to":
                if(strlen($value)>0){
                    $arrFields[] = " freight_ikg_grease.scheduled_date  <= '".$_POST['to']."'";
                }
                break;
            case "account_rep":
                if(strlen($value)>0){
                    $arrFields[]= " account_rep = $value";
                    //$names = " account_rep = $value";
                } 
                break; 
            case "salesrep":
                if(strlen($value)>0){
                    $arrFields[]= " original_sales_person = $value";
                    //$names .= " original_sales_person = $value";
                }
                break;
            case "friendly":
                if($value !="null"){
                    $arrFields[] = "friendly like '%$value%'";   
                }
                break;
            case "facility":
                if($value !="ignore"){
                    $arrFields[] = "freight_accounts.division = $value";
                }else  if ( $value == 99){
                    $arrFields[] = "freight_accounts.division in (24,30,31,32,33)";
                } 
            break;
        }
   } 
   
   
   
    $format = "SELECT 
                freight_grease_data_table.account_no, 
                freight_grease_data_table.inches_to_gallons, 
                freight_grease_data_table.date_of_pickup,
                freight_grease_data_table.ppg,
                freight_grease_data_table.route_id,
                freight_grease_data_table.fieldreport,
                COALESCE(ap_form_29942.element_8,NULL,0) as jet_hours,
                COALESCE(ap_form_29942.element_12,NULL,0) as jet_charge,
                freight_accounts.account_ID, 
                freight_accounts.address, 
                freight_accounts.city, 
                freight_accounts.state,
                freight_accounts.division,
                freight_accounts.grease_ppg, 
                freight_accounts.grease_volume,
                freight_accounts.account_rep,
                freight_grease_data_table.facility_origin,freight_ikg_grease.percent_fluid,
                freight_grease_data_table.inches_to_gallons * freight_grease_data_table.ppg as paid,freight_ikg_grease.ikg_manifest_route_number,freight_ikg_grease.scheduled_date,freight_ikg_grease.completed_date                 
                FROM freight_grease_data_table LEFT JOIN freight_accounts ON 
                freight_grease_data_table.account_no = freight_accounts.account_ID LEFT JOIN Inetforms.ap_form_29942 ON ap_form_29942.element_2 = freight_grease_data_table.route_id AND ap_form_29942.element_1 = freight_grease_data_table.account_no AND ap_form_29942.element_3 = freight_grease_data_table.schedule_id LEFT JOIN freight_ikg_grease ON freight_ikg_grease.route_id = freight_grease_data_table.route_id  WHERE 1 $criteria";
   
   echo $format."<br>";
   
   $request = $db->query($format);
    
}else {    
    $format = "SELECT 
                freight_grease_data_table.account_no, 
                freight_grease_data_table.inches_to_gallons, 
                freight_grease_data_table.date_of_pickup,
                freight_grease_data_table.ppg,
                freight_grease_data_table.route_id,
                freight_grease_data_table.fieldreport,
                freight_grease_data_table.facility_origin,
                COALESCE(ap_form_29942.element_8,NULL,0) as jet_hours,
                COALESCE(ap_form_29942.element_12,NULL,0) as jet_charge,
                freight_accounts.account_ID, 
                freight_accounts.address, 
                freight_accounts.city, 
                freight_accounts.state,
                freight_accounts.division,
                freight_accounts.grease_ppg, 
                freight_accounts.grease_volume,
                freight_ikg_grease.conductivity,
                freight_accounts.account_rep,
                freight_grease_data_table.inches_to_gallons * freight_grease_data_table.ppg as paid,freight_ikg_grease.percent_fluid,freight_ikg_grease.scheduled_date,freight_ikg_grease.completed_date FROM freight_grease_data_table LEFT JOIN freight_accounts ON freight_grease_data_table.account_no = freight_accounts.account_ID LEFT JOIN Inetforms.ap_form_29942 ON ap_form_29942.element_2 = freight_grease_data_table.route_id AND ap_form_29942.element_1 = freight_grease_data_table.account_no AND ap_form_29942.element_3 = freight_grease_data_table.schedule_id LEFT JOIN freight_ikg_grease ON freight_ikg_grease.route_id = freight_grease_data_table.route_id WHERE freight_ikg_grease.completed_date !='0000-00-00' AND freight_ikg_grease.completed_date IS NOT NULL";
    echo $format;
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
        
<table  style="width: 100%;margin:auto;margin-top:10px;margin-bottom:10px;"><tr><td style="height: 10px;">
<?php 

$allxx = "SELECT 
    SUM(freight_grease_data_table.inches_to_gallons) as bigbucket, 
    COUNT(freight_grease_data_table.inches_to_gallons) as num ,
    freight_grease_data_table.jetting,
    freight_grease_data_table.cs,
    freight_grease_data_table.ppg,
    freight_grease_data_table.route_id,
    freight_ikg_grease.conductivity,
    freight_accounts.account_ID,freight_ikg_grease.percent_fluid,freight_ikg_grease.scheduled_date,freight_ikg_grease.completed_date,freight_grease_data_table.facility_origin FROM $data_table INNER JOIN freight_accounts ON freight_grease_data_table.account_no = freight_accounts.account_ID LEFT JOIN freight_ikg_grease ON freight_ikg_grease.route_id = freight_grease_data_table.route_id WHERE freight_ikg_grease.completed_date !='0000-00-00' AND freight_ikg_grease.completed_date IS NOT NULL $criteria";
echo "<br/>".$allxx."<br/>";

$all = $db->query($allxx);
?>
</td></tr><tr><td style="text-align: center;">
<?php


if(count($all)>0){
    foreach($all as $answer){
        echo "<span style='font-weight:bold;font-size:24px;'>Total Gallons :".round($answer['bigbucket'],2)."</span><br/><br/>";
        echo "<span style='font-weight:bold;font-size:24px;'>Total Pickups :".$answer['num']."</span>";
    }
}

  
    

?>
</td></tr></table>        
        
<table style="width: 100%;margin:auto;table-layout:fixed;" id="myTable" >
<thead>
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
<?php
if(isset($_POST['search_now'])){
    if($_POST['my_group'] !="-" && isset($_POST['my_group']) ){
         switch($_POST['my_group']){
            case "account_rep":
                echo "<th class='cell_label'>Account Rep</th>";
            break;
            case "division":
                echo "<th class='cell_label'>Facility</th>";
            break;
            case "original_sales_person":
                echo "<th class='cell_label'>Original Sales Person</th>";
            break;
            default:
            
            break;
         }
   } 
}

?>

<th class="cell_label">Act ID</th>

<th class="cell_label" style="width: 210px;">Acct Name</th>
<th class="cell_label">Route ID</th>

<th class="cell_label">Loc Address</th>

<th class="cell_label">City</th>

<th class="cell_label" style="width: 70px;">State</th>
<?php
    if(isset($_POST['my_group'])  && $_POST['my_group'] !='-' ){
         switch($_POST['my_group']){
            case "account_ID": case "division": case "account_rep":case "original_sales_person":
                echo "<th class='cell_label'>Pickups</th>";
                break;
         }  
    } 

?>




    <th class="cell_label">Gallons</th>
    
    <th class="cell_label"><span title="Average Gallons per Pickup">GPP</span></th>



    <th class="cell_label">Date of Pickup</th>
    <th class="cell_label">Destination</th>
    <th class="cell_label">Field Report</th>
    <th class="cell_label">Avg Conductivity</th>
    <th class="cell_label">Avg Solids</th>
    
</tr>
</thead>
<tbody>
<?php 
if(count($request)>0){
    foreach($request as $summary){
        
        echo"<tr>";
            if(isset($_POST['search_now'])){
                 switch($_POST['my_group']){
                    case "account_rep":
                        echo "<th class='cell_label'>".uNumToName($summary['account_rep'])."</th>";
                    break;
                    case "division":
                        echo "<th class='cell_label'>".numberToFacility($summary['division'])."</th>";
                    break;
                    case "original_sales_person":
                        echo "<th class='cell_label'>".uNumToName($summary['original_sales_person'])."</th>";
                    break;
                 }
            }

            
            
                echo "<td>$summary[account_no]</td>";
                
                echo "<td>".account_NumToName($summary['account_no'])."</td>";
                echo "<td><form action='grease_ikg.php' target='_blank' method='post' class='ikg_form' style='cursor:pointer;'><input type='hidden' value='$summary[route_id]' name='util_routes'/><span style='color:blue;text-decoration:underline;'>$summary[route_id]</span><input type='hidden' value='1' name='from_routed_grease_list'/></form></td>";           
                echo "<td>$summary[address]</td>";
                echo "<td>$summary[city]</td>";
                echo "<td>$summary[state]</td>";
           
                if(isset($_POST['my_group']) && $_POST['my_group'] !="-" ){
                    switch($_POST['my_group']){
                        case "account_ID": case "division": case "account_rep":case "original_sales_person":
                            echo "<td>$summary[num]</td>";
                        break;
                    }    
                }
                
            
            
            echo "<td>".round("$summary[inches_to_gallons]",2)."</td>";
            if(isset($_POST['my_group']) && $_POST['my_group'] !="-" ){
                echo "<td>"; 

                switch($_POST['my_group']){
                     case "account_ID": case "division": case "account_rep":case "original_sales_person":
                        echo number_format($summary['avg'],2);
                     break;
                     default:
                        echo "0";
                     break;
                }
                
            echo "</td>";
            }else{
                echo "<td>";
                 $ui = $db->query("SELECT AVG(inches_to_gallons) as avx FROM freight_grease_data_table WHERE account_no = $summary[account_no]");
                    if(count($ui)>0){
                        echo number_format($ui[0]['avx'],2);
                    } else {
                        echo 0;
                    }
                echo "</td>";
            }
            
            echo "<td>$summary[scheduled_date]</td>";
            echo "<td>".numberToFacility("$summary[facility_origin]")."</td>";
            echo "<td>";if($summary['fieldreport'] != NULL && $summary['fieldreport'] !=''){
                    echo "<img src='../img/info_icon_12.png' style='cursor:pointer;' title='$summary[fieldreport]'/>";
                  }else{
                    echo "&nbsp;";
                  } echo "</td>
                  <td>$summary[conductivity]</td>
                  <td>$summary[percent_fluid]</td>
                 </tr>";
    }
}    

?>
</tbody>
</table>
<script>
$('.ikg_form').click(function(){
    $(this).submit();
})
</script>
