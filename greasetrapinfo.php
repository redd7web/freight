<?php
include "protected/global.php";

$account = new Account($_GET['account_no']);
$num = str_replace("p","",$_GET['trap_no']);
$grease_stop = new Grease_Stop($num);


?>


<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/auto.css"/>
<style type="text/css">
body{
    padding:10px 10px 10px 10px;
}

div.ui-datepicker{
 font-size:11px;
 margin-top:30px;
}

</style>
<div id="debug"></div>

<table id="acnt_tab" style="width: 100%;margin-top:20px;">
        <tr class="table_row">  
            <td style="width: 350px;" >Trap Active&nbsp;:</td>
            <td style="width: 300px;" >
                No&nbsp;<input value="0" checked="" type="radio" name="radios" class="acti" value="<?php if($account->trap_active == 0 ){
                    echo "checked";
                    
                } ?>" />
            &nbsp;&nbsp;Yes<input value="1" type="radio" name="radios" class="acti" <?php if($account->trap_active == 1 ){
                    echo "checked";
                    
                } ?>/></td>
           
            
            <td colspan="2">Label</td>
            <td class="table_data" style="font-size: 13px;" colspan="6">
                <input style="width: 100%" name="trap_label" value="<?php
                         echo $account->grease_label;
                         ?>" id="tr_trap_label_update" size="20" placeholder="Name this" />
            </td>
           
            
        </tr>
        <tr>
             <td style="">Service Type  </td>
            <td><select name="service_type" id="service_type"><option <?php 
             if( strtolower($account->service_type) == "trap"){
                echo "selected";
             }
            
            ?> value="Trap">Trap</option><option <?php 
             if( strtolower($account->service_type) == "jet"){
                echo "selected";
             }
            
            ?> value="Jet">Line Jetter</option></select></td>
         <td class="table_label" align="right" nowrap="" valign="top">Freq.</td>
        <td class="table_data" ><input name="frequency" value="<?php
                         echo $account->grease_freq;
                         ?>" id="tr_frequency_update" size="4" /></td>
        <td>Volume</td>    
        <td class="table_data" ><input placeholder="Volume" name="volume" value="<?php
                        echo $account->grease_volume;
                         ?>"  id="tr_volume_update" size="5"/></td>
        <td>Rate</td>
        <td class="table_data" ><input placeholder="Rate" value="<?php
                          
                        echo $account->grease_rate;
                         ?>" name="price_flat"  id="tr_price_flat_update" size="5"></td>
        <td>PPG</td>    
        <td class="table_data" ><input value="<?php
                         echo $account->grease_ppg;
                         ?>" placeholder="PPG" name="price_per_gallon"  id="tr_price_per_gallon_update" size="5" />
       
         <input type="hidden" id="account_for_sched" name="account_for_sched" value="<?php echo $account->acount_id; ?>"/>
        </td>
        
        </tr>
        

</table>

<table style="width: 550px;margin:auto;">
<tr><td colspan="6" style="text-align: center;vertical-align:center;">Update Trap Service</td></tr>

<tr><td class="table_label" id="line_type_id_label" style="font-size: 13px;border:1px solid red;text-align:center;color:black;" colspan="6">Preferred <img src="img/clock-12.png" align="bottom" width="12"><br>Time of Service<br><span class="mini">(M,W,Th - Before 7AM, etc)</span></td>


</tr>
<tr>
                        <td class="table_data" colspan="6">
                            <textarea name="time_of_service_note" id="tr_time_of_service_note_update" style="width: 95%;border:0px solid #bbb;text-align:center;" ><?php echo $grease_stop->addt_info;  ?></textarea>
                        </td>
                        </tr>


<tr><td>Scheduled Date:&nbsp;</td><td colspan="2"><input name="date" type="text" id="trapschedule" value="<?php echo $grease_stop->service_date; ?>" style="width: 100%;"/></td></tr>
<tr>
<td style="vertical-align: top; text-align:right;" colspan="5">Dispatcher Notes</td>
<td colspan="4">
<textarea style="width: 100%;" id="notes" name="notes">
<?php echo $grease_stop->notes; ?>
</textarea>
<input type="hidden" value="<?php echo $grease_stop->grease_no; ?>" id="g_no" name="g_no"/>
</td></tr>
<tr>
<td colspan="4" style="text-align: right;"> <input type="submit" value=""  class="static_grease" style="background:url(img/save.png) no-repeat center center;width:20px;height:20px;background-size:contain;border:0px solid #bbb;cursor: pointer;" title="Update Grease Stop"/></td>
</tr>
</table>

<script>
$("input#trapschedule").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
$(".static_grease").click(function(){

    $.post("insert_static_grease.php",{
            active:$(".acti:checked").val(),
            service_type:$("#service_type").val(),
            label:$("input#tr_trap_label_update").val(),
            freq:$("input#tr_frequency_update").val(),
            volume:$("input#tr_volume_update").val(),
            rate:$("input#tr_price_flat_update").val(),
            ppg:$("input#tr_price_per_gallon_update").val(),
            account_no:$("input#account_for_sched").val()
        },function(data){
            $.post("update_grease_stop.php",{
                    grease_no:$("input#g_no").val(),
                    dos:$("input#trapschedule").val(),
                    note:$("#notes").val(),                   
                    active:$(".acti:checked").val(),
                    service_type:$("#service_type").val(),
                    label:$("input#tr_trap_label_update").val(),
                    freq:$("input#tr_frequency_update").val(),
                    volume:$("input#tr_volume_update").val(),
                    rate:$("input#tr_price_flat_update").val(),
                    ppg:$("input#tr_price_per_gallon_update").val(),
                    addt:$("#tr_time_of_service_note_update").val()
            },function(data){
                $("#debug").html(data);
                alert("Grease Stop updated!");
                location.reload();
            });
            
            
    });
    
    
    
});

</script>