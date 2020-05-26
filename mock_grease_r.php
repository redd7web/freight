<?php
include "plugins/phpToPDF/phpToPDF.php";
include "protected/global.php";

//echo $_GET['route_no'];





$grease_ikg = new Grease_IKG($_GET['route_no']);
//var_dump($grease_ikg);

 
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
if(count($grease_ikg->scheduled_routes)>0){
        $acc = new Account();
        foreach($grease_ikg->scheduled_routes as $grease){
            $g_stop = new Grease_Stop($grease);
            $rep = $acc->singleField($g_stop->account_number,"contact_name");
            $address = $acc->singleField($g_stop->account_number,"address");
            $exploded = explode(" ",$grease['date']);
            $city = $acc->singleField($g_stop->account_number,"city");
            $state = $acc->singleField($g_stop->account_number,"state");
            $html .= '<table style="width:45%;height:100%;float:left;margin-left:3%;" class="myTable">
                      <tr><td style="vertical-align:center;text-align:right;border:1px solid black;" colspan="2"><img src="http://www.datastormdesigns.com/biotane/img/blogo.jpg"/></td></tr>                      
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Date</td><td style="vertical-align:top;text-align:left;border:1px solid black;">'.date("Y-m-d").' </td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Manifest</td><td style="border:1px solid black;">'.$grease_ikg->ikg_manifest_route_number.'</td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Driver\'s Name</td><td style="border:1px solid black;">'.uNumToName($grease_ikg->driver_no).'</td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Driver\'s Signature</td><td style="border:1px solid black;">&nbsp;</td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Recieving Facility</td style="border:1px solid black;"><td style="border:1px solid black;vertical-align:top;">'. $facils[$grease_ikg->recieving_facility_no] .'<br/>'; 
                      $html .= $grease_ikg->recieving_facility;
                      $html .='</td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Restaurant Name</td><td style="border:1px solid black;">'. account_NumToName($g_stop->account_number). '</td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Restaurant Rep</td><td style="border:1px solid black;">'.$rep.'</td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Restaurant Rep Signature</td><td style="border:1px solid black;">&nbsp;</td></tr>
                      <tr><td colspan="2" style="text-align:right;vertical-align:bottom;border:1px solid black;"><input type="checkbox"/> Generator not available for Signature</td></tr>
                      <tr><td colspan="2"  style="vertical-align:top;text-align:left;border:1px solid black;">Address: '.$address.' '. $city.', '.$state.'</td></tr>
                      <tr><td style="border:1px solid black;vertical-align:top;">Grease Trap Size</td><td style="vertical-align:top;text-align:left;border:1px solid black;">'.$g_stop->volume.'</td></tr>     
                      <tr><td colspan="2"  style="vertical-align:top;text-align:left;height:100px;border:1px solid black;">Comments: </td></tr></table>';
                      
                                            
                       $html .= '<table style="width:45%;height:100%;float:left;margin-left:3%;" class="myTable">
                      <tr><td style="vertical-align:center;text-align:right;border:1px solid black;" colspan="2"><img src="http://www.datastormdesigns.com/biotane/img/blogo.jpg"/></td></tr>                      
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Date</td><td style="vertical-align:top;text-align:left;border:1px solid black;">'.date("Y-m-d").' </td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Manifest</td><td style="border:1px solid black;">'.$grease_ikg->ikg_manifest_route_number.'</td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Driver\'s Name</td><td style="border:1px solid black;">'.uNumToName($grease_ikg->driver_no).'</td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Driver\'s Signature</td><td style="border:1px solid black;">&nbsp;</td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Recieving Facility</td style="border:1px solid black;"><td style="border:1px solid black;vertical-align:top;">'. $facils[$grease_ikg->recieving_facility_no] .'<br/>'; 
                      $html .= $grease_ikg->recieving_facility;
                      $html .='</td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Restaurant Name</td><td style="border:1px solid black;">'. account_NumToName($g_stop->account_number). '</td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Restaurant Rep</td><td style="border:1px solid black;">'.$rep.'</td></tr>
                      <tr><td style="vertical-align:top;text-align:left;border:1px solid black;">Restaurant Rep Signature</td><td style="border:1px solid black;">&nbsp;</td></tr>
                      <tr><td colspan="2" style="text-align:right;vertical-align:bottom;border:1px solid black;"><input type="checkbox"/> Generator not available for Signature</td></tr>
                      <tr><td colspan="2"  style="vertical-align:top;text-align:left;border:1px solid black;">Address: '.$address.' '. $city.', '.$state.'</td></tr>
                      <tr><td style="border:1px solid black;vertical-align:top;">Grease Trap Size</td><td style="vertical-align:top;text-align:left;border:1px solid black;">'.$g_stop->volume.'</td></tr>     
                      <tr><td colspan="2"  style="vertical-align:top;text-align:left;height:100px;border:1px solid black;">Comments: </td></tr></table>';
                      echo '<div id="break" style="page-break-after:always;width:100%;">.</div>';
        }
}            
$html .='</body></html>';              
echo $html;


$new_string = "IKG_GREASE_RECIEIPT-$grease_ikg->ikg_manifest_route_number".date("Ymd_his");
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
/**/
//phptopdf($pdf_options);

$path = $new_string.".pdf";
$filename = $new_string.".pdf";
/*
header("Content-disposition: attachment; filename=".$filename);
header("Content-type: application/pdf");
readfile($filename);
*/
$track = array(
    "date"=>date("Y-m-d H:i:s"),
    "user"=>$person->user_id,
    "actionType"=>"Receipt Downloaded",
    "descript"=>"Grease Receipt Downloaded for grease route $_GET[route_no]",
    "pertains"=>6
);
$db->insert($dbprefix."_activity",$track);  

?>