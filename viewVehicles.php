<?php include "protected/global.php"; $page = "Management | View Vehicle"; 
$headers = 'From: asset-management@iwpusa.com' . "\r\n" .
                'Reply-To: no-reply@iwpusa.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
ini_set('display_errors',1); 
function is_dir_empty($dir) {
  if (!is_readable($dir)) return NULL; 
  $handle = opendir($dir);
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      return FALSE;
    }
  }
  return TRUE;
}

if(isset($_SESSION['sludge_id'])){  
    
    //error_reporting(E_ALL);

    if(isset($_POST['sc'])){
        print_r($_POST['sc']);
        $new_year = explode("/",$_POST['year']);
        if(empty($new_year)){
            $new_year = array(
                0=>"00",
                1=>"00",
                2=>"0000"
            );
        }
       
       if(isset($_POST['year']) && strlen(trim($_POST['year']))>0 ){
            $new_renew = date('Y-m-d',strtotime($_POST['year']));
        }else {
            $new_renew =null;
        }
       
       
        if(isset($_POST['lic_renewed']) && strlen(trim($_POST['lic_renewed']))>0 ){
            $new_renew = date('Y-m-d',strtotime($_POST['lic_renewed']));
        }else {
            $new_renew =null;
        }
        
        if(isset($_POST['lic_expire']) && strlen(trim($_POST['lic_expire']))>0 ){
            $new_end = date('Y-m-d',strtotime($_POST['lic_expire']));
        }else {
            $new_end =null;
        }
        
        
        if(isset($_POST['date_acq']) && strlen(trim($_POST['date_acq']))>0 ){
            $new_acq = date('Y-m-d',strtotime($_POST['date_acq']));
        }else {
            $new_acq =null;
        }
     
        
        if(isset($_POST['opacity_due']) && strlen(trim($_POST['opacity_due']))>0 ){
            $new_opacity = date('Y-m-d',strtotime($_POST['opacity_due']));
        }else {
            $new_opacity =null;
        }
        
        $cam = 0;
        $gps = 0;
        if(isset($_POST['camera'])){
            $cam =1;
        }
        
        
        if(isset($_POST['gps'])){
            $gps = 1;
        }
        
        if(isset($_POST['service_due']) && strlen(trim($_POST['service_due']))>0 ){
            $serv_date = date('Y-m-d',strtotime($_POST['service_due']));
        }else {
            $serv_date =null;
        }
        if(isset($_POST['ikg_renewed']) && strlen(trim($_POST['ikg_renewed']))>0){
            $ikg = date('Y-m-d',strtotime($_POST['ikg_renewed']));
        } else {
            $ikg = null;
        }
        
        
        if(isset($_POST['annual']) && strlen(trim($_POST['annual']))>0){
            $annual =  date('Y-m-d',strtotime($_POST['annual']));
        } else {
            $annual = null;
        }
        
        if(isset($_POST['registration']) && strlen(trim($_POST['registration']))>0){
            $regis = date('Y-m-d',strtotime($_POST['registration']));
        } else {
            $regis = null;
        }
        
        if(isset($_POST['other_permit_due']) && strlen(trim($_POST['other_permit_due']))>0){
           $other = date('Y-m-d',strtotime($_POST['other_permit_due']));
        }else{
            $other = null;
        }
        if(isset($_POST['repair_date']) && strlen(trim($_POST['repair_date']))>0){
            $repair = date('Y-m-d',strtotime($_POST['repair_date']));
        } else {
            $repair = null;
        }
        
        if($_POST['facility'] !="ignore"){
            $fac = $_POST['facility'];
        } else {
            $fac= null;
        }
        
        $package = array(
            "truck_year"=>(int)$_POST['year'],
            "truck_model"=>$_POST['model'],
            "truck_make"=>$_POST['make'],            
            "plates"=>$_POST['lic_num'],
            "ikg_decal"=>$_POST['ikg_code'],
            "max_capacity"=>$_POST['capac'],
            "start_mileage"=>$_POST['milatpurch'],
            "current_mileage"=>$_POST['mileage'],
            "mpg"=>$_POST['mpg'],
            "facility"=>$fac,            
            "status"=>$_POST['vehicle_status_id'],
            "name"=>$_POST['name'],
            "type"=>$_POST['type'],
            "acquired"=>"$new_acq",
            "renewed"=>"$new_renew",
            "expires"=>"$new_end",
            "vin"=>$_POST['vin'],
            "state_acquired"=>$_POST['stat_acq'],
            "notes"=>$_POST['veh_notes'],
            "placard"=>$_POST['placard'],
            "lic_requirement"=>"$_POST[lic_require]",
            "insurance_id"=>$_POST['insurance'],
            "insurance_carrier"=>$_POST['carrier'],
            "description"=>$_POST['descri'],
            "weight_empty"=>$_POST['weight_empty'],
            "r_m"=>$_POST['r_m'],
            "dep_rate"=>$_POST['dep'],
            "opacity_due"=>$new_opacity,
            "service_due"=>$serv_date,
            "ikg_renewed"=>$ikg,
            "annual"=>$annual,
            "registration"=>$regis,
            "other_permit_due"=>$other,
            "repair_date"=>$repair,
            "camera_installed"=>$cam,
            "gps_installed"=>$gps,
            "enabled"=>(int)$_POST['endis'],
            "module"=>$_POST['module']
        );
        echo "<br/><br/><br/><br/><br/><br/>";
        /*
        echo "<pre>";
        var_dump($package);
        echo "</pre>";
        echo "<br/>";*/
        $table = $_POST['typey'];
        if(isset($_POST['tid']) && strlen(trim($_POST['tid']))>0){
            if($db->where('truck_id',$_POST['tid'])->update("assets.$table",$package)){
                $db->where('module_id',$_POST['tid'])->update("sludge_truck_id",$package);
                 mail("KMickle@iwpusa.com","Grease Trap Asset Updated"," Please log in asset module to review  $_POST[name] ",$headers);
                echo "ASSET $_POST[name]  UPDATED<br/>";
            }    
        }else{
            switch($_POST['typey']){
                case "trailer":
                    if($db->insert("assets.trailer",$package)){
                        $asset_id = $db->getInsertId();
                        $extra = array("module_id"=>$asset_id);
                        $package = $package + $extra;
                        $db->insert("sludge_trailer",$package);
                        mail("KMickle@iwpusa.com,AParsons@iwpusa.com","Grease Trap Asset Added"," Please log in asset module to review  $_POST[name] ",$headers);                        
                        $ppp = $db->query("SELECT truck_id FROM sludge_trailer WHERE name ='$_POST[name]'");
                        $db->query("UPDATE assets.trailer SET trailer.module_id =".$ppp[0]['truck_id']." WHERE trailer.truck_id = $asset_id");
                        echo "ASSET $_POST[name]  Added<br/>";
                        
                    }
                    
                break;
                case "truck":
                    if($db->insert("assets.truck",$package)){
                        $asset_id = $db->getInsertId();
                        $extra = array("module_id"=>$asset_id);
                        $package = $package + $extra;
                        $db->insert("sludge_truck_id",$package); 
                        mail("KMickle@iwpusa.com,AParsons@iwpusa.com","Grease Trap Asset Added"," Please log in asset module to review  $_POST[name] ",$headers);
                        $ppp = $db->query("SELECT truck_id FROM sludge_truck_id WHERE name = '$_POST[name]'");
                        $db->query("UPDATE assets.truck SET truck.module_id=".$ppp[0]['truck_id']." WHERE truck.truck_id=$asset_id");
                        echo "ASSET $_POST[name]  Added<br/>";   
                    }
                break;
                case "other":
                    $db->insert("assets.truck",$package);
                break;
            }
        }
        
    }
    
    
    $person = new Person();
    
    if(isset($_GET['id'])){
         switch($_GET['type']){
            case "trailer":
                $edit_vehicle = new Trailer($_GET['id']);
            break;
            default:
                $edit_vehicle = new Vehicle($_GET['id']);
            break;
         }
    }else{
        $edit_vehicle = new Vehicle();
    }
    
     
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
    input[type=text]{
        width:100px;
    }
    tbody.added {
      outline: solid #000000 2px;
      outline-radius: 0.4em;
      -moz-outline-radius: 0.4em;
     
      padding:10px 10px 10px 10px;
    }
    </style>
</head>
<body>
<?php include "source/header.php"; ?>


<div id="wrapper" style="margin-top: 110px;width:1000px;border-radius:9px;border:1px solid #black;height:auto;padding:10px 10px 10px 10px 10px;margin-bottom:20px;-webkit-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);">
<table  style="width:990px;margin: auto;">

<tbody>
<tr><td colspan="2"><?php if(isset($_GET['id'])){  ?> 
<form action="viewVehicles.php<?php if(isset($_GET['id'])){ echo "?id=$_GET[id]&type=$_GET[type]";  } ?>" method="post">
<input type="hidden" name="tisvehicle" value="<?php echo $edit_vehicle->truck_id; ?>"/>
<input name="deletevehicle" value="Delete this Asset" type="submit"/></form>
<?php } ?></td>

<td colspan="4" style="text-align: center;">
<div id="wholepic" style="width: 289px;height:143px;background:url(img/truck2.jpg) no-repeat top center;background-size:contain;">
<div id="backpart" style="width: 200px;height:160px;background:url(img/imageedit_2_2646569836.png) no-repeat right top;background-size:contain;position:relative;left:205px;top:5px;"></div>

</div>
</td>

<td colspan="2" style="text-align: right;"><form action="viewVehicles.php<?php if(isset($_GET['id'])){ echo "?id=$_GET[id]&type=$_GET[type]";  } ?>" method="POST"><input value="Save Changes" type="submit"  id="sc" name="sc"/></td>

</tr>

<tbody class="added">
<tr class="table_row">
<tr><td colspan="8"><h2>General Information</h2></td></tr>
<td class="table_label">
    <span class="display_field">Make</span>
</td>
<td class="table_data">
    <input type="text" id="make" name="make" placeholder="make" value="<?php echo $edit_vehicle->truck_make; ?>"/>
</td>

<td  class="table_label">
    <span class="display_field" >Model</span>
</td>
<td class="table_data">
    
    <input type="text" id="model" name="model" placeholder="model" value="<?php echo $edit_vehicle->truck_model; ?>" />

</td>

<td class="table_label">

<span class="display_field">Truck Year</span>
</td>

<td>
<input type="text" placeholder="Truck Year" id="year" name="year" value="<?php

if($edit_vehicle->truck_year !="0000-00-00"  && strlen(trim($edit_vehicle->truck_year)>0) && $edit_vehicle->truck_year != null){
    $new_date = date('m/d/Y', strtotime($edit_vehicle->truck_year)); 
    echo $new_date;  
}

 ?>"/>
</td>
<td class="table_label">

<span class="display_field">Name</span>
</td>
<td valign="top" align="left" class="table_data">

    
    
    <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $edit_vehicle->name; ?>"/>

</td>


</tr>
<tr><td colspan='2'>Enable/Disable Asset</td><td colspan='2'><input type='radio'   name='endis' class='endis' value='1'  <?php 

         if(isset($_GET['id'])){
            if($edit_vehicle->endis == 1){ echo " checked "; } 
        }else{
            echo " checked='' ";
        }
    ?> />&nbsp;Enable&nbsp;&nbsp;&nbsp;&nbsp;<input type='radio' name='endis' class='endis' value='0'  <?php  
         if(isset($_GET['id'])){
            if($edit_vehicle->endis == 0){ echo " checked "; } 
        }else{
            
        }
    ?> />&nbsp;Disable</td>
    <td>Asset Type</td><td style="text-align: left;">
        <select name="typey" id="typey">        
        <option <?php if( $edit_vehicle->typex == "truck"){ echo " selected "; } ?>   value="truck">Truck</option>
        <option <?php if( $edit_vehicle->typex == "trailer"){ echo " selected "; } ?>  value="trailer">Trailer</option>
        <option <?php if( $edit_vehicle->typex == "other"){ echo " selected "; } ?>   value="other">Other</option>
        </select>
    
    </td>
    <td>Module</td><td>
    <select name="module" id="module">
        <option value="grease" <?php if($edit_vehicle->module == "grease"){ echo " selected "; } ?> >Grease</option>
        <option value="oil" <?php if($edit_vehicle->module == "oil"){ echo " selected "; } ?> >Oil</option>
        <option value="bakery" <?php if($edit_vehicle->module == "bakery"){ echo " selected "; } ?> >Bakery</option>
        <option value="other" <?php if($edit_vehicle->module == "other"){ echo " selected "; } ?> >Other</option>
    </select></td>
    </tr>
   
</tbody>

<tbody><tr><td colspan="8" style="height: 10px;">&nbsp;</td></tr></tbody>

<tbody class="added">
<tr><td colspan="8"><h2>Registration Information</h2></td></tr>

<tr>


<td class="table_label"><span class="display_field">License&nbsp;#</span>
</td>
<td valign="top" align="left" class="table_data">

<input type="text" placeholder="License Number" name="lic_num" id="lic_num" value="<?php echo $edit_vehicle->plates; ?>"/>


</td>
<td class="table_label"><span class="display_field" >VIN</span>
</td>
<td valign="top" align="left" class="table_data">

<input type="text" id="vin" name="vin" placeholder="VIN" value="<?php echo $edit_vehicle->vin; ?>"/>

</td>
<td class="table_label"><span class="display_field">State of Registration</span>
</td>
<td valign="top" align="left" class="table_data">

    <input type="text" maxlength="2" value="<?php echo $edit_vehicle->state_acquired; ?>" id="state_acq" name="stat_acq"/>

</td>
 <td class="table_label">
        <span class="display_field">Mileage at Purchase</span>
    </td>
    <td class="table_data">
        <input type="text" name="milatpurch" id="milatpurch" value="<?php echo $edit_vehicle->start_mileage; ?>"/>
    </td>
</tr>



<tr>
<td class="table_label"><span class="display_field">License Expires</span>
</td>

<td valign="top" align="left" class="table_data"><span id="date_license_renewal_date_value_id_140">


<input type="text" name="lic_expire" id="end_date" placeholder="License Expires" value="<?php
if($edit_vehicle->expires !="0000-00-00"  && strlen(trim($edit_vehicle->expires)>0) && $edit_vehicle->expires != null){
   $oldexp = strtotime($edit_vehicle->expires);
    $new_exp = date('m/d/Y', $oldexp); 
 echo $new_exp;  
}
 ?>"/>

</td>

<td class="table_label"><span class="display_field" >License Renewed</span>
</td>
<td valign="top" align="left" class="table_data">

<input type="text" id="lic_renewed" name="lic_renewed" placeholder="Date Renewed" value="<?php 
if($edit_vehicle->renewed !="0000-00-00" && strlen(trim($edit_vehicle->renewed)>0) && $edit_vehicle->renewed != null ){
   $oldreq = strtotime($edit_vehicle->renewed);
$new_req = date('m/d/Y', $oldreq); 
 echo $new_req;  
}
  ?>"/>

</td>

<td class="table_label"><span class="display_field"><span title="Any certifications the driver needs to operate this vehicle.">License Requirement</span></span>
</td>

<td class="table_data">
    <input type="text" name="lic_require" id="lic_require" placeholder="License Requirement"  value="<?php echo $edit_vehicle->lic_require; ?>" />
</td>
<td class="table_label"><span class="display_field" ><span title="4-digit D.O.T. hazardous materials code">D.O.T. Placard Code</span></span>
</td>
<td valign="top" align="left" class="table_data">

    <input type="text" name="placard" id="placard" placeholder="Placard" value="<?php echo $edit_vehicle->placard; ?>"/>

</td>
</tr>

</tbody>
<tbody><tr><td colspan="8" style="height: 10px;">&nbsp;</td></tr></tbody>


<tbody class="added">
<tr><td colspan="8"><h2>Statistics</h2></td></tr>
<tr>
<td class="table_label"><span class="display_field">Current Mileage</span>
</td>
<td valign="top" align="left" class="table_data">

    <input type="text" id="mileage" name="mileage" placeholder="Mileage" value="<?php echo $edit_vehicle->current_mileage; ?>"/>

</td>
<td class="table_label">
    <span class="display_field"><span title="In Gallons">Gross Vehicle Weight</span></span>
</td>
<td class="table_data">    
    <input type="text" name="capac" id="capac" placeholder="capcity" value="<?php echo $edit_vehicle->max_capacity; ?>"/>
</td>

<td class="table_label">MPG</td><td class="table_data">

<input type="text" placeholder="mpg" id="mpg" name="mpg" value="<?php echo $edit_vehicle->mpg; ?>"/>
</td>


<td class="table_label">
    <span class="display_field">Tare Weight</span>
</td>
<td class="table_data">
    <input type="text" name="weight_empty" id="weight_empty" placeholder="weight empty" value="<?php echo $edit_vehicle->w_empty; ?>"/>
</td>
  
</tr>



<tr>
<td class="table_label">
    <span class="display_field">Facility</span>
</td>
<td class="table_data">
    <?php  echo getFacilityList("",$edit_vehicle->facility_no); ?>

</td>

<td style="text-align: right;">R & M</td><td style="text-align: left;"><input type="text" id="r_m" name="r_m"   value="<?php echo $edit_vehicle->r_m; ?>"/></td>
<td>
        <span class="display_field" id="option_vehicle_type_label_id_140">Type</span>
    </td>
    <td>
        <input type="text" name="type" id="type" placeholder="type" value="<?php echo $edit_vehicle->type; ?>"/>
    </td>
    <td class="table_label">
        <span class="display_field" id="option_vehicle_status_label_id_140">Vehicle Status</span>
    </td>
    <td class="table_data">
    
        <?php echo truck_service($edit_vehicle->status); ?>
    </td>
</tr>

<tr>
<td class="table_label"><span class="display_field" >IKG Code</span>
</td>
<td valign="top" align="left" class="table_data">

<input type="text" id="ikg_code" name="ikg_code" value="<?php echo $edit_vehicle->ikg_decal;?>"/>


</td>
<td class="table_label"><span class="display_field" >Insurance Carrier</span>
</td>
<td class="table_data">

<input type="text" id="carrier" name="carrier" placeholder="Carrier" value="<?php echo $edit_vehicle->carrier; ?>"/>

</td>

<td class="table_label"><span class="display_field" >Insurance ID</span>
</td>
<td valign="top" align="left" class="table_data"><span id="vehicle_insurance_id_value_id_140"></span>

<input type="text" name="insurance" id="insurance" placeholder="Insurance ID" value="<?php echo $edit_vehicle->insurance_id;; ?>"/>

</td>

<td style="text-align: right;">Depreciation Rate</td><td style="text-align:left;"><input  value="<?php echo $edit_vehicle->dep; ?>" type="text" id="dep" name="dep"/></td>


</tr>
</tbody>

<tbody><tr><td colspan="8" style="height: 10px;">&nbsp;</td></tr></tbody>

<tbody class="added">
<tr><td colspan="8"><h2>MAINTENANCE DATES</h2></td></tr>
<tr>
    <td class="table_label"><span class="display_field">Opacity Due</span></td>
    <td><input class="date"  type="text" name="opacity_due" id="opacity_due" value="<?php 
    if($edit_vehicle->opacity !="0000-00-00" && $edit_vehicle->opacity!="1969-12-31" && $edit_vehicle->opacity !=null && $edit_vehicle->opacity != null){
   $oldop = strtotime($edit_vehicle->opacity);
$new_opac = date('m/d/Y', $oldop); 
 echo $new_opac;  
}
  ?>" /></td>
    
    <td class="table_label"><span class="display_field">Service Date</span></td>
    <td><input class="date"  type="text" name="service_due" id="service_due"  value="<?php 
    if($edit_vehicle->service !="0000-00-00" && $edit_vehicle->service !="1969-12-31"  && $edit_vehicle->service !=null && $edit_vehicle->service != null){
        $new_serv = date('m/d/Y', strtotime($edit_vehicle->service)); 
        echo $new_serv;  
    }
  ?>"  /></td>
    
    <td class="table_label"><span class="display_field">IKG renew</span></td>
    <td><input class="date"  type="text" name="ikg_renewed" id="ikg_renewed" value="<?php 
    if($edit_vehicle->renew !="0000-00-00"  && $edit_vehicle->renew !="1969-12-31"  && $edit_vehicle->renew !=null){
   $oldrenew = strtotime($edit_vehicle->renew);
$new_renew = date('m/d/Y', $oldrenew); 
 echo $new_renew;  
}
  ?>" /></td>
    
    <td class="table_label"><span class="display_field">Due 90</span></td>
    <td><input class="date"  type="text" name="due_90" id="due_90" value="<?php 
    if($edit_vehicle->due_90 !="0000-00-00"  && $edit_vehicle->due_90 !="1969-12-31" && $edit_vehicle->due_90 !=null){
   $olddue = strtotime($edit_vehicle->due_90);
$new_due = date('m/d/Y', $olddue); 
 echo $new_due;  
}
  ?>" /></td>
</tr>

<tr>
    <td class="table_label"><span class="display_field">Annual Service Due</span></td>
    <td><input class="date"  type="text" name="annual" id="annual" value="<?php 
    if($edit_vehicle->annual !="0000-00-00" && $edit_vehicle->annual !="1969-12-31" && $edit_vehicle->annual !=null){
   $oldannual = strtotime($edit_vehicle->annual);
$new_annual = date('m/d/Y', $oldannual); 
 echo $new_annual;  
}
  ?>" /></td>
    
    <td class="table_label"><span class="display_field">Registration Date</span></td>
    <td><input class="date"  type="text" name="registration" id="registration" value="<?php 
    if($edit_vehicle->registration !="0000-00-00" && $edit_vehicle->registration !="1969-12-31" && $edit_vehicle->registration !=null){
   $oldreg = strtotime($edit_vehicle->registration);
$new_reg = date('m/d/Y', $oldreg); 
 echo $new_reg;  
}
  ?>"  /></td>
    
    <td class="table_label"><span class="display_field">Other Permit Due</span></td>
    <td><input class="date"  type="text" name="other_permit_due" id="other_permit_due"  value="<?php 
    if($edit_vehicle->other_permit !="0000-00-00" && $edit_vehicle->other_permit !="1969-12-31"  && $edit_vehicle->other_permit !=null){
    
    $new_perm = date('m/d/Y', strtotime($edit_vehicle->other_permit)); 
 echo $new_perm;  
}
  ?>"  /></td>
     
    <td class="table_label"><span class="display_field">Repair Date</span></td>
    <td><input class="date" type="text" name="repair_date" id="repair_date"  value="<?php 
    if($edit_vehicle->repair !="0000-00-00" && $edit_vehicle->repair !="1969-12-31"  && $edit_vehicle->repair !=null){
        $new_rep = date('m/d/Y',  strtotime($edit_vehicle->repair)); 
        echo $new_rep;  
    }
  ?>" /></td>
</tr>
</tbody>

<tbody><tr><td colspan="8" style="height: 10px;">&nbsp;</td></tr></tbody>
<tbody class="added">
<tr><td colspan="8"><h2>MISC</h2></td></tr>
<tr><td>Has GPS?</td><td><input type="checkbox" name="gps" <?php if($edit_vehicle->gps ==1){ echo " checked "; } ?> /></td><td>Has Camera?</td><td><input type="checkbox" name="camera"  <?php if($edit_vehicle->camera ==1){ echo " checked "; } ?> /></td><td class="table_label"><span class="display_field">Acquired</span>
</td>
<td valign="top" align="left" class="table_data">
<input type="text" name="date_acq" id="date_acq" value="<?php
if($edit_vehicle->acquired !="0000-00-00" && $edit_vehicle->acquired !=null){
   $oldeq = strtotime($edit_vehicle->acquired);
$new_eq = date('m/d/Y', $oldeq); 
 echo $new_eq;  
}


 ?>" placeholder="Date Acquired" />
</td>
<td>Mileage for Next Service</td>
<td><input type="text" name="mfns" id="mfns" /></td>
    </tr>
    
   
    
<tr>

<td>Description</td>
<td colspan="3"><textarea name="descri" id="descri" style="width: 90%;height:200px;"><?php echo $edit_vehicle->description; ?></textarea></td>
<td >

Notes 
</td>
<td  colspan="3">
<textarea name="veh_notes" id="veh_notes" style="width: 90%;height:200px;"><?php echo $edit_vehicle->notes; ?></textarea>

</td>


</tr>
</tbody>
</table>
    <div style="clear: both;"></div>

</div>
<input type="hidden" value="<?php echo $_GET['id']; ?>" name="tid"/>
</form>

<script>
$("body #transparent").remove();
$("#lic_renewed").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
$("#end_date").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
$(".date").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
$('#year').datepicker({dateFormat: 'yy',changeMonth: false, changeYear: true,yearRange: "1:c+10"});
$("#date_acq").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
</script>
<?php include "source/footer.php"; ?>

</body>
</html>