<?php
include "plugins/phpToPDF/phpToPDF.php";
include "protected/global.php";
ini_set("display_errors",0);

if(isset($_POST['export_now'])){
    $account = new Account($_POST['account_no']);
    $bio="Co-West";
    $string = "";
    $title = "";
    foreach($_POST as $name=>$value){
        switch($name){
            case "from":
                if(strlen($value)>0 && $value !=''){
                    $arrField[] = "date_of_pickup >= '$value'";
                    $title .= "From $_POST[from]";
                }
            break;
            
            case "to":
                if(strlen($value)>0 && $value !=''){
                    $arrField[] = "date_of_pickup <= '$value'";
                    $title .="To $_POST[to]";
                }
            break;
        }
    }
    
    if(!empty($arrField)){
        $string = " AND ".implode(" AND ",$arrField);
    }
    //echo "string: $string <br/><br/>";
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

table {
  width:100%;
  border:0px solid #bbb;
}

td {
padding:5px;
border: 0px solid #bbb;
}

tr.odd {
  background:#e1ffe1;
}
-->
</style>
</head>
<body>
';
    
  
    $gv = $db->query("SELECT 
                        sludge_grease_data_table.*, 
                        sludge_ikg_grease.conductivity,
                        sludge_ikg_grease.percent_fluid,
                        sludge_ikg_grease.ikg_manifest_route_number,
                        COALESCE(ap_form_28181.element_48,NULL,0) as cs_total,
                        COALESCE(ap_form_28181.element_5,NULL,0) as cs_hours FROM sludge_grease_data_table
                LEFT JOIN Inetforms.ap_form_29942 ON 
                            sludge_grease_data_table.route_id = ap_form_29942.element_2 AND 
                            sludge_grease_data_table.schedule_id =  ap_form_29942.element_3 AND
                            sludge_grease_data_table.account_no =  ap_form_29942.element_1 
                LEFT JOIN Inetforms.ap_form_28181 ON  
                           sludge_grease_data_table.route_id = ap_form_28181.element_50 AND 
                           sludge_grease_data_table.schedule_id =  ap_form_28181.element_51 AND
                           sludge_grease_data_table.account_no =  ap_form_28181.element_52 
                LEFT JOIN sludge_ikg_grease ON
                            sludge_grease_data_table.route_id = sludge_ikg_grease.route_id 
                WHERE account_no= $_POST[account_no] OR facility_origin = $_POST[account_no] $string");
    
    
    
    $html .= "<table style='width:100%;'>
        <tr><td colspan='5' style='text-align:center;font-weight:bold;'>$bio Commodities <br/>".date("F d, Y") ."</td></tr>
        <tr><td colspan='3'>".$account->singleField($_POST['account_no'],"name")."<br/>New Bos #:".$account->new_bos."<br/>" .$account->singleField($_POST['account_no'],"address")."<br/>".$account->singleField($_POST['account_no'],"city").",". $account->singleField($_POST['account_no'],"state")."</td></tr>
        <tr><td colspan='7'>Dear Valued Customer:,<br/>This report shows sludge from your location(s) for the dates shown. Thank you for your business.  <br/></td></tr>
        <tr><td colspan='7' style='text-align:center;font-weight:bold;'>The Management of $bio Commodities</td></tr>
        <tr><td colspan='7' style='text-align:center;>Sludge services  $title</td></tr>
    
    <tr><td colspan='7' style='text-align:center;'>Sludge services from $_POST[from] to $_POST[to] in Gallons</td></tr>
    
    <tr>
        <td>#</td>
        <td>Date</td>
        <td>Volume Pumped</td>
        <td>Confined Space Hours</td>
        <td>Confined Space Charge</td>
        <td>Route Id</td>
        <td>Ikg Manifest Title</td>
        <td>Arrival/Removal</td>
        <td>Conductivity</td>
        <td>% Solids</td>
    </tr>
    ";        
    if(count($gv)>0){
        //print_r($gv);
        
        $alter=0;
        $tot_adj= 0;
        foreach($gv as $gvo){
            
            if($gvo['account_no']== $_POST['account_no']){
                $ax = "Removal";
            }else if($gvo['facility_origin'] == $_POST['account_no']){
                $ax = "Arrival";
            }
           
            $html .= "<tr><td>$gvo[route_id]</td>
                      <td>$gvo[date_of_pickup]</td>
                      <td>$gvo[inches_to_gallons]</td>
                      <td>$gvo[cs_hours]</td>
                      <td>$gvo[cs_total]</td>
                      <td>$gvo[route_id]</td>
                      <td>$gvo[ikg_manifest_route_number]</td>
                      <td>$ax</td>
                      <td>$gvo[conductivity]</td>
                      <td>$gvo[percent_fluid]</td>
                      </tr>";
        }
       
    }    
    
    $html .="</table></body></html>";
    
  
    
    $fnak = date('Y-m-d_H-i-s');
    $name_without_spaces = str_replace(' ','-',$account->singleField($_POST['account_no'],"name"));
    $filename = $fnak.'-'.$name_without_spaces.'_exported_completed_stops.pdf';
    $pdf_options = array(
      "source_type" => 'html',
      "source" => $html,
      "action" => 'save',
      "save_directory" => '',
      "page_orientation" => 'landscape',
      "file_name" => $filename.'.pdf',
      "page_size" => 'legal'
    );
    phptopdf($pdf_options);
 
    $path = $new_string.".pdf";
    $filename = $new_string.".pdf";
    
    header("Content-disposition: attachment; filename=".$filename);
    header("Content-type: application/pdf");
    readfile($filename);
   
       /*echo $html;*/
}



?>