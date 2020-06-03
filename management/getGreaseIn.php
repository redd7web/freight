<?php
ini_set("display_errors",1);
$data_table = $dbprefix."_grease_data_table";
$account_table = $dbprefix."_accounts";
$criteria = "";

if(isset($_POST['search_now'])){
    
    foreach($_POST as $name=>$value){
        switch($name){
            case "tank":
                if( $value !="ignore"){
                    $v = $value - 200;
                    $arrFields[]= "ap_form_27963.element_55 = $v";
                }
            break;
            case "ikg":
                if(strlen(trim($value))>0){
                    $arrFields[]= "ap_form_27963.element_57 like '%$value%'";    
                }
            break;
            case "weight_cert":
                if(strlen(trim($value))>0){
                    $arrFields[]="ap_form_27963.element_41 like '%$value%'";
                }
            break;
            case "invoice":
                if(strlen(trim($value))>0){
                    $arrFields[] = "ap_form_27963.element_52 = $value";    
                }
            break;
            
            case "element_6"://customer
                if(strlen(trim($value))>0){
                    $arrFields[]="ap_form_27963.element_6 = $value";    
                }                
            break;
            case "element_8"://transport
                if(strlen(trim($value))>0){
                    if($value == 4){
                        if(strlen(trim($_POST['o_choice']))>0){
                            $arrFields[]= "ap_form_27963.element_43 like '%$_POST[o_choice]%'";    
                        }else{
                            $arrFields[]= "ap_form_27963.element_43 like '% %'";
                        }
                    }else {
                        $arrFields[]= "ap_form_27963.element_8 = $value";
                    }
                        
                }
            break;
        }
    }
    
    if(!empty($arrFields)){
        $string =  " AND ".implode (" AND ",$arrFields);
    }
    
    $format = "SELECT date_created as date_of_pickup,element_57 as ikg_manifest_route_number,element_38 as gross,element_39 as tare, element_40 as net,element_41 as weight_certificate,element_55 as account_no,element_52 as invoice_number,element_6,element_43,element_9_1,element_9_2,element_8,element_52 FROM Inetforms.ap_form_27963 WHERE 1 $string";
    echo $format;
    $request = $db->query($format);
}else {    
    $format = "SELECT date_created as date_of_pickup,element_57 as ikg_manifest_route_number,element_38 as gross,element_39 as tare, element_40 as net,element_41 as weight_certificate,(element_55) as account_no,element_52 as invoice_number,element_6,element_43,element_9_1,element_9_2,element_8,element_52 FROM Inetforms.ap_form_27963";
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

$allxx = "SELECT  SUM(element_40)/8.34 as bigbucket,COUNT(element_40) as num  FROM Inetforms.ap_form_27963 WHERE 1 $criteria";
echo "<br/>".$allxx."<br/>";

$all = $db->query($allxx);
?>
</td></tr><tr><td style="text-align: center;">
<?php


if(count($all)>0){
    foreach($all as $answer){
        echo "<span style='font-weight:bold;font-size:24px;'>Total Lbs :".round($answer['bigbucket'],2)."</span><br/><br/>";
        echo "<span style='font-weight:bold;font-size:24px;'>Total Pickups :".$answer['num']."</span>";
    }
}

  
    

?>
</td></tr></table>        
        
<table style="width: 100%;margin:auto;table-layout:fixed;" id="myTable" >
<thead>
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
    <td>Tank #</td>
    <td>Date</td>
    <td>IKG Manifest Number</td>
    <td>Customer</td>
    <td>Carrier/Transporter </td>
    <td>Gross</td>
    <td>Tare</td>
    <td>Net</td>
    <td>Weight Certificate </td>
    <td>Driver Name</td>
    <td>Invoice Number</td>
</tr>
</thead>
<tbody>
<?php 
if(count($request)>0){
    foreach($request as $summary){
        
        switch($summary['account_no']){
                case 1:case 2:case 3: case 4:case 5: case 6:case 7:case 8:case 9: case 10:case 11:case 16:
                $j = $summary['account_no']+200;
                $tank = account_NumToName($j); break;
                 
                case 18: $tank = "VV"; break;
                case 15: $tank = "other"; break;
                default: $tank = ""; break;
          }
          
          switch($summary['element_6']){
            case 4: $customer = "OTHER"; break;
            case 5: $customer = "ABILITY"; break;
           case 45: $customer = "ALPHA PETROLEUM"; break;
           case 52: $customer = "ALPHA PUMPING"; break;
           case 29: $customer = "AMBERWICK"; break;
           case 7: $customer = "ATLAS PUMPING"; break;
           case 53: $customer = "Athens"; break;
           case 8: $customer = "BAKER COMMODITIES"; break;
           case 38: $customer = "BIOTANE"; break;
           case 31: $customer = "BLUE WATER"; break;
           case 9: $customer = "BOB WALTON"; break;
           case 54: $customer = "CARI RECYCLING"; break;
           case 33: $customer = "CRIMSON"; break;
           case 10: $customer = "CO-WEST"; break;
           case 11: $customer = "DIAMOND ENV. (Delivered)"; break;
           case 12: $customer = "Diamond Environmental (Pick Up)"; break;
           case 50: $customer = "Environmental &amp; Chem Consult"; break;
           case 13: $customer = "HAZ-MAT"; break;
           case 42: $customer = "INLAND PUMPING"; break;
           case 14: $customer = "IWP"; break;
           case 46: $customer = "IWP - G Division"; break;
           case 47: $customer = "IWP - K Division"; break;
           case 44: $customer = "JR GREASE"; break;
           case 43: $customer = "LAMB CANYON"; break;
           case 15: $customer = "L &amp; S PIPELINE"; break;
           case 16: $customer = "LIQUID ENV."; break;
           case 17: $customer = "MAJOR CLEAN UP"; break;
           case 40: $customer = "OC PUMPING"; break;
           case 18: $customer = "PIPE MAINT."; break;
           case 19: $customer = "Pipe Maintenance"; break;
           case 30: $customer = "RE COMMODITIES"; break;
           case 20: $customer = "ROTO ROOTER"; break;
           case 37: $customer = "SB INDUSTRIAL"; break;
           case 21: $customer = "STATER BROS - BIG BEAR"; break;
           case 22: $customer = "STATER BROS Lake Arrowhead"; break;
           case 23: $customer = "STRESS LESS ENV."; break;
           case 28: $customer = "T&amp;R"; break;
           case 32: $customer = "UNITED PUMPING"; break;
           case 41: $customer = "VENTURA FOODS"; break;
           case 27: $customer = "Victorville"; break;
           case 35: $customer = "WESTERN ENV."; break;
           case 24: $customer = "WESTERN PACIFIC"; break;
           case 25: $customer = "WHITE HOUSE"; break;
           case 26: $customer = "WRIGHT"; break;
           case 48: $customer = "Sustainable Restaurant Services"; break;
           default: $customer = ""; break; 
            		
          }
          
          switch($summary['element_8']){
            case 1:  $transporter = "Co-West"; break;
            case 31: $transporter = "Ability"; break;
            case 28: $transporter = "Alpha Petroleum"; break;
            case 35: $transporter = "Alpha Pumping"; break;
            case 6:  $transporter = "Amberwick"; break;
            case 7:  $transporter = "Atlas Pumping"; break;
            case 36: $transporter = "Athens"; break;
            case 8:  $transporter = "Baker Commodities"; break;
            case 12: $transporter = "Best Western"; break;
            case 16: $transporter = "Biotane"; break;
            case 37: $transporter = "CARI RECYCLING"; break;
            case 20: $transporter = "Desert Soul"; break;
            case 24: $transporter = "Diamond"; break;
            case 5:  $transporter = "Empire"; break;
            case 33: $transporter = "Environmental &amp; Chem Consult"; break;
            case 27: $transporter = "FEMA"; break;
            case 9:  $transporter = "Haz-Mat"; break;
            case 19: $transporter = "Inland Pumping"; break;
            case 2:  $transporter = "IWP"; break;
            case 26: $transporter = "JL Trucking"; break;
            case 21: $transporter = "JR Grease"; break;
            case 11: $transporter = "Liquid Env"; break;
            case 22: $transporter = "Major CleanUp"; break;
            case 18: $transporter = "OC Pumping"; break;
            case 25: $transporter = "Plowman"; break;
            case 3:  $transporter = "Nu West"; break;
            case 10: $transporter = "RE Commodities"; break;
            case 17: $transporter = "SB Industrial"; break;
            case 29: $transporter = "Stressless"; break;
            case 15: $transporter = "T&amp;R"; break;
            case 13: $transporter = "White House"; break;
            case 14: $transporter = "Wright"; break;
            case 4:  $transporter = $summary['element_43']; break;
            case 32: $transporter = "Sustainable Restaurant Services"; break;
            default: $transporter = "";break;
          }
          
          echo "<tr>";
		  echo "<td>$tank</td>"	;
          echo "<td>$summary[date_of_pickup]</td>";
          echo "<td>$summary[ikg_manifest_route_number]</td>";
          echo "<td>$customer</td>";
          echo "<td>$transporter</td>";
          echo "<td>$summary[gross]</td>";
          echo "<td>$summary[tare]</td>";
          echo "<td>$summary[net]</td>";
          echo "<td>$summary[weight_certificate]</td>";
          echo "<td>$summary[element_9_2], $summary[element_9_1]</td>";
          echo "<td>$summary[element_52]</td>";
          echo "</tr>"; 
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
