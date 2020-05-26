<style>
body{
    padding:10px 10px 10px 10px;
    font-family:arial;
}
</style>
<?php
include "protected/global.php";


$accounts_container = $dbprefix."_containers";
$containerslist = $dbprefix."_list_of_containers";
$data_table = $dbprefix."_grease_data_table";
$list_of_routes = $dbprefix."_list_of_grease";
$account = new Account($_GET['account']);


if(isset($_POST['edit'])){
    $data = array(
        "inches_to_gallons"=>$_POST['gallons']
    );
    //var_dump($data);
    $db->where('entry_number',$_POST['entry_id'])->update($dbprefix."_data_table",$data);
    echo "<br/>";
    $recalc = $db->query("SELECT SUM(inches_to_gallons) AS alk FROM $data_table WHERE route_id= $_POST[route_id]");
    $new_tot = $recalc[0]['alk'];
    echo "new route total: ".$new_tot;
    echo "<br/>";
    $db->query("UPDATE $list_of_routes SET collected = $new_tot WHERE route_id=$_POST[route_id]");
    
    $track = array(
        "date"=>date("Y-m-d H:i:s"),
        "user"=> $person->user_id,
        "actionType"=>"Route stop altered",
        "descript"=>"Pickup altered for Route $_POST[route_id] Deleted",
        "account"=>$_GET['account'],
        "type"=>14
    );
    $db->insert($dbprefix."_activity",$track);
    
}


if(isset($_POST['deletethis'])){
    $db->where('entry_number',$_POST['entry'])->delete($dbprefix."_data_table");
    
    
    
    $recalc = $db->query("SELECT SUM(inches_to_gallons) AS alk FROM $data_table WHERE route_id= $_POST[route_id]");
    $new_tot = $recalc[0]['alk'];
    echo "new route total: ".$new_tot;
    
    $db->query("UPDATE $list_of_routes SET collected = $new_tot  WHERE route_id=$_POST[route_id]");
    
    $track = array(
        "date"=>date("Y-m-d H:i:s"),
        "user"=> $person->user_id,
        "actionType"=>"Route stop altered",
        "descript"=>"Pickup Deleted for Route $_POST[route_id] Deleted",
        "account"=>$_GET['account'],
         "pertains"=>6,
        "type"=>14
    );
    $db->insert($dbprefix."_activity",$track);
}

$schedule_info = new Grease_Stop($_GET['schedule']);
//echo $_GET['entry']." ".$_GET['route_id'];

$route_info = $db->where('schedule_id',$_GET['schedule'])->where('account_no',$_GET['account'])->where('route_id',$_GET['route_id'])->get($dbprefix."_grease_data_table");

?>

<table style="width: 100%;">
<tr><td colspan="10" style="text-align: cener;">
<h2 style="width: 200px;margin:auto;text-transform:uppercase;">Pickup Details</h2><br />
<h3 style="width: 210px;margin:auto;"><?php 
             echo "PICKUP ID: ".$_GET['entry']; 
     ?></h3><br />
</td></tr>
<tr>
<td style="width: 50%;text-align:center;padding:0px 0px 0px 0px;">
    <form action="edit_route_stop.php?entry=<?php echo $_GET['entry'] ?>&route_id=<?php echo $_GET['route_id']; ?>&account=<?php echo $_GET['account']; ?>&schedule=<?php echo $_GET['schedule']; ?>" method="post">
    <input type="hidden" value="<?php echo $_GET['entry']; ?>" name="entry"/>
    <input type="hidden" value="<?php echo $_GET['route_id']; ?>" name="route_id" />
    <input id="deletethis" name="deletethis" type="submit" value="Delete this pickup"/>
</form>

</td>
<td style="width: 50%;text-align:center;padding:0px 0px 0px 0px;">
<form action="edit_route_stop.php?entry=<?php echo $_GET['entry'] ?>&route_id=<?php echo $_GET['route_id']; ?>&account=<?php echo $_GET['account']; ?>&schedule=<?php echo $_GET['schedule']; ?>" method="post">
<input type="submit" id="enter" value="Edit Data" name="edit"/>
<input type="hidden" name="entry_id" value="<?php echo $_GET['entry']; ?>" />
<input type="hidden" name="route_id" value="<?php echo $_GET['route_id']; ?>" />
</td>

</tr>
<tr><td>Date Created: </td><td><?php echo $schedule_info->date_created; ?></td></tr>
<tr><td>Created By: </td><td><?php echo uNumToName($schedule_info->created_by); ?></td></tr>
<tr><td>Route: </td><td><?php echo $route_info[0]['route_id']; ?></td></tr>
<tr><td>Driver: </td><td><?php echo uNumToName($route_info[0]['driver']) ?></td></tr>
<tr><td>Field Report: </td><td> <?php  if( count($route_info)>0 ){ echo $route_info[0]['fieldreport'];} ?> </td>
<tr><td>Zero Gallon Reason</td><td><?php if(count($route_info) >0){ echo $route_info[0]['zero_gallon_reason']; } ?></td></tr>
<?php
if(count($route_info)>0){
    foreach($route_info as $routed){
            echo "<tr class='pick_up_row' rel='$_GET[entry]'>";
            
            echo "<td>Gallons Collected :</td><td style='text-align:left;' colspan='2'><input type='text' value='$routed[inches_to_gallons]' style='width:60px;' name='gallons' /> / $account->grease_volume";
        echo "</tr>";
    }
}
?>
</table>
</form>