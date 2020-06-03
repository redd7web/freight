<html>
<head>
<meta charset="utf-8" />
<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        
<?php   
//error_reporting(E_WARNING | E_PARSE | E_NOTICE); //utility routing
include "protected/global.php";
include "source/scripts.php"; 
include "source/css.php";
$utility_table = $dbprefix."_utility";
$account_table = $dbprefix."_accounts";
$util_route_list = $dbprefix."_ikg_utility";
$util_list_route = $dbprefix."_list_of_utility";
$schedulesx="";
 $total_col =0;  
 if(isset($_SESSION['freight_id'])){ 
    $person = new Person();
 }



$acnts = "";
$scheds = "";

if( isset($_POST['from_routed_util_list']) ){
    //echo $_POST['util_number']."<br/>";
    $container_route = new Container_Route($_POST['util_number']);
    if(isset($_POST['add_to_existing'])){//are you adding more stops to an existing account ?
      $acnts = array_map('intval', explode("|","$_POST[accounts_checked]"));
      $scheds = array_map('intval', explode("|","$_POST[schecheduled_ids]"));
      array_pop($acnts);
      array_pop($scheds);
      $newlist_accounts = array_merge($container_route->account_numbers,$acnts);// temporarily add to stops 
      $newlist_scheds = array_merge($container_route->scheduled_routes,$scheds);// temporarily add to stops
      
       
    }else {
        $newlist_accounts = $container_route->account_numbers;
        $newlist_scheds = $container_route->scheduled_routes;
    }
    
    
}
else if(isset($_POST['from_schedule_list'])){//creating a new manifest
   if( isset($_POST['accounts_checked']) && isset($_POST['schecheduled_ids']) ){
       //echo "$_POST[schecheduled_ids]<br/>"     ;
       //echo "$_POST[accounts_checked]<br/>";
       $acnts = array_map('intval', explode("|","$_POST[accounts_checked]"));
       $scheds = array_map('intval', explode("|","$_POST[schecheduled_ids]"));
       array_pop($acnts);
       array_pop($scheds);
       
   }
}
?>

<style type="text/css">

input[type="text"] {
    border: 1px solid #bbb;
    border-radius: 5px;
    height: 25px;
    width: 140px;
    margin-left:5px;
}
</style>

<title>Mainline Jetting Routing</title>
</head>
<input type="hidden" name="mode" id="mode"  value=""/>
<div id="debug"></div>
<div id="wrapper">
<div id="spacer" style="width: 100%;height:10px;">&nbsp;</div>

