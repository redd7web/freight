<?php
include "protected/global.php";

if(isset($_POST['setdriver'])) { 
    $jh = array(
        "driver"=>$_POST['drivers']
    );
    
    $db->where('schedule_id',$_GET['sched_id'])->update("freight_scheduled_routes",$jh);
}



echo $_GET['sched_id'];
?>


<form method="post" action="setDriver.php?sched_id=<?php echo $_GET['sched_id']; ?>">
<span style="float: left;margin-left:10px;">Select Driver :<select name="drivers">
<?php
    $bv = $dbprefix."_users";
    $ju = $db->query("SELECT first,last,user_id FROM $bv WHERE roles like '%service driver%' order by last");
    if(count($ju)>0){
        foreach($ju as $role){
            echo "<option value='$role[user_id]'>$role[first] $role[last]</option>";
        }
    }
?>
</select></span> <input type="submit" name="setdriver" value="Set Driver" style="float: left;"/>

</form>