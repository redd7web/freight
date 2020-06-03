<?php include "../protected/global.php"; $page = "Management | Add Asset";
$headers = 'From: asset-management@iwpusa.com' . "\r\n" .
                'Reply-To: no-reply@iwpusa.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
ini_set('display_errors',1);
if(isset($_SESSION['freight_id'])){
    
     
    $person = new Person();

    if(isset($_POST['sc'])){
        
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
            "enabled"=>1,
            "module"=>"grease"
        );
        $db->insert("freight_truck_id",$package);
        echo "<br/><br/><br/><br/><br/><br/>";
        echo "INSERT table: $_POST[typey]<br/>";
        if(isset($_POST['typey']) && strlen(trim($_POST['typey'])) >0){
            if($db->insert("assets.$_POST[typey]",$package)){
                $new_id = $db->getInsertId();
                 mail("KMickle@iwpusa.com","New Asset added via Grease Trap Module"," Please log in asset module to review  $_POST[name] ",$headers);
                if(!file_exists("../../assets/vehicles/$new_id")){
                    mkdir("../../assets/vehicles/$new_id");
                }
                echo "ASSET $_POST[name] INSERTED<br/>";
            }

        }else{
            echo "Please select an asset type.<br/>";
        }
    }
    
    
    
    
    
    
     
}