<div class="content-wrapper" style="min-height:450px;height: auto;">
    <div id="fullgray" style="width: 100%;height:auto;background: linear-gradient(#F0F0F0, #CFCFCF) repeat scroll 0 0 rgba(0, 0, 0, 0);border-bottom: 1px solid #808080;margin-bottom:10px;">
        <div id="info_hold" style="width: 800px;margin:auto;height:auto;background:transparent;padding">
        <table style="width:800px;">
        <tr><td colspan="5" style="color: red;font-size:16px;text-align:center;">Please do not click the back or refresh button on the browser, if you need to refresh please click the manual refresh button</td></tr>
        <tr><td>Route ID:</td><td id="rid">  <?php if( isset($_POST['from_routed_util_list']) ){ echo $container_route->route_id; }   ?> </td><td>Facility: <span id="facholder">
        <?php if( isset($_POST['from_routed_util_list']) ){ echo $container_route->recieving_facility; }   ?>
        </span> </td></tr>
        
        </table>
        <div style="clear: both;"></div>
        </div>
        <div style="clear: both;"></div>
    </div>
    
    <div id="fullgray" style="width: 100%;height:150px;background: linear-gradient(#F0F0F0, #CFCFCF) repeat scroll 0 0 rgba(0, 0, 0, 0);border-bottom: 1px solid #808080;margin-bottom:10px;">
        <div id="info_hold" style="width: 1000px;margin:auto;height:100px;background:transparent;padding">
                <div id="leftsideboxes" style="float: left;width:600px;height:100px;">
                       <div class="leftsides" style="width:600px;height:100px;float:left;">
                        <div class="box" style="width:250px;border-radius:7px;height:60px;margin-bottom:5px;cursor:pointer;">
                        <table><tr><td style="text-align: center;vertical-align:middle;width:250px;height:60px;" id="edata">
                        <?php
                            if(isset($_POST['from_routed_util_list'])){
                                echo "<a href='enterUtilComplete.php?route_id=$container_route->route_id' target='_blank'><img src='img/enterdata.jpg'/></a>";    
                            }
                        
                         ?>
                         </td></tr></table>
                        </div>    
                        <div  <?php  if(isset($_POST['from_routed_util_list'])){ echo 'id="refresh"';}else { echo "title='Please Create a route before refreshing.' id='null'";  } ?> class="box" style="width: 60px;height:60px;box-shadow: 2px 2px 5px #888888;float:left;background:url(img/refresh.png) no-repeat center top;background-size:contain;cursor:pointer;"></div>                     
                        <div class="box" style="width: 60px;height:60px;box-shadow: 2px 2px 5px #888888;float:left;"><img src="img/newticket.jpg"/></div>
                        <div class="box" style="width: 60px;height:60px;box-shadow: 2px 2px 5px #888888;float:left;">
                        <img src="img/route.jpg"/>
                        </div>
                        <div class="box" style="width: 60px;height:60px;border-radius:7px;box-shadow: 10px 10px 5px #888888;float:left;text-align:center;" id="exp">
                        <?php
                             if( isset($_POST['from_routed_util_list'])){
                                echo "<a href='container_to_excel.php?route_no=$container_route->route_id' target='_blank'><img src='img/xls.jpg' /></a>";
                            }
                        ?>
                        </div>
                        <div class="box" style="width: 60px;height:60px;border-radius:7px;box-shadow: 10px 10px 5px #888888;float:left;text-align:center;" id="pdfexp">
                        <?php
                            if( isset($_POST['from_routed_util_list'])){
                                echo "<a href='downloadreceiptcontainer.php?route_no=$container_route->route_id' target='_blank'><img src='img/pdf.jpg' /></a>";
                            }
                        
                        ?>
                        
                        </div>
                        <div class="box" style="width: 60px;height:60px;border-radius:7px;box-shadow: 10px 10px 5px #888888;float:left;background-size:contain;" id="print">
                        <?php
                         if( isset($_POST['from_routed_util_list']) ){
                            echo "<a href='print_util.php?route_no=$container_route->route_id'  target='_blank'><img src='img/print.jpg'/></a>";   
                         }
                        
                        ?>
                        
                        </div>
                        <div class="box" style="width: 60px;height:60px;border-radius:7px;box-shadow: 10px 10px 5px #888888;float:left;background:url(img/createmod.jpg) center top;background-size:contain;cursor:pointer;"  <?php if(isset($_POST['from_routed_util_list'])){ echo "title='Update' id='ikg_update'";} else {?>  title="Save"   id="ikg_create" <?php } ?> >
                        </div>
                         <div class="box" style="width: 60px;height:60px;box-shadow:2px 2px 5px #888888;float:left;backgroundsize:contain;" id="field_report">
                         <?php  if(isset($_POST['from_routed_util_list'])){ ?> <a href="field_report.php?ikg=<?php   echo $container_route->route_id."&type=util";   ?>"  rel="shadowbox;width=600px;height=500px;"><img src="img/bullhorn.png" style="width: 60px;height:60px;"/></a> <?php } ?>
                        </div>
                        <div class="box" style="width: 60px;height:60px;box-shadow: 2px 2px 5px #888888;float:left;" title="Manual Reorder" id="manual_reorder" >
                            <?php if(isset($_POST['from_routed_util_list'])){ echo '<a href="manualreorder.php?type=util&route_id='.$container_route->route_id.'" rel="shadowbox;width=500;height=500;"><img src="img/reorder.png"/></a>';   } ?>
                        </div>
                        
                    
                    </div>             
                </div>
                <div id="rightsideboxes" style="width: 400px;height:100px;float:left;">
                    <table style="width: 400px;height:100px;font-size:10px;"><tr><td>Enter Data In</td><td>
                    
                    <select name="entered_data" id="entered_data">
                        <option value="gallons">gallons</option></select></td>
                        
                        <td>Status</td>
                        <td  style="font-size: 22px;font-weight:bold;" id="statquo">
                   <?php
                   
                   
                    if(isset($_POST['from_routed_util_list'])){ echo $container_route->route_status; }  ?>

