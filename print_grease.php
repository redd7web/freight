<?php

include "plugins/phpToPDF/phpToPDF.php";
include "protected/global.php";
ini_set("display_errors",0); 

$account_table = $dbprefix."_accounts";
$ikg_grease_table = $dbprefix."_ikg_grease";
$grease_list = $dbprefix."_list_of_grease";

$grease_ikg = new Grease_IKG($_GET['route_no']);




$logo="<img src='https://inet.iwpusa.com/img/cwlogo_line.jpg'  style='height:50%;'/>";


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
    width:12.5%;
}

div{
    text-align:center;
}
.manifest{
    vertical-align:top;    
    text-align:center;
}

</style>
';
$html .="<div class='content-wrapper' style='min-height:450px;height: auto;'>
    
    <div id='fields' style='width: 100%;min-height:300px;height:auto;'>
    
    <table style='width: 100%;font-size:14px;table-layout:fixed' id='meatikg'>
        <tr><td colspan='8' style='text-align:right;'>$logo</td></tr>
        <tr>
            <td>IKG Title</td>
            <td>$grease_ikg->ikg_manifest_route_number</td>
            <td>Truck</td>
            <td>".vehicle_name($grease_ikg->truck)."</td>
            <td>Time Start</td>
            <td>$grease_ikg->time_start</td>
            <td>Start Mileage</td>
            <td>$grease_ikg->start_mileage</td>
        </tr>
        
        <tr>
            <td>Scheduled Date</td>
            <td>$grease_ikg->scheduled_date</td>
            <td>License Plate</td>
            <td>$grease_ikg->license_plate</td>
            <td>First Stop</td>
            <td>$grease_ikg->first_stop</td>
            <td>First Stop Mileage</td>
            <td>$grease_ikg->first_stop_mileage</td>
        </tr>
        
        <tr>
            <td>Completion Date</td>
            <td>$grease_ikg->completed_date</td>
            <td>IKG Decal</td>
            <td>$grease_ikg->ikg_decal</td>
            <td>Last Stop</td>
            <td>$grease_ikg->last_stop</td>
            <td>Last Stop Mileage</td>
            <td>$grease_ikg->last_stop_mileage</td>
        </tr>
        
        <tr>
            <td>IKG Route Number</td><td>$grease_ikg->route_id</td>
            <td>Trailer</td>
            <td>".trailer_name($grease_ikg->trailer)."</td>
            <td>End Time</td>
            <td>$grease_ikg->end_time</td>
            <td>End Mileage</td> 
            <td>$grease_ikg->end_mileage</td>
        </tr>
            ";
        if($grease_ikg->number_days_route>1){
            $yc = $db->query("SELECT DISTINCT(what_day),first_stop,last_stop,first_stop_mileage,last_stop_mileage FROM freight_rout_history_grease WHERE route_no = $grease_ikg->route_id GROUP BY what_day ORDER BY what_day ASC");
            if(count($yc)>0){
                $i=1;
                foreach($yc as $poc){
                    if($i >1){
                        $html .= "
                       
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>$i)First Stop</td><td>$poc[first_stop]</td>
                            <td>First Stop Mileage</td><td>$poc[first_stop_mileage]</td>
                            </tr>
                            <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>$i)Last Stop</td><td>$poc[last_stop]</td>
                            <td>Last Stop Mileage</td><td>$poc[last_stop_mileage]</td>
                            </tr>";
                    }
                    $i++;
                }
            }
        }
        
        
        $html .="
        <tr>
            <td>".$grease_ikg->labels[0]."</td>
            <td>$grease_ikg->location</td>
             <td>Trailer LP</td>
            <td>$grease_ikg->trailer_lp</td>
            <td>Total Time Elapsed</td><td>";
                $checkTime = strtotime($grease_ikg->end_time);
                $loginTime = strtotime($grease_ikg->time_start);
                $diff = $checkTime - $loginTime;
                
                $html .= abs(round($diff/3600,2))." Hours</td>
            <td>Total Mileage</td><td>".
                number_format($grease_ikg->end_mileage - $grease_ikg->start_mileage,2)."</td>
        </tr>
        
    
        <tr>
            <td>".$grease_ikg->labels[1]."</td>
            <td>$grease_ikg->inventory_code
             <td>Trailer Decal</td>
             <td>$grease_ikg->trailer_decal</td>
            <td>Fuel</td>
            <td>$grease_ikg->fuel</td>
            <td>Total Lbs /Mile</td>
            <td>$grease_ikg->lbs_per_mile</td>
        </tr>
        
        
        <tr>
            <td>Lot #</td>            
            <td>$grease_ikg->lot_no</td>
             <td>Driver Complete</td>
             <td>$grease_ikg->driver_complete_dated</td>
            
            <td>Percent Solids %</td>
            <td>".number_format( $grease_ikg->percent_fluid,2)."</td>
            <td>Net Mileage</td>
            <td>$grease_ikg->sum_net_route_mileage</td>
        </tr>
        
        
        <tr>
            <td>Receiving Facility (required)</td>
            <td>".numberToFacility($grease_ikg->recieving_facility_no)."</td>
             <td>Gross Weight</td>
            <td>$grease_ikg->ikg_gross_weight</td>
            <td>Weight Ticket Number</td>
            <td>$grease_ikg->wtn</td>
        
            <td>Route Notes</td>
        <td rowspan='2' style='vertical-align: top;'>$grease_ikg->route_notes</td>
        </tr>
            
            <tr>
            <td>Facility Address</td>
            <td>".$facils[$grease_ikg->recieving_facility_no]."</td>
            <td>Tare Weight</td>
            <td>$grease_ikg->tare_weight</td>
            <td>Bol Number</td>
            <td>$grease_ikg->bol</td>
        </tr>
      
        <tr>
            <td>Facility Rep</td>
            <td>$grease_ikg->facility_rep</td>
             <td>Net Weight</td>
             <td>$grease_ikg->net_weight</td>
            <td rowspan='2' colspan='4'>
           
            Facility Rep ________________________________________________________________________<br/>
             Driver _____________________________________________________________________________
            </td>
        </tr>
        <tr>
            <td>Driver(required)</td>
            <td>".uNumToName($grease_ikg->driver_no)."</td>
            <td>IKG Collected</td>
            <td>$grease_ikg->ikg_transporter</td>
        </tr>
        <tr>
            <td>IKG Transporter</td><td>$grease_ikg->ikg_transporter</td>
        </tr>
        
        <tr>
            <td>Total day(s) in route</td><td>$grease_ikg->number_days_route</td>
        </tr>
        
    </table>
    </div>
    
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
    
 <table style='width:100%;margin:auto;'>              
    <tr>
        <td>Total Pickup Hours</td>
        <td> $grease_ikg->total_pickup_hours </td>
        <td>Net Route Mileage</td>
        <td> $grease_ikg->sum_net_route_mileage </td>
        <td>Net Gallons</td>
        <td> $grease_ikg->net_gallons </td>
        <td>Avg Gal/Stop</td>
        <td> $grease_ikg->avg_gal_per_stop </td>
        <td>Avg Mile/Stop</td>
        <td> $grease_ikg->avg_mile_per_stop </td>
        <td>Avg Min/Stop</td>
        <td> $grease_ikg->avg_min_per_stop </td>
    </tr>
