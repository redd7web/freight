<?php
include "protected/global.php";
$date = date("Y-m-d");
$person = new Person();



?>
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/auto.css"/>
<style>

input[type=submit]{
    cursor:pointer;
}
.ui-tabs .ui-tabs-nav li {
    border-bottom-width: 0;
    float: left;
    list-style: none outside none;
    margin: 1px 0.2em 0 0;
    padding: 0;
    position: relative;
    top: 0;
    white-space: nowrap;
    background:white;
}
</style>
<div id="sgtholder" style="width: 610px;height:auto;margin:auto;overflow-x:hidden;">
    <div id="tabs" style="width:600px;margin:auto;">
   
    <ul>
    <li><a href="#fragment-1">Trap</a></li>
    <li><a href="#fragment-3">Line Jetting</a></li>
    </ul>
    
    
     <?php
    if(isset($_POST['trap'])){
        
        $acc = new Account($_GET['account']);
        if(isset($_POST['tr_stairs_update'])){
            $stairs = 1;
        }else {
            $stairs = 0;
        }
        
        if(isset($_POST['fire'])){
            $fire = 1;
        } else {
            $fire =0;
        }
        
        $jet_data = array(
           "account_no"=>$_GET['account']
           ,"date"=>$date
           ,"notes"=>$_POST['trap_description']            
           ,"price_per_gallon"=>$_POST['price_per_gallon']
           ,"created_by"=>$person->user_id
           ,"frequency"=>$_POST['frequency']
           ,"volume"=>$_POST['volume']
           ,"service"=>$_POST['service']
           ,"hoseline_length"=>$_POST['hose_length']   
           ,"stairs"=>$stairs        
           ,"grease_name"=>$_POST['trap_label']
           ,"time_of_service"=>$_POST['time_of_service_note']
           ,"addt_price"=>$_POST['price_additional']
           ,"addt_info"=>trim($_POST['price_additional_info'])
           ,"fire"=>$fire
           ,"service_date"=>$_POST['dos']
           ,"route_status"=>"scheduled"
           ,"credit_notes"=>$acc->credit_notes
           ,"credits"=>$acc->credits
           ,"facility_origin"=>$acc->division
           
        );
        var_dump($jet_data);
        
        if( $db->insert($dbprefix."_grease_traps",$jet_data)){
            echo "<span style='color:green;font-weight:bold;'>Grease Trap scheduled</span>";
        }
    }    
    
    ?>
    
    <div id="fragment-1">
        <form action="scheduleGrease.php?account=<?php echo $_GET['account']; ?>" method="post">
        <table style="width: 600px;">
        <tr><td colspan="2" style="text-align:center;"><input placeholder="Date of Service" type="text" name="dos" class="dos" style="width: 90%;"/></td></tr>
        <tr><td  style="vertical-align: top;">
           
</td><td  style="vertical-align: top;"><table style="width: 200px;margin:auto;">
        <tbody><tr class="table_row">
      
        </tr>
        <tr class="table_row">
        <td class="table_label" id="line_type_id_label" align="right" nowrap="" valign="top" style="font-size: 13px;">Description</td>
        <td class="table_data" align="left" valign="top"><textarea name="trap_description" id="tr_description_update" rows="4" cols="16"></textarea></td>
        </tr>
        <tr class="table_row">
        <td class="table_label" id="line_type_id_label" align="right" nowrap="" valign="top" style="font-size: 13px;">Add'l Price</td>
        <td class="table_data" align="left" valign="top"><input name="price_additional" value="" id="tr_price_additional_update" size="5"></td>
        </tr>
        <tr class="table_row">
        <td class="table_label" id="line_type_id_label" align="right" nowrap="" valign="top" style="font-size: 13px;">Add'l Price<br> Details</td>
        <td class="table_data" align="left" valign="top"><textarea name="price_additional_info" id="tr_price_additional_info_update" rows="3" cols="16"></textarea>
        <input type="text" value="<?php echo $account->acount_id; ?>" id="account_id" />
        </td>
        </tr>
        </tbody>
        </table>
