<?php
include "protected/global.php";
ini_set("display_errors",1);
if(isset($_POST['add_size'])){
    
    
    if( $db->query("UPDATE freight_accounts SET grease_volume = $_POST[tsize] WHERE account_ID = $_GET[account]") &&  $db->query("UPDATE freight_grease_traps SET volume = $_POST[tsize],	grease_trap_size= $_POST[tsize] WHERE account_no = $_GET[account] AND route_status IN ('enroute','scheduled')") ){// update stops enroute or scheduled with new trap size
         echo "<span style='color:green;'>Account and enroute/scheduled Trapsize updated</span>";
    }
}

?>
<style>
body{
    padding:20px 20px 20px 20px;
}
</style>
<form action="addtrapsize.php?account=<?php echo $_GET['account']; ?>" method="post">
<input placeholder="Input Trap size here" type="text" style="float: left;" name="tsize" id="tsize"/>&nbsp;<input type="submit" value="Add Size" name="add_size" id="add_size" style="float: left;"/>

</form>