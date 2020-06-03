

<?php
include "protected/global.php";
$person = new Person();
ini_set("display_errors",1);
$account = new Account($_GET['account_no']);
 
?>

<link rel="stylesheet" href="css/style.css"/>
<style type="text/css">
body{
    background:rgb(242,242,242);
    font-family:arial;
    height:350px;
}

td{
    text-align:left;
    padding:0px 0px 0px 0px;
}
</style>
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<?php

if(  isset($_POST['schedulepickup'])  ){    
    
    if(isset($_POST['date_sched_pickup_month'])  && strlen($_POST['date_sched_pickup_month'])>0){
        $scheddate =$_POST['date_sched_pickup_month'];
    } else {
        $scheddate = date("Y-m-d");
    }
    
    $notex = NULL;
    $fire="";    
    if (isset($_POST['is_fire'])){
        $fire =1;
    } else {
        $fire =0;
    }
    
    $k =date('Y-m-d');
    
    switch($_POST['later_or_new']){
        case "add_to":
        
            $x = $db->query("SELECT account_no FROM freight_scheduled_routes WHERE account_no=$_POST[account_no] AND route_status='scheduled'");
            if(count($x)>0){
                $db->query("DELETE FROM freight_scheduled_routes WHERE account_no=$_POST[account_no] AND route_id IS NULL AND route_status='scheduled'");
                $db->query("DELETE FROM freight_notes WHERE schedule_id=$_POST[scedule_id_] AND account_no=$_POST[account_no]");
            }
            
            
            $kut = $db->where("route_id",$_POST['existing_routes'])->get($dbprefix."_ikg_manifest_info","driver");
            $addinfo = Array(
                "route_id"=>$_POST['existing_routes'],
                "scheduled_start_date"=>$scheddate,
                "facility_origin"=>23,
                "code_red"=>$fire,
                "account_no"=>$_GET['account_no'],
                "route_status"=>"scheduled",
                "created_by"=>$person->user_id,
                "date_created"=>$k,
                "driver"=>$kut[0]['driver'],
                "cs_reason"=>$_POST['cs_reason']
            );
            
            if ($db->insert($dbprefix."_scheduled_routes",$addinfo)){
                echo "Scheduled pickup for". account_NumtoName($_GET['account_no']). "added to route ". $_POST['existing_routes'];// if Scheduled route was inserted  insert the corresponding notes if they exist 
                $sched_num = $db->getInsertId(); 
                 if(   ( isset($_POST['dispatcher_note']) && strlen($_POST['dispatcher_note']) ) || ( isset($_POST['special_instructions']) && strlen($_POST['special_instructions']) >0   )  ){
                    if(isset($_POST['dispatcher_note']) && strlen($_POST['dispatcher_note'])>0){
                        $notex = $_POST['dispatcher_note'];
                    }
                    if(isset($_POST['special_instructions']) && strlen($_POST['special_instructions']) ){
                        $notex .= "|".$_POST['special_instructions'];
                    }
                    $schednotes = Array(  //route_no will be updated upon route creation                            
                        "schedule_id"=>$sched_num,          
                        "author_id"=>$person->user_id,
                        "date"=>date('Y-m-d h:i:s'),
                        "notes"=>$notex,                
                        "created_by"=>$person->user_id,
                        "account_no"=>"$_POST[account_no]",
                        "cs_reason"=>$_POST['cs_reason']
                    );
                    $db->insert($dbprefix."_notes",$schednotes);
                }
            }
                
                       
            
                 
            $manifest_table = $dbprefix."_ikg_manifest_info";
            $db->query("UPDATE $manifest_table set account_numbers = concat(account_numbers,'$_GET[account_no]|') WHERE route_id = $_POST[existing_routes]");
            
            
            break;
        case "later":            
        
            $x = $db->query("SELECT account_no FROM freight_scheduled_routes WHERE account_no=$_POST[account_no] AND route_status='scheduled'");
            
            if(count($x)>0){
                $db->query("DELETE FROM freight_scheduled_routes WHERE account_no=$_POST[account_no] AND route_id IS NULL AND route_status='scheduled'");
            }
        
            
             $schedinfo = Array(//id will be automatically assigned in database , route_no will be updated upon route creation                      
                "scheduled_start_date"=>$scheddate,
                "facility_origin"=>$account->division,
                "code_red"=>$fire,
                "account_no"=>"$_GET[account_no]",
                "route_status"=>"scheduled",
                "created_by"=>$person->user_id,
                "date_created"=>$k       
            );
            var_dump($schedinfo);
            $db->insert("freight_scheduled_routes",$schedinfo);
             
            
            //where("scheduled_start_date","$_POST[date_sched_pickup_month]")->where("date_created",$k)->get($dbprefix."_scheduled_routes","schedule_id");
            break;
                case "new":
                $schedinfo = Array(//id will be automatically assigned in database , route_no will be updated upon route creation                      
                "scheduled_start_date"=>$scheddate,
                "facility_origin"=>$account->division,
                "code_red"=>$fire,
                "account_no"=>"$_GET[account_no]",
                "route_status"=>"Scheduled",
                "created_by"=>$person->user_id,
                "date_created"=>$k,
                "cs_reason"=>$_POST['cs_reason']       
            );
            $db->query("DELETE FROM freight_scheduled_routes WHERE account_no = $_GET[account_no]");
            var_dump($schedinfo);
            if( $db->insert("freight_scheduled_routes",$schedinfo) ){
                
                $sched_num = $db->getInsertId();
                echo $sched_num;
            ?>
                <!--- for immediate routing ---!>
                <form action="oil_routing.php" id="route_now" method="post" target="_blank">
                <input type="hidden"  name="from_schoipu" value="1" />
                <input type="hidden" class="schecheduled_ids" value="<?php echo $sched_num."|"; ?>"  name="schecheduled_ids" placeholder="schedule numbers"/> 
                <input type="hidden" name="accounts_checked" id="accounts_checked" value="<?php echo $_GET['account_no']."|"; ?>"/>
                </form>
                <script>
                    $("#route_now").submit();
                </script>
                <!--- for immediate routing ---!>
            <?php
            }
            break;
        default:
            echo "Please choose Later, Route Now, or Add to a route."; 
    }
}
?>
<div style="left: 471px; top: 550px; display: block;" id="edit_pickup_box" class="edit_box_pickup">
<form method="post" action="schedulepickup.php?account_no=<?php echo $_GET['account_no']; ?>"  style="height: 350px;">
<table align="center" width="370" style="padding: 0px 0px 0px 0px;">

    <tbody>
    <tr><td colspan="10" style="text-align: center;"><span style="width:80%;padding:12px;margin:auto;text-align:center;font-weight:bold;font-size:16px;text-transform:uppercase;">Schedule Freight</span><br/></td></tr>
    <tr class="table_row">
        <td class="table_label" align="right" nowrap="" valign="top">Date of Pickup</td>
        <td class="table_data" align="left" valign="top">

