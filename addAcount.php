
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
    box-shadow:         1px 1px 1px 1px #000000;
    width: 900px;
    min-height:550px;
    height:auto;
    margin:10px auto;
    border-radius:10px;
    border:1px solid black;padding-top:10px;
    padding-bottom:10px;
}
</style>
<form id="myForm" action="insertAccount.php" method="post">
<div id="adduser" style="margin-top:90px;background:rgb(242, 242, 242);height:auto;padding-bottom:10px;">
    <div class="field">
        <label for="first_name">Account Name</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="account_name" name="accountname" placeholder="Account Name" required/>
    </div>
    
    <div class="field">
        <label for="first_name">Sales Representative</label>&nbsp;
    </div>
    <div class="field">
        <select id="sales_rep" name="sales_rep" required>
        <option value="">--</option>
        <?php
        //list of salespersons 
             $sales_reps = $db->query("SELECT user_id FROM `sludge_users` WHERE roles like '%representative%' order by first");
              if(count($sales_reps) >0){
                foreach($sales_reps as $value){
                    $sales_guy = new Person($value['user_id']);
                    echo "<option value=$sales_guy->user_id>$sales_guy->fullname</option>";
                }
              }  
        ?>      
        </select>
    </div>
    
    <div class="field">
        <label for="zipcode">Zipcode</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="zipcode" name="zipcode" maxlength="5"  placeholder="zipcode" required />
    </div>
    <div class="field">
        <label for="contact_first_name">Contact First Name</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="c_first_name" name="c_first_name"  placeholder="Contact First Name" required/>
    </div>
    <div class="field">
        <label for="city">City</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="city" name="city"  placeholder="city" required />
    </div>
    
     
     <div class="field">
        <label for="password">Contact Last Name</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="c_last_name" name="c_last_name"  placeholder="Contact Last Name" required/>
    </div>
    
    <div class="field">
        <label for="state">State</label>&nbsp;
    </div>
    <div class="field">
        <select name="state" id="state" required>
         <option value="">--</option>
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
        <label for="title">Contact Title</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="title" name="title"  placeholder="contact title" required/>
    </div>
    
    
    <div class="field">
        <label for="address">Address</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="address" name="address"  placeholder="address" required/>
    </div>
    
    <div class="field">
        <label for="areacode">Areacode</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  maxlength="3" id="areacode" name="areacode"  placeholder="111" required/>
    </div>
    
    <div class="field">
        <label for="payee">Payee</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="payee" name="payee"  placeholder="Payee Name" required/>
    </div>
    
    
    <div id="rowx" style="width: 50%;height;30px;margin-top:15px;margin-bottom:5px;float:left;font-size:14px;">
        <input type="checkbox" id="same" style="margin-left: 20px;"/>&nbsp;Billing location same as location
        <script>
        $("#same").click(function(){
           if( $("#same").is(":checked") ) { 
                $("input#billing_address").val(  $("input#address").val() );
                $("input#billing_city").val( $("input#city").val() );
                $("input#billing_zip").val( $("input#zipcode").val() );
                $("#billing_state").val( $("#state").val() );
           }  
           else { 
                $("input#billing_address").val(  "" );
                $("input#billing_city").val( "" );
                $("input#billing_zip").val("" );
                $("#billing_zip").val('AL');
           }
        });
        </script>
    </div>
    
    
    
     <div class="field">
        <label for="last_name">Billing Address</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="billing_address" name="billing_address"  placeholder="Billing Address" required/>
    </div>
    
       
    <div class="field">
        <label for="phone">Phone</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="phone" name="phone" maxlength="7"  placeholder="2223333" required/>
    </div>

    
    <div class="field">
        <label for="last_name">Billing City</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="billing_city" name="billing_city"  placeholder="Billing City" required/>
    </div>
    
     <div class="field">
        <label for="duty">Fax Area Code</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="fax_area_code" name="fax_area_code"  placeholder="111" maxlength="3" />
    </div>
    
    
    <div class="field">
        <label for="last_name">Billing State</label>&nbsp;
    </div>
    <div class="field">
        <select name="billing_state" id="billing_state" required>
         <option value="">--</option>
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
        <label for="confirm">Fax Number</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="fax_number" name="fax_number"  placeholder="222333" />
    </div>
    
     <div class="field">
        <label for="last_name">Billing Zipcode</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="billing_zip" name="billing_zip"  placeholder="Billing Zipcode" required/>
    </div>
    
   
    <div class="field">
        <label for="email">Email</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="email" name="email"  placeholder="email" />
    </div>  
    
 
    
     <div class="field">
        <label for="facility_restriction">Website</label>&nbsp;
    </div>
    <div class="field">
        http://<input type="text"  id="website" name="website"  placeholder="wwww.yourdomain.com" style="width: 150px;font-size:11px;"/>
    </div>
    
    
    
    
    <div class="field">
        <label for="estimated_monthly_volume">Grease Trap Size</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="estimated_monthly_volume" name="estimated_monthly_volume"  placeholder="Grease Trap Size" style="font-size:11px;" required />
    </div>
      <div class="field">
        <label for="new_bos">New Bos Number</label>&nbsp;
    </div>
     <div class="field">
        <input type="text" id="new_bos" name="new_bos" placeholder="New Bos Number" />
     </div>
     
      <!--<div class="field">
        <label for="facility_restriction">Floor Type</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  placeholder="Enter Custom" id="floorvalue" name="floorvalue" style="width:125px;" />
        <select name="floor_type" id="floor_type" required>
            <option value="35">.35</option>
            <option value="30">.30</option>
            <option value="25">25</option>
        </select>
    </div>--!>
  
  
     
 
    
    <div class="field">
        <label for="payment_type">Payment Type</label>&nbsp;
    </div>
    <div class="field">
        <select id="payment_type" name="payment_type"   style="font-size:11px;" required>
             <option value="">--</option>
            <option class="payment_option" value="Charge Per Pickup" id="cpp">Charge Per Pickup</option>
            <option class="payment_option" value="Per Gallon" id="pergallon">Per Gallon</option>
            <option class="payment_option" value="One Time Payment" id="otp">One Time Payment</option>
            <option class="payment_option" value="O.T.P. PG" id="otppg">O.T.P. PG</option>
            <option class="payment_option" value="No Pay" id="nopay">No Pay</option>
            <option class="payment_option" value="Cash On Delivery" id="cod">Cash On Delivery</option>
        </select>
    </div>
       
       <!--<div class="field">
        <label for="miu">M.I.U. %</label>&nbsp;
    </div>
    <div class="field">
        %<input type="text"  id="miu" name="miu"  placeholder="M.I.U." value="25" style="font-size:11px;width:180px;" readonly/>
    </div> --!>
       
     
     <div class="field">
        <label for="changer" id="changer">Index/Amount</label>&nbsp;
    </div>
    <div class="field" id="addtional" style="height: auto;">
        <input type="text"  id="changer_input" name="changer_input"   style="font-size:11px;" /><br />
    </div>  
       
    
     <div class="field">
        <label for="end_date">Facility</label>&nbsp;
    </div>
    <div class="field">
        <?php 
        echo getFacilityList("");
        
         ?>
    </div>
     
    <div class="field">
        <label for="frequency">Frequency</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="frequency" name="frequency"  placeholder="Frequency" style="font-size:11px;" required/>
    </div>
       
       
     <div class="field">
        <label for="start_date">Start Date</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="start_date" name="start_date"  placeholder="Click here to choose a date" style="font-size:11px;" required/>
    </div> 
     
     <div class="field">
        <a href="https://inet.iwpusa.com/NewBuyerRequest.php" target="_blank">Request a New Bos Number</a>
     </div>
    <div class="field">
       &nbsp;
    </div> 
     
     <div class="field">
        <label for="end_date">End Date</label>&nbsp;
    </div>
    <div class="field">
        <input type="text"  id="end_date" name="end_date"  placeholder="Click here to choose a date" style="font-size:11px;" required/>
    </div>
    
    
    
     
    <div class="field">
        <label for="sharing">Sharing</label>&nbsp;
    </div>
    <div class="field">
        <input type="checkbox" name="sharing" id="sharing" value="sharing" style="float:left;"/>
        <select name="sharing_type" id="sharing_type" style="float: left;margin-left:5px;">
            <option value="host">Host</option>
            <option value="guest">Guest</option>
        </select>
        <input type="text"  id="accound_host_sharing" name="accound_host_sharing" placeholder="Host Account" style="float: left;width:105px;margin-left:5px;"/>
        
    </div> 
     
     <div class="field">
        <label for="competitors">Competitor on Site?</label>&nbsp;
    </div>
    <div class="field">
        <span style="float: left;"><input type="checkbox" id="on_site" name="on_site" value="Yes" />&nbsp;Yes</span> 
        
            <select id="comp_name" name="comp_name" style="float: left;margin-left:5px;width:150px;" >
            <option value="Advantage Bio">Advantage Bio</option> 
            <option value="Affordable Grease Pumping">Affordable Grease Pumping</option>
            <option value="BioDriven">BioDriven</option> 
             <option value="Buster Bio">Buster Bio</option> 
             <option value="Baker Comm.">Baker Comm.</option> 
             <option value="Darling Int.">Darling Int.</option>
             <option value="New Leaf">New Leaf</option>              
             <option value="Promethian">Promethian</option> 
             <option value="HT Grease">HT Grease</option> 
             <option value="Co-West">Co-West</option>  
             <option value="Industrial Bio">Industrial Bio</option>	
             <option value="JK Collections">JK Collections</option> 
             <option value="AWJ">AWJ</option> 
             <option value="Triple A">Triple A</option> 
             <option value="So-Cal Pumping">So-Cal Pumping</option>	
             <option value="Harbor">Harbor</option>
             <option value="GCI">GCI</option>
             <option value="North County">North County</option>	
           	 <option value="OC Bio">OC Bio</option>
             <option value="Eco-Fry">Eco-Fry</option>
             <option value="Grand Natural">Grand Natural</option>
             <option value="Grease Masters">Grease Masters	</option>	
             <option value="LA Grease Solutions">LA Grease Solutions</option>
             <option value="CGC">CGC</option>	
             <option value="Coastal By-Products">Coastal By-Products</option>
             <option value="SMC">SMC</option>
             <option value="HP Comm.">HP Comm.</option>
             <option value="All Pro">All Pro</option>
             <option value="Belcito AJAX Pumping">Belcito AJAX Pumping</option>
             <option value="Green Dining Network">Green Dining Network</option>

            </select>
    </div>
         <div class="field">
        <label for="account_type">Type of Account</label>&nbsp;<br />(Please choose oil or grease)
    </div>
    
     <div class="field">
        <label for="competitors"><input checked="checked"  type="checkbox" name="type2" value="1" />&nbsp;Grease Trap</label>
    </div>
    
        <div style="clear: both;"></div>
    </div>
    
    <div id="submit" style="width: 100%;padding:5px 5px 5px 5px;margin-top:5px;float:left;">
        <input type="submit" name="usercreate" id="usercreate" style="float: right;margin-right:10px;" value="Create Account"/>
    </div>   

   <!--</div>-->
