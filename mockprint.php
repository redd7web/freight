<?php
include "plugins/phpToPDF/phpToPDF.php";
include "protected/global.php";
$accounts_container = $dbprefix."_containers";
$containerslist = $dbprefix."_list_of_containers";
//ini_set('display_errors',1); 
 error_reporting(E_ALL);
$ikg_info = new IKG($_GET['ikg']);  
"IKG_RECEIPT".date('Y-m-d_H-i-s').".pdf";


$html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>IKG MANIFEST RECIEPT</title>
<style type="text/css">
<!--
@page{
    size: 8.5in 11in;
    margin: 0.5in;
} 

table#meatikg{
    border: 0px solid #bbb;
}
input[type="text"]{
    border:0px solid #bbb;
    
}

.myTable{
    size: 8.5in 14in;
    transform:rotate(90deg);
}
body {
  font-family:Tahoma;
}

img {
  border:0;
}

tbody#meat td{
    text-align:center;
}

thead#meathead td{
    text-align:center;

#page {
  width:800px;
  margin:0 auto;
  padding:15px;

}

#logo {
  float:left;
  margin:0;
}

#address {
  height:181px;
  margin-left:250px; 
}

table {
  width:100%;
}



tr.odd {
  background:#e1ffe1;
}
-->
</style>
</head>
<body>
';



