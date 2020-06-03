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
</style>
<div id="adduser" style="margin-top:90px;background:rgb(212, 208, 200)">
    <div class="field">
        <label for="first_name">First Name</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="first_name" name="first_name" placeholder="first name" required/>
    </div>
    
    <div class="field">
        <label for="last_name">Last Name</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="last_name" name="last_name"  placeholder="first name" required/>
    </div>
    
    <div class="field">
        <label for="last_name">Middle Name</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="middle_name" name="middle_name"  placeholder="middle name" required/>
    </div>
    
    <div class="field">
        <label for="email">Email</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="email" name="email"  placeholder="email" required/>
    </div>
    
    <div class="field">
        <label for="address">Address</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="address" name="address"  placeholder="address" required/>
    </div>
    
    <div class="field">
        <label for="city">City</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="city" name="city"  placeholder="city" required/>
    </div>
    
    <div class="field">
        <label for="state">State</label>&nbsp;
    </div>
    <div class="field">
        <select name="state" id="state"><option value="" selected="selected">Select a State</option> 
<option value="AL">Alabama</option> 
<option value="AK">Alaska</option> 
<option value="AZ">Arizona</option> 
<option value="AR">Arkansas</option> 
<option value="CA">California</option> 
<option value="CO">Colorado</option> 
<option value="CT">Connecticut</option> 
<option value="DE">Delaware</option> 
<option value="DC">District Of Columbia</option> 
<option value="FL">Florida</option> 
<option value="GA">Georgia</option> 
<option value="HI">Hawaii</option> 
<option value="ID">Idaho</option> 
<option value="IL">Illinois</option> 
<option value="IN">Indiana</option> 
<option value="IA">Iowa</option> 
<option value="KS">Kansas</option> 
<option value="KY">Kentucky</option> 
<option value="LA">Louisiana</option> 
<option value="ME">Maine</option> 
<option value="MD">Maryland</option> 
<option value="MA">Massachusetts</option> 
<option value="MI">Michigan</option> 
<option value="MN">Minnesota</option> 
<option value="MS">Mississippi</option> 
<option value="MO">Missouri</option> 
<option value="MT">Montana</option> 
<option value="NE">Nebraska</option> 
<option value="NV">Nevada</option> 
<option value="NH">New Hampshire</option> 
<option value="NJ">New Jersey</option> 
<option value="NM">New Mexico</option> 
<option value="NY">New York</option> 
<option value="NC">North Carolina</option> 
<option value="ND">North Dakota</option> 
<option value="OH">Ohio</option> 
<option value="OK">Oklahoma</option> 
<option value="OR">Oregon</option> 
<option value="PA">Pennsylvania</option> 
<option value="RI">Rhode Island</option> 
<option value="SC">South Carolina</option> 
<option value="SD">South Dakota</option> 
<option value="TN">Tennessee</option> 
<option value="TX">Texas</option> 
<option value="UT">Utah</option> 
<option value="VT">Vermont</option> 
<option value="VA">Virginia</option> 
<option value="WA">Washington</option> 
<option value="WV">West Virginia</option> 
<option value="WI">Wisconsin</option> 
<option value="WY">Wyoming</option></select>
    </div>
    
    <div class="field">
        <label for="zipcode">Zipcode</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="zipcode" name="zipcode"  placeholder="zipcode" required/>
    </div>
    
    <div class="field">
        <label for="areacode">Areacode</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" maxlength="3" id="areacode" name="areacode"  placeholder="111" required/>
    </div>

    
    <div class="field">
        <label for="areacode">Phone</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="phone" name="phone" maxlength="7"  placeholder="2223333" required/>
    </div>

    
     <div class="field">
        <label for="title">Title</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="title" name="title"  placeholder="title" required/>
    </div>
    
    
     <div class="field">
        <label for="facility">Facility</label>&nbsp;
    </div>
    <div class="field">
        <select  id="facility" name="facility"  >
        
        <option value="22">San Diego (Division US)</option>
        <option value="23">Coachella (Division R)</option>
        <option value="24">LA (Division UC)</option>
        <option value="30">LA (Division UC-Norm)</option>
        <option value="31">LA (Division UC-Ramon)</option>
        <option value="32">LA (Division UC-Chato)</option>
        <option value="33">LA (Division UC-Chuck)</option>
        <option value="8">Arizona (Division 4)</option>
        <option value="5">Selma (Division V)</option>
        <option value="15">W Division</option>
        </select>
    </div>
    
     <div class="field">
        <label for="duty">Duty</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="duty" name="duty"  placeholder="duty"/>
    </div>
    
    
    
     <div class="field">
        <label for="username">Username</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="username" name="username"  placeholder="username" readonly/>
    </div>
    
     <div class="field">
        <label for="password">Password</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="password" name="password"  placeholder="password" required/>
    </div>
    
     <div class="field">
        <label for="confirm">Confirm Password</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="confirm" name="confirm"  placeholder="" required/>
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
    
    <!---
     <div class="field">
        <label for="facility_restriction">Facility Restriction</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="facility_restriction" name="facility_restriction"  placeholder="facility restriction"/>
    </div>
    
     <div class="field">
        <label for="division_restriction">Division Restriction</label>&nbsp;
    </div>
    <div class="field">
        <input type="text" id="division_restriction" name="division_restriction"  placeholder="division restriction"/>
    </div>
    ---!>
    
    <div id="last_row" style="width: 100%;height:auto; float:left;margin-top:15px;">
        <div class="field">
        <label for="areacode">Roles <input id="checkall" type="checkbox"/>&nbsp;Check All</label><br /> <span style="font-size:10px;">(check that apply) </span>&nbsp;
    </div>
    <div class="field" style="height: auto;text-align:left;margin-top:15px;">
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="admin"  type="checkbox"/>&nbsp;Admin</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="customer support"  type="checkbox"/>&nbsp;Customer Support (Basic)</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="customer support full" type="checkbox" />&nbsp;Customer Support(Full)</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="routing" type="checkbox" />&nbsp;Routing</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="advancing routing" type="checkbox"/>&nbsp;Routing (Advanced)</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="advanced searching" type="checkbox"/>&nbsp;Searching (Advanced)</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="report access" type="checkbox" />&nbsp;Reporting Access</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="customer support advanced" type="checkbox" />&nbsp;Customer Support (Advanced)</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="sales management" type="checkbox" />&nbsp;Sales Management</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="staff management" type="checkbox" />&nbsp;Staff Management</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="user management" type="checkbox" />&nbsp;User Management</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="data management" type="checkbox" />&nbsp;Data Management</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="account represntative"  type="checkbox"/>&nbsp;Account Representative</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="sales representative"  type="checkbox" />&nbsp;Sales Representative</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="sales leads"  type="checkbox" />&nbsp;Sales Leads User</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="service driver"  type="checkbox" />&nbsp;Service Driver</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="oil driver"  type="checkbox" />&nbsp;Oil Driver</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="scheduler"  type="checkbox" />&nbsp;Scheduler</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="facility manager"  type="checkbox" />&nbsp;Facility Manager</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="corporate manager"  type="checkbox" />&nbsp;Corporate Manager</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="data management" type="checkbox" />&nbsp;Data Management</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="data entry" type="checkbox" />&nbsp;Data Entry</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="sales zone" type="checkbox" />&nbsp;Sales Zone Manager</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="assigned issues"  type="checkbox" />&nbsp;Can be Assigned Issues</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="new fires"  type="checkbox" />&nbsp;MMS for New fires</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="call center"  type="checkbox" />&nbsp;MMS for Call Center Message</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles"  value="theft" type="checkbox" />&nbsp;MMS for Theft Alert</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="phone message" type="checkbox" />&nbsp;Receive Phone Messages</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="shop crew" type="checkbox" />&nbsp;Shop Crew</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="Friendly" type="checkbox"/>&nbsp;Friendly</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="cowest" type="checkbox"/>&nbsp;Cowest Manager</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="cowestdriver" type="checkbox"/>&nbsp;Cowest Driver</div>
        <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="creditmanager" type="checkbox"/>&nbsp;Credit Manager</div>
          <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="payrollmanager" type="checkbox"/>&nbsp;Payroll Manager</div>
          
          <div class="checkrow" style="width: 50%;height:55px;margin-bottom:2px;margin-top:2px;float:left;"><input class="roles" value="Master Lock" type="checkbox"/>&nbsp;Master Lock</div>
        
    </div>
    
         <div class="field">
        <label for="notes">Notes</label>&nbsp;
    </div>
    <div class="field" style="height:auto;">
        <textarea style="width: 95%;height:200px;"></textarea>
        
    </div>  
        
    </div>
    
    <div id="submit" style="width: 100%;padding:5px 5px 5px 5px;margin-top:5px;float:left;">
        <input type="submit" name="usercreate" id="usercreate" style="float: right;margin-right:10px;" value="Create User"/>
    </div>
    
    <div style="clear: both;"></div>
