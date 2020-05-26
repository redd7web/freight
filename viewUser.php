<?php include "protected/global.php"; $page = "Management | View User"; if(isset($_SESSION['sludge_id'])){  
    
    $person = new Person();
    $edit_person = new Person($_GET['id']); 
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="ReDDaWG" />
    <meta charset="UTF-8" />
   <?php include "source/css.php"; ?>
   <?php include "source/scripts.php"; ?> 
	<title>Customer Management System</title>
</head>

<body>
<?php include "source/header.php"; ?>

<div id="wrapper" style="margin-top: 90px;">
<div id="spacer" style="width: 100%;height:10px;">&nbsp;</div>

<div class="content-wrapper" style="min-height:450px;height: auto;">
<div class="panel-pane pane-views pane-site-channel" >
  
      
  
  <!--<div class="pane-content">-->
    <div class="view view-site-channel view-id-site_channel view-display-id-block_1 view-dom-id-414f899872b9e7e4b76dcc6bd791c02b">
        
  
  
      <div class="view-content">

<style type="text/css">

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
    width:95%;
    height:25px;
}

#adduser { 
    box-shadow:         1px 1px 3px 3px #70a170;
    width: 900px;min-height:400px;height:auto;margin:10px auto;border-radius:10px;border:1px solid black;padding-top:10px;
    padding-bottom:10px;
}
td{
    text-align:left;
    vertical-align:top;
}
</style>




<div id="adduser" >
    <div class="field">
        <label for="first_name">First Name</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="first_name" name="first_name" placeholder="first name" value="<?php echo $edit_person->first_name; ?>" required/>
    </div>
    
    <div class="field">
        <label for="last_name">Last Name</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="last_name" name="last_name"  placeholder="first name" value="<?php echo $edit_person->last_name; ?>" required/>
    </div>
    
    <div class="field">
        <label for="last_name">Middle Name</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="middle_name" name="middle_name"  placeholder="middle name" value="<?php echo $edit_person->middle_name; ?>" required/>
    </div>
    
    <div class="field">
        <label for="email">Email</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="email" name="email"  placeholder="email" value="<?php echo $edit_person->email; ?>"/>
    </div>
    
    <div class="field">
        <label for="address">Address</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="address" name="address"  placeholder="address" value="<?php echo $edit_person->address; ?>"/>
    </div>
    
    <div class="field">
        <label for="city">City</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="city" name="city"  placeholder="city" value="<?php echo $edit_person->city; ?>"/>
    </div>
    
    <div class="field">
        <label for="state">State</label>&nbsp;
    </div>
    <div class="field">
        <select name="state" id="state">