</td></tr>
                    <tr><td style="vertical-align: top;">Recurring</td><td style="vertical-align: top;"><input type="checkbox" id="recurring"/><br /><input type="text" id="recurring_value" style="width: 50px;height:25px;" /></td><td colspan="2">How Many Days In Route</td><td colspan="2"><select name="days_in_route" id="days_in_route"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></td></tr>
                    </table>
                </div>                
        </div>    
    </div>
    
    <div id="fields" style="width: 1000px;min-height:300px;height:auto;margin:auto;">
    <table style="width: 100%;font-size:10px;" id="meatikg">
        <tr>
            <td>IKG Ttitle</td>
            <td><input type="text" id="ikgmanifestnumber" value="<?php if(isset($_POST['from_schedule_list'])){ echo "bio-".date("YmdHis"); } else if(isset($_POST['from_routed_util_list'])){ echo $container_route->ikg_manifest_route_number; }   ?>" name="ikgmanifestnumber"/></td>
            <td>Tank 1<br />Tank 2</td>
            <td><input id="tank1" value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->tank1; }  ?>" name="tank1" type="text"/><br /><input id="tank2" name="tank2" value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->tank2; }  ?>" type="text"/></td>
            <td>Time Start</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->time_start; }  ?>" id="timestart" name="timestart" type="text"/></td>
            <td>Start Mileage</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->start_mileage; }  ?>" id="start_mileage" name="start_mileage" type="text"/></td>
        </tr>
        
        <tr>
            <td>Scheduled Date</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->scheduled_date; }  ?>" type="text" id="sched_route_start" name="sched_route_start"/></td>
            <td>Truck</td>
            <td><select name="vehicle" id="vehicle">
            <?php
                $compare= "";
                if(isset($_POST['from_routed_util_list'])){ 
                    $compare = $container_route->truck;
                    getVehiclesList($compare);
                }else {
                    getVehiclesList("");
                }
            ?>
            </select></td>
            <td>First Stop</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->first_stop; }  ?>" id="firststop" name="firststop" type="text"/></td>
            <td>First Stop Mileage</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->first_stop_mileage; }  ?>" type="text" id="first_stop_mileage" name="first_stop_mileage"/></td>
        </tr>
        
        <tr>
            <td>Completion Date</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->completed_date; }  ?>" id="completion" type="text"  readonly=""/></td>
            <td>License Plate</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->license_plate; }  ?>" type="text" name="lic_plate" id="lic_plate"/></td>
            <td>Last Stop</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->last_stop; }  ?>" id="laststop" name="laststop" type="text"/></td>
            <td>Last Stop Mileage</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->last_stop_mileage; }  ?>" id="last_stop_mileage" name="last_top_mileage"  type="text"/></td>
        </tr>
        
        <tr>
            <td>IKG Manifest Number</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->route_id; }  ?>" id="unique_route_no"  type="text" readonly="" /></td>
            <td>IKG Decal</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->ikg_decal; }  ?>" id="ikg_decal" name="ikg_decal" type="text"/></td>
            <td>End Time</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->end_time; }  ?>"  type="text" name="end_time" id="endtime"/></td>
             <td>End Mileage</td> 
             <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->end_mileage; }  ?>" id="end_mileage" name="end_mileage" type="text"/></td>
        </tr>
        
        <tr>
            <td>Location</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->location; }  ?>"  id="location" name="location" type="text"/></td>
            <td>IKG Collected</td>
            <td><input  name="ikg_collected" value="Utility" id="ikg_collected" readonly="" type="text"/></td>
            <td>Fuel</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->fuel; }  ?>" name="fuel" id="fuel" type="text"/></td>
        </tr>
        
        
        <tr>
            <td>INVENTORY CODE</td><td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->inventory_code; }  ?>" name="inventory_code" id="inventory_code" type="text"/></td></td>
        </tr>
        
        
        <tr>
            <td>Lot #</td>            
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->lot_no; }  ?>" id="lot_no" name="lot_no" type="text"/></td><td>Gross Weight</td>
            
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->tare_weight; }  ?>" id="gross_weight" name="gross_weight" type="text"/></td>
            
        </tr>
        
        
        <tr>
            <td>Receiving Facility</td><td>
            <?php if(isset($_POST['from_routed_util_list'])){ 
                    $coxp = $container_route->recieving_facility_no;  
                    getFacilityList("",$coxp);
                  } else {
                    getFacilityList("","");
                  }  
             ?></a></td>
        <td>Tare Weight</td>
        <td><input value="" type="text" name="tara_weight" id="tara_weight"/></td>
       
        
           </tr>
            
            <tr>
            <td>Facility Address</td>
            <td><input value="" name="fac_address" id="fac_address" type="text"/></td>
            <td>Net Weight</td>
            <td><input value=""  name="net_weight" id="net_weight" type="text" readonly="true"/></td>
            
        </tr>
      
        <tr>
            <td>Facility Rep</td>
            <td><input value="N/A" id="fac_rep" name="fac_rep"  type="text" readonly=""/></td>
            <td rowspan="4" colspan="6">
            <br /><br />
            Facility Rep ________________________________________________________________________<br /><br />
             Driver _____________________________________________________________________________
            
            </td>
        </tr>
        
        <tr>
            <td>Driver</td><td>
            <?php //echo $container_route->driver_no; ?>
            <select name="drivers" id="drivers">
             
                <?php
                     $compare_driver="";
                     if( isset($_POST['from_routed_util_list']) ){ $compare_driver = $container_route->driver_no; }
                    $bv = $dbprefix."_users";
                    $ju = $db->query("SELECT first,last,user_id FROM $bv WHERE roles like '%service driver%' order by last");
                    if(count($ju)>0){
                        foreach($ju as $role){
                            echo "<option "; 
                            if($compare_driver == $role['user_id']){
                                echo "selected";
                            }
                            echo " value='$role[user_id]'>$role[first] $role[last]</option>";
                        }
                    }
                ?>