$html .='<div id="fields" style="width: 100%;height:auto;margin:auto;">
    <table style="width: 100%;font-size:18px;border:0px solid transparent;" id="meatikg">
        <tr><td colspan="3" style="text-align: left;font-size:34px;fontweight:bold;">IKG MANIFEST</td><td colspan="2" style="text-align: center;font-size:24px;font-weight:bold;">'.$ikg_info->ikg_manifest_route_number.'</td><td colspan="3" style="text-align: right;"><img src="http://www.datastormdesigns.com/biotane/img/blogo.jpg"/></td></tr>
        <tr>
            <td>IKG Ttitle</td>
            <td>';
       $html .=$ikg_info->ikg_manifest_route_number;
       $html .='</td>
            <td>Tank 1<br />Tank 2</td>
            <td>';
        $html .=$ikg_info->tank1;
        $html .='<br />';
        $html .=$ikg_info->tank2;
        $html .='</td>
            <td>Time Start</td>
            <td>';
        $html .=$ikg_info->time_start;
        $html .='</td>
            <td>Start Mileage</td>
            <td>';
        $html .= $ikg_info->start_mileage;        
        $html .='</td></tr>
        
        <tr>
            <td>Scheduled Date</td>
            <td>';
        $html .=$ikg_info->scheduled_date;
        $vehicle = new Vehicle($ikg_info->truck);
        $html .= '</td>
            <td>Truck</td>
            <td>';
         $html .= $vehicle->name;       
        $html .='</td>
            <td>First Stop</td>
            <td>';
        $html .=$ikg_info->first_stop;
        $html .='</td>
            <td>First Stop Mileage</td>
            <td>';
        $html .=$ikg_info->first_stop_mileage;
        $html .='</td>
        </tr>
        
        <tr>
            <td>Completion Date</td>
            <td>';
        $html .=$ikg_info->completed_date;
        $html .='</td>
            <td>License Plate</td>
            <td>';
        $html .=$ikg_info->license_plate;
        $html .='</td>
            <td>Last Stop</td>
            <td>';
        $html .=$ikg_info->last_stop;
        $html .= '</td>
            <td>Last Stop Mileage</td>
            <td>';
        $html .=$ikg_info->last_stop_mileage;
        $html .='</td>
        </tr>
        
        <tr>
            <td>IKG Route Number</td>
            <td>';
        $html .=$ikg_info->route_id;
        $html .='</td>
            <td>IKG Decal</td>
            <td>';
        $html .=$ikg_info->ikg_decal;
        $html .='</td>
            <td>End Time</td>
            <td>';
        $html .=$ikg_info->end_time;
        $html .='</td>
             <td>End Mileage</td> 
             <td>';
        $html .=$ikg_info->end_mileage;
        $html .='</td>
        </tr>';
        
        $route_history = $db->query("SELECT * FROM freight_rout_history WHERE route_no = $ikg_info->route_id AND what_day >1");        
        if(count($route_history)>0){
            foreach($route_history as $history){
                $html .="
                     <tr>
                         <td>&nbsp;</td><td>&nbsp;</td>
                        <td>&nbsp;</td><td>&nbsp;</td>
                        <td>Day $history[what_day] )Time Start</td>
                        <td>$history[time_start]</td>
                        <td>Day $history[what_day] ) Start Mileage</td><td>$history[start_mileage]</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td><td>&nbsp;</td>
                        <td>&nbsp;</td><td>&nbsp;</td>
                        <td>Day $history[what_day] ) First Stop</td>
                        <td>$history[first_stop]</td>             
                        <td>Day $history[what_day] ) First Stop Mileage</td>
                        <td>$history[first_stop_mileage]</td>
                    
                    </tr>
                    <tr>
                        <td>&nbsp;</td><td>&nbsp;</td>
                        <td>&nbsp;</td><td>&nbsp;</td>
                        <td>Day $history[what_day] ) Last Stop</td>
                        <td>$history[last_stop]</td>
                        <td>Day $history[what_day] ) Last Stop Mileage</td><td>$history[last_stop_mileage]</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td><td>&nbsp;</td>
                        <td>&nbsp;</td><td>&nbsp;</td>
                        <td>Day $history[what_day] ) End Time</td>
                        <td>$history[time_end]</td>
                        <td>Day $history[what_day] ) End Mileage</td><td>$history[end_mileage]</td>
                    </tr>
                   
                ";    
            }
        }
        
        $html .='<tr>
            <td>Location</td>
            <td>';
        $html .=$ikg_info->location;
        $html .='</td>
            <td>IKG Collected</td>
            <td>Yellow Grease</td>
            <td>Fuel</td>
            <td>';
        $html .=$ikg_info->fuel;
        $html .='</td>
        </tr>
        
        
        <tr>
            <td>INVENTORY CODE</td><td>';
        $html .=$ikg_info->inventory_code;
        $html .= '</td></td>
        </tr>
        <tr>
            <td>Lot #</td>            
            <td>';
        $html .=$ikg_info->lot_no;
        $html .='</td><td>Gross Weight</td>
            <td>';
        $html .=$ikg_info->ikg_gross_weight;
        $html .='</td>
            <td>
            Net Weight
            </td>
            <td></td>
            <td id="output" rowspan="3" style="text-align: center;font-size:20px;">
            
            </td>
        </tr>
        
        
        <tr>
            <td>Receiving Facility</td><td>';
        $html .= numberToFacility($ikg_info->recieving_facility_no);
        $html .='</td>
        <td>Tare Weight</td>
        <td>';
        $html .=$ikg_info->tare_weight;
        $html .='</td>
        <td>Collected Weight (lbs)</td>
        <td>'.$ikg_info->collected_Weight.'</td>
        
           </tr>
            
            <tr>
            <td>Facility Address</td>
            <td>';
        $html .=$ikg_info->facility_address;
        $html .='</td>
            <td>Net Weight</td>
            <td>';
        $html .=$ikg_info->net_weight;
        $html .='</td>
            <td>Difference </td>
            <td>'.$ikg_info->difference_weight.'</td>
        </tr>
        
        <tr>
           <td>Print Rep</td>
            <td>___________________</td>
            <td rowspan="4" colspan="6">
            <br /><br />
            Facility Rep ________________________________________________________________________<br /><br />
             Driver _____________________________________________________________________________
            
            </td>
        </tr>
        
        <tr>
            <td>Driver</td><td>
            ';
            
    $html .= uNumToName($ikg_info->driver_no); 
    $html .='
            </td>
        </tr>
        <tr>
            <td>IKG Transporter</td><td>Biotane Pumping</td>
        </tr>
        
        <tr>
            <td>View Day Route</td><td><select name="mult_day_route" id="mult_day_route"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select>
        </tr>
        
    </table>
    
    
    
    <table style="width:100%;border:0px solid black;" id="chart">
    <thead id="meathead">
    <tr>        
        <td style="padding: 0px 0px 0px 0px;width:9px;border:1px solid black;">#</td>
        <td style="padding: 0px 0px 0px 0px;width:300px;border:1px solid black;">Name</td>
         <td  style="padding: 0px 0px 0px 0px;width:600px;width:5%;border:1px solid black;">Account</td>
        <td  style="padding: 0px 0px 0px 0px;width:260px;border:1px solid black;">Address</td>
        <td style="padding: 0px 0px 0px 0px;width:150px;border:1px solid black;">City</td>
        <td style="padding: 0px 0px 0px 0px;border:1px solid black;width:50px;">Zip</td>
        <td style="padding: 0px 0px 0px 0px;width:60px;border:1px solid black;">Tote Size</td>
        <td style="padding: 0px 0px 0px 0px;width:50px;border:1px solid black;">Inches</td>
        <td style="padding: 0px 0px 0px 0px;width:25px;border:1px solid black;">Gals</td>
        <td style="padding: 0px 0px 0px 0px;width:130px;border:1px solid black;">Notes</td>
    </tr>
    </thead>
    <tbody id="meat">
    <tr>
