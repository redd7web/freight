<?php

ini_set("display_errors",1);
include "protected/global.php";
include "source/scripts.php";
include "source/css.php";

if(isset($_POST['change_sched'])){
    echo "UPDATE freight_grease_traps SET service_date ='$_POST[new_schedule_date]' WHERE grease_no=$_GET[grease_no]";
    if($db->query("UPDATE freight_grease_traps SET service_date ='$_POST[new_schedule_date]' WHERE grease_no=$_GET[grease_no]")){
        echo "Service date successfully updated!";
    }
}else{
    echo "No post;";
}


$cn = $db->query("SELECT service_date FROM freight_grease_traps WHERE grease_no = $_GET[grease_no]");
?>
<style type="text/css">
body{
    padding:10px 10px 10px 10px;
    margin:10px 10px 10px 10px;
}
</style>
<form action="edit_current_stop.php?grease_no=<?php echo $_GET['grease_no']; ?>" method="post">
<input type="text" id="new_schedule_date" name="new_schedule_date" placeholder="New Schedule Date" value="<?php echo $cn[0]['service_date']; ?>"/><br />
<input type="submit" value="Change Now" name="change_sched"/>
</form>
<script>
$("#new_schedule_date").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
</script>