</select>
            </td>
        </tr>
        <tr>
            <td>IKG Transporter</td><td><input name="ikg_transporter" id="ikg_transporter" value="Biotane Pumping" readonly="" type="text"/></td>
        </tr>
        
        <tr>
            <td>View Day Route</td><td><select name="what_day" id="what_day"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select>
        </tr>
        
    </table>
    
    </div>
    <div id="spacebottom" style="height: 10px;background:white;width:100%;"></div>
    
    
    
    <div class="spacebottom" style="height: 10px;background:white;width:100%;"></div>
    
    <div id="data_display" style="width:100%;margin:auto;height:auto;min-height:400px;">
    <table style="width: 100%;padding:0px 0px 0px 0px;height:auto;">
    <tr><td colspan="15" style="font-weight:bold;color:red;">*NOTE: you MUST CLICK UPDATE FOR ANY CHANGES TO TAKE EFFECT.</td></tr>
    <tr>
    <td style="text-align:center;cursor:pointer;" id="test">Pickups:</td>
    
    <td style="text-align:center;" id="nop"><?php 
    if(isset($_POST['from_schedule_list'])){ 
        echo count($acnts);
    } else if(isset($_POST['from_routed_util_list'])){
        echo count($container_route->account_numbers);
    }
     ?></td>
    <td style="text-align:center;" colspan="1">&nbsp;</td>
    
    <td style="text-align:center;" colspan="3" id="estimated">
    
    </td>
    <td>
     &nbsp;
    </td>
    <td style="text-align: center;" id="collected">
    
    </td>
    </tr>
    </table>
    
    <table style="width:100%;margin:auto;" id="sortable">
    <thead>
    <tr>
        <td></td>
        <td style="padding: 0px 0px 0px 0px;width:50px;">
            <div class="cell" style="border-top-left-radius:5px;border-left: 1px solid black;border-top:1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);">
                Stop #
            </div>
        </td>
        
        
        <td  style="padding: 0px 0px 0px 0px;width:90px;">
            <div class="cell" style="border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);90px;">
                Code Red
            </div>
        </td>
        
        
        <td style="padding: 0px 0px 0px 0px;width:100px;">
            <div class="cell" style="border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:100px;">Status</div>
        </td>
        <td style="padding: 0px 0px 0px 0px;width:100px">
            <div class="cell" style="border-top: 1px solid black;padding:0px 0px 0px 0px;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:100px;">Scheduled</div>
        </td>
        <td style="padding: 0px 0px 0px 0px;"><div class="cell" style="border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);">Name</div></td>
        <td  style="padding: 0px 0px 0px 0px;"><div class="cell" style="border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);">City</div></td>
        
        <td  style="padding: 0px 0px 0px 0px;"><div class="cell" style="border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);">Address</div></td>
        <td  style="padding: 0px 0px 0px 0px;"><div class="cell" style="border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);">Info</div></td>
        <td  style="padding: 0px 0px 0px 0px;"><div class="cell" style="border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);">Account Notes</div></td>
       
       
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 1;
    $alter = 0;  
    $total_caps = 0;  
    if(isset($_POST['from_schedule_list'])){
        foreach($scheds as $x){  
           $alter++;            
            if($alter%2 == 0){
                $bg = '-moz-linear-gradient(center top , #F7F7F9, #E5E5E7) repeat scroll 0 0 rgba(0, 0, 0, 0)';
            }
            else { 
                $bg = 'transparent';
            }
        
            $util_sched = new Util_Stop($x);
            $acnt_info = new Account();
            $newdos = explode(" ",$buk[0]['date_of_service']);
            echo "<tr class='accnt_row' xlr='".$util_sched->account_number."' style='cursor:pointer;background:$bg'>";
                echo "<td><img src='img/delete-icon.jpg' title='remove stop $util_sched->schedule_id' xlr='$util_sched->account_number' class='deletesched' rel='$util_sched->schedule_id'/></td>";
                echo "<td>&nbsp;&nbsp;$count</td>";
                echo "<td>".code_red($util_sched->code_red)."</td>";
                echo "<td>".$util_sched->route_status ."</td>";
                echo "<td>".$util_sched->scheduled_start_date."</td>";
                echo "<td>".$util_sched->account_name."</td>";
                echo "<td>".$acnt_info->singleField($util_sched->account_number,"city")."</td>";
                echo "<td>".$acnt_info->singleField($util_sched->account_number,"address")."</td>";            
                echo "<td>".$util_sched->special_instructions.'<br/> '.$util_sched->notes."</td>";
                echo "<td>".$acnt_info->singleField($util_sched->account_number,"notes")."</td>";
            echo "</tr>";
            $count++;
        }
    } else if(  isset($_POST['from_routed_util_list'] )  )  {            
            $account_info = new Account();
            $count = 1;
            //var_dump($newlist_scheds);
            foreach ($newlist_scheds as $x){
                $alter++;            
                if($alter%2 == 0){
                    $bg = '-moz-linear-gradient(center top , #F7F7F9, #E5E5E7) repeat scroll 0 0 rgba(0, 0, 0, 0)';
                }
                else { 
                    $bg = 'transparent';
                }
                
                $util_sched = new Util_Stop($x);
                $acnt_info = new Account();
                $newdos = explode(" ",$buk[0]['date_of_service']);
                echo "<tr class='accnt_row' xlr='".$util_sched->account_number."' style='cursor:pointer;background:$bg'>";
                    echo "<td>"; 
                        if(strtolower($util_sched->route_status) == "enroute" || strtolower($util_sched->route_status) == "scheduled" ){
                            echo "<img src='img/delete-icon.jpg' title='remove stop $util_sched->schedule_id' xlr='$util_sched->account_number' class='deletesched2' rel='$util_sched->schedule_id'/>";
                        }else {
                            echo "&nbsp;";
                        }                    
                    echo "</td>";
                    echo "<td>&nbsp;&nbsp;$count</td>";
                    echo "<td>".code_red($util_sched->code_red)."</td>";
                    echo "<td>".$util_sched->route_status."</td>";
                    echo "<td>".$util_sched->scheduled_start_date."</td>";
                    echo "<td>".$util_sched->account_name."</td>";
                    echo "<td>".$acnt_info->singleField($util_sched->account_number,"city")."</td>";
                    echo "<td>".$acnt_info->singleField($util_sched->account_number,"address")."</td>";            
                    echo "<td>";
                    $kl = $db->query("SELECT fieldreport FROM freight_utility_data_table WHERE schedule_id= $x");
                    if(count($kl)>0){
                        foreach($kl as $fr){
                            echo $fr['fieldreport'];
                        }
                    }
                    
                    echo"</td>";
                    echo "<td>".$acnt_info->singleField($util_sched->account_number,"notes")."</td>";
                    
                echo "</tr>";
                $count++;
                $schedulesx .= $schedules['utility_sched_id']."|"; 
            }
          
    }
    ?>
    </tbody>
    </table>
        <div style="clear: both;"></div>
    </div>
    <div class="spacebottom" style="height: 10px;background:white;width:100%;"></div>