';
    
    //var_dump($ikg_info->account_numbers); 
    //var_dump($ikg_info->scheduled_routes);
       
    $aaccoouunntt = new Account();
    $count =1;
         foreach ($ikg_info->scheduled_routes as $ekc ){  
            $inches = "";
            $sched_ro = new Scheduled_Routes($ekc);
            $request_inches = $db->where('route_id',$_GET['ikg'])->where('schedule_id',$sched_ro->schedule_id)->where('account_no',$sched_ro->account_number)->get($dbprefix.'_data_table','inches_to_gallons,inches_entered');
            
            $html .= "<tr>";
                $html .= "<td style='border:1px solid black;padding:0px 0px 0px 0px;width:9px;'>$count</td>";
                $html .= "<td style='border:1px solid black;padding:0px 0px 0px 0px;width:300px;'>$sched_ro->account_name</td>";
                
                $html .= "<td style='border:1px solid black;padding:0px 0px 0px 0px;width:260px;'>".$sched_ro->account_number."</td>";
                $html .= "<td style='border:1px solid black;padding:0px 0px 0px 0px;width:10%'>".$aaccoouunntt->singleField($sched_ro->account_number,"address")."</td>";
                $html .= "<td style='border:1px solid black;padding:0px 0px 0px 0px;width:10%'>".$aaccoouunntt->singleField($sched_ro->account_number,"city")."</td>";  
                $html .= "<td style='border:1px solid black;padding:0px 0px 0px 0px;width:5%'>".$aaccoouunntt->singleField($sched_ro->account_number,"zip")."</td>";   
               
                $html .= "<td  style='border:1px solid black;padding:0px 0px 0px 0px;'>";  
                    $get = $db->query("SELECT $containerslist.* , $accounts_container.* FROM `$containerslist` INNER JOIN $accounts_container ON $containerslist.container_id = $accounts_container.container_no WHERE $accounts_container.account_no =".$sched_ro->account_number);
                    if(count($get)>0){
                         foreach($get as $info){
                            $html .= $info['amount_holds']."</br>";
                         }    
                    }
                $html .="</td>";
                $html .= "<td  style='border:1px solid black;padding:0px 0px 0px 0px;width:10%'> "; 
                
                if(count($request_inches)>0){
                    foreach($request_inches as $entered){
                        $html .= $entered['inches_entered']."<br/>";
                    }
                }
                else {
                    $html .= "&nbsp;";
                }
                $html .="</td>";
                $html .= "<td style='border:1px solid black;padding:0px 0px 0px 0px;width:40px'>";
                    if(count($request_inches)>0){
                       foreach($request_inches as $inches){
                         $html .= round($inches['inches_to_gallons'],2)."<br/>";
                       }
                    }
                    else {
                        $html .= "&nbsp;";
                    }
                $html .= "</td>";
                
                 $html .= "<td style='border:1px solid black;padding:0px 0px 0px 0px;width:150px;'>"; 
                 $bh = $db->query("SELECT fieldreport FROM freight_data_table WHERE route_id=$ikg_info->route_id AND schedule_id=$sched_ro->schedule_id AND account_no = $sched_ro->account_number");
                 
                 if(count($bh)>0){
                    $html .= $bh[0]['fieldreport'];
                 } else {
                     $html .="&nbsp;";
                 } 
                 $html .="</td>";    
            $html .= "</tr>";
            $count++;
        }
        
    
    
    $html .='</tbody>
    </table></div>';
    echo $html;    
    

?>
    
    
   