<option value="AL" <?php if($edit_person->state == "AL"){ echo "selected";} ?>  >Alabama</option> 
<option value="AK" <?php if($edit_person->state == "AK"){ echo "selected";} ?>>Alaska</option> 
<option value="AZ" <?php if($edit_person->state == "AZ"){ echo "selected";} ?>>Arizona</option> 
<option value="AR" <?php if($edit_person->state == "AR"){ echo "selected";} ?>>Arkansas</option> 
<option value="CA" <?php if($edit_person->state == "CA"){ echo "selected";} ?>>California</option> 
<option value="CO" <?php if($edit_person->state == "CO"){ echo "selected";} ?>>Colorado</option> 
<option value="CT" <?php if($edit_person->state == "CT"){ echo "selected";} ?>>Connecticut</option> 
<option value="DE" <?php if($edit_person->state == "DE"){ echo "selected";} ?>>Delaware</option> 
<option value="DC" <?php if($edit_person->state == "DC"){ echo "selected";} ?>>District Of Columbia</option> 
<option value="FL" <?php if($edit_person->state == "FL"){ echo "selected";} ?>>Florida</option> 
<option value="GA" <?php if($edit_person->state == "AL"){ echo "selected";} ?>>Georgia</option> 
<option value="HI" <?php if($edit_person->state == "HI"){ echo "selected";} ?>>Hawaii</option> 
<option value="ID" <?php if($edit_person->state == "ID"){ echo "selected";} ?>>Idaho</option> 
<option value="IL" <?php if($edit_person->state == "IL"){ echo "selected";} ?>>Illinois</option> 
<option value="IN" <?php if($edit_person->state == "IN"){ echo "selected";} ?>>Indiana</option> 
<option value="IA" <?php if($edit_person->state == "IA"){ echo "selected";} ?>>Iowa</option> 
<option value="KS" <?php if($edit_person->state == "KS"){ echo "selected";} ?>>Kansas</option> 
<option value="KY" <?php if($edit_person->state == "KY"){ echo "selected";} ?>>Kentucky</option> 
<option value="LA" <?php if($edit_person->state == "LA"){ echo "selected";} ?>>Louisiana</option> 
<option value="ME" <?php if($edit_person->state == "ME"){ echo "selected";} ?>>Maine</option> 
<option value="MD" <?php if($edit_person->state == "MD"){ echo "selected";} ?>>Maryland</option> 
<option value="MA" <?php if($edit_person->state == "MA"){ echo "selected";} ?>>Massachusetts</option> 
<option value="MI" <?php if($edit_person->state == "MI"){ echo "selected";} ?>>Michigan</option> 
<option value="MN" <?php if($edit_person->state == "MN"){ echo "selected";} ?>>Minnesota</option> 
<option value="MS" <?php if($edit_person->state == "MS"){ echo "selected";} ?>>Mississippi</option> 
<option value="MO" <?php if($edit_person->state == "MO"){ echo "selected";} ?>>Missouri</option> 
<option value="MT" <?php if($edit_person->state == "MT"){ echo "selected";} ?>>Montana</option> 
<option value="NE" <?php if($edit_person->state == "NE"){ echo "selected";} ?>>Nebraska</option> 
<option value="NV" <?php if($edit_person->state == "NV"){ echo "selected";} ?>>Nevada</option> 
<option value="NH" <?php if($edit_person->state == "NH"){ echo "selected";} ?>>New Hampshire</option> 
<option value="NJ" <?php if($edit_person->state == "NJ"){ echo "selected";} ?>>New Jersey</option> 
<option value="NM" <?php if($edit_person->state == "NM"){ echo "selected";} ?>>New Mexico</option> 
<option value="NY" <?php if($edit_person->state == "NY"){ echo "selected";} ?>>New York</option> 
<option value="NC" <?php if($edit_person->state == "NC"){ echo "selected";} ?>>North Carolina</option> 
<option value="ND" <?php if($edit_person->state == "ND"){ echo "selected";} ?>>North Dakota</option> 
<option value="OH" <?php if($edit_person->state == "OH"){ echo "selected";} ?>>Ohio</option> 
<option value="OK" <?php if($edit_person->state == "OK"){ echo "selected";} ?>>Oklahoma</option> 
<option value="OR" <?php if($edit_person->state == "OR"){ echo "selected";} ?>>Oregon</option> 
<option value="PA" <?php if($edit_person->state == "PA"){ echo "selected";} ?>>Pennsylvania</option> 
<option value="RI" <?php if($edit_person->state == "RI"){ echo "selected";} ?>>Rhode Island</option> 
<option value="SC" <?php if($edit_person->state == "SC"){ echo "selected";} ?>>South Carolina</option> 
<option value="SD" <?php if($edit_person->state == "SD"){ echo "selected";} ?>>South Dakota</option> 
<option value="TN" <?php if($edit_person->state == "TN"){ echo "selected";} ?>>Tennessee</option> 
<option value="TX" <?php if($edit_person->state == "TX"){ echo "selected";} ?>>Texas</option> 
<option value="UT" <?php if($edit_person->state == "UT"){ echo "selected";} ?>>Utah</option> 
<option value="VT" <?php if($edit_person->state == "VT"){ echo "selected";} ?>>Vermont</option> 
<option value="VA" <?php if($edit_person->state == "VA"){ echo "selected";} ?>>Virginia</option> 
<option value="WA" <?php if($edit_person->state == "WA"){ echo "selected";} ?>>Washington</option> 
<option value="WV" <?php if($edit_person->state == "WV"){ echo "selected";} ?>>West Virginia</option> 
<option value="WI" <?php if($edit_person->state == "WI"){ echo "selected";} ?>>Wisconsin</option> 
<option value="WY" <?php if($edit_person->state == "WY"){ echo "selected";} ?>>Wyoming</option></select>
    </div>
    
    <div class="field">
        <label for="zipcode">Zipcode</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="zipcode" name="zipcode"  placeholder="zipcode" value="<?php echo $edit_person->zipcode; ?>"/>
    </div>
    
    <div class="field">
        <label for="areacode">Areacode</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" maxlength="3" id="areacode" name="areacode"  placeholder="111" value="<?php echo $edit_person->areacode; ?>"/>
    </div>

    
    <div class="field">
        <label for="areacode">Phone</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="phone" name="phone" maxlength="7"  placeholder="2223333"  value="<?php echo $edit_person->phone; ?>"/>
    </div>

    <div class="field">
        <label for="areacode">Phone Carrier</label>&nbsp;
    </div>
    <div class="field">
        <select name="carrier">
            <option value="tmobile" <?php if($edit_person->carrier == "tmobile") { echo "selected";} ?> >T Mobile</option>
            <option value="att" <?php if($edit_person->carrier == "att") { echo "selected";} ?>>AT&T</option>
            <option value="verizon" <?php if($edit_person->carrier == "verizon") { echo "selected";} ?>>Verizon</option>
            <option value="sprint" <?php if($edit_person->carrier == "sprint") { echo "selected";} ?>>Sprint</option>
        </select>
    </div>
    

    

    
     <div class="field">
        <label for="title">Title</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="title" name="title"  placeholder="title" value="<?php echo $edit_person->title; ?>"/>
    </div>
    
    
     <div class="field">
        <label for="facility">Facility</label>&nbsp;
    </div>
    <div class="field">
        
        
        <?php getFacilityList("",$edit_person->facility); ?>
    </div>
    
     <div class="field">
        <label for="duty">Duty</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="duty" name="duty"  placeholder="duty" value="<?php echo $edit_person->duty; ?>"/>
    </div>
    
    
    
    
    
     <div class="field">
        <label for="facility_restriction">Facility Restriction</label>&nbsp;
    </div>
    <div class="field">
        <?php  getFacilityList("facility_restrict",$edit_person->facility_restriction);  ?>
        
    </div>
    
     <div class="field">
        <label for="division_restriction">Division Restriction</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="division_restriction" name="division_restriction"  placeholder="division restriction"  value="<?php echo $edit_person->division_restriction; ?>"/>
    </div>
    
    <div class="field">
        <label for="pay">Hourly Pay</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="pay" name="pay"  placeholder="Hourly Pay (Payroll Manager Only)"  value="<?php echo $edit_person->driver_hourly_pay; ?>" <?php if(!$person->isPayroll()){ echo " readonly ";  } ?>/>
    </div>
    
    <div class="field">
        <label for="pay">New Bos COA</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="coa" name="coa"  placeholder="COA"  value="<?php echo $edit_person->driver_hourly_pay; ?>" <?php if(!$person->isPayroll()){ echo " readonly ";  } ?>/>
    </div>
    
    <div id="last_row" style="width: 100%;height:auto; float:left;">
        <div class="field">
        <label for="areacode">Roles <input id="checkall" type="checkbox"/>&nbsp;Check All</label><br /> <span style="font-size:10px;">(check that apply) </span>&nbsp;</label>&nbsp;
    </div>
    
    <?php 
    
    
    ?>
    <div class="field" style="height: auto;text-align:left;">
        <table style="width: 100%;font-size:10px;">
        
            <tr>
                <td><input  class="roles" value="admin" type="checkbox" <?php if($edit_person->isAdmin()){ echo "checked";} ?>/>&nbsp;Admin</td>
                <td><input class="roles" value="customer support" type="checkbox" <?php if($edit_person->isCustSupport_Basic()){ echo "checked";} ?>/>&nbsp;Customer Support(Basic)</td>
            </tr>
            
            <tr>
                <td><input class="roles" value="customer support full" type="checkbox"   <?php if($edit_person->isCSupport_Full()){ echo "checked";} ?> />&nbsp;Customer Support(Full)</td>
                <td><input class="roles" value="routing" type="checkbox"   <?php if($edit_person->isRouting()){ echo "checked";} ?> />&nbsp;Routing</td>    
            </tr>
            
            <tr>
                <td><input class="roles" value="advancing routing" type="checkbox"   <?php if($edit_person->isRouting_Adv()){ echo "checked";} ?> />&nbsp;Routing (Advanced)</td>
                <td><input class="roles" value="advanced searching" type="checkbox"   <?php if($edit_person->isSearch()){ echo "checked";} ?> />&nbsp;Searching (Advanced)</td>
            </tr>
            
            <tr>
                <td><input class="roles" value="report access" type="checkbox"   <?php if($edit_person->isReport()){ echo "checked";} ?> />&nbsp;Reporting Access</td>
                <td><input class="roles" value="customer support advanced" type="checkbox"   <?php if($edit_person->isCSupport_Adv()){ echo "checked";} ?> />&nbsp;Customer Support (Advanced)</td>
            </tr>
            
            <tr>
                <td><input class="roles" value="sales management" type="checkbox"   <?php if($edit_person->isSalesManagement()){ echo "checked";} ?> />&nbsp;Sales Management</td>
                 <td><input class="roles" value="staff management" type="checkbox"   <?php if($edit_person->isStaffManagement()){ echo "checked";} ?> />&nbsp;Staff Management</td>
            </tr>
            
            <tr>
                <td><input class="roles" value="user management" type="checkbox"   <?php if($edit_person->isUserManagement()){ echo "checked";} ?> />&nbsp;User Management</td>
                 <td><input class="roles" value="data management" type="checkbox"   <?php if($edit_person->isDataManage()){ echo "checked";} ?> />&nbsp;Data Management</td>
            </tr>
            
            
            <tr>
                <td><input class="roles" value="account represntative"  type="checkbox" <?php if($edit_person->isAccountRep()){ echo "checked";} ?>/>&nbsp;Account Representative</td>
                <td><input class="roles" value="sales representative"  type="checkbox" <?php if($edit_person->isSalesRep()){ echo "checked";} ?>/>&nbsp;Sales Representative</td>
            </tr>
            
            
            <tr>
                <td><input class="roles" value="sales leads"  type="checkbox" <?php if($edit_person->isSalesLeads()){ echo "checked";} ?>/>&nbsp;Sales Leads User</td>
                <td><input class="roles" value="service driver"  type="checkbox"<?php if($edit_person->isServiceDriver()){ echo "checked";} ?>/>&nbsp;Service Driver</td>
            </tr>
            
            
            <tr>
                <td><input class="roles" value="oil driver"  type="checkbox" <?php if($edit_person->isOilDriver()){ echo "checked";} ?>/>&nbsp;Oil Driver</td>
                <td><input class="roles" value="scheduler"  type="checkbox" <?php if($edit_person->isScheduler()){ echo "checked";} ?>/>&nbsp;Scheduler</td>
            </tr>
            
            
            
            <tr>
                <td><input class="roles" value="facility manager"  type="checkbox" <?php if($edit_person->isFacilityManager()){ echo "checked";} ?>/>&nbsp;Facility Manager</td>
                <td><input class="roles" value="corporate manager"  type="checkbox" <?php if($edit_person->isCorporateManager()){ echo "checked";} ?>/>&nbsp;Corporate Manager</td>
            </tr>
            
            
            <tr>                
                <td><input class="roles" value="data management" type="checkbox" <?php if($edit_person->isDataManage()){ echo "checked";} ?>/>&nbsp;Data Management</td>
                  <td><input class="roles" value="data entry" type="checkbox"  <?php if($edit_person->isDataEntry()){ echo "checked";} ?>/>&nbsp;Data Entry</td>
            
            <tr>
                <td><input class="roles" value="sales zone" type="checkbox"  <?php if($edit_person->isZoneManager()){ echo "checked";} ?>/>&nbsp;Sales Zone Manager</td>
                <td><input class="roles" value="assigned issues"  type="checkbox" <?php if($edit_person->isAssignIssue()){ echo "checked";} ?>/>&nbsp;Can be Assigned Issues</td>
            </tr>
            
            
            <tr>
                <td><input class="roles" value="new fires"  type="checkbox" <?php if($edit_person->MMSforFires()){ echo "checked";} ?>/>&nbsp;MMS for New fires</td>
                <td><input class="roles" value="call center"  type="checkbox" <?php if($edit_person->MMSCallCenter()){ echo "checked";} ?>/>&nbsp;MMS for Call Center Message</td>
            </tr>
            
            <tr>
                <td><input class="roles"  value="theft" type="checkbox" <?php if($edit_person->MMSforTheft()){ echo "checked";} ?>/>&nbsp;MMS for Theft Alert</td>
                <td><input class="roles" value="phone message" type="checkbox" <?php if($edit_person->PhoneMsgs()){ echo "checked";} ?>/>&nbsp;Receive Phone Messages</td>
            </tr>
            
          
            <tr>              
                <td><input class="roles" value="shop crew" type="checkbox"  <?php if($edit_person->isShopCrew()){ echo "checked";} ?>/>&nbsp;Shop Crew</td>
               <td><input class="roles" value="Friendly" type="checkbox"  <?php if($edit_person->isFriendly()){ echo "checked";} ?>/>&nbsp;Friendly </td>
            </tr>
            <tr>
                 <td><input class="roles" value="cowest" type="checkbox"/>&nbsp;Cowest user</td>
                 <td><input class="roles" value="cowestdriver" type="checkbox"  <?php if($edit_person->isCoWestDriver()){ echo "checked";} ?>/>&nbsp;Cowest driver</td>
            </tr>
            <tr><td><input class="roles" value="creditmanager" type="checkbox"  <?php if($edit_person->isCreditManager()){ echo "checked";} ?>/>&nbsp;Credit Manager</td>
            <td><input class="roles" value="payrollmanager" type="checkbox"   <?php if($edit_person->isPayroll()){ echo "checked";} ?>/>&nbsp;Payroll Manager</td>
            </tr>  
            <tr>
             <td><input class="roles" value="Master Lock" type="checkbox"  <?php if($edit_person->isMasterLock()){ echo "checked";} ?>/>&nbsp;Master Lock</td>
            </tr> 
            </tr>
        </table>
       
    </div>
    
         <div class="field">
        <label for="notes">Notes</label>&nbsp;
    </div>
    <div class="field" style="height:auto;">
        <textarea style="width: 95%;height:200px;"><?php echo $edit_person->notes; ?></textarea>
        <div style="clear: both;"></div>
    </div>  
        <div style="clear: both;"></div>
    </div>
    
    <div id="submit" style="width: 100%;padding:5px 5px 5px 5px;margin-top:5px;float:left;">
        <input type="submit" name="edituser" id="edituser"  style="float: right;margin-right:10px;" value="Edit User"/>
    </div>
    
    <div style="clear: both;"></div>
