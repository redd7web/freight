<?php
include "protected/global.php";
include "source/scripts.php";
include "source/css.php";

$account = new Account($_GET['account_no']);

$check_grease_ = $db->query("SELECT * FROM freight_grease_traps WHERE account_no = $account->acount_id AND route_status !='completed' ORDER BY date DESC");
?>

<div id="debug"></div>

<div id="times" style="width: 100%;height:100%;position:absolute;background:rgba(210,210,210,.9);z-index:9999;cursor:pointer;font-family:arial;">
                    <input type="hidden" value="<?php echo $account->grease_volume; ?>" id="tr_volume_update" name="tr_volume_update"/>
                     <table style="width: 100%;border:1px solid #bbb;">
                        
                        <tr class="table_row">
                        <td class="table_label" id="line_type_id_label" style="font-size: 13px;border:1px solid red;text-align:center;color:black;" colspan="2">Preferred <img src="img/clock-12.png" align="bottom" width="12"><br>Time of Service<br><span class="mini">(M,W,Th - Before 7AM, etc)</span></td>
                        </tr>
                        <tr>
                        <td class="table_data" colspan="2">
                            <textarea name="time_of_service_note" id="tr_time_of_service_note_update" style="width: 90%;border:0px solid #bbb;" ><?php if(count($check_grease_)>0){ echo $check_grease_[0]['addt_info']; }  ?>
                            </textarea>
                        </td>
                        </tr>
                        
                        <tr class="table_row">
                        <td>Trap Name</td>
                        <td><input type="text" id="g_label" name="g_label" value="<?php echo $account->grease_label; ?>"/></td>
                        </tr>
                       <tr class="table_row">
                        <td class="table_label" id="line_type_id_label" align="right" nowrap="" valign="top" style="font-size: 13px;color:black;">Description</td>
                        <td class="table_data" ><textarea style="border:0px solid #bbb;" name="trap_description" id="tr_description_update" rows="4" cols="16"><?php if(count($check_grease_)>0){ echo $check_grease_[0]['notes']; } ?></textarea></td>
                        </tr>
                        <tr class="table_row">
                        <td class="table_label" id="line_type_id_label" align="right" nowrap="" valign="top" style="font-size: 13px;color:black;">Add'l Price</td>
                        <td class="table_data" ><input name="price_additional" value="<?php echo $account->grease_ppg;?>" id="tr_price_additional_update" size="5"></td>
                        </tr>
                        <tr class="table_row">
                        <td class="table_label" id="line_type_id_label" align="right" nowrap="" valign="top" style="font-size: 13px;color:black;">Add'l Price<br> Details</td>
                        <td class="table_data" ><textarea style="border:0px solid #bbb;" name="price_additional_info" id="tr_price_additional_info_update" rows="3" cols="16"><?php echo $account->grease_rate;?></textarea></td>
                        </tr>
                        
                        <tr><td>Date of service</td><td>
                        <input style="border:0px solid #bbb;" type="text" placeholder="Date of service" id="dost" value="<?php if(count($check_grease_)>0){echo $check_grease_[0]['service_date'];}?>" name="dost"/> <span style="font-size: 10px;"><?php 
                        if(count($check_grease_)>0){
                                if( $check_grease_[0]['route_status'] == "enroute" ){
                                    echo "&nbsp;(stop is enroute)";
                                } else {
                                     echo "&nbsp;(stop is scheduled)";
                                }
                             }
                        ?></span>
                        </td></tr>
                        
                        <tr>
                            <td>
                                Route Later
                            </td>
                            
                            <td  style="text-align: left;"><input checked="" type="radio" name="sched_type" value="1" class="sched_type"/></td>
                        </tr>
                        
                        <tr><td>Create New</td><td  style="text-align: left;"><input type="radio" value="2" name="sched_type" class="sched_type"/></td></tr>
                        <tr><td>Add to Existing</td><td style="text-align: left;"><input type="radio" value="3" name="sched_type" class="sched_type"/>
                            <?php 
                            $ko = $db->query("SELECT ikg_manifest_route_number,route_id,driver FROM freight_list_of_grease WHERE status='enroute' ORDER BY created_date DESC");
                            echo "<select id='grease_routes' name='grease_routes'><option>--</option>";
                            if(count($ko)>0){
                                foreach($ko as $ok){
                                    echo "<option value='$ok[route_id]'>$ok[ikg_manifest_route_number] (".uNumToName($ok['driver']).")</option>";
                                }
                            }
                            echo "</select>";
                            ?>
                            </td></tr>
                        
                        <tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="submit" class="sched_trap" style="background:url(img/save.png) no-repeat center center;width:20px;height:20px;background-size:contain;border:0px solid #bbb;" title="<?php if(count($grease_info)>0){ echo "Update Grease Trap info"; } else { echo "Save Grease Trap Info";} ?>" value=""/>
                            <br />
                            <input type="hidden" id="account_for_sched" name="account_for_sched" value="<?php echo $_GET['account_no']; ?>"/>
                        </td></tr>
                        
                    </table>
                    
<form id="add_to" method="post" target="_blank" action="grease_ikg.php">
    <input type="hidden" name="util_routes" id="util_routes" value=""  readonly=""/>
    <input type="hidden" name="from_routed_grease_list" id="from_routed_grease_list" value="1" readonly=""/>
    <input type="hidden" name="add_to_route" id="add_to_route" value="1" readonly=""/>
    <input type="hidden" name="accounts_checked" value="<?php echo $_GET['account_no']."|"; ?>" readonly=""/>
    <input type="hidden" name="schecheduled_ids" class="sched_to_route" value="" readonly=""/>
</form> 
<br />


<form id="new_route" method="post" target="_blank" action="grease_ikg.php">
    <input type="hidden" name="from_schedule_list" id="from_schedule_list" value="1" readonly=""/>
    <input type="hidden" name="accounts_checked" value="<?php echo $_GET['account_no']."|"; ?>" readonly="" title="accounts checkd"/>
    <input type="hidden" name="schecheduled_ids" class="sched_to_route" value="" readonly="" title="schedules"/>
</form>
                </div>

               
<script>
    $("#grease_routes").change(function(){
        $("#util_routes").val($(this).val()); 
    });
    $("input#dost").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
    
    $(".sched_trap").click(function(){
        var type =$(".sched_type:checked").val() ;    
        $.post("schedgrease.php",{
            "account":$("input#account_for_sched").val(),
            "trap_description": $("#tr_description_update").val(),
            "price_per_gallon": $("input#tr_price_per_gallon_update").val(),
            "frequency":$("input#tr_frequency_update").val(),
            "volume":$("input#tr_volume_update").val(),
            "service":$("#service_type").val(),           
            "time_of_service_note":"",
            "price_additional":$("input#tr_price_additional_update").val(),
            "price_additional_info":$("#tr_time_of_service_note_update").val(),    
            "dos":$("input#dost").val(),               
            "rate":$("input#tr_price_flat_update").val(),            
            "g_label":$("input#g_label").val()
        },function(data){
            //$("#debug").html(data);
            alert("Grease Trap Crated/Updated!"); 
            $(".sched_to_route").val(data+"|");
                  if($(".sched_type:checked").val() == 2){
                $("#new_route").submit();
            } else if ( $(".sched_type:checked").val() == 3 ){
                $("#add_to").submit();
            }
        });
        
       
        
       
        
    });
</script>