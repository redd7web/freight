<?php

include "plugins/phpToPDF/phpToPDF.php";
include "protected/global.php";
 ini_set("display_errors",1);

$account_table = $dbprefix."_accounts";
$ikg_grease_table = $dbprefix."_ikg_grease";
$grease_list = $dbprefix."_list_of_grease";

$grease_ikg = new Grease_IKG($_GET['route_no']);


$html ='
<style type="text/css">
body{
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
    height:990px;
    font-family:Tahoma;
    
}

@page{
    size: 8.5in 14in;
    margin: 0.5in;
} 


input[type=text]{
    border-top:0px;
    border-left:0px;
    border-right:0px;
    border-bottom:1px solid black;
}

table#meatikg td{
    border: 0px solid #ccc;
    border-collapse: separate;
    padding:0px 0px 0px 0px;
    font-size:14px;
}


</style>
';
$html .="
<div class='content-wrapper' style='min-height:450px;height: auto;'>
    
    <div id='fields' style='width: 100%;min-height:300px;height:auto;'>
    <table style='width: 100%;font-size:10px;' id='meatikg'>
    <tr><td colspan='5' style='text-align:center;font-weight:bold;font-size:16px;'>$grease_ikg->ikg_manifest_route_number</td>
    <td colspan='5' style='text-align:right;'><img src='https://inet.iwpusa.com/img/biologo.jpg'/></td></tr>
        <tr>
            <td>IKG Title</td>
            <td><input type='text' id='ikgmanifestnumber' value='$grease_ikg->ikg_manifest_route_number' name='ikgmanifestnumber'/></td>
            <td>Tank 1<br />Tank 2</td>
            <td><input id='tank1' value='$grease_ikg->tank1' name='tank1' type='text'/><br /><input id='tank2' name='tank2' value='$grease_ikg->tank2' type='text'/></td>
            <td>Time Start</td>
            <td><input value='$grease_ikg->time_start' id='timestart' name='timestart' type='text'/></td>
            <td>Start Mileage</td>
            <td><input value='$grease_ikg->start_mileage' id='start_mileage' name='start_mileage' type='text'/></td>
        </tr>
        
        <tr>
            <td>Scheduled Date</td>
            <td><input value='$grease_ikg->scheduled_date' type='text' id='sched_route_start' name='sched_route_start'/></td>
            <td>Truck</td>
            <td>
            ";
            $truck = new Vehicle($grease_ikg->vehicle);
            $html .= $truck->name;
            
            $html .="</td>
            <td>First Stop</td>
            <td><input value='   $grease_ikg->first_stop' id='firststop' name='firststop' type='text'/></td>
            <td>First Stop Mileage</td>
            <td><input value='   $grease_ikg->first_stop_mileage' type='text' id='first_stop_mileage' name='first_stop_mileage'/></td>
        </tr>
        
        <tr>
            <td>Completion Date</td>
            <td><input value='$grease_ikg->completed_date' id='completion' type='text'/></td>
            <td>License Plate</td>
            <td><input value='$truck->lp_no' type='text' name='lic_plate' id='lic_plate'/></td>
            <td>Last Stop</td>
            <td><input value='$grease_ikg->last_stop' id='laststop' name='laststop' type='text'/></td>
            <td>Last Stop Mileage</td>
            <td><input value='$grease_ikg->last_stop_mileage' id='last_stop_mileage' name='last_top_mileage'  type='text'/></td>
        </tr>
        
        <tr>
            <td>IKG Route Number</td>
            <td><input value='$grease_ikg->route_id' id='unique_route_no'  type='text' readonly='' /></td>
            <td>IKG Decal</td>
            <td><input value='$truck->ikg_decal' id='ikg_decal' name='ikg_decal' type='text'/></td>
            <td>End Time</td>
            <td><input value='$grease_ikg->end_time'  type='text' name='end_time' id='endtime'/></td>
             <td>End Mileage</td> 
             <td><input value='$grease_ikg->end_mileage' id='end_mileage' name='end_mileage' type='text'/></td>
        </tr>
        ";
        
        $route_history = $db->query("SELECT * FROM sludge_rout_history WHERE route_no = $_GET[route_no]");        
        if(count($route_history)>0){
            foreach($route_history as $history){
                $html .="<tr>
                    <td>Day $history[what_day] ) First Stop</td>
                    <td>$history[first_stop]</td>                                        
                    <td>Day $history[what_day] ) Last Stop</td>
                    <td>$history[last_stop]</td>
                    <td>Day $history[what_day] ) First Stop Mileage</td>
                    <td>$history[first_stop_mileage]</td>
                    <td>Day $history[what_day] ) Last Stop Mileage</td>
                    <td>$history[last_stop_mileage]</td>
                </tr>
                ";    
            }
        }
        
        $html .="<tr>
            <td>Location</td>
            <td><input value='$grease_ikg->location'  id='location' name='location' type='text'/></td>
            <td>IKG Collected</td>
            <td><input  name='ikg_collected' value='Grease Trap' id='ikg_collected' readonly='' type='text'/></td>
            <td>Fuel</td>
            <td><input value='$grease_ikg->fuel' name='fuel' id='fuel' type='text'/></td>
        </tr>
        
        
        <tr>
            <td>INVENTORY CODE</td><td><input value='$grease_ikg->inventory_code' name='inventory_code' id='inventory_code' type='text'/></td></td>
        </tr>
        
        
        <tr>
            <td>Lot #</td>            
            <td><input value='$grease_ikg->lot_no' id='lot_no' name='lot_no' type='text'/></td><td>Gross Weight</td>
            
            <td><input value='$grease_ikg->ikg_gross_weight' id='gross_weight' name='gross_weight' type='text'/></td>
            
        </tr>
        
        
        <tr>
            <td>Receiving Facility</td><td>".numberToFacility($grease_ikg->recieving_facility_no)."</td>
        <td>Tare Weight</td>
        <td><input value='$grease_ikg->tare_weight' type='text' name='tara_weight' id='tara_weight'/></td></tr><tr>
            <td>Facility Address</td>
            <td> ".$facils[$grease_ikg->recieving_facility_no]." </td>
            <td>Net Weight</td>
            <td><input value='$grease_ikg->net_weight'  name='net_weight' id='net_weight' type='text' readonly='true'/></td></tr>
      
        <tr>
            <td>Facility Rep</td>
            <td><input value='' id='fac_rep' name='fac_rep' type='text'/></td>
            <td rowspan='4' colspan='6'>
            <br /><br />
            Print Facility Rep ________________________________________________________________________<br /><br />
             Driver _____________________________________________________________________________
            
            </td>
        </tr>
        
        <tr>
            <td>Driver</td><td>".uNumToName($grease_ikg->driver_no)."</td>
        </tr>
        <tr>
            <td>IKG Transporter</td><td><input name='ikg_transporter' id='ikg_transporter' value='Biotane Pumping' readonly='' type='text'/></td>
        </tr>
        
        <tr>
            <td>View Day Route</td><td><select name='mult_day_route' id='mult_day_route'><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select>
        </tr>
        
    </table>
    <div id='data_display' style='width:100%;margin:auto;height:auto;min-height:400px;'>
    <table style='width: 100%;padding:0px 0px 0px 0px;height:auto;'>
    <tr>
    <td style='text-align:center;cursor:pointer;' id='test'>Pickups:</td>
    
    <td style='text-align:center;' id='nop'>".count($grease_ikg->scheduled_routes)."</td>
    <td style='text-align:center;' colspan='1'>&nbsp;</td>
    
    <td style='text-align:center;' colspan='3' id='estimated'>
    
    </td>
    <td>
     &nbsp;
    </td>
    <td style='text-align: center;' id='collected'>
    
    </td>
    </tr>
    </table>
    
    <table style='width:100%;margin:auto;' id='sortable'>
    <thead>
    <tr>
        <td style='padding: 0px 0px 0px 0px;width:50px;'>
            <div class='cell'>
                Stop #
            </div>
        </td>
        <td style='padding: 0px 0px 0px 0px;width:100px;'>
            <div class='cell' style='width:100px;'>Status</div>
        </td>
        <td style='padding: 0px 0px 0px 0px;width:100px'>
            <div class='cell' style='width:100px;'>Scheduled</div>
        </td>
        <td style='padding: 0px 0px 0px 0px;'>Name</td>
        <td style='padding: 0px 0px 0px 0px;'>City</td>
        <td  style='padding: 0px 0px 0px 0px;'>Address</td>
        <td  style='padding: 0px 0px 0px 0px;'>Zip</td>
        <td  style='padding: 0px 0px 0px 0px;'>Info</td>        
        <td  style='padding: 0px 0px 0px 0px;'>Grease Trap Size</td>
       <td  style='padding: 0px 0px 0px 0px;'>Grease Picked Up</td>
    </tr>
    </thead>
    <tbody>"; ?>
    <?php
    $count = 1;  
    $total_caps = 0;
    $acnt_info = new Account();  
     
    
    
        if( count($grease_ikg->scheduled_routes) >0 ){
            foreach( $grease_ikg->scheduled_routes as $ekc  ){
                $g_stop = new Grease_Stop($ekc);
               $html .= "<tr class='accnt_row' xlr='$ekc'>";
                $html .= "<td style='border:1px solid black;'>$count</td>";
                $html .= "<td style='border:1px solid black;'>$g_stop->route_status</td>";
                $html .= "<td style='border:1px solid black;'>$g_stop->service_date</td>";
                $html .= "<td style='border:1px solid black;'>".account_NumToName($g_stop->account_number)."</td>";
                 $html .= "<td style='border:1px solid black;'>".$acnt_info->singleField($g_stop->account_number,"city")."</td>";
                $html .= "<td style='border:1px solid black;'>".$acnt_info->singleField($g_stop->account_number,"address")."</td>";
                
                 $html .= "<td style='border:1px solid black;'>".$acnt_info->singleField($g_stop->account_number,"zip")."</td>";
                $html .= "<td style='border:1px solid black;'>";   
                if(strlen($g_stop->notes)>0){
                    $html .= $g_stop->notes;
                } else {
                    $html .="&nbsp;";
                }
                
                $html .="</td>";
                $html .= "<td style='border:1px solid black;'>$g_stop->volume</td>";
                $html .="<td style='border:1px solid black;'>";
                $gh = $db->query("SELECT SUM(inches_to_gallons) as io FROM sludge_grease_data_table WHERE schedule_id=$g_stop->grease_no AND account_no = $g_stop->account_number AND route_id=$g_stop->grease_route_no");
                if(count($gh)>0){
                    foreach($gh as $bv){
                       $html .= $bv['io'];
                    }
                }
                $html .="</td>";
                $html .= "</tr>";
                $count++;
            }
        }   
  $html .="</tbody>
    </table>
        <div style='clear: both;'></div>
    </div>
    </div>
</div>";


echo $html;


$new_string = "IKG_GREASE_MANIFEST-$grease_ikg->ikg_manifest_route_number".date("Ymd_his");
$new_string = str_replace(" ","-",$new_string);
$pdf_options = array(
  "source_type" => 'html',
  "source" => $html,
  "action" => 'save',
  "save_directory" => '',
  "page_orientation" => 'landscape',
  "file_name" => $new_string.'.pdf',
  "page_size" => 'legal'
);

?>

