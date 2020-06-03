<?php
include "plugins/phpToPDF/phpToPDF.php";
include "protected/global.php";
ini_set("display_errors",0);
//echo $_GET['route_no'];
$person = new Person();
$grease_ikg = new Grease_IKG($_GET['route_no']);
//var_dump($grease_ikg);


 $logo="https://inet.iwpusa.com/img/cwlogo_line.jpg";

$route_copy = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>IKG MANIFEST RECIEPT</title>
<style type="text/css">

@page{
    size: 8.5in 14in;
    margin: 1in;
} 
html,body{
    height:100%;
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
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





$trap_service_cost = "";
$g_stop = new Grease_Stop($_GET['schedule']);
$ant = new Account($g_stop->account_number);                

    
 $route_copy .= "<table style='height:20px;width:100%;border:0px solid #bbb;'><tr><td>&nbsp;</td></tr></table><table style='width:99%;table-layout:fixed;font-size:13px;display:in-line;' id='myTable'><tr><td colspan='4' style='border:0px solid #000000;text-align:center;padding:0px 0px 0px 0px;height:83px;background:url($logo) no-repeat center;background-size:203px 83px'>&nbsp;<br/><br/><br/><b>NON-HAZARDOUS WASTE MANIFEST</b></td></tr><tr><td style='border:1px solid #000000;text-align:left;width:50%;vertical-align:top;' colspan='2'><b>Generator Name</b><br/>IWP</td><td style='border:1px solid #000000;text-align:left;width:50%;vertical-align:top;' colspan='2'><b>Date</b><br/>$grease_ikg->scheduled_date</td></tr><tr><td style='border:1px solid #000000;text-align:left;width:50%;' colspan='2'><b>Address</b>:<br/>4085 Bain St Mira Loma CA 91752</td><td  style='border:1px solid #000000;text-align:left;width:50%;vertical-align'top;' colspan='2'><b>Phone Number</b>:<br/>$ant->area_code.$ant->phone</td></tr><tr><td colspan='2'  style='border:1px solid #000000;text-align:left;width:50%;vertical-align:top;'><b>Waste Type</b>:<br/>&nbsp;Grease Trap<br /><input type='checkbox'/>&nbsp;Non-Industrial</td><td colspan='2' style='border:1px solid #000000;text-align:left;width:50%;vertical-align:top;'><b>Waste Type</b>:<br/>&nbsp;High Moisture Content<br /><input type='checkbox'/>&nbsp;Industrial special</td></tr><tr><td colspan='4' style='border:1px solid #000000;text-align:left;' colspan='2'><b>Generator Cerftification</b>: <br/>I do certify that the waste material provided by the above generator does not contain any radioacive, flammable, explosive, toxic substance, hazardous substance, solvent or oil as defined in or pursuant to the Resource Conservation and Recovery Act, the Comprehensive Environmental Response Compenation and Liability Act, the Federal Clean Water Act, or any other federal, state or local environmental law, regulation, ordanance, or rule, whether existing as of the date of this agreement or subsequently enacted.  I also acknowledge that the Generator shall be responsible for any cost incurred by the Transporter or Disposal Facility in handling or proper disposal of any hazardous waste and that the Generator expressly agrees to defend, identify and hold harmless the transporter from and against any and all damages, costs, fines and liabilities resulting resulting from or arising out of any such hazardous waste.</td></tr><tr><td  colspan='2' style='border:1px solid #000000;text-align:left;width:50%;'><b>Generator Rep. Name:</b>(Please Print)<br/>&nbsp;</td><td colspan='2'  style='border:1px solid #000000;text-align:left;width:50%;'><b>Generator Rep. Signature</b><br/>&nbsp;</td></tr><tr><td style='border:1px solid #000000;text-align:left;width:75%;' colspan='3'><b>Transporter Name</b>:<br/>CO-WEST COMMODITIES ".$facils[$grease_ikg->recieving_facility_no]."</td><td style='border:1px solid #000000;text-align:left;width:25%;'><b>Phone Number</b>:<br/>&nbsp;909-887-4309</td></tr><tr><td colspan='2'  style='border:1px solid #000000;text-align:left;width:50%;vertical-align:top;'><b>Waste Quantity Removed</b><br />(Gallons)<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$ant->grease_volume&nbsp;GLS.</td><td style='border:1px solid #000000;text-align:left;width:25%;vertical-align:top;'><b>Date</b>:<br/>$grease_ikg->scheduled_date</td><td   style='border:1px solid #000000;text-align:left;width:25%;vertical-align:top;'>&nbsp;</td></tr><td colspan='4' style='border:1px solid #000000;text-align:left;' colspan='2'>I certify that the information above is accurate, and that only the waste certified for removal by the Generator is contained in the serving vehicle.  I am aware that falsification of this manifest may result in prosecution.</td><tr><td style='border:1px solid #000000;text-align:left;width:25%;vertical-align:top;'><b>Driver Name</b><br />(Please Print)</td><td style='border:1px solid #000000;text-align:left;width:25%;vertical-align:bottom;'>$grease_ikg->driver</td><td style='border:1px solid #000000;text-align:left;width:25%;vertical-align:top;'><b>Driver Signature</b></td> <td style='border:1px solid #000000;text-align:left;width:25%;vertical-align:top;'>&nbsp;</td></tr><tr><td style='border:1px solid #000000;text-align:left;width:25%;vertical-align:top;'><b>Disposal Facility</b>:$grease_ikg->recieving_facility<br />Address: ".$facils[$grease_ikg->recieving_facility_no]."</td><td colspan='3'  style='border:1px solid #000000;text-align:left;width:75%;vertical-align:top;'>CO-WEST COMMODITIES&nbsp;&nbsp;&nbsp;&nbsp;(909) 887-4309<br />".$facils[$grease_ikg->recieving_facility_no]."</td></tr><tr><td style='border:1px solid #000000;text-align:left;width:25%;vertical-align:top;'><b>Waste Quantity Recieved</b><br />(Gallons)</b></td><td style='border:1px solid #000000;text-align:left;width:25%;vertical-align:top;'>&nbsp;</td><td style='border:1px solid #000000;text-align:left;width:25%;vertical-align:top;'><b>Date</b><br/>$grease_ikg->scheduled_date</td><td style='border:1px solid #000000;text-align:left;width:25%;vertical-align:top;'>&nbsp;</td></tr><tr><td style='border:1px solid #000000;text-align:left;width:20%;vertical-align:top;'><b>Facility Rep. Name</b><br />(Please Print)</b></td><td  style='border:1px solid #000000;text-align:left;width:20%;vertical-align:top;'>&nbsp;</td><td  colspan='2' style='border:1px solid #000000;text-align:left;width:60%;vertical-align:top;'><b>Facility Rep. Signature</b></b></td></tr></table><div class=\"phpToPDF-page-break\"></div></body></html>";
$pdf_options = array(//save individual receipt to account folder

  "source_type" => 'html',
  "source" => $route_copy,
  "action" => 'save',
  "save_directory" => "reciepts/",
  "page_orientation" => 'landscape',
  "file_name" => "$g_stop->grease_no.pdf",
  "page_size" => 'legal',
  "margin"=>array("right"=>"2","left"=>'2',"top"=>"2","bottom"=>"2")
);
phptopdf($pdf_options);       
             


$filename = "$g_stop->grease_no.pdf";


header("Content-disposition: attachment; filename=$filename");
header("Content-type: application/pdf");
readfile("reciepts/".$filename);;

$track = array(
    "date"=>date("Y-m-d H:i:s"),
    "user"=>$person->user_id,
    "actionType"=>"Receipt Downloaded",
    "descript"=>"Grease Receipt Downloaded for grease route $_GET[route_no]",
    "pertains"=>6
);
$db->insert("xlogs.".$dbprefix."_activity",$track);  
/*
echo $route_copy;
*/

?>