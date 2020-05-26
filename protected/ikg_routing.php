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
 if(isset($_SESSION['id'])){ 
    $person = new Person();
 }

$acnts = "";
$scheds = "";

if( isset($_POST['from_routed_util_list']) ){
    $container_route = new util_route($_POST['util_number']);
    if(isset($_POST['add_to_existing'])){//are you adding more stops to an existing account ?
      $acnts = array_map('intval', explode("|","$_POST[accounts_checked]"));
      $scheds = array_map('intval', explode("|","$_POST[schecheduled_ids]"));
      array_pop($acnts);
      array_pop($scheds);
      $newlist_accounts = array_merge($container_route->account_numbers,$acnts);// temporarily add to stops 
      $newlist_scheds = array_merge($container_route->scheduled_routes,$scheds);// temporarily add to stops 
    }else {
        $newlist_accounts = $ikg_info->account_numbers;
        $newlist_scheds = $ikg_info->scheduled_routes;
    }
    
    echo "<pre>";
    print_r($container_route);
    echo "</pre>";
    //var_dump($container_route->account_numbers);
    //var_dump($container_route->scheduled_routes);
    
}
else if(isset($_POST['from_schedule_list'])){//creating a new manifest
       if( isset($_POST['accounts_checked']) && isset($_POST['schecheduled_ids']) ){
           //echo "$_POST[schecheduled_ids]<br/>"     ;
           //echo "$_POST[accounts_checked]<br/>";
           $acnts = array_map('intval', explode("|","$_POST[accounts_checked]"));
           $scheds = array_map('intval', explode("|","$_POST[schecheduled_ids]"));
           array_pop($acnts);
           array_pop($scheds);
           //var_dump($scheds);
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
<title>Utility Routing</title>
<input type="hidden" name="mode" id="mode"  value=""/>
<div id="wrapper">
<div id="spacer" style="width: 100%;height:10px;">&nbsp;</div>

<div class="content-wrapper" style="min-height:450px;height: auto;">
    <div id="fullgray" style="width: 100%;height:30px;background: linear-gradient(#F0F0F0, #CFCFCF) repeat scroll 0 0 rgba(0, 0, 0, 0);border-bottom: 1px solid #808080;margin-bottom:10px;">
            <div id="info_hold" style="width: 700px;margin:auto;height:30px;background:transparent;padding">
            <table style="width:700px;"><tr><td>Route ID:</td><td id="rid">  <?php if( isset($_POST['from_routed_util_list']) ){ echo $container_route->route_id; }   ?> </td><td>Facility: <span id="facholder">
            <?php if( isset($_POST['from_routed_util_list']) ){ echo $container_route->recieving_facility; }   ?>
            </span> </td></tr></table>
            </div>
    </div>
    
    <div id="fullgray" style="width: 100%;height:150px;background: linear-gradient(#F0F0F0, #CFCFCF) repeat scroll 0 0 rgba(0, 0, 0, 0);border-bottom: 1px solid #808080;margin-bottom:10px;">
        <div id="info_hold" style="width: 1000px;margin:auto;height:100px;background:transparent;padding">
                <div id="leftsideboxes" style="float: left;width:600px;height:100px;">
                       <div class="leftsides" style="width:600px;height:100px;float:left;">
                        <div class="box" style="width:250px;border-radius:7px;height:60px;margin-bottom:5px;cursor:pointer;">
                        <table><tr><td style="text-align: center;vertical-align:middle;width:250px;height:60px;" id="edata">
                        <?php
                            if(isset($_POST['from_routed_util_list'])){
                                echo "<a href='enterUtilComplete.php?route_id=$container_route->route_id' target='_blank'<img src='img/enterdata.jpg'/></a>";    
                            }
                        
                         ?>
                         </td></tr></table>
                        </div>                       
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
                        <div class="box" style="width: 60px;height:60px;border-radius:7px;box-shadow: 10px 10px 5px #888888;float:left;background:url(img/createmod.jpg) center top;background-size:contain;cursor:pointer;"   title="Save"   id="ikg_create" >
                        
                        </div>
                    
                    </div>             
                </div>
                <div id="rightsideboxes" style="width: 400px;height:100px;float:left;">
                    <table style="width: 400px;height:100px;font-size:10px;"><tr><td>Enter Data In</td><td>
                    
                    <select name="entered_data" id="entered_data">
                        <option value="gallons">gallons</option></select></td>
                        
                        <td>Status</td>
                        <td>
                    <!--- selecting en-route enables 'enter data' button ---!>
                    <select id="status" name="status">
                        <option id="route"value="enroute">En-Route</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                    </select>


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
            <td><input id="tank1" value="" name="tank1" type="text"/><br /><input id="tank2" name="tank2" value="" type="text"/></td>
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
                if(isset($_POST['from_routed_util_list'])){ $compare = $container_route->truck; }            
                $vehicle = $db->get($dbprefix."_truck_id");
                if(count($vehicle) >0){
                    foreach($vehicle as $truck){
                        
                       echo "<option "; 
                        if($compare == $truck['truck_id']){
                            echo "selected";
                        }
                       echo " value='$truck[truck_id]'>$truck[name]</option>";
                    }
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
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->completed_date; }  ?>" id="completion" type="text"/></td>
            <td>License Plate</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->license_plate; }  ?>" type="text" name="lic_plate" id="lic_plate"/></td>
            <td>Last Stop</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->last_stop; }  ?>" id="laststop" name="laststop" type="text"/></td>
            <td>Last Stop Mileage</td>
            <td><input value="<?php if(isset($_POST['from_routed_util_list'])){ echo $container_route->last_stop_mileage; }  ?>" id="last_stop_mileage" name="last_top_mileage"  type="text"/></td>
        </tr>
        
        <tr>
            <td>IKG Route Number</td>
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
            <td><input value="" id="fac_rep" name="fac_rep" type="text"/></td>
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
            <td>View Day Route</td><td><select name="mult_day_route" id="mult_day_route"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select>
        </tr>
        
    </table>
    
    </div>
    <div id="spacebottom" style="height: 10px;background:white;width:100%;"></div>
    
    
    
    <div class="spacebottom" style="height: 10px;background:white;width:100%;"></div>
    
    <div id="data_display" style="width:100%;margin:auto;height:auto;min-height:400px;">
    <table style="width: 100%;padding:0px 0px 0px 0px;height:auto;">
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
        <td  style="padding: 0px 0px 0px 0px;"><div class="cell" style="border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);">Address</div></td>
        <td  style="padding: 0px 0px 0px 0px;"><div class="cell" style="border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);">Info</div></td>
        
        <td  style="padding: 0px 0px 0px 0px;"><div class="cell" style="border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);">Tote-Size</div></td>
       
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
                echo "<td>".$acnt_info->singleField($util_sched->account_number,"address")."</td>";            
                echo "<td>".$util_sched->special_instructions.'<br/> '.$util_sched->notes."</td>";
                echo "<td>".containerNumToName($util_sched->container_label) ."</td>";
            echo "</tr>";
            $count++;
        }
    } else if(  isset($_POST['from_routed_util_list'] )  )  {
        $ask = $db->where("rout_no",$container_route->route_id)->get($dbprefix."_utility");
        
        if(count($ask)>0){
            $schedulesx="";
            $account_info = new Account();
            $count = 1;
            foreach ($ask as $schedules){
                $buffer4 = explode(" ",$schedules['date_of_service']);
                $check_container_info = $db->where('container_id',$schedules['container_label'])->get($dbprefix.'_list_of_containers','amount_holds');
                echo "<tr class='accnt_row' xlr=' ".$schedules['account_no']."'>
                    <td>$count</td>
                    <td>".code_red($schedules['code_red'])."</td>
                    <td>$schedules[route_status]</td>
                    <td>$buffer4[0]</td>
                    <td>".account_NumToName($schedules['account_no'])."</td>
                    <td>". $account_info->singleField($schedules['account_no'],"address")."</td>
                    <td>$schedules[dispatcher_note]<br/>$schedules[driver_note]</td>
                    <td>".$check_container_info[0]['amount_holds']."</td>
                </tr>";
                $count++;
                $schedulesx .= $schedules['utility_sched_id']."|"; 
            }
        }  
    }
    ?>
    </tbody>
    </table>
        <div style="clear: both;"></div>
    </div>
    <div class="spacebottom" style="height: 10px;background:white;width:100%;"></div>
