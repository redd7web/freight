<?php
include "protected/global.php";
include "source/css.php";
include "source/scripts.php";
ini_set("display_errors",0);
if(isset($_SESSION['sludge_id'])){
    $grease_ikg = new Grease_IKG($_GET['route']);
    $person  = new Person();
    
?>
<style>
body{
    padding:10px 10px 10px 10px;
    margin:10px 10px 10px 10px;
}
</style>

<div id="duplicatesix" style="width: 1000px;margin:auto;height:auto;">
        <div id="searchTableLeft" style="width: 600px;height:auto;float:left;">
        <table style="width:600px;border:1px solid #bbb;float:left;">
          <tr>
            <td style="vertical-align: middle;text-align:center;border:0px solid #bbb;width:20px;padding:0px 0px 0px 0px;">
            <form action="grease_ikg.php" method="post" target="_parent" id="schedgreasetoikg">
                <input type="hidden" value="1" name="from_schedule_list"/>
                <input type="hidden" class="schecheduled_ids"  name="schecheduled_ids" placeholder="schedule numbers" id="schedule_numbers" value="<?php 
                    if(!empty($_SESSION['temp_stops'])){
                        foreach($_SESSION['temp_stops'] as $tstops){
                            $temp = new Grease_Stop($tstops);
                            echo $temp->grease_no."|";
                        }
                    }
                ?>"/>
                <input type="hidden" class="accounts_checked" name="accounts_checked" placeholder="account numbers" value="<?php 
                    if(!empty($_SESSION['temp_stops'])){
                        foreach($_SESSION['temp_stops'] as $tstops){
                            $temp = new Grease_Stop($tstops);
                            echo $temp->account_number."|";
                        }
                    }
                ?>" />
                <input type="hidden" name="from_schedule_list" value="1" readonly=""/>
           
            </td>
            <td style="width:580px;">
             Add to this completed route : <?php echo $grease_ikg->ikg_manifest_route_number; ?> 
            <input type="hidden" name="from_routed_grease_list" value="1" />
            <input type="hidden" name="add_to_route" value="1"/>
            <input type="hidden" name="util_routes" value="<?php echo $grease_ikg->route_id; ?>"/>&nbsp;
            <input type="submit" value="Add to Route" name="submit"/>
            </form>
            
            </td>
          </tr>
         <tr>
            <td style="vertical-align: middle;text-align:center;border:0px solid #bbb;width:20px;padding:0px 0px 0px 0px;">
            
            <input type="hidden" class="schecheduled_ids"  name="schecheduled_ids" placeholder="schedule numbers"/>
            <input type="hidden" class="accounts_checked" name="accounts_checked" placeholder="account numbers" />
            </form>
               &nbsp;
            </td>
            <td style="width:580px;text-align:center;"> 
               &nbsp;
                
            </td>
        </tr>
        <tr>                            
            <td colspan="2" style="padding:0px 0p 0px 0px;text-align:center;">
                &nbsp;
                <table style="width: 100%;">
                
                <form action="min_sched_trap.php" target="_self" method="post">
                
                 <tr><td style="padding: 5px 5px 5px 5px;vertical-align:middle;text-align:center;">Time of Day</td><td style="border: 0px solid #bbb;"> <input type="text" name="timesearch" id="todsearch"/> </select></td></tr>
                     <tr>
                     <td><input value="<?php echo $_POST['from']; ?>" type="text" id="from" name="from" placeholder="From:"/></td><td><input type="text" id="to" name="to" placeholder="To:"  value="<?php echo $_POST['to']; ?>"/></td>
                     </tr>
                     <tr><td>Trap Size: </td><td><input placeholder="From:" type="text" id="size_from" name="size_from"  value="<?php echo $_POST['size_from']; ?>"/><br /><input placeholder="To:" type="text" id="size_to" name="size_to" value="<?php echo $_POST['size_to']; ?>"/></td></tr>
                <tr style="background: white;border:1px solid white;"><td>Search By Debt</td><td><input type="text" id="from_debt" name="from_debt" placeholder="Minimum Amount $"  value="<?php echo $_POST['from_debt']; ?>"/><br /><input value="<?php echo $_POST['to_debt']; ?>" type="text" id="to_debt" name="to_debt" placeholder="Maximum Amount $" /></td></tr>
                <tr><td>Friendly</td><td><?php if(isset($_POST['search_now'])){
                      getFriendLists($_POST['friendly']);
                }else{
                    getFriendLists("");
                }  ?></td></tr>
                </table>
            </td>
        </tr>
        
        
        
            </table>
            
        </div>
                            
        <div id="spacer" style="width: 30px;height:80px;float:left;">&nbsp;</div>
            <div id="searchTableRight" style="width: 370px;height:auto;float:left;">
                <?php 
                    if(!$person->isFriendly() ){
                        ?>
                        <table style="width:370px;background:white;height:100px;width:100%;">
                 
                    <tr>
                        <td style="text-align: center;" colspan="2">Facility</span></td>
                    </tr>
                    <tr>
        <td style="vertical-align:top;width:50%;pading:0px 0px 0px 0px;text-align:left;padding:0px 0px 0px 0px;" colspan="2">
            <ul class="facs">          
                <li><input id="all" name="all" <?php if(isset($_POST['all'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;All</li>
               <li><input name="allfac" type="checkbox" id="alluc"/>&nbsp;ALL UC</li>
                
                <li><input value="24" name="fac3" type="checkbox" class="fac uc" <?php if(isset($_POST['fac3'])){ echo "checked"; } else { if($person->facility == 24){ echo "checked"; } }?>  />&nbsp;LA Division(UC)</li> 
                <li><input value="32" name="fac4" type="checkbox" class="fac uc" <?php if(isset($_POST['fac4'])){ echo "checked"; } else {  if($person->facility == 32){ echo "checked"; } }?>/>&nbsp;LA Division(UC-Chato)</li>
                <li><input value="33"  <?php if(isset($_POST['fac5'])){ echo "checked"; } else {    if($person->facility == 33){ echo "checked"; } }?>  name="fac5" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Chuck)</li>
                <li><input value="25" <?php if(isset($_POST['fac6'])){ echo "checked"; } else {  if($person->facility == 25){ echo "checked"; } }?>  name="fac6" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Ramon)</li>
                <li><input value="30" <?php if(isset($_POST['fac7'])){ echo "checked"; } { if($person->facility == 30){ echo "checked"; } }?>  name="fac7" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Tony)</li>
                
                
                <li><input value="15" name="fac15" type="checkbox" class="fac uc"  <?php if(isset($_POST['fac15'])){ echo "checked"; } else {  if($person->facility == 15){ echo "checked"; } }?>/>&nbsp;W Division Bernadino</li>    
                  
            </ul>
        </td>
        
        </tr>
       <tr>
        <td style="width: 80%;pading:0px 0px 0px 0px;text-align:left;padding:0px 0px 0px 0px;" colspan="2">
          <?php 
            if(!$person->isCoWest()){
          ?>
            <ul class="facs" > 
                <li><input name="allselma"  id="allselma" type="checkbox" class="selma"/>&nbsp;All Selma</li>
                <li><input value="10" name="fac10" type="checkbox" class="fac selma"  <?php if(isset($_POST['fac10'])){ echo "checked"; } else {  if($person->facility == 10){ echo "checked"; } }?>/>&nbsp;V-BAK</li>
                <li><input value="11" name="fac11" type="checkbox" class="fac selma"  <?php if(isset($_POST['fac11'])){ echo "checked"; } else {  if($person->facility == 11){ echo "checked"; } }?>/>&nbsp;V-Fresno</li>
                <li><input value="14"  <?php if(isset($_POST['fac14'])){ echo "checked"; } else {  if($person->facility == 14){ echo "checked"; } }?> name="fac14" type="checkbox" class="fac"/>&nbsp;L Division (Coachella)</li>                                   
                <li><input value="8" name="fac1" type="checkbox" class="fac"  <?php if(isset($_POST['fac1'])){ echo "checked"; } else {  if($person->facility == 8){ echo "checked"; }} ?> />&nbsp;Arizona Division(4)</li>
                <li><input value="23" name="fac2" type="checkbox" class="fac" <?php if(isset($_POST['fac2'])){ echo "checked"; }  ?>  />&nbsp;Coachella Division (UD)</li>
              
                <li><input value="22" <?php if(isset($_POST['fac8'])){ echo "checked"; } else { if($person->facility == 22){ echo "checked"; } }?>  name="fac8" type="checkbox" class="fac"/>&nbsp;San Diego Division (US)</li>
                <li><input value="5" <?php if(isset($_POST['fac9'])){ echo "checked"; } else {  if($person->facility == 5){ echo "checked"; } }?>  name="fac9" type="checkbox"  class="fac selma"/>&nbsp;Selma (V)</li>
                <li><input value="12"  <?php if(isset($_POST['fac12'])){ echo "checked"; } else {  if($person->facility == 12){ echo "checked"; } }?> name="fac12" type="checkbox" class="fac selma"/>&nbsp;V-North</li>
                <li><input value="13"  <?php if(isset($_POST['fac13'])){ echo "checked"; } else {  if($person->facility == 13){ echo "checked"; } }?> name="fac13" type="checkbox" class="fac selma"/>&nbsp;V-Visalia</li>
                 
            </ul>
            <?php } else{
                echo "&nbsp;";
            } ?>
        </td>
       </tr>
       <tr><td colspan="4" style="text-align: right;vertical-align:top;"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>" style="margin-left: 10px;">Default Data View</a>&nbsp;<input type="reset" id="remove_cached"/>&nbsp;<input style="color: black;" type="submit" value="Search" name="search_now" style="margin-left: 10px;"/></form></td></tr>
    </table>
            <?php
        }
    ?>     
</div>
                           
                          
                                   
        <div style="clear: both;"></div>
    </div>                            
  <style type="text/css">
  
   table#secondsearchparams td{
font-size: 12px; border:1px green solid; margin:auto;width:80%;margin-top:35px;font-weight:normal;font-weight:bold;
text-align:left;
}
  </style>
