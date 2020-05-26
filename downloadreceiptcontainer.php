<?php
include "plugins/phpToPDF/phpToPDF.php";
include "protected/global.php";
$accounts_container = $dbprefix."_containers";
$containerslist = $dbprefix."_list_of_containers";
//echo $_GET['route_no'];
$scheds = $db->where("rout_no",$_GET['route_no'])->get($dbprefix."_utility"); 
$route = $db->where("route_id",$_GET['route_no'])->get($dbprefix."_ikg_utility");


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
</head><body>
';

if(count($route)>0){
    $ikg = $route[0]['ikg_manifest_route_number'];
    $driver = $route[0]['driver']; 
    $facil =  numberToFacility($route[0]['recieving_facility']); 
}



if(count($scheds)>0){
    $acc = new Account();
    foreach($scheds as $acnts){
        $rep = $acc->singleField($acnts['account_no'],"contact_name");
        $address = $acc->singleField($acnts['account_no'],"address");
        $exploded = $acnts['date_of_service'];
        $city = $acc->singleField($acnts['account_no'],"city");
        $state = $acc->singleField($acnts['account_no'],"state");
        $html .='<table style="width:45%;height:100%;float:left;margin-left:2%;" class="myTable">
              <tr><td style="vertical-align:center;text-align:right;" colspan="2"><img src="https://inet.iwpusa.com/img/blogo.jpg"/></td></tr>
              
              <tr><td style="vertical-align:top;text-align:left;">Date: '.date("Y-m-d").'</td><td style="vertical-align:top;text-align:left;">Time: '.date("H:i:s").' </td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Manifest</td><td>'.$ikg.'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Driver\'s Name</td><td>'.uNumToName($driver).'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Driver\'s Signature</td><td></td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Recieving Facility</td><td>'. $facil .'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Restaurant Name</td><td>'. account_NumToName($acnts['account_no']). '</td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Restaurant Rep</td><td>'.$rep.'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Restaurant Rep Signature</td><td></td></tr>
              <tr><td colspan="2" style="text-align:right;vertical-align:bottom;"><input type="checkbox"/> Generator not available for Signature</td></tr>
              <tr><td colspan="2"  style="vertical-align:top;text-align:left;">Address: '.$address.' '. $city.', '.$state.'</td></tr>
              <tr><td colspan="2"  style="vertical-align:top;text-align:left;height:200px;">Comments: </td></tr>
              <tr><td colspan="2" style="text-align:center;">'; 
                    
                     $html .= "<table style='width:100%;'>";
                     
                     $html .="<tr><td>".containerNumToName($acnts['container_label'])."</td></tr>";
                    $html  .="</table>";
              $html .= '</td></tr>
              </table>';
              $html .='<table style="width:45%;height:100%;float:left;margin-left:2%;" class="myTable">
              <tr><td style="vertical-align:center;text-align:right;" colspan="2"><img src="https://inet.iwpusa.com/img/blogo.jpg"/></td></tr>
              
              <tr><td style="vertical-align:top;text-align:left;">Date: '.date("Y-m-d").'</td><td style="vertical-align:top;text-align:left;">Time: '.date("H:i:s").' </td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Manifest</td><td>'.$ikg.'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Driver\'s Name</td><td>'.uNumToName($driver).'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Driver\'s Signature</td><td></td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Recieving Facility</td><td>'. $facil .'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Restaurant Name</td><td>'. account_NumToName($acnts['account_no']). '</td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Restaurant Rep</td><td>'.$rep.'</td></tr>
              <tr><td style="vertical-align:top;text-align:left;">Restaurant Rep Signature</td><td></td></tr>
              <tr><td colspan="2" style="text-align:right;vertical-align:bottom;"><input type="checkbox"/> Generator not available for Signature</td></tr>
              <tr><td colspan="2"  style="vertical-align:top;text-align:left;">Address: '.$address.' '. $city.', '.$state.'</td></tr>
              <tr><td colspan="2"  style="vertical-align:top;text-align:left;height:200px;">Comments:'; 
                $html .="\r\n".$acc->singleField($acnts['account_no'],"notes");
              
              $html .='</td></tr>
              <tr><td colspan="2" style="text-align:center;">'; 
                    
                     $html .= "<table style='width:100%;'>";
                     
                     $html .="<tr><td>".containerNumToName($acnts['container_label'])."</td></tr>";
                    $html  .="</table>";
              $html .= '</td></tr>
              </table>';
              
              $html .='<div id="break" style="page-break-after:always;width:100%;">.</div> ';        
    }  
}

$html .= '
</body>
</html>';

//echo $html;


$new_string = "IKG_UTILITY_RECIEIPT".date("Ymd_his");

$pdf_options = array(
  "source_type" => 'html',
  "source" => $html,
  "action" => 'save',
  "save_directory" => '',
  "page_orientation" => 'landscape',
  "file_name" => $new_string.'.pdf',
  "page_size" => 'legal'
);
//var_dump($pdf_options);

phptopdf($pdf_options);

$path = $new_string.".pdf";
$filename = $new_string.".pdf";
header("Content-disposition: attachment; filename=".$filename);
header("Content-type: application/pdf");
readfile($filename);

?>