</div>

<div id="debug"></div>
</div>
<?php


?>
<input type="text" name="scheduled_ids" value="<?php  if(isset($_POST['from_schedule_list'])){ echo "$_POST[schecheduled_ids]"; } else if(  isset($_POST['from_routed_util_list'] )  )  {  echo $schedulesx;  } ?>" id="scheduled_ids"/>
<input type="text" name="account_nos" value="<?php  if(isset($_POST['from_schedule_list'])){ echo "$_POST[accounts_checked]";}else if(  isset($_POST['from_routed_util_list'] )  )  { echo $container_route->acount_numbers_full_string; } ?>" id="account_nos" />
 <input type="text" name="return_stops" placeholder="return stops" id="return_stops"/>
<script>
     
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
    $("input#completion").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
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
            mult_day_route : $("#mult_day_route").val(),           
            number_of_picksup: $("#nop").html(),
            accounts: $("input#account_nos").val(),
            schedules: $("input#scheduled_ids").val(),
            status: $("#status").val(),
            total_estimate:$("#estimated").html()
            
        },function(data){
            alert('Route Updated!');
        });
        
    });
    
    $("#ikg_create").click(function(){
        
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
                mult_day_route : $("#mult_day_route").val(),           
                number_of_picksup: $("#nop").html(),
                accounts: $("input#account_nos").val(),
                schedules: $("input#scheduled_ids").val(),
                status: $("#status").val(),
                mode:$("input#mode").val(),
                total_estimate:$("#estimated").html()
            },function(data){
                alert('Route Saved!');  
                $("#debug").html(data);
                $("#rid").html(data);                 
                $("input#unique_route_no").val(data);
                $("#edata").html('<a href="enterUtilComplete.php?route_id='+data+'"><img src="img/enterdata.jpg"/></a>');
                $("#pdfexp").html('<a href="downloadreceipt.php?route_no='+data+'" target="_blank"><img src="img/pdf.jpg"/></a> ');
                $("#print").html('<a href="print_oil_ikg.php?ikg='+data+'" target="_blank"><img src="img/print.jpg"/></a>');
                $("#exp").html('<a href="export_route.php?ikg='+data+'" target="_blank"><img src="img/xls.jpg"/></a>')
            });  
        } else {
            alert($("#rid").html()+" updating");
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
                mult_day_route : $("#mult_day_route").val(),           
                number_of_picksup: $("#nop").html(),
                accounts: $("input#account_nos").val(),
                schedules: $("input#scheduled_ids").val(),
                status: $("#status").val(),
                total_estimate:$("#estimated").html()
            },function(data){
                $("#debug").html(data);
            });   
        }
    });
    
    
    function reOrder(){        
        $("#sortable tbody tr").find("td:nth-child(2)").each(function (i) {
             $(this).html(i+1);
        });
        var rowCount = $('#sortable tbody tr').length;
        $("#nop").html(rowCount);
    }
   
    
    $( "#sortable tbody" ).on( "sortbeforestop", function( event, ui ) {
       setTimeout('reOrder()',1000);
    });
</script>
