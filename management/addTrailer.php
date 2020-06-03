<?php 
include "../protected/global.php";
//ini_set("display_errors",1);
$person = new Person();
$headers = 'From: asset-management@iwpusa.com' . "\r\n" .
                'Reply-To: no-reply@iwpusa.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();


    
   
    // var_dump($buffer);
    //$dd  = date("Y-m-d");   
    
    
      if( isset(  $_POST['addfr'])  ) {
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
        
        $new_acq = explode("/",$_POST['acquired']);
        if(empty($new_acq)){
            $new_acq = array(
                0=>"00",
                1=>"00",
                2=>"0000"
            );
        }
        
        $buffer = Array(
          "status"=>"$_POST[status]",
          "vin"=>$_POST['vin'],
          "name"=>"$_POST[name]",
          "truck_year"=>"$new_year[2]-$new_year[1]-$new_year[0]",
          "truck_model"=>"$_POST[model]",
          "max_capacity"=>"$_POST[capac]",
          "division"=>"$_POST[facility]",
          "acquired"=>"$new_acq[2]-$new_acq[1]-$new_acq[0]",
          "renewed"=>"$new_renew[2]-$new_renew[1]-$new_renew[0]",
          "expires"=>"$new_end[2]-$new_end[1]-$new_end[0]",
          "facility"=>$_POST['facility'],
          "plates"=>$_POST['plates'],
          "rm_total"=>$_POST['r_m'],
          "module"=>"grease",
          "enabled"=>0      
        );
        echo "<pre>";
        print_r($buffer);
        echo "</pre>";
        if($db->insert("assets.trailers",$buffer)){
            $db->insert("freight_truck_id",$buffer);
            echo "Trailer $_POST[name] added.  Waiting for approval.";    
            mail("KMickle@iwpusa.com","New Asset added via Grease Module"," Please log in asset module to review  $_POST[name] ",$headers);
        }
        
            
    } else {
        echo "Please enter a status, vin, name, year, model,  facility and plates.<br/>";
    }


?>


<link rel="stylesheet" href="../css/style.css" />
<style type="text/css">
body{
    padding:9px 9px 9px 9px;
    text-align:center;
}

.field { 
    margin-top:10px;
    padding-left: 10px;
    width:23%;
    float:left;
    font-family:Tahoma;
    letter-spacing:1px;
   height:20px;
}
input[type=text]{ 
    border-radius:5px;
    border:1px solid #bbb;
    width:161px;
    height:25px;
}
td{
    padding:0px 0px 0px 0px;
}
#adduser { 
    box-shadow:         1px 1px 1px 1px #000000;
    width: 450px;min-height:400px;height:auto;margin:10px auto;border-radius:10px;border:1px solid black;padding-top:10px;
    padding-bottom:10px;
}


</style>
<link rel="stylesheet" href="../css/auto.css"/>
<script type="text/javascript" src="../js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<body>
 
<form action="addTrailer.php" method="post">
<div id="adduser" style="margin-top:20px;background:rgb(204, 204, 204);height:auto;">
<table style="width: 99%;margin:auto;">
    <tr><td style="text-align: right;vertical-align: top;font-weight:bold;">Status:</td><td style="text-align: left;vertical-align:top;"><select name="status"><option value="active">Active</option><option value="nonactive">Non-Active</option></select>*</td></tr>
    
    <tr><td style="text-align: right;vertical-align: top;font-weight:bold;">Name:</td><td style="text-align: left;vertical-align:top;"><input type="text" placeholder="Trailer Name" name="name" />*<br /><br /></td></tr>
    
    
    <tr><td style="text-align: right;vertical-align: top;font-weight:bold;">Vin:</td><td style="text-align: left;vertical-align:top;"><input type="text" placeholder="Trailer Vin" name="vin" />*<br /><br /></td></tr>
    
    <tr><td style="text-align: right;vertical-align: top;font-weight:bold;">R & M:</td><td style="text-align: left;vertical-align:top;"><input type="text" placeholder="R & M" name="r_m" />*<br /><br /></td></tr>
    
    <tr><td style="text-align: right;vertical-align: top;font-weight:bold;">Plates:</td><td style="text-align: left;vertical-align:top;"><input type="text" placeholder="License Plate" name="plates" />*</td></tr>
    
    
    <tr><td style="text-align: right;vertical-align: top;font-weight:bold;">Type:</td><td style="text-align: left;vertical-align:top;"><input type="text" placeholder="License Type" name="type" /></td></tr>
    
    <tr><td style="text-align: right;vertical-align: top;font-weight:bold;">Year:</td><td style="text-align: left;vertical-align:top;"><input type="text" placeholder="Year" name="year" id="year"  />*</td></tr>
    
    
    <tr><td style="text-align: right;vertical-align: top;font-weight:bold;">Model:</td><td style="text-align: left;vertical-align:top;"><input type="text" placeholder="Model" name="model" />*</td></tr>
    
    <tr><td style="text-align: right;vertical-align: top;font-weight:bold;">Capacity:</td><td style="text-align: left;vertical-align:top;"><input type="text" placeholder="Capacity" name="capac" /></td></tr>
    
    
    <tr><td style="text-align: right;vertical-align: top;font-weight:bold;">Facility:</td><td style="text-align: left;vertical-align:top;"><?php getFacilityList("facility",""); ?>*</td></tr>



<tr><td style="text-align: right;vertical-align: top;font-weight:bold;">Acquired</td><td style="text-align: left;vertical-align:top;"><input type="text" placeholder="Acquired" name="acquired" id="date_acq" /></td></tr>


<tr><td style="text-align: right;vertical-align: top;font-weight:bold;">Lic. Renewed&nbsp;</td><td style="text-align: left;vertical-align:top;"><input type="text" placeholder="Date of license renewal"  id="lic_renewed" name="lic_renewed"/></td></tr>


<tr><td style="text-align: right;vertical-align: top;font-weight:bold;">End Date&nbsp;</td><td style="text-align: left;vertical-align:top;"><input type="text" placeholder="End Date"  id="end_date" name="end_date"/></td></tr>

<tr><td colspan="2" style="height: 10px;">* denotes required</td></tr>

<tr><td colspan="2" style="text-align:left;">
<input type="submit" value="Add Trailer" name="addfr" style="margin-left: 285px;"/></td></tr>
</table>
</form>

<div style="clear: both;"></div>

</div>

<script>


$("#lic_renewed").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
$("#end_date").datepicker({dateFormat: "yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});

$('#year').datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
$("#date_acq").datepicker({dateFormat: "mm/dd/yy",changeMonth: true, changeYear: true,yearRange: "1:c+10"});            
            
</script>
</body>