</div>
<script>

$("input#first_name").change(function(){
   var firstnamefield = $(this).val();
   var lastnamefield  = $("input#last_name").val();
   if ( lastnamefield.length !=0  ){ 
       var finit = firstnamefield.split("");
       var username = finit[0]+lastnamefield;
       $("input#username").val(username);  
   }
});

$("input#last_name").change(function(){
   var lastnamefield = $(this).val();
   var firstnamefield= $("input#first_name").val();
   
   if( firstnamefield.length !=0){ 
     var finit = firstnamefield.split("");
     var username = finit[0] + lastnamefield;
     $("input#username").val(username)
   }
});


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

$("body #transparent").remove();
$("#usercreate").click(function(){
         var usertype="";
         $(".roles:checked").each(function(){
            if($(this).val().length !=0){
                usertype = $(this).val()+"~"+usertype;
            }
         });     
        
     if(  $("input#password").val() == $("input#confirm").val() ) { 
        alert(usertype);
        $.post("insertUser.php",{
            fname:          $("input#first_name").val(), 
            lname:          $("input#last_name").val(), 
            mname:          $("input#middle_name").val(),
            email:          $("input#email").val(),
            address:        $("input#address").val(),
            city:           $("input#city").val(),
            state:          $("#state").val(),
            zipcode :       $("input#zipcode").val(),
            areacode:       $("input#areacode").val(),
            phone:          $("input#phone").val(),
            
            carrier:        "verizon",
            title:          $("input#title").val(),
            facility :      $("#facility").val(),
            duty :          $("input#duty").val(),
            username:       $("input#username").val(),
            password:       $("input#password").val(),
            pay:            $("input#pay").val(),
            coa:            $("input#coa").val(),
            roles:          usertype
            },function(data){
                alert(data);
                $("input[type=text], textarea").val("");
                $('.roles').each(function() { //loop through each checkbox
                    this.checked = false;  //select all checkboxes with class "checkbox1"              
                });
                $("#usercreate").checked = false;
         });
     } else { 
        alert('Passwords do not match');
     }
});

</script>