<input type="text"  name="date_sched_pickup_month" value="<?php echo $account->cs['scheduled_start_date']; ?>" id="date_sched_pickup_month" placeholder="Click to select pick up date"/>
</td>
</tr>
<tr><td>Freight Reason</td><td><textarea id="cs_reason" name="cs_reason"><?php echo $account->cs['cs_reason']; ?></textarea></td></tr>

<tr class="table_row">

<td class="table_data" style="text-align:left;vertical-align:top;"><input type="radio" name="later_or_new" value="later"/>Route Later</td>

</tr>
<tr class="table_row">

<td class="table_data"  style="text-align:left;verttical-align:top;"> <input type="radio" name="later_or_new" id="new" value="new"/> Create New Route</td>

</tr><br />
<tr><td  style="text-align:left;verttical-align:top;">

 <input type="radio" id="add_to" name="later_or_new" value="add_to"/> Add to Existing Route</span>
                                    
</td>
<td>
<select name="existing_routes">
    <?php  
            $route_list_table = $dbprefix."_list_of_routes";
            $scrts = $db->query("SELECT route_id,ikg_manifest_route_number,driver FROM $route_list_table WHERE status in('enroute')");
            
            if(count($scrts)>0){
                foreach($scrts as $add_existing){
                    echo "<option value='$add_existing[route_id]'>$add_existing[route_id] $add_existing[ikg_manifest_route_number] (".  uNumToName($add_existing['driver']).")</option>";
                }
            }
    ?>
</select>
</td>
</tr>
<tr class="table_row">
<td class="table_label" align="right" nowrap="" valign="top">Note<br><span style="font-size:smaller;">For the Dispatcher</span><br/><span style="font-size:70%;">( Optional )</span></td>
<td class="table_data" align="left" valign="top"><textarea cols="45" rows="4" name="dispatcher_note" id="dispatcher_note"></textarea></td>
</tr>
<tr class="table_row">
<td class="table_label" align="right" nowrap="" valign="top">Special<br>Instructions<br><span style="font-size:smaller;">For the Driver</span><br/><span style="font-size:7;">( Optional )</span></td>
<td class="table_data" align="left" valign="top"><textarea cols="45" rows="4" name="special_instructions" id="special_instructions"></textarea></td>
</tr>
<tr class="table_row">
<td class="table_label" align="right" nowrap="" valign="top"></td>
<td class="table_data" align="left" valign="top"><table align="left"><tbody><tr><td class="table_label" align="right" nowrap="" valign="middle"><input name="is_fire" type="checkbox" id="is_fire" value="1"/></td>
<td class="table_data" colspan="10"><span style="color:#ff0000;font-weight:bold;text-transform: uppercase;">Code Red</span>&nbsp;&nbsp;&nbsp;&nbsp;<input id="schedulepickup" name="schedulepickup" value="Schedule Now" type="submit" style="float:right;" /></td>
</tr></tbody></table></td>
</tr>
<input type="hidden" value="<?php echo $_GET['account_no']; ?>" name="account_no"/>
<input type="hidden" value="<?php  echo $account->schedule['schedule_id'];   ?>"  name="scedule_id_"  /> 
</tbody></table></form></div>
<script>
$("#date_sched_pickup_month").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
</script>