</table>
    <table style='width:100%;'>
    <thead>
    <tr>
            <td style='width: 19px;'>&nbsp;</td>
            <td style='padding: 0px 0px 0px 0px;width:23px;'>
                <div class='cell' style='border-top-left-radius:5px;border-left: 1px solid black;border-top:1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>
                    #
                </div>
            </td>
            <td style='padding: 0px 0px 0px 0px;width:55px;'>
                <div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:55px;'>Status</div>
            </td>
            <td style='padding: 0px 0px 0px 0px;width:100px'>
                <div class='cell' style='border-top: 1px solid black;padding:0px 0px 0px 0px;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:100px;'>% Split</div>
            </td>
            <td style='padding: 0px 0px 0px 0px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>Name</div></td>
            <td style='padding: 0px 0px 0px 0px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>City</div></td>
            
            <td  style='padding: 0px 0px 0px 0px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>Address</div></td>
           
            <td  style='padding: 0px 0px 0px 0px;width:53px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:53px;'>Zip</div></td>
          
           
             <td  style='padding: 0px 0px 0px 0px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>Notes</div></td>
            <td  style='padding: 0px 0px 0px 0px;width:44px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:44px;'>Trap</div></td>
            
           
           <td style='padding: 0px 0px 0px 0px;width:45px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:45px;'>Col. gal</div></td>
           
          
        </tr>
    </thead>
     <tbody id='dddata'>
   "; 
    //$grease_ikg->scheduled_routes
    $count = 1;  
    $total_caps = 0;
    $acnt_info = new Account();  
    $counter = 0;
        if( !empty($grease_ikg->scheduled_routes) ){ //scheduled are reordered in accordance to accounts placed
            foreach( $grease_ikg->scheduled_routes as $ekc  ){
                     $grease_info = new Grease_Stop($ekc);
                    $html .= "<tr class='accnt_row' xlr='".$grease_info->account_number."' id='row$grease_info->grease_no' account='$grease_info->account_number' style='cursor:pointer;' >";
                    $html .= "<td style='width:10%;'>"; 
                        
                        if($acnt_info->singleField($grease_info->account_number,"locked") ==1){
                            $html .= " <span style='font-weight:bold;color:red;'>(LOCKED)";
                        }
                        
                        
                    $html .="</td>";
                    $html .= "<td>$count</td>";
                    $html .= "<td>";
                        $first = ""; 
                        $last = "";
                        if($person->user_id == 149){
                            
                            $first = "<a href='manifest_change_ppg_payment.php?schedule=$grease_info->grease_no' rel='shadowbox;width=400px;height=130px;'>";
                            $last = "</a>";
                        }
                    $html .= $first.$grease_info->route_status.$last."</td>"; 
                    $html .= "<td><input  style='width:75px;' type='text' class='percent_split' schedule_id='$grease_info->grease_no' account_no ='$grease_info->account_number'  placeholder='Percent Split' rel='$grease_info->grease_no'  value='$grease_info->percent_split'/></td>";
                    $html .= "<td>".$grease_info->account_name."</td>";
                    $html .= "<td>".$acnt_info->singleField($grease_info->account_number,"city")."</td>";
                    $html .= "<td>".$acnt_info->singleField($grease_info->account_number,"address")."</td>";
                    
                    $html .= "<td  style='width:53px;'>".$acnt_info->singleField($grease_info->account_number,"zip")."</td>";
             
                    
                    
                    $html .= "<td class='notes' rel='".htmlspecialchars($acnt_info->singleField($grease_info->account_number,"notes"))."'".$acnt_info->singleField($grease_info->account_number,"notes")."'>".$acnt_info->singleField($grease_info->account_number,"notes")."</td>";
                    $html .= "<td>".$acnt_info->singleField($grease_info->account_number,"grease_volume")."</td>";
              
                    $html .= "<td>".number_format($grease_info->volume,2)."</td>";
                       
                        
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


/*echo $html;

/**/
$path = "grease_route_manifests/$grease_ikg->ikg_manifest_route_number/";
$new_string = "IKG_GREASE_MANIFEST-$grease_ikg->ikg_manifest_route_number".date("Ymd_his").".pdf";
$pdf_options = array(
  "source_type" => 'html',
  "source" => $html,
  "action" => 'save',
  "page_orientation" => 'landscape',
  "file_name" => $new_string,
  "page_size" => 'legal',
  "save_directory" => $path
);

phptopdf($pdf_options);




$track = array(
    "date"=>date("Y-m-d H:i:s"),
    "user"=>$person->user_id,
    "actionType"=>"Manifest Printed",
    "descript"=>"Manifest Printed for grease route ".$_GET['route_no'],
    "pertains"=>6
);
$db->insert("xlogs.".$dbprefix."_activity",$track);    

/**/
header("Content-disposition: attachment; filename=".$new_string);
header("Content-type: application/pdf");
readfile($path.$new_string);




unset($db);
?>

