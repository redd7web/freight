<?php include "protected/global.php"; $page = "Management | View Vehicle"; if(isset($_SESSION['freight_id'])){  
    //ini_set('display_errors',1); 
    //error_reporting(E_ALL);
    
    
    if(isset($_POST['sc'])){
        $new_year = explode("/",$_POST['year']);
        if(empty($new_year)){
            $new_year = array(
                0=>"00",
                1=>"00",
                2=>"0000"
            );
        }
        $new_renew = explode("/",$_POST['lic_renewed']);
        if(empty($new_renew)){
            $new_renew = array(
                0=>"00",
                1=>"00",
                2=>"0000"
            );
        }
        $new_end = explode("/",$_POST['end_date']);
        if(empty($new_end)){
            $new_end = array(
                0=>"00",
                1=>"00",
                2=>"0000"
            );
        }
        
        $new_acq = explode("/",$_POST['date_acq']);
        if(empty($new_acq)){
            $new_acq = array(
                0=>"00",
                1=>"00",
                2=>"0000"
            );
        }
        $package = array(
            "truck_year"=>"$new_year[2]-$new_year[1]-$new_year[0]",
            "truck_model"=>$_POST['model'],
            "truck_make"=>$_POST['make'],            
            "plates"=>$_POST['lic_num'],
            "ikg_decal"=>$_POST['ikg_code'],
            "max_capacity"=>$_POST['capac'],
            "start_mileage"=>$_POST['milatpurch'],
            "current_mileage"=>$_POST['mileage'],
            "mpg"=>$_POST['mpg'],
            "facility"=>$_POST['facility'],            
            "status"=>$_POST['vehicle_status_id'],
            "name"=>$_POST['name'],
            "type"=>$_POST['typex'],
            "acquired"=>"$new_acq[2]-$new_acq[1]-$new_acq[0]",
            "renewed"=>"$new_renew[2]-$new_renew[1]-$new_renew[0]",
            "expires"=>"$new_end[2]-$new_end[1]-$new_end[0]",
            "vin"=>$_POST['vin'],
            "state_acquired"=>$_POST['stat_acq'],
            "notes"=>$_POST['veh_notes'],
            "placard"=>$_POST['placard'],
            "lic_requirement"=>"$_POST[lic_require]",
            "insurance_id"=>$_POST['insurance'],
            "insurance_carrier"=>$_POST['carrier'],
            "description"=>$_POST['descri'],
            "weight_empty"=>$_POST['weight_empty'],
            "rm_total"=>$_POST['r_m'],
            "dep_rate"=>$_POST['dep']
        );
        //echo "<br/><br/><br/><br/><br/><br/>";
        
       /*echo "<pre>";
        var_dump($package);
        echo "</pre>";*/ 
        
        $db->where('truck_id',$_POST['tid'])->update($dbprefix."_trailers",$package);
    }
    $person = new Person();
    $edit_vehicle = new Trailer($_GET['id']); 
    
    
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="ReDDaWG" />
    <meta charset="UTF-8" />
    <script src="js/jquery-ui.js"></script>
    <link rel="stylesheet" href="css/auto.css"/>
   <?php include "source/css.php"; ?>
   <?php include "source/scripts.php"; ?> 
	<title>Customer Management System</title>
    <style type="text/css">
    td { 
        text-align:left;
        vertical-align: top;
        padding:5px 5px 5px 5px;
    }
    </style>
</head>
<body>
<?php include "source/header.php"; ?>


<div id="wrapper" style="margin-top: 90px;">
    
<table width="900"  style="margin: auto;"><tbody><tr><td width="25%" valign="top" align="left">

<table width="250">




<tbody>
<tr><td colspan="6"><form action="editTrailer.php?task=vv&id=<?php echo $edit_vehicle->truck_id; ?>" method="post">
<input type="hidden" name="tisvehicle" value="<?php echo $edit_vehicle->truck_id; ?>"/>
<input name="deletevehicle" value="Delete this Vehicle" type="submit"/></form></td></tr>

<form action="editTrailer.php?task=vv&id=<?php echo $edit_vehicle->truck_id; ?>" method="POST">
<tr class="table_row">
    <td>
        <span class="display_field" id="option_vehicle_type_label_id_140">Type</span>
    </td>
    <td>
        <input type="text" name="typex" id="typex" placeholder="type" value="<?php echo $edit_vehicle->type; ?>"/>
    </td>
</tr>



<tr class="table_row">
<td class="table_label">
    <span class="display_field">Make</span>
</td>
<td class="table_data">
    <input type="text" id="make" name="make" placeholder="make" value="<?php echo $edit_vehicle->truck_make; ?>"/>
</td>
</tr>
<tr class="table_row">
<td  class="table_label">
    <span class="display_field" >Model</span>
</td>
<td class="table_data">
    
    <input type="text" id="model" name="model" placeholder="model" value="<?php echo $edit_vehicle->truck_model; ?>" />

</td>
</tr>
<tr class="table_row">
<td class="table_label">
    <span class="display_field"><span title="In Gallons">Capacity</span></span>
</td>
<td class="table_data">    
    <input type="text" name="capac" id="capac" placeholder="capcity" value="<?php echo $edit_vehicle->max_capacity; ?>"/>
</td>
</tr>
<tr class="table_row">
<td class="table_label">
    <span class="display_field">Weight Empty</span>
</td>
<td class="table_data">
    <input type="text" name="weight_empty" id="weight_empty" placeholder="weight empty" value="<?php echo $edit_vehicle->w_empty; ?>"/>
</td>
</tr>
<tr>
<td style="text-align: right;">R & M</td><td style="text-align: left;"><input type="text" id="r_m" name="r_m"   value="<?php echo number_format($edit_vehicle->rm_total,2); ?>"/></td>
</tr>
</tbody></table>

</td>

<td >

<table width="290"><tbody><tr class="table_row">
<td class="table_label">

<span class="display_field">Name</span>
</td>
<td valign="top" align="left" class="table_data">

    
    <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $edit_vehicle->name; ?>"/>

</td>
</tr>
<tr class="table_row">
<td  class="table_label">
    <span class="display_field">Description</span>
</td>
<td valign="top" align="left" class="table_data">

<textarea name="descri" id="descri" placeholder="Write Description Here" style="width: 187px;">
<?php
    echo $edit_vehicle->description;
?>
</textarea><br />

</td>
</tr>




<tr class="table_row">
<td class="table_label">
    <span class="display_field" id="option_vehicle_status_label_id_140">Vehicle Status</span>
</td>
<td class="table_data">

    <?php echo truck_service($edit_vehicle->status); ?>
</td>
</tr>




<tr class="table_row">
<td class="table_label">
    <span class="display_field">Facility</span>
</td>
<td class="table_data">
    <?php  echo getFacilityList("",$edit_vehicle->facility_no); ?>

</td>
</tr>
<tr class="table_row">
<td class="table_label"><span class="display_field">Current Mileage</span>
</td>
<td valign="top" align="left" class="table_data">

    <input type="text" id="mileage" name="mileage" placeholder="Mileage" value="<?php echo $edit_vehicle->current_mileage; ?>"/>

</td>
</tr>

<tr class="table_row"><td class="table_label">MPG</td><td class="table_data">

<input type="text" placeholder="mpg" id="mpg" name="mpg" value="<?php echo $edit_vehicle->mpg; ?>"/>
</td></tr>
<tr  class="table_row">
    
    <td style="text-align: right;">Depreciation Rate</td><td style="text-align:left;"><input  value="<?php echo $edit_vehicle->dep; ?>" type="text" id="dep" name="dep"/></td>
</tr>
</tbody></table></td>

<td width="30%" valign="top" align="left"><table width="250"><tbody>
<tr class="table_row">

<td class="table_label">

<span class="display_field">Truck Year</span>
</td>

<td>
<input type="text" placeholder="Trailer Year" id="year" name="year" value="<?php

if($edit_vehicle->truck_year !="0000-00-00"){
    $old_date_timestamp = strtotime($edit_vehicle->truck_year);
$new_date = date('m/d/Y', $old_date_timestamp); 
 echo $new_date;     
}

?>"/>
</td>

</tr>

<tr class="table_row">


<td class="table_label"><span class="display_field">License&nbsp;#</span>
</td>
<td valign="top" align="left" class="table_data">

<input type="text" placeholder="License Number" name="lic_num" id="lic_num" value="<?php echo $edit_vehicle->plates; ?>"/>


</td>
</tr>
<tr class="table_row">
<td class="table_label"><span class="display_field" >VIN</span>
</td>
<td valign="top" align="left" class="table_data">

<input type="text" id="vin" name="vin" placeholder="VIN" value="<?php echo $edit_vehicle->vin; ?>"/>

</td>
</tr>
<tr class="table_row">
<td class="table_label"><span class="display_field">Acquired</span>
</td>
<td valign="top" align="left" class="table_data">
<input type="text" name="date_acq" id="date_acq" value="<?php


if($edit_vehicle->acquired !="0000-00-00"){
$acq = strtotime($edit_vehicle->acquired);
$newacq = date('m/d/Y', $acq); 
 echo $newacq;
 }
  ?>" placeholder="Date Acquired" />


</td>
</tr>
<tr class="table_row">
<td class="table_label"><span class="display_field">State of Registration</span>
</td>
<td valign="top" align="left" class="table_data">

    <input type="text" maxlength="2" value="<?php echo $edit_vehicle->state_acquired; ?>" id="state_acq" name="stat_acq"/>

</td>
</tr>

<tr class="table_row">
    <td class="table_label">
        <span class="display_field">Mileage at Purchase</span>
    </td>
    <td class="table_data">
        <input type="text" name="milatpurch" id="milatpurch" value="<?php echo $edit_vehicle->start_mileage; ?>"/>
    </td>
</tr>


</tbody></table></td></tr>


<tr>


<td  colspan="2">

<div id="add_note_button" style=" font-weight:bold; margin-bottom:5px; margin-left:20px; text-align:left; width:560px; ">Notes 

<textarea name="veh_notes" id="veh_notes" style="width: 100%;height:200px;"><?php echo $edit_vehicle->notes; ?></textarea>

</td>


<td width="30%" valign="middle" align="left"><table width="280"><tbody><tr class="table_row">
<td class="table_label"><span class="display_field" ><span title="4-digit D.O.T. hazardous materials code">D.O.T. Placard Code</span></span>
</td>
<td valign="top" align="left" class="table_data">

    <input type="text" name="placard" id="placard" placeholder="Placard" value="<?php echo $edit_vehicle->placard; ?>"/>

</td>
</tr>
<tr class="table_row">
<td class="table_label"><span class="display_field"><span title="Any certifications the driver needs to operate this vehicle.">License Requirement</span></span>
</td>

<td class="table_data">
    <input type="text" name="lic_require" id="lic_require" placeholder="License Requirement"  value="<?php echo $edit_vehicle->lic_require; ?>" />
</td>
</tr>
<tr class="table_row">
<td class="table_label"><span class="display_field" >License Renewed</span>
</td>
<td valign="top" align="left" class="table_data"><span id="date_license_renewal_last_value_id_140">

<input type="text" id="lic_renewed" name="lic_renewed" placeholder="Date Renewed" value="<?php

if( $edit_vehicle->renewed !="0000-00-00"){
$renew = strtotime( $edit_vehicle->renewed);
$newrenew = date('m/d/Y', $renew); 
 echo $newrenew; } 

  ?>"/>

</td>
</tr>
<tr class="table_row">
<td class="table_label"><span class="display_field">License Expires</span>
</td>

<td valign="top" align="left" class="table_data"><span id="date_license_renewal_date_value_id_140">


<input type="text" id="end_date" name="end_date" placeholder="License Expires" value="<?php

if($edit_vehicle->expires !="0000-00-00"){
$exp = strtotime($edit_vehicle->expires);
$newexp = date('m/d/Y', $acq); 
 echo $newexp; } ?>"/>

</td>
</tr>
<tr class="table_row">
<td class="table_label"><span class="display_field" >Insurance ID</span>
</td>
<td valign="top" align="left" class="table_data"><span id="vehicle_insurance_id_value_id_140"></span>

<input type="text" name="insurance" id="insurance" placeholder="Insurance ID" value="<?php echo $edit_vehicle->insurance_id;; ?>"/>

</td>
</tr>
<tr class="table_row">
<td class="table_label"><span class="display_field" >Insurance Carrier</span>
</td>
<td class="table_data">

<input type="text" id="carrier" name="carrier" placeholder="Carrier" value="<?php echo $edit_vehicle->carrier; ?>"/>

</td>
</tr>
<tr class="table_row">
<td class="table_label"><span class="display_field" >IKG Code</span>
</td>
<td valign="top" align="left" class="table_data">

<input type="text" id="ikg_code" name="ikg_code" value="<?php echo $edit_vehicle->ikg_decal;?>"/>


</td>
</tr>
</tbody></table></td></tr>
<tr><td colspan="3" style="text-align:right;"><span style="float:right;">After updating data be sure to&nbsp;<input value="Save Changes" type="submit" style="margin-right:30px;" id="sc" name="sc"/></span></td></tr>
</tbody></table>



</div>
<input type="hidden" value="<?php echo $_GET['id']; ?>" name="tid"/>
</form>

<script>
$("body #transparent").remove();
$("#lic_renewed").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
$("#end_date").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});

$('#year').datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
$("#date_acq").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
</script>
<?php 

    

include "source/footer.php"; ?>

</body>
</html>