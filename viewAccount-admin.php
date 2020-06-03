<?php 
ini_set("display_errors",0);
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

include "protected/global.php"; $page = "customers"; if(isset($_SESSION['freight_id'])){  
    date_default_timezone_set("America/Los_Angeles");
    $account = new Account($_GET['id']);
    $person = new Person();
    $value = $db->where("account_no",$account->acount_id)->get("freight_containers");     
   //echo $account->payment_method;
    //echo "<br/>".$account->acount_id;
  // var_dump($account);
}
function get_index(){
    global $db;
    $xo =  $db->query("SELECT date,percentage FROM freight_jacobsen ORDER BY DATE DESC LIMIT 0,1 ");
    
    if(count($xo)>0){
        return $xo;
    } else {
        return 0;
    }
}

$ko =get_index();
?>
<html>
<head>
<?php 
include "source/scripts.php";
include "source/css.php";
?>
<script type="text/javascript">
    Shadowbox.init();
</script>
 <style>
    #table_labels td{ 
        text-align:right;
        vertical-align:top;
    }
    
    table#bottominfo td{
        padding:0px 0px 0px 0px;
        font-size:14px; 
        
    }
    .editable_field{
        border-spacing: 0px;
        border-collapse: collapse;
        border-left:3px solid #bbb;
        font-size:12px;
    }
    
    .close {
        cursor:pointer;
    }
    
    select{
        float:right;
    }
    </style>