</div>
 </div>
</div>
  
      
  
  
  
  
</div>  <!--</div>-->

  
  </div>

</div>
</div>
<script>
$("body #transparent").remove();
$("#checkall").click(function(){
    if( $(this).is(":checked")  ) { 
      $('.roles').each(function() { //loop through each checkbox
            this.checked = true;  //select all checkboxes with class "checkbox1"              
      });  
    } else { 
       $('.roles').each(function() { //loop through each checkbox
            this.checked = false;  //select all checkboxes with class "checkbox1"              
       }); 
    } 
});

$("#edituser").click(function(){
         var usertype="";
         $(".roles:checked").each(function(){
            if($(this).val().length !=0){
                usertype = $(this).val()+"~"+usertype;
            }
         });     
        alert(usertype);
     
        $.post("edituser.php",{
            fname:                  $("input#first_name").val(), 
            lname:                  $("input#last_name").val(), 
            mname:                  $("input#middle_name").val(),
            email:                  $("input#email").val(),
            address:                $("input#address").val(),
            city:                   $("input#city").val(),
            state:                  $("#state").val(),
            zipcode :               $("input#zipcode").val(),
            areacode:               $("input#areacode").val(),
            phone:                  $("input#phone").val(),            
            carrier:                "verizon",
            title:                  $("input#title").val(),
            facility :              $("#facility").val(),
            facility_restrict:      $("#facility_restrict").val(),
            duty :                  $("input#duty").val(),
            pay:                    $("input#pay").val(),
            coa:                    $("input#coa").val(),
            roles:                  usertype,
            id:                     <?php echo $_GET['id']; ?>
            },function(data){
                alert(data);
         });
     
     return false;
});

</script>
<?php include "source/footer.php"; ?>

</body>
</html>