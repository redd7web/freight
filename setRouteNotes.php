<?php
include "protected/global.php";

if(isset($_POST['upnote'])) { 
    $opc = Array(        
        "notes"=>$_POST['pickupnote']."|".$_POST['pickupspec']
    );
    $db->where("schedule_id",$_GET['schedule_id'])->update($dbprefix."_notes",$opc);
}

$qd = $db->where("schedule_id",$_GET['schedule_id'])->get($dbprefix."_notes");


$ni = explode ("|",$qd[0]['notes']); 
?>

<form action="setRouteNotes.php?schedule_id=<?php echo $_GET['schedule_id']; ?>" method="post" style="width: 90%;margin-left:3%;">
<textarea name="pickupnote" placeholder="Driver Notes"><?php echo $ni[0]; ?></textarea>

<textarea name="pickupspec" placeholder="Special Instructions"><?php echo $ni[1]; ?></textarea>
<input type="submit" value="Update Notes" name="upnote"/>
</form>