</td></tr>
        <tr><td colspan="2">
        
        </td></tr>
        </table>
        <div style="clear: both;"></div>
        </form>
    </div>
    
    
     <?php
    if(isset($_POST['intercept'])){
        if(isset($_POST['tr_stairs_update'])){
            $stairs = 1;
        }else {
            $stairs = 0;
        }
        
        $jet_data = array(
           "account_no"=>$_GET['account']
           ,"date"=>$date
           ,"notes"=>$_POST['price_additional_info']            
           ,"price_per_gallon"=>$_POST['price_per_gallon']
           ,"created_by"=>$person->user_id
           ,"frequency"=>$_POST['frequency']
           ,"volume"=>$_POST['volume']
           ,"service"=>$_POST['service']
           ,"hoseline_length"=>$_POST['hose_length']   
           ,"stairs"=>$stairs        
           ,"addt_price"=>$_POST['price_additional']
           ,"addt_info"=>$_POST['price_additional_info']
           ,"fire"=>$_POST['fire']
           ,"service_date"=>$_POST['dos']
           ,"route_status"=>"scheduled"
        );
        
        var_dump($jet_data);
        
        if( $db->insert($dbprefix."_grease_traps",$jet_data)){
            echo "<span style='color:green;font-weight:bold;'>Grease Trap scheduled</span>";
        }
    }    
    ?>
    
    
    
    
    
    
    
   
    
    
    <div id="fragment-3" style="width: 600px;">
        <form action="scheduleGrease.php?account=<?php echo $_GET['account']; ?>" method="post">
        <table style="width: 600px;">
        <tr><td style="vertical-align: top;"><table style="width:50%;float:left;">
            <tbody>
            
            <tr class="table_row">
            <td class="table_label" align="right" nowrap="" valign="top">Frequency</td>
            <td class="table_data" align="left" valign="top"><input name="frequency" value="90" id="tr_frequency_update" size="4"></td>
            </tr>
          
            <tr class="table_row">
            <td class="table_label" id="" align="right" nowrap="" valign="top">Base Rate</td>
            <td class="table_data" align="left" valign="top"><input name="price_flat" value="" id="tr_price_flat_update" size="5"></td>
            </tr>
            <tr><td>Fire</td><td><input type="checkbox" value="1" name="fire" id="fire"/></td></tr>
            
            <tr><td class="table_label" align="right" nowrap="" valign="top">&nbsp;</td><td>&nbsp;</td></tr>
            </tbody>
        </table></td><td  style="vertical-align: top;"> <table  style="width:50%;float:left;">
        <tbody><tr class="table_row">
        <td class="table_label" id="stairs_to_trap_label" align="right" nowrap="" valign="top">Stairs</td>
        <td class="table_data" align="left" valign="top"><input id="tr_stairs_update" name="tr_stairs_update" class="display_field" type="checkbox">
        </td>
        </tr>
     
        <tr class="table_row">
        <td  class="table_label" id="hose_length_label" align="right" nowrap="" valign="top">Line Length</td>
        <td class="table_data" align="left" valign="top"><input name="hose_length" value="" id="tr_hose_length_update" size="5"></td>
        </tr>
        <tr class="table_row">
        <td class="table_label" id="line_type_id_label" align="right" nowrap="" valign="top">Line Type</td>
        <td class="table_data" align="left" valign="top"><select name="tr_line_type_id_update" id="tr_line_type_id_update"><option value="">--</option></select></td>
        </tr>
         <tr><td class="table_label" align="right" nowrap="" valign="top">&nbsp;</td><td>&nbsp;</td></tr>
        </tbody></table></td></tr>
         <tr><td colspan="2" style="text-align:center;"><input placeholder="Date of Service" type="text" name="dos" class="dos" style="width: 90%;"/></td></tr>
        <tr><td  style="vertical-align: top;">
            <table style="width: 100%;border:1px solid #bbb;">
                <tr class="table_row">
                <td class="table_label" id="line_type_id_label" style="font-size: 13px;border:1px solid red;text-align:center;">Preferred <img src="img/clock-12.png" align="bottom" width="12"><br>Time of Service<br><span class="mini">(M,W,Th - Before 7AM, etc)</span></td>
                </tr>
                <tr>
                <td class="table_data" align="left" valign="top">
                    <textarea name="time_of_service_note" id="tr_time_of_service_note_update" style="width: 100%;" >
                    </textarea>
                </td>
                </tr>
            </table>
</td><td  style="vertical-align: top;"><table style="width: 200px;margin:auto;">
        <tbody><tr class="table_row">
        <td class="table_label" id="line_type_id_label" align="right" nowrap="" valign="top" style="font-size: 13px;">Name it</td>
        <td class="table_data" align="left" valign="top" style="font-size: 13px;"><input name="trap_label" value="" id="tr_trap_label_update" size="20"></td>
        </tr>
        <tr class="table_row">
        <td class="table_label" id="line_type_id_label" align="right" nowrap="" valign="top" style="font-size: 13px;">Description</td>
        <td class="table_data" align="left" valign="top"><textarea name="trap_description" id="tr_description_update" rows="4" cols="16"></textarea></td>
        </tr>
        <tr class="table_row">
        <td class="table_label" id="line_type_id_label" align="right" nowrap="" valign="top" style="font-size: 13px;">Add'l Price</td>
        <td class="table_data" align="left" valign="top"><input name="price_additional" value="" id="tr_price_additional_update" size="5"></td>
        </tr>
        <tr class="table_row">
        <td class="table_label" id="line_type_id_label" align="right" nowrap="" valign="top" style="font-size: 13px;">Add'l Price<br> Details</td>
        <td class="table_data" align="left" valign="top"><textarea name="price_additional_info" id="tr_price_additional_info_update" rows="3" cols="16"></textarea></td>
        </tr>
        </tbody>
        </table>
</td></tr>
<tr><td colspan="2">
<input type="hidden" value="Jet" name="service" />
<input type="submit" value="Schedule Line Jettting" name="line-jet" id="line-jeft" style="float: right;margin-right:25px;"/>
</td></tr>
        </table>
        
       
        
            <div style="clear: both;"></div>
    </div>
    </form>
    </div>

    
</div>

<script>

$(".dos").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});

$( "#tabs" ).tabs();
</script>