<?php    
    
    
    
$head ='From: greasetrap-scheduler@iwpusa.com' . "\r\n" .
            'Reply-To: no-replyr@iwpusa.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion()."\r\n";
            $head .= "MIME-Version: 1.0\r\n";
            $head .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';    

$grease_table = $dbprefix."_grease_traps";
$account_table = $dbprefix."_accounts";


if( isset($_POST['search_now'])  ){ //********************  POSTED SEARCH  ********************************
    foreach($_POST as $name=>$value){
            switch($name){
                case "manual":
                    if(isset($name)  ){
                        $arrFields[]="manual_ok =1";
                    }
                break;
                case "from_debt":
                    if($value>0){
                        $arrFields[] = "$account_table.out_standing_debts >= $value";
                    }
                    break;
                case "to_debt":
                    if($value>0){
                        $arrFields[] = "$account_table.out_standing_debts <=$value";
                    }
                    break;
                case "size_from":
                    if($value >0 && strlen($value)>0 && $value !="" && $value !=" "){
                        $arrFields[] = " $account_table.grease_volume >= $value";    
                    }
                    break;
               
                case "size_to":
                    if($value >0 && strlen($value)>0 && $value !="" && $value !=" "){
                        $arrFields[] = " $account_table.grease_volume <= $value";
                    }
                    break;
             
                case "from":
                    if(strlen($value)>0 && $value !="" && $value !=" "){
                        $arrFields[] = " service_date >= '$value' ";
                    }
                break;
                case "to":
                    if(strlen($value)>0 && $value !="" && $value !=" "){
                        $arrFields[] = " service_date <= '$value' ";
                    }
                break;
                case "tod":
                    if(strlen($value)>0){
                        if($value == "am"){
                            $arrFields[] = "( TIME(date)  < '12:00:00' || TIME(date) > '00:00:00' )";
                        } else if($value == "pm"){
                            $arrFields[] = " ( TIME(date) >= '12:00:00' || TIME(date) <= '23:59:59' )";
                        }
                    }
                    break;
                case "id":
                    if(strlen($value)>0){
                        $arrFields[] = " schedule_id=".$value;
                    }
                    break;
                case "name":
                    if(strlen($value)>0){
                        $arrFields[] = " name like '%".$value."%'";
                    }
                    break;
                case "address":
                    if(strlen($value)>0){
                        $arrFields[] = " address='".$value."'";
                    }
                    break;
                case "city":
                    if(strlen($value)>0){
                        $value = str_replace(" ","%",$value);
                        $arrFields[] = " city like '%".$value."%'";
                    }
                    break;
                case "state":
                    if(strlen($value)>0){
                        $arrFields[] = " state = '".$value."'";
                    }
                    break;   
                case "zip":
                    if(strlen($value)>0){
                        $arrFields[] = " zip = $value";
                    }
                    break;               
                case "from":
                    if(strlen($value)>0 && isset($value)){
                        $arrFields[] = " state_date >= '$_POST[from]'";   
                    }
                    break;
                case "to":
                    if(strlen($value)>0 && isset($value)){
                        $arrFields[] = " expires <= '$_POST[to]'";
                    }
                    break;
                case "fac1":
                if(isset($name)){                   
                    $facField[]=" division =".$value;} 
                    break;
                case "fac2":
                    if(isset($name)){                    
                        $facField[]=" division =".$value;}
                    break;
                case "fac3":
                if(isset($name)){               
                    $facField[]=" division =".$value; }
                    break;
                case "fac4":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                case "fac5":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                case "fac6":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                case "fac7":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                case "fac8":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                case "fac9":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                 case "fac10":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                    
               case "fac11":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
               case "fac12":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                    
                case "fac13":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;   
                case "fac14":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                break;  
                case "fac15":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                break;             
                case "friendly":
                    if($value !="null"){
                        $arrFields[] = " $account_table.friendly  ='$value' ";
                    }
                break;
                 case "fac22":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
               case "fac21":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                    
                case "fac23":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;              
            }
        }
       $criteria1 ="";
       $criteria2 = "";  
         
        if(!empty($arrFields)){
             $criteria1 = " AND  ". implode (" AND ",$arrFields)." ";
        }
        
        if(!empty($facField)) {
            $criteria2 = " AND ( ".implode (" OR ", $facField)."  )";
        }

        $ask = " SELECT $grease_table.*,
                    $account_table.name ,
                    $account_table.account_ID,
                    $account_table.address,
                    $account_table.city,
                    $account_table.state,
                    $account_table.division,
                    $account_table.zip,
                    $account_table.grease_volume,
                    $account_table.avg_gallons_per_Month,
                    $account_table.credit_note,
                    $account_table.credits,
                    $account_table.notes,
                    $account_table.code_red_email,
                    $account_table.locked,$account_table.cc_on_file,$account_table.out_standing_debts,$account_table.decline_note,$account_table.Name
                        FROM $grease_table INNER JOIN $account_table ON $grease_table.account_no = $account_table.account_ID WHERE $grease_table.route_status ='scheduled' ".$criteria1." ".$criteria2;

        echo "ssearch: ".$ask.$addtn;
        $check =  $db->query($ask);


} else if( isset($_POST['scrs']) ){
    $check = $db->query("SELECT * FROM $grease_table WHERE (route_status = 'scheduled' || route_status ='new') && grease_route_no IS NULL && fire =1 $addtn");
} else {
   echo "SELECT $grease_table.*,$account_table.division,$account_table.account_ID,$account_table.address,$account_table.city,$account_table.state,$account_table.zip,$grease_table.prepay,$account_table.credits,$account_table.notes,$account_table.credit_note,$account_table.Name,
                    $account_table.grease_volume,
                    $account_table.code_red_email,
                    $account_table.locked,$account_table.cc_on_file,$account_table.out_standing_debts,$account_table.decline_note  FROM $grease_table INNER JOIN $account_table ON $account_table.account_id = $grease_table.account_no WHERE (route_status = 'scheduled' || route_status ='new') && grease_route_no IS NULL  $addtn";
                    
                    
    $check = $db->query("SELECT $grease_table.*,$account_table.division,$account_table.account_ID,$account_table.address,$account_table.city,$account_table.state,$account_table.zip,$grease_table.prepay,$account_table.credits,$account_table.notes,$account_table.credit_note,$account_table.Name,
                    $account_table.grease_volume,
                    $account_table.code_red_email,
                    $account_table.locked,$account_table.cc_on_file,$account_table.out_standing_debts,$account_table.decline_note  FROM $grease_table INNER JOIN $account_table ON $account_table.account_id = $grease_table.account_no WHERE (route_status = 'scheduled' || route_status ='new') && grease_route_no IS NULL  $addtn");


}

?>

<style type="text/css">
.tableNavigation {
    width:1000px;
    text-align:center;
    margin:auto;
    overflow-x:auto;
}
.tableNavigation ul {
    display:inline;
    width:1000px;
}
.tableNavigation ul li {
    display:inline;
    margin-right:5px;
}

td{
    background:transparent;
    border:0px solid #bbb;
    padding:0px 0px 0px 0px;
}

tr.even{
    background:-moz-linear-gradient(center top , #F7F7F9, #E5E5E7);
}
.five-o{

widith:50px;
}

tr.odd{
    background:transparent;
}
.setThisRoute{
    z-index:9999;
}



input[type=checkbox]{
    width:10px;
}
</style>
<script>

$(document).ready(function(){
   $('#myTable').dataTable({
        "lengthMenu": [ [10, 25, 50,100,150, -1], [10, 25, 50,100,150, "All"] ],
         "order": [[ 0, "desc" ]]
   });
});
</script>
<table style="width: 100%;margin:auto;border-collapse:collapse;cell-padding:0px 0px 0px 0px;"  id="myTable" >

<thead>
    <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
    
    <td style="width: 15px;"><input type='submit' value="Del All" id="del_selected_scheds"/></td>
    <td class="cell_label" style="width:20px;">Code Red</td>
    <td class="cell_label" style='width:auto;'>Last Serviced</td>
    <td class="cell_label" style='width:auto;'>ID</td>
    <td class="cell_label" style='width:auto;'>Trap Volume</td>
    <td class="cell_label" style='width:auto;'>Note</td>
    <td class="cell_label" style='width:auto;'>Jetting</td>
    
    
    
    
    <td class="cell_label" style='width:auto;white-space: nowrap;'>on File</td>
    <td class="cell_label" style='width:auto;'>Scheduled</td>
    <td class="cell_label" style='width:auto;'>Days past</td>

    <td class="cell_label" style='width:auto;'>Name</td>
    <td class="cell_label" style='width:auto;'>Address</td>
    <td class="cell_label" style='width:auto;'>City</td>


    <td class="cell_label" style='white-space: nowrap;'>State</td>

    <td class="cell_label" style='width:auto;'>Zip</td>

    <td class="cell_label" style='width:auto;'>Facility</td>

    <td class="cell_label" style='white-space: nowrap;'>One Service</td>
    
    <td class="cell_label" style="white-space: nowrap;width:45px;">Manual</td>
    <td class="cell_label" style="white-space: nowrap;">Zero Charge</td>
    <td class="cell_label" style="white-pace:nowrap;">Emergency</td>
    </tr>
</thead>

<tbody>

<?php
//**********************  SESSION TEMPORARY ARRAY ******************************************//
echo "temp stops: <pre>";
print_r($_SESSION['temp_stops']);
echo "</pre>";
    if(!empty($_SESSION['temp_stops'])){
        foreach($_SESSION['temp_stops'] as $saved){
            $saved_gs = new Grease_Stop($saved);
            echo "<tr class='cached'>";
            echo "<td style='vertical-align:top;text-align:left;'>
                <img src='img/delete-icon.jpg' class='delgrease' rel='".$saved_gs->grease_no."' style='width:15px;height:15px;cursor:pointer;float:left;;display:block'/>                <input type='checkbox' class='setThisRoute cachedx' style='cursor:pointer;width:10px;height:15px;z-index:9999;float:left;;display:block;margin-left:5px;' checked='true'   xlr='".$saved_gs->account_number."' rel='".$saved_gs->grease_no."' "; 
                if($saved_gs->locked == 1 && $saved_gs->mlocked == 1){
                     echo " disabled  ";
                }
            echo " />
                </td>";
            echo "<td  style='width:20px;'>";
            if(date_different(date("Y-m-d"),$saved_gs->service_date) <= 5 && date_different(date("Y-m-d"),$saved_gs->service_date) > 0){
                echo "<img src='../img/graphics-flashing-light-245546.gif'  style='width:25px;height:25px;'/>";
            } else {
                echo "<img src='../img/redlight.jpg' style='width:25px;height:25px;'/>";
            }
    
            if(date_different(date("Y-m-d"),$saved_gs->service_date) ==5){                
                mail("$saved_gs->code_red_email","Scheduled Stop $saved_gs->grease_no $saved_gs->account_name coming up","Scheduled Stop $saved_gs->grease_no $saved_gs->account_number 5 days away",$head);
            }
    
            echo "</td>";
            echo "<td style='width:auto;'>";
                $last = $db->query("SELECT date_of_pickup FROM sludge_grease_data_table WHERE account_no = $saved_gs->account_number ORDER BY date_of_pickup DESC LIMIT 0,1");
                if(count($last)>0){
                    echo $last[0]['date_of_pickup'];
                }else{
                    echo "N/A";
                }
            echo "</td>";
            echo "<td style='width:auto;'>$saved_gs->grease_no</td>";
            echo "<td style='width:auto;'>$saved_gs->volume</td>";
            echo "<td style='width:auto;' class='text' rel='$saved_gs->notes'>$saved_gs->notes</td>";
            echo "<td style='width:auto;'> <input type='checkbox' class='jetbox' rel='$saved_gs->grease_no' ";
                if($saved_gs->jetting==1){
                    echo " checked='checked'";
                } else {
    
                }
    
                if($saved_gs->locked == 1){
                    echo " disabled='disabled' ";
                }
            echo "  /></td>";
            echo "<td style='width:auto;'>";
                if($saved_gs->cc_on_file == 0){
                    echo "<img src='img/red_cancel.png' style='width:10px;height:10px;'/>";
                } else {
                    echo "<img src='img/check_green_2s.png' style='width:10px;height:10px;'/>";
                }
            echo "</td>";
            echo "<td style='width:auto;'>$saved_gs->service_date&nbsp;<img src='img/edit-icon.jpg' rel='$saved_gs->grease_no' class='change' style='cursor:pointer;'></td>";
            echo "<td style='width:auto;'>".date_different($saved_gs->service_date,date("Y-m-d"))."</td>";
            echo "<td style='width:auto;'>".account_NumToName($saved_gs->account_number)." ";
                if($saved_gs->locked == 1 && $saved_gs->mlocked ==1){
                    echo " ( <span style='font-weight:bold;color:red;font-size:10px;'>MASTER LOCKED</span> ) ";
                }else if ($saved_gs->locked == 1 && $saved_gs->mlocked == 0){
                     echo " ( <span style='font-weight:bold;color:red;font-size:10px;'>LOCKED</span> ) ";
                }
            echo" </td>";
            echo "<td style='width:auto;'>$saved_gs->account_address</td>";
            echo "<td style='width:auto;'>$saved_gs->account_city</td>";
            echo "<td style='width:auto;'>$saved_gs->account_state</td>";
            echo "<td style='width:auto;'>$saved_gs->account_zip</td>";
    
            echo "<td style='width:auto;'>".numberToFacility($saved_gs->division)."</td>";
    
            echo "<td style='width:auto;'><input type='checkbox' xlr='' class='multi_route' rel='$saved_gs->grease_no' ";
                if($saved_gs->mult_day_route==1){
                    echo "checked='checked'";
                }
                if($saved_gs->locked == 1){
                        echo " disabled='disabled' ";
                }
            echo " /></td>";
            
            echo "<td style='width:;'><input rel='$saved_gs->grease_no' class='manual_ok' type='checkbox'"; if($saved_gs->manual_ok ==1){  echo "checked='checked'"; }  echo " /></td>";
            echo "<td><input type='checkbox' class='zcp' ";    
                if($saved_gs->zero_charge_pickup==1){
                    echo " checked ";
                }
            echo " rel='$saved_gs->grease_no'/></td>";
            echo "<td>";
            echo "<input type='checkbox' rel='$saved_gs->grease_no' class='emergency' ";
            if($saved_gs->emergency ==1){
               echo " checked='checked' ";
            }
            echo " />";
          
            echo "</td>";
            
            echo "</tr>";
        }
    }
     //**********************  SESSION TEMPORARY ARRAY ******************************************//


if(count($check)> 0){
    foreach($check as $grease){
        if(!in_array($grease['grease_no'],$_SESSION['temp_stops'])   ){
            $grease_stop = new Grease_Stop($grease['grease_no']);
            echo "<tr>";
            echo "<td style='vertical-align:top;text-align:left;'>
                <img src='img/delete-icon.jpg' class='delgrease' rel='".$grease_stop->grease_no."' style='width:15px;height:15px;cursor:pointer;float:left;;display:block'/>
                <input type='checkbox' class='setThisRoute' style='cursor:pointer;width:10px;height:15px;z-index:9999;float:left;display:block;margin-left:5px;'   xlr='".$grease_stop->account_number."' rel='".$grease_stop->grease_no."' "; 
                if($grease_stop->locked == 1 && $grease_stop->mlocked == 1){
                     echo " disabled  ";
                }
            echo " />
                </td>";
            echo "<td  style='width:20px;'>";
            if(date_different(date("Y-m-d"),$grease_stop->service_date) <= 5 && date_different(date("Y-m-d"),$grease_stop->service_date) > 0){
                echo "<img src='../img/graphics-flashing-light-245546.gif'  style='width:25px;height:25px;'/>";
            } else {
                echo "<img src='../img/redlight.jpg' style='width:25px;height:25px;'/>";
            }
    
            if(date_different(date("Y-m-d"),$grease['service_date']) ==5){
                
                mail("$grease[code_red_email]","Scheduled Stop $grease_stop->grease_no $grease[Name] coming up","Scheduled Stop $grease_stop->grease_no $grease[Name] 5 days away",$head);
            }
    
            echo "</td>";
            echo "<td style='width:auto;'>";
                $last = $db->query("SELECT date_of_pickup FROM sludge_grease_data_table WHERE account_no = $grease_stop->account_number ORDER BY date_of_pickup DESC LIMIT 0,1");
                if(count($last)>0){
                    echo $last[0]['date_of_pickup'];
                }else{
                    echo "N/A";
                }
            echo "</td>";
            echo "<td style='width:auto;'>$grease_stop->grease_no</td>";
            echo "<td style='width:auto;'>$grease_stop->volume</td>";
            echo "<td style='width:auto;' class='text' rel='$grease[notes]'>$grease[notes]</td>";
            echo "<td style='width:auto;'> <input type='checkbox' class='jetbox' rel='$grease_stop->grease_no' ";
                if($grease['jetting']==1){
                    echo " checked='checked'";
                } else {
    
                }
    
                if($grease['locked'] == 1){
                    echo " disabled='disabled' ";
                }
            echo "  /></td>";
            
            
            
            
            echo "<td style='width:auto;'>";
                if($grease['cc_on_file'] == 0){
                    echo "<img src='img/red_cancel.png' style='width:10px;height:10px;'/>";
                } else {
                    echo "<img src='img/check_green_2s.png' style='width:10px;height:10px;'/>";
                }
            echo "</td>";
            echo "<td style='width:auto;'>$grease_stop->service_date&nbsp;<img src='img/edit-icon.jpg' rel='$grease_stop->grease_no' class='change' style='cursor:pointer;'></td>";
            echo "<td style='width:auto;'>".date_different($grease['service_date'],date("Y-m-d"))."</td>";
            echo "<td style='width:auto;'>".account_NumToName($grease['account_no'])." ";
                if($grease_stop->locked == 1 && $grease_stop->mlocked ==1){
                    echo " ( <span style='font-weight:bold;color:red;font-size:10px;'>MASTER LOCKED</span> ) ";
                }else if ($grease_stop->locked == 1 && $grease_stop->mlocked == 0){
                     echo " ( <span style='font-weight:bold;color:red;font-size:10px;'>LOCKED</span> ) ";
                }
            echo" </td>";
            echo "<td style='width:auto;'>$grease[address]</td>";
            echo "<td style='width:auto;'>$grease[city]</td>";
            echo "<td style='width:auto;'>$grease[state]</td>";
            echo "<td style='width:auto;'>$grease[zip]</td>";
    
            echo "<td style='width:auto;'>".numberToFacility($grease['division'])."</td>";
    
            echo "<td style='width:auto;'><input type='checkbox' xlr='' class='multi_route' rel='$grease_stop->grease_no' ";
                if($grease['multi_day_stop']==1){
                    echo "checked='checked'";
                }
                if($grease['locked'] == 1){
                        echo " disabled='disabled' ";
                }
            echo " /></td>";
            
            echo "<td style='width:;'><input rel='$grease_stop->grease_no' class='manual_ok' type='checkbox'"; if($grease['manual_ok'] ==1){  echo "checked='checked'"; }  echo " /></td>";
            echo "<td><input type='checkbox' class='zcp' ";    
                if($grease['zero_charge_pickup']==1){
                    echo " checked ";
                }
            echo " rel='$grease_stop->grease_no'/></td>";
            echo "<td>";
            echo "<input type='checkbox' rel='$grease_stop->grease_no' class='emergency' ";
            if($grease_stop->emergency ==1){
               echo " checked='checked' ";
            }
            echo " />";
          
            echo "</td>";
            echo "</tr>";
        }
    }
}

?>
</tbody>
</table>
<div id="debug"></div>
<script>

$(".emergency").click(function(){
    
    if($(this).is(":checked")){
        $.post("emergency.php",{grease_id:$(this).attr('rel'),value:1},function(data){
            alert(data);
        });
    }else{
        $.post("emergency.php",{grease_id:$(this).attr('rel'),value:0},function(data){
            alert(data);
        });
    }
    
    
    if(confirm("Refresh page to see changes?")){
        window.location.reload();
    }    
});  

 $("#del_selected_scheds").click(function(){
    if(confirm("Are you sure you wish to delete these stops?")){
         $.post("del_selected_scheds.php",{selected_scheds:$("input#schedule_numbers").val()},function(data){
            alert(data);
            window.location.reload();
         });
    }
 })
 $('td.text').text(function(i, text) {
    var t = $.trim(text);
    if (t.length > 10) {
        return $.trim(t).substring(0, 10) + "...";
    }
    return t;
}).hover(function(){
     $(this).html("<span style='font-size:10px;'>"+$(this).attr('rel')+"</span>");
},function(){
     var t = $.trim($(this).attr('rel'));
    if (t.length > 10) {
        $(this).html ($.trim(t).substring(0, 10) + "...");
    }
    
});
$("#todsearch").timepicker();

$(".mainline").click(function(){
   $.post("mainline.php",{account_no:$(this).attr('rel')},function(data){
    
   }); 
});

$(".zcp").click(function(){
    if($(this).is(":checked")){
        $.post("zero_charge.php",{grease_no:$(this).attr('rel'),value:1},function(data){
            alert("Set to Zero Charge Pickup "+ data);
            window.location.reload();
        });
    }else{
        $.post("zero_charge.php",{grease_no:$(this).attr('rel'),value:0},function(data){
            alert("Unset Zero Charge Pickup" + data);
            window.location.reload();
        });
    }
});

$(".manual_ok").click(function(){
    if($(this).is(":checked")){
        $.post("manual_update.php",{grease_no:$(this).attr('rel'),value:1},function(data){
            alert('Manual Ok Updated!');
        });
    }else{
        $.post("manual_update.php",{grease_no:$(this).attr('rel'),value:0},function(data){
            alert('Manual Ok Updated!');
        });
    }
});

$(".multi_route").click(function(){
   if($(this).is(":checked")){
        $.post("mult_route_grease.php",{sched:$(this).attr('rel'),val:1},function(data){
            alert("Multi Stop Route "+ data);
        });
   } else{
        $.post("mult_route_grease.php",{sched:$(this).attr('rel'),val:0},function(data){
            alert("Multi Stop Route");
        });
   }
});

$("table").on("click",".setThisRoute",function(){

   if($(this).is(":checked")  ){
        $(".schecheduled_ids").val( $(".schecheduled_ids").val() + $(this).attr('rel')+"|"  );
        $(".accounts_checked").val( $(".accounts_checked").val() + $(this).attr('xlr')+"|" );
        $.post("putIntoSessionArray.php",{schedule_id:$(this).attr('rel')},function(data){
            alert(data);
        });
    }else {
        
        var replace =$(this).attr('rel')+"|";
        var newVal =  $(".schecheduled_ids").val().replace(replace,"");
        $(".schecheduled_ids").val(newVal);
        var replace1 = $(this).attr('xlr')+"|";
        var newVal2 =  $(".accounts_checked").val().replace(replace1,"");
        $(".accounts_checked").val(newVal2);
        $.post("removeSessionArray.php",{schedule_id:$(this).attr('rel'),remove:"remove"},function(data){
            alert(data);
        });
    }
     
});



$(".existing").click(function(){
    $(".new").prop('checked', false);
});

$(".new").click(function(){
    $(".existing").prop('checked', false);
});



$("#schedule_us").click(function(e){
    if($(".existing").is(":checked")){
        $("form#add_to_form").submit();  
    }
    
    
    if( $(".new").is(":checked") ){
         $("form#schedgreasetoikg").submit();
    }
    
    e.preventDefault();
});



$("#reset").click(function(){   
   window.location='scheduling.php?task=sgt'; 
});


function numberColumnJq(){
    $("#myTable tr td:nth-child(4)").each(function () {        
        var sched_id = $(this).html();
        var row =$(this).closest("tr");
        $.ajax({
            type: "POST",
            url: "remove_grease_unavail.php",
            data: { sched:sched_id }
            })
            .done(function( msg ) {
                 if(msg == "unavai"){// check if route has been routed, if it has been, dynamically remove it from the visible list
                    $(row).remove();
                    $("#debug").append("routed and removed - "+ sched_id+"<br/>");
                }
        });
    });
}

//setInterval("numberColumnJq();",5000);

$(".delgrease").click(function(data){
    if(confirm("Are you sure you want to delete this stop?")){
         
      $.ajax({
          method: "POST",
          url: "adminDelGrease.php",
          data: { grease_no: $(this).attr('rel') }
      }).done(function( msg ) {         
         alert(msg);
         location.reload();
      });
    }
});


$(".jetbox").click(function(){
    if($(this).is(':checked')){
        $.post('update_jet_stop.php',{grease_no:$(this).attr('rel'),val:1},function(data){
             alert('Line Jetting option set!');
        });
    }else{
        $.post('update_jet_stop.php',{grease_no:$(this).attr('rel'),val:0},function(data){
             alert('Line Jetting option set!');
        });
    }
});

$(".change").click(function(){
   Shadowbox.open({
        content:"edit_current_stop.php?grease_no="+$(this).attr('rel')+"",
        player:"iframe",
        width:"250px",
        height:"300px",
        title:"Edit Service Date"
   });
});

$("#remove_cached").click(function(){
    $.post("remove_cached.php",function(data){
         alert("Checked stops no longer carry over");         
         $('.cachedx input[type=checkbox]').each(function () {
            $(this).prop("checked", false);
            window.location.reload();
         });
     });
 });
 
$(".fac").click(function(data){
    var u = $(this).val();
    if( $(this).is(":checked") )  {
        $("input#facsx").val( $("input#facsx").val() + $(this).val()+"|"  );
    }else{
        var replace =u+"|";
        var newVal =  $("input#facsx").val().replace(replace,"");
        $("input#facsx").val(newVal);
    }
});
 
$("#alluc").click(function(data){
    if( $(this).is(":checked") )  {
         $("input#facsx").val("24|31|32|33|34|");
    }else{
         $("input#facsx").val("");
    }
});
</script>
<?php
}else{
    echo "<a href='index.php'>Your session has expired.  Please click here to relogin back in.</a>";
}
?>