</div>


</div>
<?php


?>
<input type="text" name="scheduled_ids" value="<?php  if(isset($_POST['from_schedule_list'])){ echo "$_POST[schecheduled_ids]"; } else if(  isset($_POST['from_routed_util_list'] )  )  {  foreach($newlist_scheds as $sch){
    echo "$sch|";
}  } ?>" id="scheduled_ids"  readonly=""/>
<input type="text" name="account_nos" value="<?php  if(isset($_POST['from_schedule_list'])){ echo "$_POST[accounts_checked]";}else if(  isset($_POST['from_routed_util_list'] )  )  { echo $container_route->acount_numbers_full_string; } ?>" id="account_nos"  readonly=""/>
 <input type="text" name="return_stops" placeholder="return stops" id="return_stops" readonly=""/>
 
 <input type="hidden" value="<?php if(isset($_POST['add_to_existing'])){ echo $_POST['schecheduled_ids']; } ?>" name="newly_added_stops" placeholder="Newly added stops"  readonly="" id="newly_added_stops" title="Newly added stops"/>
 
 
 <input type="text"  value="<?php if(isset($_POST['add_to_existing'])){ echo $_POST['accounts_checked']; } ?>"   name="newly_added_accounts" placeholder="Newly added accounts"  readonly="" id="newly_added_accounts" title="Newly added accounts"/>
 
 <form id="resubmit_this" method="post" action="ikg_routing.php">