</head>
<body>
<div id="leftbox" style="width:400px ;float:left;min-height:900px;height:auto;">
            <div id="mainInfo" style="width: 357px;height:250px;float:left;padding-right:10px;text-align:right;padding-top:10px;margin-top:10px;">
   
              <table style="width: 100%;min-height:300px;height:auto;padding:0px 0px 0px 0px;padding-left:5px;padding-top:5px;border-collapse:collapse;font-size:12px;" id="table_labels">
                <tr>
                    <td style="padding-right: 5px;">Account Name</td>
                    <td style="border-top: 3px solid #bbb;border-right: 3px solid #bbb;padding:0px 0px 0px 0px; text-align:left;vertical-align: top;padding-left:5px;" id="account_name_value"  title="Edit Account Name" class="editable_field" rel="aname">
                       <span id="acname"> <?php echo $account->name; ?><br /></span>
                      <input type="" placeholder="enter new name here" class="field" rel="name" xlr="<?php echo $account->acount_id; ?>"/>
                    </td></tr>
                <tr>
            
        
                
                <tr>
                    <td style="padding-right: 5px;">Payee Name</td>
                    <td style="border-right: 3px solid #bbb;padding:0px 0px 0px 0px; text-align:left;vertical-align: top;padding-left:5px;" id="payee_value"  title="Edit Payee" class="editable_field" ><input type="text" value="<?php echo $account->payee_name; ?>" style="border-right: 0px;border-top: 0px;border-left: 0px;width:90%;" class="field" rel="payee_name"/>&nbsp;</td></tr>
                
                <tr>
                    <td style="padding-right: 5px;">Contact</td>
                    <td style="border-right: 3px solid #bbb;padding:0px 0px 0px 0px; text-align:left;vertical-align: top;padding-left:5px;" id="contact_value"  title="Edit Contact"  class="editable_field"> <input type="text" value="<?php echo $account->contact_name; ?>" style="border-right: 0px;border-top: 0px;border-left: 0px;width:90%;" class="field"  rel="contact_name"/>&nbsp;</td></tr>
                
                <tr>
                    <td style="padding-right: 5px;">Area Code</td>
                    <td style="border-right: 3px solid #bbb;padding:0px 0px 0px 0px; text-align:left;
                    vertical-align: top;padding-left:5px;" id="area_code_value" title="Edit Area Code"  class="editable_field"><input type="text" value="<?php echo $account->area_code; ?>" style="border-right: 0px;border-top: 0px;border-left: 0px;width:90%;" class="field"  rel="area_code"/>&nbsp;</td>
                </tr>
                <tr>


                <tr>
                    <td style="padding-right: 5px;">Phone</td>
                    <td style="border-right: 3px solid #bbb;padding:0px 0px 0px 0px; text-align:left;
                    vertical-align: top;padding-left:5px;" id="phone_value" title="Edit Area Code"  class="editable_field"><input type="text" value="<?php echo $account->phone; ?>" style="border-right: 0px;border-top: 0px;border-left: 0px;width:90%;" class="field"  rel="phone"/>&nbsp;</td>
                </tr>
               
                 <tr>
                    <td style="padding-right: 5px;">Email</td>
                    <td style="border-right: 3px solid #bbb;padding:0px 0px 0px 0px; text-align:left;
                    vertical-align: top;padding-left:5px;" id="email_value" title="Edit Email"  class="editable_field"><input type="text" value="<?php echo $account->email_address; ?>" style="border-right: 0px;border-top: 0px;border-left: 0px;width:90%;" class="field"  rel="email_address"/>&nbsp;</td>
                </tr>
                <tr>
                    <td style="padding-right: 5px;">Website</td>
                    <td style="border-right: 3px solid #bbb;border-bottom:3px solid #bbb;padding:0px 0px 0px 0px; text-align:left;vertical-align: top;padding:0px 0px 0px 0px;padding-left:5px;" id="web_value" title="Edit Web Url"   class="editable_field" >http://<input type="text" value="<?php echo $account->url; ?>" placeholder="www.domain.com"  style="border-right: 0px;border-top: 0px;border-left: 0px;width:80%;" class="field" rel="url"/>&nbsp;</td>
                </tr>
              </table>
              
                
            </div>
          <!-- <div id="debug"></div>--!>
            <table style="margin-left:39px;width:320px;border-top:0px solid #bbb;" id="lastinfo">
                <tr><td colspan="2" style="height: 100px;">&nbsp;</td></tr>
                <tr><td colspan="2" style="height: 100px;"><a href="validate_address.php?account=<?php echo $account->acount_id; ?>" rel="shadowbox;width=900px;height=500px;">Click here to validate address</a></td></tr>
                 <tr><td>Physical Street <br /> Address</td><td><input  value="<?php echo $account->address ?>"  type="text" id="address" name="address" class="editable_field field" rel="address" /></td></tr>
                <tr><td>Physical City</td><td><input value="<?php echo $account->city; ?>" type="text" id="city" name="city" class="editable_field field" rel="city"  /></td></tr>
                <tr><td>Physical State</td><td><input  value="<?php echo $account->state; ?>"  type="text" id="state" name="state" class="editable_field field" rel="state"  /></td></tr>
                <tr><td>Physical Zip <br /> Code</td><td><input  value="<?php echo $account->zip; ?>"  type="text" id="zip" name="zip" class="editable_field field" rel="zip"  /></td></tr>
                <tr><td>Billing Street Address</td><td><input  value="<?php echo $account->billing_address; ?>"   type="text" id="billing_address" name="billing_address" class="editable_field field" rel="billing_address"  /></td></tr>
                <tr><td>Billing City</td><td><input   value="<?php echo $account->billing_city; ?>"  type="text" id="billing_city" name="billing_city" class="editable_field field" rel="billing_city"  /></td></tr>
                <tr><td>Billing State</td><td><input   value="<?php echo $account->billing_state; ?>"  type="text" id="billing_state" name="billing_state" class="editable_field field" rel="billing_state" /></td></tr>
                <tr><td>Billing Zip</td><td><input   value="<?php echo $account->billing_zip; ?>"  type="text"  name="billing_zip" class="editable_field field"  rel="billing_zip" /></td></tr>
                <tr><td>New Boss #</td><td><input type="text" id="new_bos" name="new_bos" class="editable_field field" rel="new_bos"  value="<?php echo $account->new_bos; ?>" /></td></tr>
                
                <!--<tr><td>Account Type</td><td>
                    <input <?php if($account->is_oil==1){ echo "checked";} ?> type="checkbox" name="is_oil" id="is_oil" class="fieldx"  rel="is_oil" value="1"/> Oil&nbsp;&nbsp;
                    <input <?php if($account->is_trap==1){ echo "checked";} ?> type="checkbox" name="is_trap" id="is_trap" class="fieldx"  rel="is_trap" value="1"/> Trap
                    </td>
                </tr>--!>
                
                <tr><td style="text-align: right;">Account Status</td><td>
                
                <select id="status" name="status" class="field" rel="status"  style="margin-left:20px;width:100px;">
                    <option>--Please choose a status</option>
                    <option <?php if(strtolower($account->status)== "on hold"){echo "selected";} ?> value="On Hold">On Hold / Seasonal</option>
                    <option <?php if(strtolower($account->status) == "archive"){echo "selected";} ?> value="Archive">Archived</option>
                    <option <?php if( strtolower($account->status) == "new"){echo "selected";} ?>  value="New">New</option>
                    <option <?php if(strtolower($account->status) == "active"){echo "selected";} ?>  value="Active">Active</option>
                    <option <?php if(strtolower($account->status) == "ending"){echo "selected";} ?>  value="Ending">Ending</option>
                    <option <?php if(strtolower($account->status) == "out of business") ?> value="Out of Business" >Out of business</option>
                </select>

                </td></tr>
               <tr><td style="text-align: right;">Grease Pickup Frequency</td><td><input type="text" value="<?php echo $account->grease_freq; ?>" name="grease_freq" id="grease_freq" rel="grease_freq" class="field editable_field"/></td></tr>
                
                
                <tr><td style="text-align: right;vertical-align:top;"><img src="img/settings.png" id="settings" title="Change Payment Type" style="width: 20px;height:20px;cursor:pointer;"/>
                
                    <div id="dialog" style="width:250px;height:auto;position:absolute;background:rgba(255,255,255,.7);border:1px solid green;">
        
        
        <table style="width: 100%;">
            
            <tr><td><select style="width: 100%;" id="ptype">
            <option>--</option>
            <option value="No Pay"  id="np">No Pay</option>
            <option value="Charge Per Pickup" id="index">Charge Per Pickup</option>
            <option value="Per Gallon" id="pg">Per Gallon</option>
            <option value="O.T.P. Per Gallon" id="otppg">One Time Payment Per Gallon</option>
            <option value="O.T.P." id="otp">One Time Payment</option>
            <option value="Cash On Delivery" id="cod">Cash On Delivery</option>
        </select>
        </td><td style='text-align:right;'><span title='close' class='close'>X</span></td></tr>
        </table>
        
        <table style="width: 100%;height:60px;" id="payment_options">
        
        <tr><td></td></tr>  
        
        </table>
        
        
        <table style="width: 100%;"><tr><td style='text-align:right;'><input type="submit" value="Change Now" id="changepayment"/></td></tr></table>
        
        
    </div>
    
     <span id="pyment"><?php  payment_label($account); ?> </span></td><td rel="jakepercent" class="editable_field" style="border-left: 0px solid transparent;text-align:left;vertical-align:top;">
                
                <?php 
                   
                    switch($account->payment_method){
                        case "Charge Per Pickup": case "Index":
                        ?>
                         <input type="text" value="<?php echo round($account->index_percentage,2);?>" rel="index_percentage" class="field dynamic"/>
                        <?php
                        break;
                        case "Per Gallon": case "Normal":
                        ?>
                        <input type="text" value="<?php echo $account->grease_ppg;?>" rel="grease_ppg" class="field dynamic"/>
                        <?php
                        break;
                        case "One Time Payment Per Gallon": case "O.T.P. Per Gallon":
                          ?>
                      <input type="text" value="<?php echo round( $account->ppg_jacobsen_percentage,2);?>" rel="ppg_jacobsen_percentage" class="field"/>
                      <input type="text" value="<?php echo round( $account->price_per_gallon,2);?>" rel="price_per_gallon" class="field dynamic"/>
                      <?php  
                        break;
                        case "O.T.P.": case "One Time Payment":
                         ?>
                        <input class="field dynamic" type="text" value="<?php echo $account->ppg_jacobsen_percentage; ?>"/>
                        <?php
                        break;
                        case"Cash On Delivery":
                        ?>
                        <input type="text" value="<?php echo $account->ppg_jacobsen_percentage; ?>" rel="ppg_jacobsen_percentage" class="field dynamic"/>
                        <?php
                        break;
                        
                        
                    }
                   
                   
                    
                ?>
                </td></tr>
                
                <!--<tr><td style="text-align: right;">M.I.U. %</td><td style="text-align: left;vertical-align:top;">
                    <input type="text" class="field" rel="miu" value="<?php echo ($account->miu * 100); ?>"/>
                </td></tr>--!>
                
                 <?php 
                if($person->user_id == 149){
                ?>
                <tr><td colspan="2">
                <div id="kkd" style="width:360px;height:200px;overflow:auto;">
                <?php 
                    $hj = $db->query("SELECT * FROM freight_grease_traps WHERE account_no = $account->acount_id ORDER BY service_date DESC");

                    echo "<table style='width:360px;'>";
                    echo "<tr>
                        <td>&nbsp;</td>
                        <td>Schedule Id</td>
                        <td>Route id</td>
                        <td>Status</td>
                        <td>Service Date</td>
                        </tr>";;
                    if(count($hj)>0){
                        foreach($hj as $tt){
                            echo "<tr>
                                <td><input type='checkbox' rel='$tt[grease_no]' class='stops'/></td>
                                <td>$tt[grease_no]</td>
                                <td>$tt[grease_route_no]</td>
                                <td>$tt[route_status]</td>
                                <td>$tt[service_date]</td></tr>";
                        }
                    }
                   
                    echo "</table>";
                ?>
                </div>
                </td></tr>        
                <script>
                    var stopsx="";
                    
                    $(".stops").click(function(){
                        var val =  $(this).attr("rel");
                       if($(this).is(":checked")){
                         stopsx +=val+"~";
                        $("input#stops").val( stopsx );
                       }else{
                         var replace_string = val+"~";
                         stopsx = stopsx.replace(replace_string,"");
                         $("input#stops").val( stopsx );
                       }
                    });
                </script>
                <input type="text" id="stops" readonly=""/>
                <?php
                }
                ?>
                
                <tr><td colspan="2"><input rel="<?php echo $account->acount_id; ?>" id="updpaymfstop" type="submit" value="Update Payment for Current Stop(s)"/>&nbsp;</td></tr>
                
               
                
                
                <tr><td colspan="2" style="height: 20px;"></td></tr>
                 <!--<tr><td  style="text-align: right;">Pickup Frequency</td><td><input class="field" rel="pickup_frequency"  type="text" value="<?php echo $account->pick_up_freq; ?>"/></td></tr>--!>
                <tr><td style="text-align: right;">Account Rep</td><td>
                
                    <?php 
                     echo "&nbsp;".uNumToName($account->account_rep)."<br/>";  
                     getSalesRep($account->account_rep);
                    ?>
                    </td></tr>
                <tr><td style="text-align: right;">Original Sale By</td><td><?php echo "&nbsp;".uNumToName($account->original_sales)."<br/>";                 
                    getOrigRep($account->original_sales);
                ?>
                
                </td></tr>

                <!--<tr><td>Friendly</td><td><?php getFriendLists($account->friendly); ?></td></tr>--!>
                <tr><td style="text-align: right;">Referred By</td><td><input name="referred" id="referred" type="text" rel="referred" class="field editable_field" value="<?php echo $account->referred; ?>"/></td></tr>
                <tr><td colspan="2" style="height: 10px;"></td></tr>
                <tr><td style="text-align: right;">Contract Period</td><td style="vertical-align: top; text-align:left;"><?php 
                
                
                if($account->expires != "0000-00-00")  { 
                  echo date_different($account->state_date,$account->expires)." days ";
                  if($account->expires<date("Y-m-d")){
                    echo "(<span style='color:red;font-weight:bold;'>Account expired</span>)";
                  }
                  
                  
                } else {
                    echo "Expiration date not set";
                }
                
                ?></td></tr>
                
                <tr><td style="text-align: right;font-size:12px;">Contract Signed On</td><td style="vertical-align: top;text-align:left;">
                
                <input type="text" id="csigned" name="csigned" class="field" rel="state_date" value="<?php echo $account->state_date; ?>"/>
                </td></tr>
                <tr><td style="text-align: right;font-size:12px;"   >Contract Expires On</td><td style="vertical-align: top;text-align:left;">
                <input type="text" id="expires" name="<br />expires" class="field" rel="expires" value="<?php echo $account->expires; ?>"/>
                </td></tr>
                <tr><td style="text-align: right;font-size:12px;"  >Code Red Email</td><td  style="vertical-align: top;text-align:left;"><input type="text" value="<?php echo $account->code_red_email; ?>" rel="code_red_email" class="field editable_field" placeholder="Code Red Emai"/></td></tr>
                
               
                <!--<tr><td style="text-align: right;">Total Capacity</td><td>
                <?php                 
                   echo $account->total_barrel_capacity;
                ?> 
                    
                 </td></tr>--!>
                <!--<tr><td style="text-align: right;">Containers</td><td>
                  <?php echo $account->number_of_barrels; ?>
                </td></tr>--!>
               
               
                                                    
            </table>            
           
           
           </div>
           <div id="rightbox" style="width: 500px;min-height:900px;height:auto;float:left;background:transparent;">
                <div id="notesbox" style="width: 480px;border:2px solid gray;height:250px;margin-left:5px;margin-top:10px;">    
                <!---
                    <div class="squarebox" style="border-radius: 10px;background:#bbbbbb;width:50px;height:50px;float:left;margin-left:9px;margin-top:5px;"></div> 
                    <div class="squarebox" style="border-radius: 10px;background:#bbbbbb;width:50px;height:50px;float:left;margin-left:9px;margin-top:5px;"></div>
                    <div class="squarebox" style="border-radius: 10px;background:#bbbbbb;width:50px;height:50px;float:left;margin-left:9px;margin-top:5px;"></div>
                    <div class="squarebox" style="border-radius: 10px;background:#bbbbbb;width:50px;height:50px;float:left;margin-left:9px;margin-top:5px;"></div>      
                    <div class="squarebox" style="border-radius: 10px;background:#bbbbbb;width:50px;height:50px;float:left;margin-left:9px;margin-top:5px;"></div>
                    <div class="squarebox" style="border-radius: 10px;background:#bbbbbb;width:50px;height:50px;float:left;margin-left:9px;margin-top:5px;"></div>
                    <div class="squarebox" style="border-radius: 10px;background:#bbbbbb;width:50px;height:50px;float:left;margin-left:9px;margin-top:5px;"></div>
                    <div class="squarebox" style="border-radius: 10px;background:#bbbbbb;width:50px;height:50px;float:left;margin-left:9px;margin-top:5px;"></div>
                    ---!>
                    
                    <div id="subinfo" style="width: 98px;padding-right:10px;text-align:right;float:left;margin-top:5px;font-size:9px;height:250px;">
                    
                    <table style="width: 98px;height:250px">
                    <tr><td  style="text-align: right;vertical-align:top;height:25px;"><span title="Contract" id="contract"  style="cursor: pointer;color:blue;text-decoration:underline;">Contract -</span></td></tr>
                    <tr><td  style="text-align: right;vertical-align:top;height:25px;"><span id="poster"  style="cursor: pointer;color:blue;text-decoration:underline;">Good Cleaning Practice Poster -</span> </td></tr>
                    <tr><td  style="text-align: right;vertical-align:top;height:25px;"><span id="renewal"  style="cursor: pointer;color:blue;text-decoration:underline;">Removal Notice -</span> </td></tr>
                    <tr><td  style="text-align: right;vertical-align:top;height:25px;"><span id="photos"  style="cursor: pointer;color:blue;text-decoration:underline;">Photos - </span></td></tr>
                    <tr><td  style="text-align: right;vertical-align:top;height:25px;"><span id="cancel_r" style="cursor: pointer;color:blue;text-decoration:underline;">Cancel Request - </span></td></tr>
                    </table>
                    
                        
                        
                    </div>  
                    <div id="notesection" style="width: 362px;height:240px;border:1px solid green;border-radius: 5px;float:left;margin-top:5px;">
                    <table style="width: 362px;height:240px;">
                    <tr><td style="text-align: left;vertical-align:top;;height:25px;"><?php 
                    if(file_exists("$account->acount_id/contract")){//has the sub folder already been created?
                        if ($handle = opendir("$account->acount_id/contract/")) {
                            if(!is_dir_empty("$account->acount_id/contract/")){// is the folder empty?
                                while (false !== ($entry = readdir($handle))) {            
                                    if ($entry != "." && $entry != "..") {        
                                        echo "&nbsp;&nbsp;<a href='$account->acount_id/contract/$entry' target='_blank'>$entry</a>"; 
                                    }
                                }        
                            }
                            closedir($handle);
                        }    
                    }
                    ?></td></tr>
                    <tr><td style="text-align: left;vertical-align:top;;height:25px;"><?php 
                    if(file_exists("$account->acount_id/poster")){
                          if ($handle = opendir("$account->acount_id/poster/")) {
                            if(!is_dir_empty("$account->acount_id/poster/")){
                                while (false !== ($entry = readdir($handle))) {            
                                    if ($entry != "." && $entry != "..") {        
                                        echo "<a href='$account->acount_id/poster/$entry' target='_blank'>Good Cleaing Practice Poster</a>";  
                                    }
                                }        
                            }
                            closedir($handle);
                        }    
                    }
                    ?></td></tr>
                    <tr><td style="text-align: left;vertical-align:top;;height:25px;">
                    <?php
                    if(file_exists("$account->acount_id/notices/")  && !is_dir_empty("$account->acount_id/notices/") ){
                        $dir = "$account->acount_id/notices";
                        $dh  = opendir($dir);
                        if(!is_dir_empty($dir)){
                            while (false !== ($filename = readdir($dh))) {
                                
                                if($filename !="."&& $filename !=".."){
                                    $files[] = $filename;
                                }
                            }
                            $count =1;
                            foreach($files as $file){
                                echo "
                                <a href='$account->acount_id/notices/$file' target='_blank'>View Notice $count</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
                                $count++;
                            }
                        }
                    }
                    ?>
                    </td></tr>
                    <tr><td style="text-align: left;vertical-align:top;;height:25px;"><?php
                    if(file_exists("$account->acount_id/photos/") && !is_dir_empty("$account->acount_id/photos/")){
                        $dir = "$account->acount_id/photos";
                        $dh  = opendir($dir);
                        
                        if(!is_dir_empty($dir)){    
                            while (false !== ($filename = readdir($dh))) {
                                
                                if($filename !="."&& $filename !=".."){
                                    $files[] = $filename;
                                }
                            }
                            $count =1;
                            foreach($files as $file){
                                echo "
                                <a href='$account->acount_id/photos/$file' target='_blank'>Photo $count</a> &nbsp;&nbsp;|&nbsp;&nbsp;";
                                $count++;
                            }
                        }
                    }
                    ?></td></tr>
                    <tr><td style="text-align: left;vertical-align:top;;height:25px;">
                    <?php 
                      
                    if(file_exists("$account->acount_id/cancel/")){
                        if ($handle = opendir("$account->acount_id/cancel/")) {
                            
                            if(!is_dir_empty("$account->acount_id/cancel")){    
                            while (false !== ($entry = readdir($handle))) {            
                                if ($entry != "." && $entry != "..") {        
                                      echo "<a href='$account->acount_id/cancel/$entry' target='_blank'>Cancellation Notice</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
                                }
                            }
                            }        
                            closedir($handle);
                        }
                    }
                    ?>
                    </td></tr>
                    </table>
                     <?php 
                     
                  
                    ?>
                     
                    </div>        
                </div>
                
    <div id="bottomSection" style="width: 480px;height:642px;border:1px solid #bbb;margin-left:5px;margin-top:10px;">
        <div id="pickup_title" style="width: 480px;padding-left:10px;padding-right:10px;padding-top:5px;padding-bottom:5px;color: darkgreen;text-align:center;font-size:20px;">
                completed pickups (all locations)
        </div>
        <div id="statbox" style="width: 100%;overflow:auto;height:210px;">
            <table style="width:100%;">
            <tr style="border-bottom:2px solid black;">
                <td style="background: #E2E5DE;">Date</td>
                <td style="background: #E2E5DE;">Volume</td>
                <td style="background: #E2E5DE;">CS</td>
                
                <td  style="background:#E2E5DE;">Receipt</td>
                <td style="background: #E2E5DE;">Price</td>
                <td style="background: #E2E5DE;">Route</td>
            </tr>
            <?php
                $admin_payments = $db->query("SELECT * FROM freight_grease_data_table  WHERE account_no = $account->acount_id order by date_of_pickup DESC");
                
                if(count($admin_payments)>0){
                    foreach($admin_payments as $payments){
                        
                        echo "<tr><td>$payments[date_of_pickup]</td>
                                  <td>$payments[inches_to_gallons]</td>
                                  <td>$payments[cs]</td>
                                  <td>"; 
                            
                            if(file_exists("$payments[account_no]/receipts/$payments[schedule_id].pdf")){
                            echo "<a href='$payments[account_no]/receipts/$payments[schedule_id].pdf' target='_blank'>Receipt Link</a>"; 
                            } else {
                                echo "No Receipt Found";
                            }
                            
                            echo "</td>
                                  <td>".number_format($payments['inches_to_gallons']*$payments['ppg'],2) ."</td>
                                  <td>$payments[route_id]</td>
                            </tr>";
                    }
                }
            ?>                        
            </table>
        </div>
        <div id="noted" style="width: 100%;height:20px;">
        &nbsp; <span style="color: red;font-weight:bold;font-size:14px;">*</span> Prices reflect price per gallon at time of pickup
        </div>
        <div id="lastSection" style="width: 480px;height:270px;background:transparent;margin-top:20px;">
            <div id="leftlastx" style="float:left;width:150px;min-height:250px;padding:10px 10px 10px 10px;background:transparent;height:auto;">
            <style>
            table#overview td {
                text-align:left;
            }
            </style>
                <?php 
                    $gv = $db->where("account_no",$account->acount_id)->orderby('date_of_pickup','desc')->get($dbprefix."_grease_data_table","*");
                ?>
                <table style="width: 175px;height:100px;border:0px solid #bbb;table-layout: fixed;font-size:12px;" id="overview">
                    <tr><td style="text-align: center;" colspan="2"><span style="color: green;font-size:13px;font-weight:bold;">GT Collection Overview</span></td></tr>
                    <tr><td style="height: 9px;vertical-align:top;">Total Services:</td><td>
                    <?php echo count($gv);  ?></td></tr>

                    <tr><td style="height: 9px;vertical-align:top">Gallons Retrieved:</td><td style="text-align: left;vertical-align:top;"><?php $ret = 0; $tp = 0;
                        if(count($gv)>0){
                        
                            foreach($gv as $su){
                                $ret += $su['inches_to_gallons'];
                                $tp += $su['inches_to_gallons']*$su['ppg'];
                            }
                            
                        } 
                        echo $ret;
                        ?></td></tr>
                    <tr><td style="height: 9px;vertical-align:top">Gallons Adjusted</td><td style="text-align: left;vertical-align:top;"><?php echo ""; ?></td></tr>
                    <tr><td style="height: 9px;vertical-align:top:">Value:</td><td style="text-align: left;vertical-align:top;"><?php
                    echo number_format($account->grease_ppg,2);
                      ?></td></tr>
                    
                    <tr><td style="height: 9px;vertical-align:top:">Total Paid:</td><td style="text-align: left;vertical-align:top;">
                    <?php echo number_format($tp,2); ?>
                    </td></tr>
                   
                </table>
            </div>
            
            <div id="rightlast" style="float:left;width:290px;min-height:250px;padding:10px 10px 10px 10px;background:transparent;height:auto;">
                <style type="text/css">
                    table#payment {
                        border-collapse:collapse;
                    }
                </style>
                <div id="paymentbox" style="width: 284px;margin-left:10px;border:1px solid green;border-radius:5px;height:250px;overflow-x:hidden;overflow-y:auto;">
                <table style="width: 100%;font-size:13px;font-size:12px;" id="payment">
                <thead>
                <tr style="background: #E2E5DE;border-bottom:1px solid black;">
                    <td>Date</td>
                    <td>Payment</td>
                    <td>Gallons<br/>(adj)</td>
                    <td>PPG</td>
                    <td>Status</td>
                </tr>
                </thead>
                <tbody id="meat">
                <?php                
                if(count($gv)>0){
                    $occurred_once[]="";
                    foreach($gv as $lo){
                        echo "<tr>";
                        echo "<td>$lo[date_of_pickup]</td>";
                        echo "<td>".number_format($lo['inches_to_gallons']*$lo['ppg'],2)."</td>";
                        echo "<td>".$lo['inches_to_gallons']."</td>";
                        echo "<td>".number_format($lo['ppg'],2)."</td>";
                        echo "<td>$account->status</td>";
                        echo "</tr>";
                    }
                }
                ?>
                </tbody>
                </table>
                <div style="clear: both;"></div>
                </div>
            </div>
        </div>
        <div id="ranged" style="width: 100%;height:150px;background:transparent;float:left;">
                <table style="width: 100%;">
                <tr><td><input type="text" id="from" name="fromt" placeholder="FROM"/></td><td><input type="text" id="to" name="to" placeholder="TO"/></td></tr>
                    <tr><td colspan="2" style="text-align: right;"><input type="submit" value="Get Summary for Range Date" id="range" style="height: 30px;width:210px;color:black;letter-spacing:1px;font-size:10px;"/></td></tr>
                </table>
                <script>
                    $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
                    $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
                    $("#range").click(function(){
                        $.post("get_range.php",{from:$("input#from").val(),to:$("input#to").val(),account_no:<?php echo $account->acount_id; ?>},function(data){
                            //alert(data);
                          $("#lastSection").html(data); 
                        });
                    });
                </script>
        </div>
        
        
        <div id="jet_search"  id="ranged" style="width: 100%;height:150px;background:transparent;float:left;">
            <table style="width: 100%;">
                <tr><td><input type="text" id="from_jet" name="from_jet" placeholder="FROM"/></td><td><input type="text" id="to_jet" name="to_jet" placeholder="TO"/></td></tr>
                    <tr><td colspan="2" style="text-align: right;"><input type="submit" value="Get Summary for Range Date" id="range_jet" style="height: 30px;width:210px;color:black;letter-spacing:1px;font-size:10px;"/></td></tr>
                </table>
                <script>
                    $("input#from_jet").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
                    $("input#to_jet").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
                    $("#range_jet").click(function(){
                        $.post("get_range_jet.php",{from:$("input#from_jet").val(),to:$("input#to_jet").val(),account_no:<?php echo $account->acount_id; ?>},function(data){
                            //alert(data);
                          $("#jet_overview").html(data); 
                        });
                    });
                </script>
        </div>
        <div id="cs_overview" style="width:480px;float:left;height:270px;border:1px solid #4161AC">
            <div id="cslastx" style="float:left;width:150px;min-height:250px;padding:10px 10px 10px 10px;background:transparent;height:auto;">
            <style>
            table#overview td {
                text-align:left;
            }
            </style>
                <?php 
                    $cs = $db->query("SELECT * FROM Inetforms.ap_form_28181 WHERE ap_form_28181.element_52 = $account->acount_id");
                ?>
                <table style="width: 175px;height:100px;border:0px solid #bbb;table-layout: fixed;font-size:12px;" id="overview">
                    <tr><td style="text-align: center;" colspan="2"><span style="color: green;font-size:13px;font-weight:bold;">Confined Space Overview</span></td></tr>
                    <tr><td style="height: 9px;vertical-align:top;">Total Pickups:</td><td>
                    <?php echo count($cs);  ?></td></tr>

                    <tr><td style="height: 9px;vertical-align:top">Total Charged:</td><td style="text-align: left;vertical-align:top;"><?php $retcs = 0; $tpcs = 0;
                        if(count($cs)>0){                        
                            foreach($cs as $su){
                                $retcs += $su['element_10'];
                                $tpcs += $su['element_5'];
                            }
                            
                        } 
                        echo $retcs;
                        ?></td></tr>
                    <tr><td style="height: 9px;vertical-align:top">Total Hours</td><td style="text-align: left;vertical-align:top;"><?php echo number_format($tpcs,2); ?></td></tr>
                    
                   
                </table>
            </div>
            <div id="rightlast" style="float:left;width:290px;min-height:250px;padding:10px 10px 10px 10px;background:transparent;height:auto;">
                <style type="text/css">
                    table#payment {
                        border-collapse:collapse;
                    }
                </style>
                <div id="paymentbox" style="width: 284px;margin-left:10px;border:1px solid green;border-radius:5px;height:250px;overflow-x:hidden;overflow-y:auto;">
                <table style="width: 100%;font-size:13px;font-size:12px;" id="payment">
                <thead>
                <tr style="background: #E2E5DE;border-bottom:1px solid black;">
                    <td>Date</td>
                    <td>Hours</td>
                    <td>Price Charged</td>
                    <td>Route Id</td>
                </tr>
                </thead>
                <tbody id="meat">
                <?php                
                if(count($cs)>0){
                    $occurred_once[]="";
                    foreach($cs as $lo){
                        echo "<tr>";
                        echo "<td>$lo[date_created]</td>";
                        echo "<td>".number_format($lo['element_5'],2)."</td>";
                        echo "<td>$lo[element_10]</td>";
                        echo "<td>$lo[element_52]</td>";
                        echo "</tr>";
                    }
                }
                ?>
                </tbody>
                </table>
                <div style="clear: both;"></div>
                </div>
            </div>            
        </div>
        <div id="cs_search"  id="ranged" style="width: 100%;height:150px;background:transparent;float:left;">
            <table style="width: 100%;">
                <tr><td><input type="text" id="from_cs" name="from_cs" placeholder="FROM"/></td><td><input type="text" id="to_cs" name="to_cs" placeholder="TO"/></td></tr>
                    <tr><td colspan="2" style="text-align: right;"><input type="submit" value="Get Summary for Range Date" id="rangecs" style="height: 30px;width:210px;color:black;letter-spacing:1px;font-size:10px;"/></td></tr>
                </table>
                <script>
                    $("input#from_cs").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
                    $("input#to_cs").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
                    $("#rangecs").click(function(){
                        $.post("get_range_cs.php",{from:$("input#from_cs").val(),to:$("input#to_cs").val(),account_no:<?php echo $account->acount_id; ?>},function(data){
                            //alert(data);
                          $("#cs_overview").html(data); 
                        });
                    });
                </script>
        </div>
    </div>
     <div style="clear: both;"></div>
