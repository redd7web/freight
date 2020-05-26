<style>
body{
    padding:10px 10px 10px 10px;
    margin:10px 10px 10px 10px;
}
</style>
<img src="../img/img/greasem.png"/>
<?php
include "protected/global.php";
include "source/css.php";
echo $_POST['PARMLIST']."<br/>";
echo "---------------exploded------------------<br/>";

$yhc = explode("%7C",$_POST['PARMLIST']);

echo"<pre>";
print_r($yhc);
echo "</pre>";
echo "-------------------------exploded description--------------------<br/>";
$desc = explode("%2A",$yhc[9]);
echo"<pre>";
print_r($desc);
echo "</pre>";
echo "----------------------------identifies----------------------------------------------------<br/>";
$id = explode("%3A",$desc[4]);
echo"<pre>";
print_r($id);
echo "</pre>";

$x = str_replace("BNAME%7E","",$yhc[0]);
$x = str_replace("+"," ",$x);
$grease_info = new Grease_Stop($id[2]);
 ?>
 <h1><?php echo account_NumToName($id[0]); ?></h1>
 <?php 
echo "Thank you, $x your status has been approved for service for ".$grease_info->service_date."<br/>";

echo "UPDATE sludge_pay_trace SET status=1,date_paid='".date("Y-m-d")."' WHERE account_no = $id[0] AND route_id= $id[1] AND schedule_id = $id[2]";

?>