<?php
include "protected/global.php";
ini_set("display_errors",1);
$person = new Person();
if($person->user_id != 179){
    $disabled = " readonly='' disabled";
}else{
    $disabled = "";
}

?>
<style>
body{
    padding:5px 5px 5px 5px;
    margin:5px 5px 5px 5px;
}
</style>
<?php
if(isset($_POST['cc'])){
    if($db->query("UPDATE freight_grease_traps SET custom_charge =$_POST[ccupdate] WHERE grease_no=$_GET[schedule_id]")){
        echo "Custom Charge Updated!<br/>";
        ?>
        <script>
        alert("Custom Charge Updated!");
        </script>
        <?php
    }
}
$custom = $db->query("SELECT IFNULL(custom_charge,0.00) as custom_charge FROM freight_grease_traps WHERE grease_no = $_GET[schedule_id]");



?>
<form action="custom_charge.php?schedule_id=<?php echo $_GET['schedule_id']; ?>" method="post" >
Enter Custom Charge&nbsp;&nbsp;<input name="ccupdate" type="text" value="<?php echo $custom[0]['custom_charge']; ?>"  placeholder="Enter Custom Charge Here"  <?php  echo $disabled; ?>/>&nbsp;<input type="submit" value="Set Custom Charge" name="cc"/>
</form>