</div>
 <div style="clear: both;"></div>
    
   <br />
<script>
    $("document").ready(function(){
        $("#updpaymfstop").click(function(){
           
            var payment = $("#pyment").html();
            alert( $("input#stops").val());
           $.post("update_stop_payment_type.php",{account:<?php echo $account->acount_id; ?>,payment_method:payment,amount:$(".dynamic").val(),mult_stop: $("input#stops").val()},function(data){
                alert("Stop Updated with new Payment Information! "+data);
           }); 
        });
       
       <?php if($person->isCreditManager() == true){ ?> 
        $("#activate").click(function(){
            $.post("status.php",{ status:"Active", id:<?php echo $account->acount_id; ?>},function(data){
                alert("Account Activated!");
            });
       });
       
       
       $("#decline").click(function(){
           Shadowbox.open({
                content:"decline.php?id=<?php echo $account->acount_id; ?>",
                player:"iframe",
                width:"300px",
                height:"300px",
                title:"Credit Decline"
           });
       });
      
      <?php } ?>
      
           $(".lock_").click(function(){
                $.post("update_lock.php",{account:<?php echo $account->acount_id ?>,state:1,mlock:$(this).attr('rel'),reason:$("#locks_reason").val()},function(data){
                    alert("Locked");
                });
           });
           
           $(".unlock_").click(function(){
                $.post("update_lock.php",{account:<?php echo $account->acount_id ?>,state:0,mlock:$(this).attr('rel'),reason:$("#locks_reason").val()},function(data){
                    alert("Unlocked");
                });
           });
           
           $(".checkbox").click(function(){
               if($(this).is(":checked")){
                    $.post("update_checkbox.php",{field:$(this).attr('rel'),value:1,account:<?php echo $account->acount_id; ?>},function(data){
                        alert(" updated! "+data);
                    });
               }else{
                      $.post("update_checkbox.php",{field:$(this).attr('rel'),value:0,account:<?php echo $account->acount_id; ?>},function(data){
                        alert(" updated! "+data);
                    });
               } 
           });
        
        $("body").on('focus','#expires',function(){
             $(this).datepicker( {dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
        });
        
        $("body").on('focus','#csigned',function(){
             $(this).datepicker( {dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"} );
        });
         
        $("#friendly").change(function(){
            $.post('updateFriendly.php',{friend:$("#friendly").val(),account:<?php echo $account->acount_id; ?>},function(data){
               alert("Friendly updated"); 
               window.location.reload();
            });
        });
      
                    
        
                          
                    
        $("#contract").click(function(){
           Shadowbox.open({
                content:'upload_file.php?account=<?php echo $account->acount_id; ?>&mode=1',
                player:"iframe",
                width:500,
                height:200,
                loadingImage:"shadow/loading.gif",
                title:"Contract",
                options: {
                    overLayColor:"#ffffff",
                    overlayOpacity:".9"
                }
                
           }); 
        });
                    
        $("#poster").click(function(){
            Shadowbox.open({
                content: 'upload_file.php?account=<?php echo $account->acount_id; ?>&mode=2',
                player:"iframe",
                width:500,
                height:200,
                loadingImage:"shadow/loading.gif", 
                title: "Good Cleaning Practice Poster",
                options: {    
                    overlayColor:"#ffffff",
                    overlayOpacity: ".9"
                }
            });
        });
                    
        $("#renewal").click(function(){
           Shadowbox.open({
                content: 'upload_file.php?account=<?php echo $account->acount_id; ?>&mode=3"',
                player:"iframe",
                width:500,
                height:200,
                loadingImage:"shadow/loading.gif",
                title:"Removal Notices",
                options: {
                    overLayColor:"#ffffff",
                    overlayOpacity:".9"
                }
           }); 
        });
                    
                    
       $("#photos").click(function(){
            Shadowbox.open({
                content: 'upload_file.php?account=<?php echo $account->acount_id; ?>&mode=4',
                player:"iframe",
                width:500,
                height:200,
                loadingImage:"shadow/loading.gif",
                title:"Photos",
                options: {
                    overLayColor:"#ffffff",
                    overlayOpacity:".9"
                }
           });
       });
                    
     $("#cancel_r").click(function(){
        Shadowbox.open({
            content: 'upload_file.php?account=<?php echo $account->acount_id; ?>&mode=5',
            player:"iframe",
            width:500,
            height:200,
            loadingImage:"shadow/loading.gif",
            title:"Cancellation Request",
            options: {
                overLayColor:"#ffffff",
                overlayOpacity:".9"
            }
       });                     
    });  
                    
                    
                    
                    
                   
      
      
        $("#index").click(function(){
            $("#payment_options").html("<tr><td><inpu class='dynamic'  type='text' placeholder='Charge Per Pickup' id='indexval' style='width:100%'  id='jake'/></td></tr>");
        });
        
        $("#pg").click(function(){
            $("#payment_options").html("<tr><td><input class='dynamic' type='text' placeholder='price per gallon' id='ppgval'   style='width:100%'/></td></tr>");
        });
        
        $("#otppg").click(function(){
            $("#payment_options").html("<tr><td><input class='dynamic' type='text'  placeholder='One Time Payment Amount' id='otpconsist' style='width:100%'/></td></tr><tr><td><input type='text'  id='otppgval' maxlength='2' placeholder='price per gallon' style='width:100%'/></td></tr>");
        });
        
        $("#otp").click(function(){
            $("#payment_options").html("<tr><td><input class='dynamic' type='text' placeholder='One Time Payment Amount' id='otpval' style='width:100%'/></td></tr>");
        });
        
        $("#np").click(function(){
            $("#payment_options").html(''); 
        });
        
        $("#changepayment").click(function(){
            
            //alert( $("#ptype").val()  );
            switch(  $("#ptype").children(":selected").attr("id") ){
                    case "np":                  
                              $.post("changepayment_nopay.php",{ account: <?php echo $account->acount_id; ?>,payment_type: "No Pay"    },function(data){
                                 alert("Payment Type changed!");
                                 window.location.reload();
                          });
                        break;
                case "index":
                          //alert("index!");
                          var value =$("#indexval").val();
                          alert( $("#ptype").val() );
                          $.post("changepayment_index.php",{ account: <?php echo $account->acount_id; ?>,payment_type: $("#ptype").val(),index_percent: value    },function(data){
                                alert("Payment Type changed!");
                                window.location.reload();
                          });
                    break;
                case "pg":
                      value = $("#ppgval").val()/100;
                      $.post("changepayment_ppg.php",{ account: <?php echo $account->acount_id; ?>,payment_type: "Per Gallon",ppgval: value    },function(data){
                            alert("Payment Type changed!");
                            window.location.reload();
                      });
                    break;
                case "otp":                  
                      $.post("changepayment_otp.php",{ account: <?php echo $account->acount_id; ?>,payment_type: "One Time Payment",otp: $("#otpval").val()    },function(data){
                            alert("Payment Type changed!");
                            window.location.reload();
                            
                      }); //14331 Euclid St.  
                    break;
                case "otppg":
                    value = $("#otppgval").val() /100;
                
                 $.post("changepayment_otp_ppg.php",{ account: <?php echo $account->acount_id; ?>,payment_type: $("#ptype").val(),otp: $("#otpconsist").val() ,optpg: value  },function(data){
                            alert("Payment Type changed!");
                            window.location.reload();
                      });
                    break;
                case "cod":
                    $.post("changepayment_cod.php",{ account: <?php echo $account->acount_id; ?>,payment_type: $("#ptype").val() },function(data){
                            alert("Payment Type changed!");
                            window.location.reload();
                      });
                    break;
             }
             
           
        });
        
        
        $(".field").change(function(){      
            var isname = $(this).attr('rel');
            //alert(isname);
          $.post("accountpage_editables/changefield.php",{id:<?php echo $account->acount_id; ?>,field: $(this).attr('rel'), value: $(this).val() },function(data){
           alert("Information changed! "+data);
            if(isname = "name"){
                var anumber = $(this).attr('xlr');
                $("#acname").html("<a href='viewAccount.php?id="+anumber+">"+$(this).val()+"</a>");        
            }
            window.location.reload();    
          });
        });
        
        
         $("#dialog").hide();
    
        
        $('#settings').click(function(){
              $(this).next('#dialog').show();
              $(this).next('.tooltip').position({at: 'bottom center', of: $(this), my: 'top'});
        });
        
        
        
        $(".close").click(function(){
           $("#dialog").hide(); 
        });
        
        
        
        
        
       $(".ikg_form").click(function(){
            $(this).submit();
        });
    
        
        $('.fieldx').click(function(){
            if (this.checked) {           
               $.post("change_state.php",{id:<?php echo $account->acount_id; ?>,field:$(this).attr('rel'),state:1},function(){
                 alert("turned on");
                 window.location.reload();
               });
            } else {
               $.post("change_state.php",{id:<?php echo $account->acount_id; ?>,field:$(this).attr('rel'),state:0},function(){
                 alert("turned on"); 
                 window.location.reload();
               });
            }
        }); 
        
    });    
   


   
</script>
</body>    
    </html>