if(isset($_SESSION['freight_id'])){
    

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="ReDDaWG" />
    <meta charset="UTF-8" />
    <script src="../js/jquery-ui.js"></script>
    <link type="text/css" rel="stylesheet" href="../css/first.css" media="all" />
    <link type="text/css" rel="stylesheet" href="../css/second.css" media="all" />
    <link type="text/css" rel="stylesheet" href="../css/fourth.css" media="all" />
    <link type="text/css" rel="stylesheet" href="../css/fifth.css" media="all" />
    <link type="text/css" rel="stylesheet" href="../css/sixth.css" media="all" />
    <link type="text/css" rel="stylesheet" href="../css/seventh.css" media="all" />
    <link type="text/css" rel="stylesheet" href="../plugins/shadow/shadowbox.css" />
    <link type="text/css" rel="stylesheet" href="../css/style.css" media="all" />
    <link type="text/css" rel="stylesheet" href="../css/jquery.dataTables.min.css"/>
   
   <script type="text/javascript" src="../js/jquery-1.11.1.js"></script>
    <script type="text/javascript" src="../js/jquery.dropdown.js"></script>
    <script type="text/javascript" src="../js/hoverIntent.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../plugins/shadow/shadowbox.js"></script>
    <script type="text/javascript" src="../js/timepicker.js"></script>
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="../js/Chart.js" ></script>
    <script type="text/javascript">
    Shadowbox.init({
        showOverlay:true,
        modal:false, 
        loadingImage:"shadow/loading.gif",
        displayNav: true,
        slideshowDelay: 2,        
        overlayOpacity: '0.9',
        overlayColor:"#FFFFFF",
        gallery: "gall" ,
            
    });
    
    var fixHelper = function(e, ui) {
    	ui.children().each(function() {
    		$(this).width($(this).width());
    	});
    	return ui;
    };
    
    $(function() {
    	$( "#tabs" ).tabs();
        $( "#sortable tbody" ).sortable({    
                helper: fixHelper
         }).disableSelection();
        
    });
    </script> 
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
<?php include "../source/header.php"; ?>


<div id="wrapper" style="margin:auto;margin-top: 110px;width:1000px;border-radius:9px;border:1px solid #black;height:auto;padding:10px 10px 10px 10px 10px;margin-bottom:20px;-webkit-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);">
<table style="width:990px;margin: auto;">

<tbody>
<tr><td colspan="2">&nbsp;</td>

<td colspan="4" style="text-align: center;">
<div id="wholepic" style="width: 289px;height:143px;background:url(img/truck2.jpg) no-repeat top center;background-size:contain;">
<div id="backpart" style="width: 200px;height:160px;background:url(img/imageedit_2_2646569836.png) no-repeat right top;background-size:contain;position:relative;left:205px;top:5px;"></div>

</div>
</td>

<td colspan="2" style="text-align: right;"><form action="addVehicle.php" method="POST"><input value="Save Changes" type="submit"  id="sc" name="sc"/></td>

</tr>

<tbody class="added">
<tr class="table_row">
<tr><td colspan="8"><h2>General Information</h2></td></tr>
<td class="table_label">
    <span class="display_field">Make</span>
</td>
<td class="table_data">
    <input type="text" id="make" name="make" placeholder="make" />
</td>

<td  class="table_label">
    <span class="display_field" >Model</span>
</td>
<td class="table_data">
    
    <input type="text" id="model" name="model" placeholder="model" />

</td>

<td class="table_label">

<span class="display_field">Truck Year</span>
</td>

<td>
<input type="text" placeholder="Truck Year" id="year" name="year"  />
</td>
<td class="table_label">

<span class="display_field">Name</span>
</td>
<td valign="top" align="left" class="table_data">

    
    
    <input type="text" name="name" id="name" placeholder="Name"  />

</td>


</tr>
<tr><td colspan='2'>Enable/Disable Asset</td><td colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;<input  type='radio' name='endis' class='endis' value='0'  checked=""  />&nbsp;Disable</td>
    <td>Asset Type</td><td style="text-align: left;">
        <select name="typey" id="typey">        
        <option value="truck">Truck</option>
        <option  value="trailer">Trailer</option>
        <option  value="other">Other</option>
        </select>
    
    </td>
    <td>Module</td><td>
    <select name="module" id="module">
        <option selected="" value="grease">Grease</option>
        
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

<input type="text" placeholder="License Number" name="lic_num" id="lic_num"  />


</td>
<td class="table_label"><span class="display_field" >VIN</span>
</td>
<td valign="top" align="left" class="table_data">

<input type="text" id="vin" name="vin" placeholder="VIN"  />

</td>
<td class="table_label"><span class="display_field">State of Registration</span>
</td>
<td valign="top" align="left" class="table_data">

    <input type="text" maxlength="2"   id="state_acq" name="stat_acq"/>

</td>
 <td class="table_label">
        <span class="display_field">Mileage at Purchase</span>
    </td>
    <td class="table_data">
        <input type="text" name="milatpurch" id="milatpurch"  />
    </td>
</tr>



<tr>
<td class="table_label"><span class="display_field">License Expires</span>
</td>

<td valign="top" align="left" class="table_data"><span id="date_license_renewal_date_value_id_140">


<input type="text" name="lic_expire" id="end_date" placeholder="License Expires"  />

</td>

<td class="table_label"><span class="display_field" >License Renewed</span>
</td>
<td valign="top" align="left" class="table_data"><span id="date_license_renewal_last_value_id_140">

<input type="text" id="lic_renewed" name="lic_renewed" placeholder="Date Renewed"  />

</td>

<td class="table_label"><span class="display_field"><span title="Any certifications the driver needs to operate this vehicle.">License Requirement</span></span>
</td>

<td class="table_data">
    <input type="text" name="lic_require" id="lic_require" placeholder="License Requirement"    />
</td>
<td class="table_label"><span class="display_field" ><span title="4-digit D.O.T. hazardous materials code">D.O.T. Placard Code</span></span>
</td>
<td valign="top" align="left" class="table_data">

    <input type="text" name="placard" id="placard" placeholder="Placard"  />

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

    <input type="text" id="mileage" name="mileage" placeholder="Mileage"  />

</td>
<td class="table_label">
    <span class="display_field"><span title="In Gallons">Capacity</span></span>
</td>
<td class="table_data">    
    <input type="text" name="capac" id="capac" placeholder="capcity"  />
</td>

<td class="table_label">MPG</td><td class="table_data">

<input type="text" placeholder="mpg" id="mpg" name="mpg"  />
</td>


<td class="table_label">
    <span class="display_field">Weight Empty</span>
</td>
<td class="table_data">
    <input type="text" name="weight_empty" id="weight_empty" placeholder="weight empty"  />
</td>
  
</tr>



<tr>
<td class="table_label">
    <span class="display_field">Facility</span>
</td>
<td class="table_data">
    <?php  echo getFacilityList(""); ?>

</td>

<td style="text-align: right;">R & M</td><td style="text-align: left;"><input type="text" id="r_m" name="r_m"    /></td>
<td>
        <span class="display_field" id="option_vehicle_type_label_id_140">Type</span>
    </td>
    <td>
        <input type="text" name="type" id="type" placeholder="type"  />
    </td>
    <td class="table_label">
        <span class="display_field" id="option_vehicle_status_label_id_140">Vehicle Status</span>
    </td>
    <td class="table_data">
    
        <?php echo truck_service(""); ?>
    </td>
</tr>

<tr>
<td class="table_label"><span class="display_field" >IKG Code</span>
</td>
<td valign="top" align="left" class="table_data">

<input type="text" id="ikg_code" name="ikg_code"  />


</td>
<td class="table_label"><span class="display_field" >Insurance Carrier</span>
</td>
<td class="table_data">

<input type="text" id="carrier" name="carrier" placeholder="Carrier"  />

</td>

<td class="table_label"><span class="display_field" >Insurance ID</span>
</td>
<td valign="top" align="left" class="table_data"><span id="vehicle_insurance_id_value_id_140"></span>

<input type="text" name="insurance" id="insurance" placeholder="Insurance ID"  />

</td>

<td style="text-align: right;">Depreciation Rate</td><td style="text-align:left;"><input   type="text" id="dep" name="dep"/></td>


</tr>
</tbody>

<tbody><tr><td colspan="8" style="height: 10px;">&nbsp;</td></tr></tbody>

<tbody class="added">
<tr><td colspan="8"><h2>MAINTENANCE DATES</h2></td></tr>
<tr>
    <td class="table_label"><span class="display_field">Opacity Due</span></td>
    <td><input class="date"  type="text" name="opacity_due" id="opacity_due"   /></td>
    
    <td class="table_label"><span class="display_field">Service Date</span></td>
    <td><input class="date"  type="text" name="service_due" id="service_due"    /></td>
    
    <td class="table_label"><span class="display_field">IKG renew</span></td>
    <td><input class="date"  type="text" name="ikg_renewed" id="ikg_renewed"   /></td>
    
    <td class="table_label"><span class="display_field">Due 90</span></td>
    <td><input class="date"  type="text" name="due_90" id="due_90"   /></td>
</tr>

<tr>
    <td class="table_label"><span class="display_field">Annual Service Due</span></td>
    <td><input class="date"  type="text" name="annual" id="annual"  /></td>
    
    <td class="table_label"><span class="display_field">Registration Date</span></td>
    <td><input class="date"  type="text" name="registration" id="registration"   /></td>
    
    <td class="table_label"><span class="display_field">Other Permit Due</span></td>
    <td><input class="date"  type="text" name="other_permit_due" id="other_permit_due"   /></td>
     
    <td class="table_label"><span class="display_field">Repair Date</span></td>
    <td><input class="date" type="text" name="repair_date" id="repair_date"   /></td>
</tr>
</tbody>

<tbody><tr><td colspan="8" style="height: 10px;">&nbsp;</td></tr></tbody>
<tbody class="added">
<tr><td colspan="8"><h2>MISC</h2></td></tr>
<tr><td>Has GPS?</td><td><input type="checkbox" name="gps"  /></td><td>Has Camera?</td><td><input type="checkbox" name="camera"   /></td><td class="table_label"><span class="display_field">Acquired</span>
</td>
<td valign="top" align="left" class="table_data">
<input type="text" name="date_acq" id="date_acq"  placeholder="Date Acquired" />
</td>
<td>Service at next Mileage</td>
<td><input type="text" name="sanm" id="sanm" placeholder="Service at next mileage" /></td>
    </tr>
    
   
    
<tr>

<td>Description</td>
<td colspan="3"><textarea name="descri" id="descri" style="width: 90%;height:200px;"></textarea></td>
<td >

Notes 
</td>
<td  colspan="3">
<textarea name="veh_notes" id="veh_notes" style="width: 90%;height:200px;"></textarea>

</td>


</tr>
</tbody>
</table>
    <div style="clear: both;"></div>

</div>
<input type="hidden" name="tid"/>
</form>

<script>
$("body #transparent").remove();
$("#lic_renewed").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
$("#end_date").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
$(".date").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
$('#year').datepicker({dateFormat: 'yy',changeMonth: false, changeYear: true,yearRange: "1:c+10"});
$("#date_acq").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
</script>
<?php include "../source/footer.php";  ?>

</body>
</html>
<?php } ?>