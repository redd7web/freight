<?php
include "plugins/phpToPDF/phpToPDF.php";
include "protected/global.php";


if(isset($_POST['export_now'])){
    $account = new Account();
   
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
    
  
    
    
    $data_table = $dbprefix."_grease_data_table";
    $gv = $db->query("SELECT $data_table.*,sludge_ikg_grease.ikg_manifest_route_number FROM $data_table LEFT JOIN sludge_ikg_grease ON $data_table.route_id = sludge_ikg_grease.route_id WHERE (date_of_pickup >= '$_POST[from]'  && date_of_pickup <= '$_POST[to]') && account_no=$_POST[account_no]");
    
    
    
    $html .= "<table>
        <tr><td colspan='5' style='text-align:center;font-weight:bold;'>Biotane Pumping <br/>".date("F d, Y") ."</td></tr>
        <tr><td colspan='3'>".$account->singleField($_POST['account_no'],"name")."<br/>" .$account->singleField($_POST['account_no'],"address")."<br/>".$account->singleField($_POST['account_no'],"city").",". $account->singleField($_POST['account_no'],"state")."</td></tr>
        <tr><td colspan='4'>Dear Valued Customer:<br/>This report shows grease trap collections collections from your location(s) for the dates shown. Thank you for your business.  <br/></td></tr>
        <tr><td colspan='5' style='text-align:center;font-weight:bold;'>The Management of Biotane Pumping</td></tr>
        <tr><td colspan='5' style='text-align:center;>Used/Waste Cooking Oil Collections from 2012-08-01 to 2013-12-31 in Gallons</td></tr>
    
    <tr><td colspan='5' style='text-align:center;'>Used/Waste Cooking Oil Collections from $_POST[from] to $_POST[to] in Gallons</td></tr>
    <tr><td>#</td><td>Date</td><td>Est Grease Trap Recycled</td><td>Route ID</td><td>Ikg Manifest Title</td></tr>
    ";        
    if(count($gv)>0){
        $alter=0;
        $tot_adj= 0;
        foreach($gv as $gvo){
            
            $adj=1;                        
          
            $tot_adj +=$gvo['inches_to_gallons'];       
            $alter++;
            if($alter%2 == 0){
                $bg = '-moz-linear-gradient(center top , #F7F7F9, #E5E5E7) repeat scroll 0 0 rgba(0, 0, 0, 0)';
            }
            else { 
                $bg = 'transparent';
            }
            $html .= "
                <tr style='background:$bg'>                    
                    <td>$alter</td>                    
                    <td>$gvo[date_of_pickup]</td>
                    <td>$gvo[inches_to_gallons]</td>
                   <td>$gvo[route_id]</td>    
                   <td>$gvo[ikg_manifest_route_number]</td>             
                </tr>";
        }
        $html .= "<tr><td>&nbsp;</td><td>Total: </td><td>$tot_adj</td></tr>
        <tr><td colspan='4' style='text-align:center;'>Not Sure what you want as a footer</td></tr>
        ";
    }    
    
    $html .="</table></body></html>";

    $fnak = date('Y-m-d_H-i-s');
    $name_without_spaces = str_replace(' ','-',$account->singleField($_POST['account_no'],"name"));
    $filename = $fnak.'-'.$name_without_spaces.'_exported_completed_routes.pdf';
    phptopdf_html($html,'', $filename);
   
    header("Content-disposition: attachment; filename=".$filename);
    header("Content-type: application/pdf");
    readfile("$filename");      
 
}



?>