<input type="text" name="util_number" id="form_route" value="<?php echo $_POST['util_number']; ?>" placeholder="route id to be submitted" readonly=""/>
<input type="text" name="from_routed_util_list" value="1" readonly=""/>
</form>
 
<script>
    function reOrder(){        
        $("#sortable tbody tr").find("td:nth-child(2)").each(function (i) {
             $(this).html(i+1);
        });
        var rowCount = $('#sortable tbody tr').length;
        $("#nop").html(rowCount);
        $("#loading").fadeOut("fast");
    }
     
     
    function close_window() {
      if (confirm("Route Created Successfully Please Navigate to routed Utlity Routes page")) {
        close();
      }
    } 
     
     $(".deletesched2").click(function(){        
         $(this).closest('tr').remove();
         var account   = $("input#account_nos").val();
         var schedules = $("input#scheduled_ids").val();
         var replace_scheds = $(this).attr('rel')+"|";
         var replace_accounts = $(this).attr('xlr')+"|";
         $("input#return_stops").val( $("input#return_stops").val()+replace_scheds );
         $("input#account_nos").val( account.replace(replace_accounts,"")  );
         $("input#scheduled_ids").val( schedules.replace(replace_scheds,"") );
         $("#nop").html( $("#nop").html() - 1);     
          setTimeout('reOrder()',1000);       
     });
     


    $(".deletesched").click(function(){
        $(this).closest('tr').remove();
         var account   = $("input#account_nos").val();
         var schedules = $("input#scheduled_ids").val();
         var replace_scheds = $(this).attr('rel')+"|";
         var replace_accounts = $(this).attr('xlr')+"|";
         $("input#account_nos").val( account.replace(replace_accounts,"")  );
         $("input#scheduled_ids").val( schedules.replace(replace_scheds,"") );
         $("#nop").html( $("#nop").html() - 1);
         setTimeout('reOrder()',1000);
          
    });

    $.post("getFacilAddress.php",{facil:$("#facility").val()},function(data){
        $("input#fac_address").val(data);
    });
   
   $("#facility").change(function(){
        $.post("getFacilAddress.php",{facil: $(this).val() },function(data){
            $("input#fac_address").val(data);
        });
         $("#facholder").html( $(this).find("option:selected").text()  );
         alert($(this).find("option:selected").text() );
        
   });
    $.post("getTruckInfo.php",{choose:1,id: $("#vehicle").val() },function(data){
        //alert(data);
        $("input#lic_plate").val(data);  
    });
    
    $.post("getTruckInfo.php",{choose:2,id:$("#vehicle").val() },function(data){
        $("input#ikg_decal").val(data);
    });

    $("#vehicle").change(function(){
        $.post("getTruckInfo.php",{choose:1,id:$(this).val() },function(data){
            $("input#lic_plate").val(data);  
        });
        
        $.post("getTruckInfo.php",{choose:2,id:$(this).val() },function(data){
            $("input#ikg_decal").val(data);
        });
    });

    $("input#recurring_value").hide();
    
    $("input#sched_route_start").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});    
    //$("input#completion").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
    $("input#timestart").timepicker();
    $("input#firststop").timepicker();
    $("input#laststop").timepicker();
    $("input#endtime").timepicker();
    
    
    $("#recurring").click(function(){        
        if ($(this).is(':checked')) {
            $("input#recurring_value").show();
        } else { 
            $("input#recurring_value").hide();
        }        
    });
    
    
    $("#ikg_update").on('click',function(){
        $("#loading").show();
         var reordered ="";
        $(".accnt_row").each(function(){
            reordered+= $(this).attr('xlr')+"|";
        });        
        $("input#account_nos").val(reordered);   
        $.post('ikg_update_util.php',{
            rid:$("#rid").html(),
            ikg_manifest_route_number: $("input#ikgmanifestnumber").val(),
            tank_1: $("input#tank1").val(),
            tank_2: $("input#tank2").val(),
            time_start: $("input#timestart").val(),
            start_mileage: $("input#start_mileage").val(),
            sched_route_start: $("input#sched_route_start").val(),
            vehicle: $("#vehicle").val(),
            first_stop: $("input#firststop").val(),
            first_stop_mileage: $("input#first_stop_mileage").val(),
            completion_date : $("input#completion").val(),
            lic_plate: $("input#lic_plate").val(),
            last_stop: $("input#laststop").val(),
            last_stop_mileage :$("input#last_stop_mileage").val(),
            unique_route_no : $("input#unique_route_no").val(),
            ikg_decal : $("input#ikg_decal").val(),
            end_time : $("input#endtime").val(),
            end_mileage: $("input#end_mileage").val(),
            location: $("input#location").val(),
            ikg_collected: $("input#ikg_collected").val(),
            fuel: $("input#fuel").val(),
            inventory_code : $("input#inventory_code").val(),
            lot_no : $("input#lot_no").val(),
            gross_weight: $("input#gross_weight").val(),
            reciev_fac : $("#facility").val(),
            tara_weight: $("input#tara_weight").val(),
            fac_address: $("input#fac_address").val(),
            net_weight: $("input#net_weight").val(),
            fac_rep: $("input#fac_rep").val(),
            drivers : $("#drivers").val(),
            ikg_transporter: $("input#ikg_transporter").val(),
            mult_day_route : $("#days_in_route").val(),           
            number_of_picksup: $("#nop").html(),
            accounts: reordered,
            schedules: $("input#scheduled_ids").val(),
            total_estimate:$("#estimated").html(),
            return_stops:$("input#return_stops").val(),
            what_day:$("#what_day").val(),
            newly_added_stops:$("input#newly_added_stops").val(),
            newly_added_accounts:$("input#newly_added_accounts").val()
        },function(data){
             if( parent.window.opener.location.href == "https://inet.iwpusa.com/scheduling.php?task=suc" ){//did you come from scheduled utility pickups?
                    parent.window.opener.document.location.reload(true);
             }
            alert('Route Updated!');
            //$("#resubmit_this").submit();
            //$("#debug").append(data);
        });
        
    });
    
    $("#ikg_create").click(function(){
        $("#loading").show();
         var reordered ="";
        $(".accnt_row").each(function(){
            reordered =  reordered+$(this).attr('xlr')+"|";            
        });             
        $("input#account_nos").val(reordered);   
        
        if( $("#rid").text().trim() == "" ){
            $.post('ikg_insert_util.php',{           
                ikg_manifest_route_number: $("input#ikgmanifestnumber").val(),
                tank_1: $("input#tank1").val(),
                tank_2: $("input#tank2").val(),
                time_start: $("input#timestart").val(),
                start_mileage: $("input#start_mileage").val(),
                sched_route_start: $("input#sched_route_start").val(),
                vehicle: $("#vehicle").val(),
                first_stop: $("input#firststop").val(),
                first_stop_mileage: $("input#first_stop_mileage").val(),
                completion_date : $("input#completion").val(),
                lic_plate: $("input#lic_plate").val(),
                last_stop: $("input#laststop").val(),
                last_stop_mileage :$("input#last_stop_mileage").val(),
                unique_route_no : $("input#unique_route_no").val(),
                ikg_decal : $("input#ikg_decal").val(),
                end_time : $("input#endtime").val(),
                end_mileage: $("input#end_mileage").val(),
                location: $("input#location").val(),
                ikg_collected: $("input#ikg_collected").val(),
                fuel: $("input#fuel").val(),
                inventory_code : $("input#inventory_code").val(),
                lot_no : $("input#lot_no").val(),
                gross_weight: $("input#gross_weight").val(),
                reciev_fac : $("#facility").val(),
                tara_weight: $("input#tara_weight").val(),
                fac_address: $("input#fac_address").val(),
                net_weight: $("input#net_weight").val(),
                fac_rep: $("input#fac_rep").val(),
                drivers : $("#drivers").val(),
                ikg_transporter: $("input#ikg_transporter").val(),
                mult_day_route : $("#days_in_route").val(),           
                number_of_picksup: $("#nop").html(),
                what_day:$("#what_day").val(),
                accounts: reordered,
                schedules: $("input#scheduled_ids").val(),
                mode:$("input#mode").val(),
                total_estimate:$("#estimated").html()
            },function(data){
                
                $("#debug").html(data);
                $("#rid").html(data);
                $("#statquo").html("enroute");                 
                $("input#unique_route_no").val(data);
                $("#edata").html('<a href="enterUtilComplete.php?route_id='+data+'"><img src="img/enterdata.jpg"/></a>');
                $("#pdfexp").html('<a href="downloadreceipt.php?route_no='+data+'" target="_blank"><img src="img/pdf.jpg"/></a> ');
                $("#print").html('<a href="print_oil_ikg.php?ikg='+data+'" target="_blank"><img src="img/print.jpg"/></a>');
                $("#exp").html('<a href="export_route.php?ikg='+data+'" target="_blank"><img src="img/xls.jpg"/></a>');
                $("#sortable tbody tr").find("td:nth-child(4)").each(function (i) {
                     $(this).html("enroute");
                });
                close_window();
            });  
        } else {            
           $.post('ikg_update_util.php',{
                rid:$("#rid").html(),
                ikg_manifest_route_number: $("input#ikgmanifestnumber").val(),
                tank_1: $("input#tank1").val(),
                tank_2: $("input#tank2").val(),
                time_start: $("input#timestart").val(),
                start_mileage: $("input#start_mileage").val(),
                sched_route_start: $("input#sched_route_start").val(),
                vehicle: $("#vehicle").val(),
                first_stop: $("input#firststop").val(),
                first_stop_mileage: $("input#first_stop_mileage").val(),
                completion_date : $("input#completion").val(),
                lic_plate: $("input#lic_plate").val(),
                last_stop: $("input#laststop").val(),
                last_stop_mileage :$("input#last_stop_mileage").val(),
                unique_route_no : $("input#unique_route_no").val(),
                ikg_decal : $("input#ikg_decal").val(),
                end_time : $("input#endtime").val(),
                end_mileage: $("input#end_mileage").val(),
                location: $("input#location").val(),
                ikg_collected: $("input#ikg_collected").val(),
                fuel: $("input#fuel").val(),
                inventory_code : $("input#inventory_code").val(),
                lot_no : $("input#lot_no").val(),
                gross_weight: $("input#gross_weight").val(),
                reciev_fac : $("#facility").val(),
                tara_weight: $("input#tara_weight").val(),
                fac_address: $("input#fac_address").val(),
                net_weight: $("input#net_weight").val(),
                fac_rep: $("input#fac_rep").val(),
                drivers : $("#drivers").val(),
                ikg_transporter: $("input#ikg_transporter").val(),
                mult_day_route : $("#days_in_route").val(),           
                number_of_picksup: $("#nop").html(),
                what_day:$("#what_day").val(),
                accounts: reordered,
                schedules: $("input#scheduled_ids").val(),
                total_estimate:$("#estimated").html(),
                return_stops:$("input#return_stops").val(),
                newly_added_stops:$("input#newly_added_stops").val(),
                newly_added_accounts:$("input#newly_added_accounts").val()
            },function(data){
                 if( parent.window.opener.location.href == "https://inet.iwpusa.com/scheduling.php?task=suc" ){//did you come from scheduled utility pickups?
                        parent.window.opener.document.location.reload(true);
                 }
                
                $("#resubmit_this").submit();
                //$("#debug").append(data);
                $("#loading").fadeOut("fast");
            });   
        }
    });
    
    $( "#sortable tbody" ).on( "sortbeforestop", function( event, ui ) {
        $("#loading").show();
       setTimeout('reOrder()',1000);
    });
    
    
    $("#refresh").click(function(){
        $("#resubmit_this").submit();
   });
   
    $("#null").click(function(){
        alert("Please create route before refreshing"); 
    });

</script>
</html>
