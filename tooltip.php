<?php
include "protected/global.php";
include "source/css.php";
include "source/scripts.php";
error_reporting(-1);


$list_of_routes = $dbprefix."_list_of_routes";
$person = new Person();
$account = new Account($_GET['account_no']);

//var_dump($account->schedule);

if(isset($_POST['deletethis'])){
    echo "sched: ".$_POST['entry_number'];
    $db->query("DELETE FROM sludge_notes WHERE schedule_id = $_POST[entry_number]");
    $a_info = new Account();    
    
    
    if(strlen($account->schedule['route_id'])>0){// is the schedule in a route ?
        
        
        //*********** UPDATE IKG MANIFEST ROUTE LIST ****************///
        $manifest = $db->where("route_id",$account->schedule['route_id'])->get($dbprefix."_ikg_manifest_info","account_numbers");
        if(count($manifest)>0){
            $new_string = str_replace($_POST['entry_number']."|","",$manifest[0]['account_numbers']);
            $newstrg = array (
                "account_numbers"=>$new_string
            );
            $db->where('route_id',$account->schedule['route_id'])->update($dbprefix."_ikg_manifest_info",$newstrg);//update route list
        }
        
        //*********************************************************///
        
       
       
       //*********  UPDATE ROUTE LIST ****************//
       $expected_oil = $db->where("route_id",$account->schedule['route_id'])->get($dbprefix."_list_of_routes","expected");       
       if(count($expected_oil)>0){
           $new_exp = $expected_oil[0]['expected'] - $account->expected_gallons;
           $db->query("UPDATE $list_of_routes set expected=$new_exp WHERE route_id =".$account->schedule['route_id']." && expected >0");
           $db->query("UPDATE $list_of_routes set inc = inc -1 WHERE route_id =".$account->schedule['route_id']."  && inc > 0");
           $db->query("UPDATE $list_of_routes set stops = stops-1 WHERE route_id=".$account->schedule['route_id']." && stops >0");
       }
       //*********************************************//
    }
    
    // DELETE SCHEDULE
    echo $db->where("schedule_id",$_POST['entry_number'])->delete($dbprefix."_scheduled_routes");    
    echo "schedule deleted";
}



if(isset($_POST['upnotes'])){
        
        echo "schedule_id ".$_POST['sched_id']."<br/>";
        echo "SELECT route_id FROM sludge_scheduled_routes WHERE schedule_id = $_POST[sched_id]<br/>";
        $check_route = $db->query("SELECT route_id FROM sludge_scheduled_routes WHERE schedule_id = $_POST[sched_id]");
        
        if(count($check_route)>0 || $check_route[0]['route_status'] != NULL){
            $route = $check_route[0]['route_id'];
        } else {
            $route  = 0;    
        }
        
        $k = $db->query("SELECT * FROM sludge_notes WHERE schedule_id = $_POST[sched_id]");//updating or inserting new?
        if(count($k)>0){
            $update_note = array(
                "notes"=>$_POST['notes']."|".$_POST['instruct'],
                "date"=>date("Y-m-d H:i:s")
            );
            echo "<br/>status: ".$db->where("schedule_id",$_POST['sched_id'])->where("account_no",$_POST['account_no'])->update("sludge_notes",$update_note);    
        } else {
           
            $update_note = array(
                "schedule_id"=>$_POST['sched_id'],
                "notes"=>$_POST['notes']."|".$_POST['instruct'],
                "date"=>date("Y-m-d"),                
                "author_id"=>$person->user_id,
                "account_no"=>$_POST['account_no'],
                "route_id"=>$route,
                "type"=>" ",
                "created_by"=>" "
            );
            
            var_dump($update_note);
            echo "<br/>status: ". $db->insert("sludge_notes",$update_note);
            
        }
}









//var_dump($sched);









?>
<meta charset="UTF-8"/>
<style>
table td{
    padding:9px 9px 9px 9px;
}
body{
    background:rgb(242,242,242) url(img/loading.gif)  no-repeat center center;
}
</style>
<br/>


<table style="width: 100%;">
<tr><td colspan="10" style="text-align: cener;">
<h2 style="width: 200px;margin:auto;text-transform: uppercase;">Pickup Details</h2><br />
<h3 style="width: 210px;margin:auto;">
    <?php 
           if(count($account->schedule)>0){
                $poi = $db->query("SELECT * FROM sludge_data_table WHERE schedule_id = ".$account->schedule['schedule_id']);
                if(count($poi)>0){
                    echo "Pickup ID: ".$poi[0]['entry_number'];
                }
            }           
     ?></h3><br />
</td></tr>
<tr>
<td style="width: 50%;text-align:center;padding:0px 0px 0px 0px;">
    <form action="tooltip.php?account_no=<?php echo $account->acount_id; ?>" method="post">
    <input id="deletethis" name="deletethis" type="submit" value="Delete this pickup"/>
<input name="entry_number" id="entry_number" type="hidden" value="<?php if(count($account->schedule)>0){
echo $account->schedule['schedule_id'];} ?>" />
</form>

</td>
<td style="width: 50%;text-align:center;padding:0px 0px 0px 0px;"><input type="submit" id="enter" value="Enter Data"/></td>
</tr>
</table>
<table style="width: 100%;font-size:12px;margin-top:5px;">
    <tr><td style="padding-left: 5px;">Date created</td><td><?php 
        echo $account->schedule['date_created'];
     ?></td>  <td>&nbsp;</td> <td>Gallons Expected</td><td  style="padding-right: 5px;">
     
     <?php 
           echo $account->expected_gallons;

     ?>
     </td></tr>
 <td>Created By</td><td><?php 
 
 echo uNumToName($account->schedule['created_by']);
 
  ?></td>   <td>&nbsp;</td> <td>Route</td><td><?php echo $account->schedule['route_id']; ?></td>
 
<tr>
 <td>Scheduled For</td><td><?php 
 echo $account->schedule['scheduled_start_date'];
  ?></td>   <td>&nbsp;</td> <td>Driver</td><td><?php echo uNumToName($account->schedule['driver']); ?></td>
</tr>
       
<tr>

<form action="tooltip.php?account_no=<?php echo $_GET['account_no']; ?>" method="post">
 <td>Notes</td><td><textarea id="notes" name="notes"><?php echo $account->schedule['notes']; ?></textarea></td>   
</tr>
       
<tr>
 <td>Special Instructions</td><td><textarea id="instruct" name="instruct"><?php echo $account->schedule['driver_instructions']; ?></textarea></td>
    
</tr>
 <tr>
 <td colspan="2" style="text-align: right;"><input type="submit" name="upnotes" value="Update notes for this stop"/>
 <input type="hidden" name="account_no"  value="<?php echo $_GET['account_no']; ?>"/>
 <input type="hidden" name="sched_id" value="<?php echo $account->schedule['schedule_id']; ?>" />
 </td>
 </form>
 </tr>      
</table>

<script>
    
    window.onload= function(){
        $("body").css('background','rgb(242,242,242)');
    }
    
    $("#enter").click(function(){        
        window.location.href ="managerEnterData.php";        
    });
</script>