</form>
  
<div id="debug" style="width: 1000px;height:auto;">
    
</div>


<script>
$("#comp_name").append($("#comp_name option").remove().sort(function(a, b) {
    var at = $(a).text(), bt = $(b).text();
    return (at > bt)?1:((at < bt)?-1:0);
}));


$(".payment_option").click(function(){
   $("#changer").html( "Index/Amount"  );
   
   switch ( $(this).val() ){ 
        case "Jacobson":
            $("input#changer_input").attr('placeholder','Index/Amount');
            $("input#changer_input").attr('readonly',false);
            $("#addtional").remove("input#otb");
            break;
        case "Per Gallon":
            $("input#changer_input").attr('placeholder','Index/Amount');
            $("input#changer_input").attr('readonly',false);
            $("#addtional").remove("input#otb");
            break;
        case "One Time Payment":
            $("input#changer_input").attr('placeholder','Index/Amount');
            $("input#changer_input").attr('readonly',false);
            $("#addtional").remove("input#otb");
            break;
        case "O.T.P. PG":
            $("input#changer_input").attr('placeholder','Index/Amount');
            $("#addtional").append('<input type="text"  placeholder="One Time Bonus" id="otb" name="otb" /> <div style="clear:both"></div>');
            $("input#changer_input").attr('readonly',false);
            break;
            break;
        case "No Pay":
            $("input#changer_input").attr('placeholder','Index/Amount');
            $("input#changer_input").attr('readonly','readonly');
            $("input#changer_input").val('');
            $('#otb').remove();
            break;
        case "Cash On Delivery":
            $("input#changer_input").attr('placeholder','Index/Amount');
            
            $("input#changer_input").val('');
        break;
            
   }
   
   
});

$("body #transparent").remove();
 $("input#sharing_type").hide();  
 $("input#accound_host_sharing").hide();
 $("#comp_name").hide();
 
 
$("input#start_date").datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true, changeYear: true });
$("input#end_date").datepicker( { dateFormat: 'yy-mm-dd',changeMonth: true, changeYear: true } );

$("#accound_host_sharing").hide();


$("#sharing").click(function(){
   if( $("#sharing").is(":checked") ) {
        $("#sharing_type").show();
         $("input#accound_host_sharing").show();
        
   }else {
       $("#sharing_type").hide();
        $("#accound_host_sharing").hide();
       
   }
});

$("#on_site").click(function(){
   if( $("#on_site").is(":checked") ){ 
     $("#comp_name").show();
   } 
   else { 
    $("#comp_name").hide();
   }
});
var req = "";
 
 $("#usercreate").click(function(){
      if( $("#facility").val()=="--"  || $("#facility").val()==99 ){
        alert("Please choose a facility")
        return false;
       }
     
    
    $.each("#myForm input[type='text'].req",function(){
        if( $(this).val() !== ""  ){
           req = "stop";  
        } 
    });
    
 });
 



</script>
