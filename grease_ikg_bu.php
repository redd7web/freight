<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        
<?php   

//error_reporting(E_WARNING | E_PARSE | E_NOTICE);
include "protected/global.php";
include "source/scripts.php"; 
include "source/css.php";
$account_table = $dbprefix."_accounts";
$ikg_grease_table = $dbprefix."_ikg_grease";
$grease_list = $dbprefix."_list_of_grease";
$total_col =0;  
 if(isset($_SESSION['freight_id'])){ 
    $person = new Person();
 

    $acnts_ = "";
    $scheds_ = "";


if(isset($_POST['from_routed_grease_list'])){
    //echo "route id :".$_POST['util_routes']."<br/>";
    
    $grease_ikg = new Grease_IKG($_POST['util_routes']);
    //print_r($grease_ikg);
    
    /*echo "<pre>";
    print_r($grease_ikg->account_numbers);
    echo "</pre>";*/
    //echo $grease_ikg->acount_numbers_full_string."<br/>";
    
    if(isset($_POST['add_to_route'])){
        
        $acnts = explode ("|", $_POST['accounts_checked']);//additional accounts
        $schnums = array_map('intval',explode("|",$_POST['schecheduled_ids']) );//additional schedules
        array_pop($acnts);
        array_pop($schnums);        
        $newlist_accounts = array_merge($grease_ikg->account_numbers,$acnts);
        $newlist_scheds = array_merge($grease_ikg->scheduled_routes,$schnums);
        $newlist_scheds = array_unique($newlist_scheds);
        $newlist_accounts = array_unique($newlist_accounts);
        
    } else {
        $newlist_accounts = $grease_ikg->account_numbers;
        $newlist_scheds = $grease_ikg->scheduled_routes;
        $newlist_scheds = array_unique($newlist_scheds);
        $newlist_accounts = array_unique($newlist_accounts);
        /*echo "<pre>";
        print_r($newlist_accounts);
        echo "</pre>";*/
    }
    
    
    $newlist_accounts = array_filter($newlist_accounts);
    $newlist_scheds = array_filter($newlist_scheds);
    //print_r($newlist_scheds);
   
    foreach($newlist_scheds as $check_locked){//if account becomes unlocked, remove from route 
        $gs = new Grease_Stop($check_locked);
        
        if($gs->locked ==1){
            echo $gs->account_name." locked and removed <br/>";
            $db->query("UPDATE freight_grease_traps SET grease_route_no = NULL, route_status='scheduled' WHERE grease_no = $gs->grease_no AND route_status !='completed'");
            $db->query("UPDATE freight_ikg_grease SET account_numbers = REPLACE(account_numbers, '$gs->account_number|', '') WHERE route_id =$_POST[util_routes]");
        }
    }
    
   //update inc/ completed
   
  
   
   
    
} else if( isset($_POST['from_schedule_list'])  ) {
    
    if (isset($_POST['schecheduled_ids'])  && isset($_POST['accounts_checked'])){
        $actns = explode("|",$_POST['accounts_checked']);
        $schnums = explode("|",$_POST['schecheduled_ids']);
        array_pop($actns);
        array_pop($schnums);
        $acnts = $actns;
        
        $scheds = array_unique($scheds);
        
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


<title>Grease Routing</title>
<div id="loading" style="position: fixed;width:100%;height:100%;z-index:9999;background:rgba(255,255,255,.7) url(img/loading.gif) no-repeat center center;text-align:center; font-weight:bold;font-size:30px;">
<br /><br />
Processing...
</div>
<input type="hidden" name="mode" id="mode"  value=""/>
<div id="debug"></div>
<div id="wrapper">
<div id="spacer" style="width: 100%;height:10px;">&nbsp;</div>

<div class="content-wrapper" style="min-height:450px;height: auto;">
    <div id="fullgray" style="width: 100%;height:30px;background: linear-gradient(#F0F0F0, #CFCFCF) repeat scroll 0 0 rgba(0, 0, 0, 0);border-bottom: 1px solid #808080;margin-bottom:10px;">
            <div id="info_hold" style="width: 700px;margin:auto;height:30px;background:transparent;padding">
            <table style="width:700px;"><tr><td>Route ID:</td><td id="rid">  <?php if( isset($_POST['from_routed_grease_list']) ){ echo $grease_ikg->route_id;  }   ?> </td><td>Facility: <span id="facholder">
            <?php if( isset($_POST['from_routed_grease_list']) ){ echo $grease_ikg->recieving_facility;  }   ?>
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
                            if(isset($_POST['from_routed_grease_list'])){
                                echo "<a class='enterDataLink' href='enterGreaseData.php?route_id=$grease_ikg->route_id&day=1' target='_blank'><img src='img/enterdata.jpg'/></a>";    
                            }
                        
                         ?>
                         </td></tr></table>
                        </div>
                        <div <?php  if(isset($_POST['from_routed_grease_list'])){ echo 'id="refresh"';} ?> class="box" style="width: 60px;height:60px;box-shadow: 2px 2px 5px #888888;float:left;background:url(img/refresh.png) no-repeat center top;background-size:contain;cursor:pointer;"></div> 
                        <div class="box" style="width: 60px;height:60px;box-shadow: 2px 2px 5px #888888;float:left;">
                         <?php if(isset($_POST['from_routed_grease_list'])){
                                        echo "<a href='textDriver.php?route_id=$grease_ikg->route_id' rel='shadowbox;width=400px;height=250px;'>"; 
                                }
                        ?>
                        <img src="img/text.png" style="width: 60px;height:60px;"/>
                        <?php  if(isset($_POST['from_routed_grease_list'])){ 
                             echo  "</a>";
                        } ?>
                        </div>
                        <div class="box" style="width: 60px;height:60px;box-shadow: 2px 2px 5px #888888;float:left;">
                        <?php if(isset($_POST['from_routed_grease_list'])){ ?>
                        <a href="indexmap.php?route=<?php echo $_POST['util_routes']."&first=1&type=grease"; ?>" target="_blank">
                        <?php } ?><img src="img/route.jpg"/></a>
                        
                        </div>
                        <div class="box" style="width: 60px;height:60px;border-radius:7px;box-shadow: 10px 10px 5px #888888;float:left;text-align:center;" id="exp">
                        <?php
                            if(isset($_POST['from_routed_grease_list'])){
                                echo "<a href='grease_to_excel.php?route_no=$grease_ikg->route_id'><img src='img/xls.jpg'/></a>";
                            }
                        ?>
                        </div>
                        
                        
                        <div class="box" style="width: 60px;height:60px;border-radius:7px;box-shadow: 10px 10px 5px #888888;float:left;background-size:contain;cursor:pointer;" id="pdfexp">
                        <?php if(isset($_POST['from_routed_grease_list'])){ ?>
                        <a href="grease_recipet.php?route_no=<?php echo $grease_ikg->route_id; ?>" target="_blank"><img src="img/pdf.jpg" /></a>
                        <?php }  ?>
                        </div>
                        <div class="box" style="width: 60px;height:60px;border-radius:7px;box-shadow: 10px 10px 5px #888888;float:left;background-size:contain;cursor:pointer;" id="print">
                             <?php
                            if(isset($_POST['from_routed_grease_list'])){
                                echo "<a href='print_grease.php?route_no=$grease_ikg->route_id' target='_blank'><img src='img/print.jpg'/></a>";
                            }
                        ?>
                        
                        </div>
                        <div class="box" style="width: 60px;height:60px;border-radius:7px;box-shadow: 10px 10px 5px #888888;float:left;background:url(img/createmod.jpg) center top;background-size:contain;cursor:pointer;"
                        <?php if(isset($_POST['from_routed_grease_list'])){ ?>  title="Update"  id="ikg_update" <?php } else { ?>title="Save"   id="ikg_create" <?php } ?> ></div>
                        
                        <div class="box" style="width: 60px;height:60px;box-shadow:2px 2px 5px #888888;float:left;backgroundsize:contain;" id="field_report">
                         <?php  if(isset($_POST['from_routed_grease_list'])){ ?> <a href="field_report.php?ikg=<?php   echo $grease_ikg->route_id."&type=grease";   ?>"  rel="shadowbox;width=600px;height=500px;"><img src="img/bullhorn.png" style="width: 60px;height:60px;"/></a> <?php } ?>
                        </div>
                        <div class="box" style="width: 60px;height:60px;box-shadow: 2px 2px 5px #888888;float:left;" title="Manual Reorder" id="manual_reorder" >
                            <?php if(isset($_POST['from_routed_grease_list'])){ echo '<a href="manualreorder.php?type=grease&route_id='.$grease_ikg->route_id.'" rel="shadowbox;width=500;height=500;"><img src="img/reorder.png"/></a>';   } ?>
                        </div>  
                        <div class="box" style="width: 60px;height:60px;box-shadow: 2px 2px 5px #888888;float:left;" title="Recover Stops" id="recover" title="Warning this will undo optimization">
                            <img src="../img/Recover-512.png" style="width:60px;height:60px;cursor:pointer;"/>
                        </div>                      
                    </div>             
                </div>
                <div id="rightsideboxes" style="width: 400px;height:100px;float:left;">
                    <table style="width: 400px;height:100px;font-size:10px;"><tr><td>Enter Data In</td><td>
                    
                    <select name="entered_data" id="entered_data">
                        <option value="grease">grease</option></select></td>
                        
                        <td>Status</td>
                        
                        <td  style="font-size: 22px;font-weight:bold;" id="statquo">
                        <?php
                        $r_status ="";
                        
                        if(isset($_POST['from_routed_grease_list'])){
                            //echo $_POST['util_routes']."<br/>";
                            $is_completed = $db->where("route_id",$_POST['util_routes'])->get($dbprefix."_list_of_grease","status");                        
                            //var_dump($is_completed);
                            if(count($is_completed)){
                                $r_status = $is_completed[0]['status'];
                                echo $r_status;
                            }    
                            else {
                                echo "enroute";
                            }
                        } else {
                            echo "enroute";
                        }
                        ?>
</td></tr>
                    <tr><td style="vertical-align: top;">Recurring</td><td style="vertical-align: top;"><input type="checkbox" id="recurring"/><br /><input type="text" id="recurring_value" style="width: 50px;height:25px;" /></td><td colspan="2">How Many Days In Route</td><td colspan="2">
                        <select name="days_in_route" id="mult_day_route">
                        <?php
                            
                          for($i=1;$i<6;$i++){
                            $compare ="";
                            if($grease_ikg->number_days_route == $i){
                                $compare = "selected";
                            }
                            echo "<option $compare value='$i'>$i</option>";
                          }
                        
                        ?>
                        
                        </select></td></tr>
                        <tr><td colspan="4"><input <?php if(isset($_POST['from_routed_grease_list'])){ if($grease_ikg->vic == 0){ echo " checked "; }  } ?>  type="radio" name="vic" id="imp"/>&nbsp;Import&nbsp;&nbsp;<input type="radio" name="vic" id="expo"  <?php if(isset($_POST['from_routed_grease_list'])){ if($grease_ikg->vic == 1){ echo " checked "; }  } ?>/>&nbsp;Export Only</td></tr>
                    </table>
                </div>      
               
                <?php if(isset($_POST['from_routed_grease_list'])){ 
                    ?>
                    <script>
                        $("#imp").click(function(){
                           $.post("victorville.php",{value:0,route:<?php echo $grease_ikg->route_id; ?>},function(data){
                                alert("Route Updated!");
                           }); 
                        });
                        $("#expo").click(function(){
                           $.post("victorville.php",{value:1,route:<?php echo $grease_ikg->route_id; ?>},function(data){
                                alert("Route Updated!");
                           }); 
                        });
                    </script>
                    <?php
                } ?>   
        </div>    
    </div>
    
    <div id="fields" style="width: 1000px;min-height:300px;height:auto;margin:auto;">
    <table style="width: 100%;font-size:10px;" id="meatikg">
        <tr>
            <td>IKG Title</td>
            <td><input type="text" id="ikgmanifestnumber" value="<?php if(isset($_POST['from_schedule_list'])){ echo "bio-".date("YmdHis"); } else if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->ikg_manifest_route_number;}   ?>" name="ikgmanifestnumber"/></td>
            <td>Truck</td>
            <td><select name="vehicle" id="vehicle">
            <?php
                $compare= "";
                if(isset($_POST['from_routed_grease_list'])){ 
                    $compare = $grease_ikg->truck;
                    getVehiclesList($compare);
                }else {
                    getVehiclesList("");
                }
            ?>
            </select></td>
            <td>Time Start</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->time_start;  }  ?>" id="timestart" name="timestart" type="text"/></td>
            <td>Start Mileage</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->start_mileage;  }  ?>" id="start_mileage" name="start_mileage" type="text"/></td>
        </tr>
        
        <tr>
            <td>Scheduled Date</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->scheduled_date;  }  ?>" type="text" id="sched_route_start" name="sched_route_start"/></td>
             <td>License Plate</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->license_plate; }  ?>" type="text" name="lic_plate" id="lic_plate"/></td>
            
            <td>First Stop</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->first_stop;  }  ?>" id="firststop" name="firststop" type="text" /></td>
            <td>First Stop Mileage</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->first_stop_mileage; }  ?>" type="text" id="first_stop_mileage" name="first_stop_mileage"/></td>
        </tr>
        
        <tr>
            <td>Completion Date</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->completed_date;  }  ?>" id="completion" type="text"   readonly=""/></td>
            
            <td>IKG Decal</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->ikg_decal;  }  ?>" id="ikg_decal" name="ikg_decal" type="text"/></td>
            
            
            <td>Last Stop</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->last_stop; }  ?>" id="laststop" name="laststop" type="text"  /></td>
            <td>Last Stop Mileage</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->last_stop_mileage; }  ?>" id="last_stop_mileage" name="last_top_mileage"  type="text"/></td>
        </tr>
        
        <tr>
            <td>IKG Route Number</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->route_id; }  ?>" id="unique_route_no"  type="text" readonly="" /></td>
            <td>Trailer</td>
            <td>
            <select name="trailer" id="trailer">
            <option>Trailer</option>
            <?php
                $comp ="";
                if(isset($_POST['from_routed_grease_list'])){
                    $comp = $grease_ikg->trailer;
                     
                }
                $bvc = $db->query("SELECT trailers.truck_id,trailers.name FROM assets.trailers");
                if(count($bvc)>0){
                    foreach($bvc as $trailers){
                        echo "<option ";
                                if($trailers['truck_id'] == $comp){
                                    echo " selected ";
                                }
                        
                         echo " value='$trailers[truck_id]'>$trailers[name]</option>";
                    }
                }   
                
            ?>
            </select>            
            </td>
            <td>End Time</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->end_time;  }  ?>"  type="text" name="end_time" id="endtime"/></td>
             <td>End Mileage</td> 
             <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->end_mileage;  }  ?>" id="end_mileage" name="end_mileage" type="text"/></td>
        </tr>
        
        <tr>
            <td id="loc_label"><?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->labels[0]; } ?></td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->location; }  ?>"  id="location" name="location" type="text" readonly/></td>
            
            <td>Trailer LP</td>
            <td><input type="text"  value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->trailer_lp;  }  ?>"  id="trailer_lp" placeholder="Trailer License Plate"/></td>
            
          
            
         
            <td>Total Time Elapsed</td><td><input type="text" value="<?php 
                    $checkTime = strtotime($grease_ikg->end_time);
                $loginTime = strtotime($grease_ikg->time_start);
                $diff = $checkTime - $loginTime;
                echo abs(round($diff/3600,2))." Hours";
            ?>"/></td>
            <td>Total Mileage</td><td><input type="text" value="<?php 
                echo number_format($grease_ikg->end_mileage - $grease_ikg->start_mileage,2);
            ?>"/></td>
        </tr>
        
        
        <tr>
            <td id="inv_label"><?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->labels[1]; } ?></td><td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->inventory_code;  }  ?>" name="inventory_code" id="inventory_code" type="text" readonly /></td>
              <td>Trailer Decal</td>
            <td><input type="text" value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->trailer_decal;  }  ?>" id="trailer_decal"/></td>
            
            <td>Fuel</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->fuel; }  ?>" name="fuel" id="fuel" type="text"/></td>
            <td>Total Lbs /Mile</td>
            <td><input type="text" value="<?php echo $grease_ikg->lbs_per_mile; ?>"/></td>
        </tr>
        
        
        <tr>
            <td>Lot #</td>            
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){  echo $grease_ikg->lot_no;}  ?>" id="lot_no" name="lot_no" type="text"/></td>
             <td>Driver Complete</td><td><input type="text" value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->driver_complete_dated;  } ?>" readonly/></td>
           
            
            <td>Percent Solids %</td>
            <td><input type="text" name="percent_fluid" id="percent_fluid" value="<?php if(isset($_POST['from_routed_grease_list'])){ echo number_format( $grease_ikg->percent_fluid,2); }  ?>" placeholder="%"/></td>
            
            <td>Net Mileage</td>
            <td><?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->sum_net_route_mileage; }  ?></td>
        </tr>
        
        
        <tr>
            <td>Receiving Facility (required)</td><td>
            <?php if(isset($_POST['from_routed_grease_list'])){ 
                    $coxp = $grease_ikg->recieving_facility_no;  
                    getFacilityList("",$coxp);
                  } else {
                    getFacilityList("","");
                  }  
             ?></a></td>
         <td>Gross Weight</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->ikg_gross_weight; }  ?>" id="gross_weight" name="gross_weight" type="text"/></td>
        
        
        
        <td>Weight Ticket Number</td>
        <td><input type="text" id="wtn" name="wtn"  placeholder="Weight Ticket Number" value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->wtn; }  ?>" /></td>
        
        <td>Route Notes</td>
        <td rowspan="2" style="vertical-align: top;"><textarea id="route_notes" name="route_notes" placeholder="route notes" style="height: 100%;width:100%;"><?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->route_notes;  }  ?></textarea></td>
        </tr>
            
            <tr>
            <td>Facility Address</td>
            <td><input value="" name="fac_address" id="fac_address" type="text"/></td>
            <td>Tare Weight</td>
        <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->tare_weight;  }  ?>" type="text" name="tara_weight"  id="tara_weight"/></td>
        
        
        
            <td>Bol Number</td>
            <td><input type="text" id="bol" name="bol" placeholder="BOL number"   value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->bol; }  ?>"/></td>
        </tr>
      
        <tr>
            <td>Facility Rep</td>            
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo "$grease_ikg->facility_rep";  } ?>" id="fac_rep" name="fac_rep" type="text"/></td>
                <td>Net Weight</td>
            <td><input value="<?php if(isset($_POST['from_routed_grease_list'])){ echo $grease_ikg->net_weight;  }  ?>"  name="net_weight" id="net_weight" type="text" readonly="true"/></td>
            <script>
            $("input#gross_weight").blur(function(){                
                var i = $("input#gross_weight").val() - $("input#tara_weight").val();                
                $("input#net_weight").val(i);
            });
            
             $("input#tara_weight").blur(function(){
                var i = $("input#gross_weight").val() - $("input#tara_weight").val();
                $("input#net_weight").val(i);
            });
            </script>
            <td rowspan="2" colspan="4">
            <br /><br />
            Facility Rep ________________________________________________________________________<br /><br />
             Driver _____________________________________________________________________________
            
            </td>
        </tr>
        
        <tr>
            <td>Driver(required)</td><td>
          
                <?php
                    if( isset($_POST['from_routed_grease_list'] ) ){
                        if(strlen($grease_ikg->driver_no) >0){ 
                            $compare_driver = $grease_ikg->driver_no;
                        } else { 
                           
                            $compare_driver="";
                        }
                        getDrivers($grease_ikg->driver_no);
                    } 
                    else {
                        getDrivers("");
                    }
                ?>

            </td>
               <td>IKG Collected</td>
            <td><input  name="ikg_collected" value="Grease Trap" id="ikg_collected" readonly="" type="text"/></td>
        </tr>
        <tr>
            <td>IKG Transporter</td><td><input name="ikg_transporter" id="ikg_transporter" value="<?php 
                if(isset($_POST['from_routed_grease_list'])){
                    echo $grease_ikg->ikg_transporter;
                }else{
                    echo "";
                }
            
            ?>" readonly="" type="text"/></td>
        </tr>
        
        <tr>
            <td>Total Day(s) in Route</td><td id="dir">
            <?php
                if(isset($_POST['from_routed_grease_list'])  ){
                    $com =  $grease_ikg->number_days_route;
                    echo "<select id='what_day'>";
                        for($i=1;$i<6;$i++){
                            if($com == $i){
                                $p = " selected ";
                            }else {
                                $p = "";
                            }
                            echo "<option $p value='$i'>$i</option>";
                        }    
                    echo "</select>";
                } else {
                    echo "1";
                }
                
                
            ?>
          </td>
        </tr>
        
    </table>
    
    </div>
    <div id="spacebottom" style="height: 10px;background:white;width:100%;"></div>
    
    
    
    <div class="spacebottom" style="height: 10px;background:white;width:100%;"></div>
    
    <div id="data_display" style="width:100%;margin:auto;height:auto;min-height:400px;">
    <table style="width: 100%;padding:0px 0px 0px 0px;height:auto;table-layout-fixed;">
     <tr><td colspan="15" style="font-weight:bold;color:red;">*NOTE: YOU MUST CLICK UPDATE FOR CHANGES TO TAKE EFFECT.</td></tr>
    <tr>
    <td style="text-align:center;cursor:pointer;" id="test">Pickups:</td>
    
    <td style="text-align:center;" id="nop"><?php 
    if(isset($_POST['from_schedule_list'])){ 
        echo count($acnts);
    } else if(isset($_POST['from_routed_grease_list'])){
        echo count($grease_ikg->scheduled_routes);
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
    
    <table style="width:100%;margin:auto;table-layout:fixed;">
    <thead>
    <tr>
        <td>Total Pickup Hours</td>
        <td><?php echo $grease_ikg->total_pickup_hours; ?></td>
        <td>Net Route Mileage</td>
        <td><?php echo $grease_ikg->sum_net_route_mileage; ?></td>
        <td>Net Gallons</td>
        <td><?php echo $grease_ikg->net_gallons; ?></td>
        <td>Avg Gal/Stop</td>
        <td><?php echo $grease_ikg->avg_gal_per_stop ?></td>
        <td>Avg Mile/Stop</td>
        <td><?php echo $grease_ikg->avg_mile_per_stop ?></td>
        <td>Avg Min/Stop</td>
        <td><?php echo $grease_ikg->avg_min_per_stop; ?></td>
    </tr>
    </table>
    <table style="width:100%;margin:auto;table-layout:fixed;" id="sortable">
     <tr>
        <td style="width: 19px;">&nbsp;</td>
        <td style='padding: 0px 0px 0px 0px;width:23px;'>
            <div class='cell' style='border-top-left-radius:5px;border-left: 1px solid black;border-top:1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>
                #
            </div>
        </td>
        <td style='padding: 0px 0px 0px 0px;width:55px;'>
            <div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:55px;'>Status</div>
        </td>
        <td style='padding: 0px 0px 0px 0px;width:100px'>
            <div class='cell' style='border-top: 1px solid black;padding:0px 0px 0px 0px;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:100px;'>Scheduled</div>
        </td>
        <td style='padding: 0px 0px 0px 0px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>Name</div></td>
        <td style='padding: 0px 0px 0px 0px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>City</div></td>
        
        <td  style='padding: 0px 0px 0px 0px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>Address</div></td>
       
        <td  style='padding: 0px 0px 0px 0px;width:53px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:53px;'>Zip</div></td>
       <td  style='padding: 0px 0px 0px 0px;width:53px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:53px;'>C.Notes</div></td>
       
         <td  style='padding: 0px 0px 0px 0px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>Notes</div></td>
        <td  style='padding: 0px 0px 0px 0px;width:44px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:44px;'>Trap</div></td>
        
        <td  style='padding: 0px 0px 0px 0px;width:42px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>Jet</div></td>
       <td style='padding: 0px 0px 0px 0px;width:45px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:45px;'>Col.</div></td>
       <td style='padding: 0px 0px 0px 0px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>Ok To service</div></td>
       <td style='padding: 0px 0px 0px 0px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);'>Charge</div></td>
       <td style='padding: 0px 0px 0px 0px;width:76px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:76px;'>Net Miles</div></td>
       <td style='padding: 0px 0px 0px 0px;width:41px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);width:41px;'>Hours</div></td>
        <td  style='padding: 0px 0px 0px 0px;'><div class='cell' style='border-top: 1px solid black;background:  -moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);border-top-right-radius:5px;'>Zero Charge</div></td>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 1;  
    $total_caps = 0;
    $acnt_info = new Account();  
    if(isset($_POST['from_schedule_list'])){
        //var_dump($schnums);
        foreach($schnums as $list){
            $grease_info = new Grease_Stop($list);
            //var_dump($grease_info);
            //var_dump($grease_info);
         echo "<tr class='accnt_row' xlr='".$grease_info->account_number."' id='row$grease_info->grease_no' account='$grease_info->account_number' title='$grease_info->account_number' style='cursor:pointer;' >";
         echo "<td style='width:19px;'><img src='img/delete-icon.jpg' style='cursor:pointer;' class='deletesched' rel='$grease_info->grease_no' account='$grease_info->account_number'/>"; 
            if($acnt_info->singleField($grease_info->account_number,"locked") ==1){
                echo " <span style='font-weight:bold;color:red;'>(LOCKED)";
            }
         
         echo "</td>";
         echo "<td>".$count."</td>"; 
           
         echo "<td style='width:55px;'>";
            $first = ""; 
            $last = "";
            if($person->user_id == 149){
                
                $first = "<a href='manifest_change_ppg_payment.php?schedule=$grease_info->grease_no' rel='shadowbox;width=400px;height=130px;'>";
                $last = "</a>";
            }
         echo $first.$grease_info->route_status.$last."</td>"; 
         echo "<td>".$grease_info->service_date."</td>";
         echo "<td>".$grease_info->account_name."</td>"; 
         echo "<td>".$acnt_info->singleField($grease_info->account_number,"city")."</td>";
         echo "<td>".$acnt_info->singleField($grease_info->account_number,"address")."</td>";
         echo "<td  style='width:53px;'>".$acnt_info->singleField($grease_info->account_number,"zip")."</td>";
         
         echo "<td rel='".$acnt_info->singleField($grease_info->account_number,"credit_note")."'  class='cnote'>".$acnt_info->singleField($grease_info->account_number,"credit_note")."</td>";
         
         echo "<td class='notes' rel='".$acnt_info->singleField($grease_info->account_number,"notes")."'>".$acnt_info->singleField($grease_info->account_number,"notes")."</td>";
         echo "<td style='width:44px;'>$grease_info->volume</td>";
         echo "<td style='43px;'><input type='checkbox' "; 
                    if($grease_info->jetting ==1){
                        echo "checked='checked'";
                    }
         echo " rel='$grease_info->grease_no' class='jetbox' /></td>";
         
         echo "<td>&nbsp;</td>";
         echo "<td><img src='img/email.png' class='reminder' rel='$grease_info->grease_no' title='Invoice Reminder for Account' style='width:15px;height:15px;cursor:pointer;'/></td>";
          if($grease_info->custom_charge !=NULL){
                echo "$ ".number_format($grease_info->custom_charge,2);
          }else{
               echo "$ ".number_format($grease_info->charge,2);   
          }
         echo "<td style='width:76px;' >&nbsp;</td>";//net miles
         echo "<td  style='width:41px;'>&nbsp;</td>";//hours
         echo "<td><input ";  
                        if($grease_info->zero_charge_pickup == 1){
                            echo " checked ";
                        }
                    echo " type='checkbox'  rel='$grease_info->grease_no'/></td>";
         echo "</tr>";
         $count++;   
        }
    } else  if(isset($_POST['from_routed_grease_list'])){
        //var_dump($grease_ikg->scheduled_routes);
        $counter = 0;
        if( !empty($newlist_scheds) ){ //scheduled are reordered in accordance to accounts placed
            foreach( $newlist_scheds as $ekc  ){
                 $grease_info = new Grease_Stop($ekc);
                echo "<tr class='accnt_row' xlr='".$grease_info->account_number."' id='row$grease_info->grease_no' account='$grease_info->account_number' style='cursor:pointer;' >";
                echo "<td style='width:10%;'>"; 
                    if($grease_info->route_status !="completed"){
                        echo "<img src='img/delete-icon.jpg' style='cursor:pointer;' class='deletesched2' rel='$grease_info->grease_no'  account='$grease_info->account_number'/>";
                    }
                    
                    if($acnt_info->singleField($grease_info->account_number,"locked") ==1){
                        echo " <span style='font-weight:bold;color:red;'>(LOCKED)";
                    }
                    
                    
                echo"</td>";
                echo "<td>$count</td>";
                echo "<td>";
                    $first = ""; 
                    $last = "";
                    if($person->user_id == 149){
                        
                        $first = "<a href='manifest_change_ppg_payment.php?schedule=$grease_info->grease_no' rel='shadowbox;width=400px;height=130px;'>";
                        $last = "</a>";
                    }
                echo $first.$grease_info->route_status.$last."</td>"; 
                echo "<td style='width:55px;'>".$grease_info->service_date."</td>";
                echo "<td>".$grease_info->account_name."</td>";
                echo "<td>".$acnt_info->singleField($grease_info->account_number,"city")."</td>";
                echo "<td>".$acnt_info->singleField($grease_info->account_number,"address")."</td>";
                
                echo "<td  style='width:53px;'>".$acnt_info->singleField($grease_info->account_number,"zip")."</td>";
         
                echo "<td rel='".$acnt_info->singleField($grease_info->account_number,"credit_note")."'  class='cnote'>".$acnt_info->singleField($grease_info->account_number,"credit_note")."</td>"; 
                
                echo "<td class='notes' rel='".$acnt_info->singleField($grease_info->account_number,"notes")."'".$acnt_info->singleField($grease_info->account_number,"notes")."'>".$acnt_info->singleField($grease_info->account_number,"notes")."</td>";
                echo "<td>".$acnt_info->singleField($grease_info->account_number,"grease_volume")."</td>";
                echo "<td style='43px;'><input type='checkbox' "; 
                    if($grease_info->jetting ==1){
                        echo "checked='checked'";
                    }
                    if($grease_info->locked ==1){
                        echo " disabled ";
                    }
                    
                    echo " rel='$grease_info->grease_no' class='jetbox'/></td>";
                    echo "<td>"; 
                    if($grease_info->route_status == "enroute" || $grease_info->route_status =="completed"){
                    //echo "SELECT inches_to_gallons FROM freight_grease_data_table WHERE schedule_id=$grease_info->grease_no AND account_no = $grease_info->account_number AND route_id=$grease_info->grease_route_no<br/>";
                    $gh = $db->query("SELECT inches_to_gallons FROM freight_grease_data_table WHERE schedule_id=$grease_info->grease_no AND account_no = $grease_info->account_number AND route_id=$grease_info->grease_route_no");
                        if(count($gh)>0){
                            foreach($gh as $bv){
                                echo $bv['inches_to_gallons'];
                            }
                        }
                    }
                    echo "</td>";
                    echo "<td>";
                    if(strlen($grease_info->grease_route_no)>0 && $grease_info->grease_route_no != ' '){ 
                      $yg = $db->query("SELECT status FROM freight_pay_trace WHERE route_id=$grease_info->grease_route_no AND schedule_id = $grease_info->grease_no AND account_no = $grease_info->account_number");
                      if(count($yg)==0 && $grease_info->payment_method !="Cash On Delivery"){
                        echo "<img src='img/red_cancel.png'/>";
                      }else{
                         if( $grease_info->payment_method =="Cash On Delivery"){
                            echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;'/>";
                         } else if($yr[0]['status']==0){
                            echo "<img src='img/red_cancel.png'/>";
                         }
                      }
                    }else{
                        echo "<img src='img/red_cancel.png'/>";
                    }
                    echo"&nbsp;<img src='img/email.png' class='reminder' rel='$grease_info->grease_no' title='Invoice Reminder for Account' style='width:15px;height:15px;cursor:pointer;'/>";
                    echo"</td>";
                    echo "<td>"; 
                    if($grease_info->custom_charge !=NULL){
                          echo "$ ".number_format($grease_info->custom_charge,2);
                    }else{
                       echo "$ ".number_format($grease_info->charge,2);   
                    }
                    echo "</td>";
                    echo "<td>"; 
                    if(strlen($grease_info->grease_route_no)>0 && $grease_info->grease_route_no != ' '){ 
                      $mile = $db->query("SELECT net_mileage FROM freight_grease_data_table WHERE route_id = $grease_info->grease_route_no AND account_no = $grease_info->account_number AND schedule_id=$grease_info->grease_no");
                      if(count($mile)>0){
                        echo $mile[0]['net_mileage'];
                      } else {
                        echo "0";
                      }
                    }
                    echo "</td>";
                    echo "<td style='width:41px;'>";
                        if(strlen($grease_info->grease_route_no)>0 && $grease_info->grease_route_no != ' '){ 
                      $mile = $db->query("SELECT TIME(arrival) as arrival, TIME(departure) as departure FROM freight_grease_data_table WHERE route_id = $grease_info->grease_route_no AND account_no = $grease_info->account_number AND schedule_id=$grease_info->grease_no");
                      if(count($mile)>0){
                        echo $mile[0]['departure'] - $mile[0]['arrival'];
                      } else {
                        echo "0";
                      }
                    }
                    echo "</td>";
                    echo "<td><input ";  
                        if($grease_info->zero_charge_pickup == 1){
                            echo " checked ";
                        }
                    echo " type='checkbox' class='zcp' rel='$grease_info->grease_no'/></td>";
                    echo "</tr>";
                $count++;
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


</div>

<input type="hidden" name="scheduled_ids" value="<?php  
    if(isset($_POST['from_schedule_list'])){ 
            echo "$_POST[schecheduled_ids]"; 
    } else if(  isset($_POST['from_routed_grease_list'] )  )  {  
            foreach( $newlist_scheds as $scheds ){ 
                echo $scheds."|"; 
            }  
    } ?>" id="scheduled_ids" readonly=""/>
    
    
<input type="hidden" name="account_nos" value="<?php  if(isset($_POST['from_schedule_list'])){ echo "$_POST[accounts_checked]";}else if(  isset($_POST['from_routed_grease_list'] )  )  { foreach($newlist_accounts as $combined){ echo "$combined|";  } } ?>" id="account_nos"  readonly=""/>


<input type="hidden" value="<?php if(isset($_POST['add_to_route'])){ echo $_POST['schecheduled_ids']; } ?>" name="newly_added_stops" placeholder="Newly added stops"  readonly="" id="newly_added_stops" title="Newly added stops" readonly=""/>
 
 
 <input type="hidden"  value="<?php if(isset($_POST['add_to_route'])){ echo $_POST['accounts_checked']; } ?>"   name="newly_added_accounts" placeholder="Newly added accounts"  readonly="" id="newly_added_accounts" title="Newly added accounts" readonly=""/>
 

<input type="hidden" name="return_stops" placeholder="return stops" id="return_stops" readonly=""/>

<script>


$("#mult_day_route").change(function(){
    $("#dir").html( $(this).val() );
});

$('td.cnote').text(function(i, text) {
    var t = $.trim(text);
    if (t.length > 10) {
        return $.trim(t).substring(0, 10) + "...";
    }
    return t;
}).hover(function(){
     $(this).html("<span style='font-size:10px;'>"+$(this).attr('rel')+"</span>");
},function(){
     var t = $.trim($(this).attr('rel'));
    if (t.length > 10) {
        $(this).html ($.trim(t).substring(0, 10) + "...");
    }
    
});


 $('td.notes').text(function(i, text) {
    var t = $.trim(text);
    if (t.length > 10) {
        return $.trim(t).substring(0, 10) + "...";
    }
    return t;
}).hover(function(){
     $(this).html("<span style='font-size:10px;'>"+$(this).attr('rel')+"</span>");
},function(){
     var t = $.trim($(this).attr('rel'));
    if (t.length > 10) {
        $(this).html ($.trim(t).substring(0, 10) + "...");
    }
    
});

$(".zcp").click(function(){
    if($(this).is(":checked")){
        $.post("zero_charge.php",{grease_no:$(this).attr('rel'),value:1},function(data){
            alert("Set to Zero Charge Pickup");
            $("#resubmit_this").submit();
        });
    }else{
        $.post("zero_charge.php",{grease_no:$(this).attr('rel'),value:0},function(data){
            alert("Unset Zero Charge Pickup");
            $("#resubmit_this").submit();
        });
    }
});


if(  $("input#trailer_decal").val().length === 0 ){
    $.get("getTrailerDecal.php",{trailer:$("#trailer").val()},function(data){
        $("input#trailer_decal").val(data);
    });
}

if(  $("input#trailer_lp").val().length === 0 ){
    $.get("getTrailerDecal.php",{license:$("#trailer").val()},function(data){
        $("input#trailer_lp").val(data);
    });
}

$("#trailer").change(function(){
    $.get("getTrailerDecal.php",{trailer:$(this).val()},function(data){
        $("input#trailer_decal").val(data);
    });
    $.get("getTrailerDecal.php",{license:$(this).val()},function(data){
        $("input#trailer_lp").val(data);
    });
})


function close_window() {
  if (confirm("Route Created Successfully Please Navigate to routed Grease Routes page")) {
    close();
  }
}

history.pushState({ page: 1 }, "title 1", "#nbb");
window.onhashchange = function (event) {
    window.location.hash = "nbb";
};

window.onload = function(){
    $("#loading").fadeOut("fast");
}
var remove_incompletes = 0;
var remove_stops ="";

function reOrder(){

    $("#sortable tbody tr").find("td:nth-child(2)").each(function (i) {
         $(this).html(i+1);
    });
    
    var rowCount = $('#sortable tbody tr').length;
    $("#nop").html(rowCount);
     $("#loading").fadeOut("fast");
}
       
    $(".deletesched2").click(function(){        
         $("#row"+$(this).attr('rel') ).remove();
         var account   = $("input#account_nos").val();
         var schedules = $("input#scheduled_ids").val();
         var replace_scheds = $(this).attr('rel')+"|";
         var replace_accounts = $(this).attr('account')+"|";
         $("input#account_nos").val( account.replace(replace_accounts,"")  );
         $("input#scheduled_ids").val( schedules.replace(replace_scheds,"") );
         $("input#return_stops").val( $("input#return_stops").val()+replace_scheds  );
         $("#nop").html( $("#nop").html() - 1);
          setTimeout('reOrder()','2000');
          remove_incompletes++;   
    });

    $(".deletesched").click(function(){
        $("#row"+$(this).attr('rel') ).remove();
         var account   = $("input#account_nos").val();
         var schedules = $("input#scheduled_ids").val();
         var replace_scheds = $(this).attr('rel')+"|";
         var replace_accounts = $(this).attr('account')+"|";
         $("input#account_nos").val( account.replace(replace_accounts,"")  );
         $("input#scheduled_ids").val( schedules.replace(replace_scheds,"") );
         $("#nop").html( $("#nop").html() - 1);
         setTimeout('reOrder()','2000');
    });

    <?php
    if(isset($_POST['from_routed_grease_list'])){        
    ?>
    $("#what_day").change(function(){        
        $(".enterDataLink").attr('href','enterGreaseData.php?route_id=<?php echo $grease_ikg->route_id; ?>&day='+$("#what_day").val());
        
        $.post("day_info_grease.php",{route_id:<?php echo $_POST['util_routes'] ?>,what_day:$(this).val(),mode:1},function(data){
            alert(data);
            $("input#timestart").val(data);
        });
        $.post("day_info_grease.php",{route_id:<?php echo $_POST['util_routes'] ?>,what_day:$(this).val(),mode:2},function(data){
            $("input#start_mileage").val(data);
        });
        
        $.post("day_info_grease.php",{route_id:<?php echo $_POST['util_routes'] ?>,what_day:$(this).val(),mode:3},function(data){
            $("input#firststop").val(data);
        });
        
        $.post("day_info_grease.php",{route_id:<?php echo $_POST['util_routes'] ?>,what_day:$(this).val(),mode:4},function(data){
            $("input#first_stop_mileage").val(data);
        });    
        $.post("day_info_grease.php",{route_id:<?php echo $_POST['util_routes'] ?>,what_day:$(this).val(),mode:5},function(data){
            $("input#laststop").val(data);
        });    
        $.post("day_info_grease.php",{route_id:<?php echo $_POST['util_routes'] ?>,what_day:$(this).val(),mode:6},function(data){
           $("input#last_stop_mileage").val(data);
        });    
        $.post("day_info_grease.php",{route_id:<?php echo $_POST['util_routes'] ?>,what_day:$(this).val(),mode:7},function(data){
            $("input#endtime").val(data);
        });
        $.post("day_info_grease.php",{route_id:<?php echo $_POST['util_routes'] ?>,what_day:$(this).val(),mode:8},function(data){
           $("input#end_mileage").val(data);
        }); 
     });
 
    <?php
    }
    ?>
    $.post("getFacilAddress.php",{facil:$("#facility").val()},function(data){
        $("input#fac_address").val(data);
    });
   

   
   $("#facility").change(function(){
        $.post("getFacilAddress.php",{facil: $(this).val() },function(data){
            $("input#fac_address").val(data);
        });
        
        switch($(this).val()){
            case "15":
                $("input#ikg_transporter").val("Co West Commodities");
                $("input#inventory_code").val("W3940");
                $("input#location").val("W1210"); 
                $("#loc_label").html('Location');
                $("#inv_label").html('INVENTORY CODE');             
            break;
            case "16":
                $("input#ikg_transporter").val("Co West Commodities");               
                $("input#inventory_code").val("WW905");
                $("input#location").val("WT905");
                 $("#loc_label").html('Credit');
                $("#inv_label").html('Debit');
            break;
            case "24": case "25":case "31":case "32": case "33":
                $("input#ikg_transporter").val("Biotane Pumping");
                $("input#inventory_code").val("U3930");
                $("input#location").val("U1210");  
                $("#loc_label").html('Location');
                $("#inv_label").html('INVENTORY CODE');  
                      
            break;
            case "22":
                $("input#ikg_transporter").val("Biotane Pumping");
                $("input#inventory_code").val("U3940");
                $("input#location").val("U1210");
                $("#loc_label").html('Location');
                $("#inv_label").html('INVENTORY CODE');  
            break;
            case "23": case "14":
                $("input#ikg_transporter").val("Biotane Pumping");
                $("input#inventory_code").val("U3950");
                $("input#location").val("U1210");
                $("#loc_label").html('Location');
                $("#inv_label").html('INVENTORY CODE');  
            break;
            case "5":case "11":case "12": case "13":
                $("input#ikg_transporter").val("Biotane Pumping");
                $("input#inventory_code").val("V3910");
                $("input#location").text("V1210");
                $("#loc_label").html('Location');
                $("#inv_label").html('INVENTORY CODE');  
            break;
            default:
                $("#loc_label").html('Location');
                $("#inv_label").html('INVENTORY CODE');
            
        }
        
      
        $("#facholder").html( $(this).find("option:selected").text()  );
        
        
   });
   
    /*
    $.post("getTruckInfo.php",{choose:1,id: $("#vehicle").val() },function(data){
        //alert(data);
        $("input#lic_plate").val(data);  
    });
    
    $.post("getTruckInfo.php",{choose:2,id:$("#vehicle").val() },function(data){
        $("input#ikg_decal").val(data);
    });*/

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
            reordered+= $(this).attr('account')+"|";
        });        
        $("input#account_nos").val(reordered);    
    
        $.post('ikg_update_grease.php',{
            rid:$("#rid").html(),
            ikg_manifest_route_number: $("input#ikgmanifestnumber").val(),
            trailer_lp: $("input#trailer_lp").val(),
            trailer_decal: $("input#trailer_decal").val(),
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
            total_estimate:$("#estimated").html(),
            decrement:remove_incompletes,
            remove_stops:$("input#return_stops").val(),
            new_stops: $("input#newly_added_stops").val(),
            new_accounts:$("input#newly_added_accounts").val(),
            wtn:$("input#wtn").val(),
            bol:$("input#bol").val(),
            route_notes:$("#route_notes").val(),
            percent_fluid:$("input#percent_fluid").val(),
            trailer:$("#trailer").val()
        },function(data){ 

            if( parent.window.opener.location.href == "https://inet.iwpusa.com/scheduling.php?task=sgt" ){//did you come from scheduled grease pickups?
                    parent.window.opener.document.location.reload(true);
             }
            
            alert('Route Updated!');
            //$("#debug").html(data);
            $("#loading").fadeOut("fast",function(){
                //$("#resubmit_this").submit();
            });
        });
    });
    
    $("#ikg_create").click(function(){
        $("#loading").show();
        var reordered ="";
        $(".accnt_row").each(function(){
            reordered =  reordered+$(this).attr('account')+"|";            
        });             
        $("input#account_nos").val(reordered);   
        
        if( $("#rid").text().trim() == "" ){
            $.post('ikg_insert_grease.php',{           
                ikg_manifest_route_number: $("input#ikgmanifestnumber").val(),
                trailer_lp: $("input#trailer_lp").val(),
                trailer_decal: $("input#trailer_decal").val(),
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
                mode:$("input#mode").val(),
                total_estimate:$("#estimated").html(),
                wtn:$("input#wtn").val(),
                bol:$("input#bol").val(),
                route_notes:$("#route_notes").val(),
                percent_fluid:$("input#percent_fluid").val(),
                trailer:$("#trailer").val()
            },function(data){
                //$("#debug").html(data);
                $("#rid").html(data);                 
                $("input#unique_route_no").val(data);
                $("#edata").html('<a href="enterGreaseData.php?route_id='+data+'"><img src="img/enterdata.jpg"/></a>');
                $("#pdfexp").html('<a href="grease_recipet.php?route_no='+data+'" target="_blank"><img src="img/pdf.jpg"/></a> ');
                $("#print").html('<a href="print_grease.php?ikg='+data+'" target="_blank"><img src="img/print.jpg"/></a>');
                $("#exp").html('<a href="grease_to_excel.php?ikg='+data+'" target="_blank"><img src="img/xls.jpg"/></a>');
                    
                 $("#loading").fadeOut("fast",function(){
                    close_window();        
                 });
            });  
        } else {
             
            alert($("#rid").html()+" updating");
           $.post('ikg_update_grease.php',{
                rid:$("#rid").html(),
                ikg_manifest_route_number: $("input#ikgmanifestnumber").val(),
                trailer_lp: $("input#trailer_lp").val(),
                trailer_decal: $("input#trailer_decal").val(),
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
                total_estimate:$("#estimated").html(),
                decrement:remove_incompletes,
                remove_stops:$("input#return_stops").val(),
                new_stops: $("input#newly_added_stops").val(),
                new_accounts:$("input#newly_added_accounts").val(),
                wtn:$("input#wtn").val(),
                bol:$("input#bol").val(),
                route_notes:$("#route_notes").val(),
                percent_fluid:$("input#percent_fluid").val(),
                trailer:$("#trailer").val()
                
            },function(data){
                if( parent.window.opener.location.href == "https://inet.iwpusa.com/scheduling.php?task=sgt" ){//did you come from scheduled grease pickups?
                        parent.window.opener.document.location.reload(true);
                 }
                // $("#debug").html(data);
                 $("#loading").fadeOut("fast",function(){
                    $("#resubmit_this").submit();
                 });
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

$(".jetbox").click(function(){
    if($(this).is(':checked')){
        $.post('update_jet_stop.php',{grease_no:$(this).attr('rel'),val:1},function(data){
             alert('Line Jetting option set!');
        });
    }else{
        $.post('update_jet_stop.php',{grease_no:$(this).attr('rel'),val:0},function(data){
             alert('Line Jetting option set!');
        });
    }
});


$(".reminder").click(function(){
   Shadowbox.open({
        content:"invoice_reminder.php?sched="+$(this).attr('rel')+"",
        player:"iframe",
        width:"300px",
        height:"80px",
        title:"Send Invoice Reminder"
   });
});

<?php if(isset($_POST['from_routed_grease_list'])){ ?>
$("#recover").click(function(){
    if(confirm("Warning! this will undo optimization")){
        Shadowbox.open({
           content:"<?php  echo 'x.php?route='.$_POST['manifest'];   ?>",
           player:"iframe",
           width:"100px",
           height:"100px" 
        });
    }
});
<?php } ?>

</script>
<form id="resubmit_this" action="grease_ikg.php" method="post">
<input type="hidden" value="1"  readonly="" name="from_routed_grease_list"/>
<input type="hidden" value="<?php echo $_POST['util_routes']; ?>"  readonly="" name="util_routes"/>
</form>
<?php
}else {
    echo "Session has expired, please click here to re-login <a href='index.php'>re-login</a>";
}
?>
