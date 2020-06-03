<?php
include "plugins/phpToPDF/phpToPDF.php";
include "protected/global.php";


$route = $db->where("route_id",$_GET['route_no'])->get($dbprefix."_ikg_manifest_info");
$accounts_container = $dbprefix."_containers";
$containerslist = $dbprefix."_list_of_containers";
//ini_set('display_errors',1); 
 //error_reporting(E_ALL);
$ikg_info = new IKG($_GET['route_no']);
  
$ikg = $ikg_info->ikg_manifest_route_number;
$driver = $ikg_info->driver_no; 
$facil =  numberToFacility($ikg_info->recieving_facility);



$html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>IKG MANIFEST RECIEPT</title>
<style type="text/css">

@page{
    size: 8.5in 11in;
    margin: 0.5in;
} 
html,body{
    height:100%;
margin:0px 0px 0px 0px;
}

input[type="text"]{
    border:0px solid #bbb;
    
}

table td{
 border:1px solid black;
}

body {
  font-family:Tahoma;
}

img {
  border:0;
}

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


</style>
</head><body>';


if( count($ikg_info->scheduled_routes)>0){
    $acc = new Account();
    foreach($ikg_info->scheduled_routes as $sched_num){
        $schedules = new Scheduled_Routes($sched_num);
        $rep = $acc->singleField($schedules->account_number,"contact_name");
        $address = $acc->singleField($schedules->account_number,"address");
        $exploded = explode(" ",$schedules->scheduled_start_date);
        $city = $acc->singleField($schedules->account_number,"city");
        $state = $acc->singleField($schedules->account_number,"state");
        
        $html .='<table style="width:45%;height:100%;float:left;margin-left:3%;" class="myTable">
              <tr><td style="vertical-align:center;text-align:right;border:1px solid black;" colspan="2"><img src="https://inet.iwpusa.com/img/blogo.jpg"/></td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Date</td><td style="vertical-align:top;text-align:left;border:1px solid black;">'.$ikg_info->scheduled_date.' </td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Manifest</td><td style="border:1px solid black;">'.$ikg_info->route_id.'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Driver\'s Name</td><td style="border:1px solid black;">'.uNumToName($driver).'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Driver\'s Signature</td><td style="border:1px solid black;"></td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Recieving Facility</td style="border:1px solid black;"><td style="border:1px solid black;">'. $facil .'<br/>'; 
              $html .= $facils[$route[0]['recieving_facility']];
              $html .='</td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Restaurant Name</td><td style="border:1px solid black;">'. account_NumToName($schedules->account_number). '</td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Restaurant Rep</td><td style="border:1px solid black;">'.$rep.'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Restaurant Rep Signature</td><td style="border:1px solid black;"></td></tr>
              <tr><td>Time:&nbsp;</td><td style="text-align:right;vertical-align:bottom;border:1px solid black;"><input type="checkbox"/> Generator not available for Signature</td></tr>
              <tr><td colspan="2"  style="vertical-align:top;text-align:left;border:1px solid black;">Address: '.$address.' '. $city.', '.$state.'</td></tr>
              <tr><td colspan="2"  style="vertical-align:top;text-align:left;height:100px;border:1px solid black;">Comments: ';  $html .=$schedules->notes."<br/>".$schedules->special_instructions; $html .=' </td></tr>
              <tr><td colspan="2" style="text-align:center;border:1px solid black;">'; 
                    $get = $db->query("SELECT DISTINCT (container_no), count( * ) AS num_of_barrel, account_no FROM freight_containers WHERE account_no =$schedules->account_number GROUP BY account_no");
                    if(count($get)>0){
                        $html .= "<table style='width:100%;'>";
                        foreach($get as $container){
                            $html .= "<tr><td>$container[num_of_barrel])</td><td>".containerNumToName($container['container_no'])." GPI ".round(gpi($container['container_no']),2)."</td></tr>";
                        }
                        $html .="<tr><td>Collected</td><td>";  
                        $ty = $db->query("SELECT SUM(inches_to_gallons) as s FROM freight_data_table WHERE account_no=$schedules->account_number AND route_id= $ikg_info->route_id AND schedule_id= $schedules->schedule_id");
                        
                         if( $ty[0]['s'] == 0){
                            $sum = "";
                        } else {
                            $sum = $ty[0]['s'];
                        }
                        
                        
                        $html .= $sum;
                        $html .="</td>";
                        $html .= "</table>";
                    }
                    else {
                        $html .= "&nbsp;";
                    }
              $html .= '</td></tr>
              </table> 
               <table style="width:45%;height:100%;float:left;margin-left:3%;" class="myTable">
              <tr><td style="vertical-align:center;text-align:right;border:1px solid black;" colspan="2"><img src="https://inet.iwpusa.com/img/blogo.jpg"/></td></tr>
              
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Date </td><td style="vertical-align:top;text-align:left;border:1px solid black;">'.$ikg_info->scheduled_date.' </td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Manifest</td><td style="border:1px solid black;">'.$ikg_info->route_id.'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Driver\'s Name</td><td style="border:1px solid black;">'.uNumToName($driver).'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Driver\'s Signature</td><td style="border:1px solid black;"></td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Recieving Facility</td><td style="border:1px solid black;">'. $facil .'<br/>'; 
              $html .= $facils[$route[0]['recieving_facility']];
              $html .='</td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Restaurant Name</td><td style="border:1px solid black;">'. account_NumToName($schedules->account_number).'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Restaurant Rep</td><td style="border:1px solid black;">'.$rep.'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Restaurant Rep Signature</td><td style="border:1px solid black;"></td></tr>
              <tr><td>Time:&nbsp;</td><td  style="text-align:right;vertical-align:bottom;border:1px solid black;"><input type="checkbox"/> Generator not available for Signature</td></tr>
              <tr><td colspan="2"  style="vertical-align:top;text-align:left;border:1px solid black;">Address: '.$address.' '. $city.', '.$state.'</td></tr>
              <tr><td colspan="2"  style="vertical-align:top;text-align:left;height:100px;border:1px solid black;">Comments: ';  $html .=$schedules->notes."<br/>".$schedules->special_instructions; 
               $html .="\r\n".$acc->singleField($schedules->account_number,"notes");
              
              $html .='</td></tr>
              <tr><td colspan="2" style="text-align:center;border:1px solid black;">'; 
                    $get = $db->query("SELECT DISTINCT (container_no), count( * ) AS num_of_barrel, account_no FROM freight_containers WHERE account_no =$schedules->account_number GROUP BY account_no");
                    if(count($get)>0){
                        $html .= "<table style='width:100%;'>";
                        foreach($get as $container){
                            $html .= "<tr><td>$container[num_of_barrel])</td><td>".containerNumToName($container['container_no'])." GPI ".round(gpi($container['container_no']),2)."</td></tr>";
                        }
                         $html .="<tr><td>Collected</td><td>";  
                        $ty = $db->query("SELECT SUM(inches_to_gallons) as s FROM freight_data_table WHERE account_no=$schedules->account_number AND route_id= $ikg_info->route_id AND schedule_id= $schedules->schedule_id");
                        
                         if( $ty[0]['s'] == 0){
                            $sum = "";
                        } else {
                            $sum = $ty[0]['s'];
                        }
                        $html .= $sum;
                        $html .="</td>";
                        $html .= "</table>";
                    }
                    else {
                        $html .= "&nbsp;";
                    }
              $html .= '</td></tr>
              </table>
                <div id="break" style="page-break-after:always;width:100%;">.</div>         
            ';        
    }  
}
$html .= '</body></html>';
//echo $html;

$new_string = "IKG_RECIEIPT-$ikg_info->ikg_manifest_route_number".date("Ymd_his");
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
phptopdf($pdf_options);
 
$path = $new_string.".pdf";
$filename = $new_string.".pdf";
header("Content-disposition: attachment; filename=".$filename);
header("Content-type: application/pdf");
readfile($filename);

$track = array(
    "date"=>date("Y-m-d H:i:s"),
    "user"=>$person->user_id,
    "actionType"=>"Receipt Downloaded",
    "descript"=>"Receipt Downloaded for route $ikg",
    "pertains"=>6
);
$db->insert($dbprefix."_activity",$track);    
/**/
?>
    
    
   
