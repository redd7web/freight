
<?php
include "protected/global.php";

include "source/css.php";
ini_set("display_errors",1);

function atr(){
    global $db;
    
    ?>
    <select name="manifest" id="manifest" >
                                    
    <?php  
        $route_list_table = $dbprefix."_list_of_routes";
        $scrts = $db->query("SELECT route_id,ikg_manifest_route_number,driver FROM freight_list_of_grease WHERE status IN('enroute','new','scheduled')");
        
        if(count($scrts)>0){
            foreach($scrts as $add_existing){
                echo "<option value='$add_existing[route_id]'>$add_existing[route_id] $add_existing[ikg_manifest_route_number] (".uNumtoName($add_existing['driver']).")</option>";
            }
        }
    ?></select>
    <?php
}

$account = new Account();
$person = new Person();
$check_uc = array(
    24,30,31,32,33
);



if($person->facility !=99){//default facility
    $cords = explode(",",$coords[$person->facility]);
    $coord1 = $cords[0];
    $coord2 = $cords[1]; 
    $single = $person->facility;
}else{
    $cords = explode(",",$coords[24]);
    $coord1 = $cords[0];
    $coord2 = $cords[1];
    $single = 24;
}
//if you are a facility manage of any uc facility or Richard Lopez
//echo $person->user_id;
if(  ( isset($_SESSION['freight_id']) && in_array($person->facility ,$check_uc) && $person->isFacilityManager() ) || ( isset($_SESSION['freight_id']) && (  $person->user_id ==149 || $person->user_id ==168 || $person->user_id == 137 ) ) ) {
$avg = $db->query("SELECT AVG(stops) as avg_stops FROM freight_list_of_grease WHERE  facility IN ($single) AND stops >2");
echo "Average stops per route for this facility : ".round($avg[0]['avg_stops'])."<br/><br/>";
?>

<?php





if(isset($_POST['modify'])){
    
    foreach($_POST as $name=>$value){
        switch($name){
            case "perc_full":
                if($value>=0 && strlen($value)>0){
                    
                    $having[]=" perc >=$value";
                }
                
            break;
            case "perc_full_to":
                if($value>=0 && strlen($value)>0){
                    
                    $having[]= " perc <= $value";
                }
               
            break;
            case "home_fac":
                if($value !=99){
                    $hf = $value;
                }else if($value == 99){
                    $hf = "24,30,31,32,33";
                }
            break;
        }
    }
    //print_r($having);
    if(!empty($having)){
        $string = " AND ".implode(" AND ",$having);
        //echo $string."<br/>";
    }
    
    $mod = $_POST['spr'];
    $acc =  $db->query("SELECT freight_accounts.estimated_volume,freight_accounts.avg_gallons_per_Month/freight_accounts.barrel_capacity as perc ,freight_grease_traps.grease_no,account_ID,Name, address, city, state, zip, latitude, longitude, SQRT( POW( 69.1 * ( latitude - $coord1) , 2 ) + POW( 69.1 * (  $coord2 - longitude ) * COS( latitude / 57.3 ) , 2 ) ) AS distance
FROM freight_accounts INNER JOIN freight_grease_traps ON freight_accounts.account_ID = freight_grease_traps.account_no WHERE  ( freight_accounts.division IN ($hf) AND freight_grease_traps.route_status ='scheduled' ) OR DATEDIFF( service_date, NOW( ) ) >=3 
HAVING distance <$_POST[dist_radius] $string ORDER BY perc,distance ASC");

//echo "SELECT freight_accounts.estimated_volume,freight_accounts.avg_gallons_per_Month/freight_accounts.barrel_capacity as perc ,freight_grease_traps.grease_no,account_ID,Name, address, city, state, zip, latitude, longitude, SQRT( POW( 69.1 * ( latitude - $coord1) , 2 ) + POW( 69.1 * (  $coord2 - longitude ) * COS( latitude / 57.3 ) , 2 ) ) AS distance FROM freight_accounts INNER JOIN freight_grease_traps ON freight_accounts.account_ID = freight_grease_traps.account_no WHERE  ( freight_accounts.division IN ($hf) AND freight_grease_traps.route_status ='scheduled' ) OR DATEDIFF( service_date, NOW( ) ) >=3 HAVING distance <$_POST[dist_radius] $string ORDER BY perc,distance ASC";

}else {
    
    
    $mod =7;
    if($person->facility !=99){
        $fac = $person->facility;
    }else{
        $fac = "24,30,31,32,33";
    }
 
    $acc =  $db->query("SELECT freight_accounts.estimated_volume,freight_accounts.avg_gallons_per_Month/freight_accounts.barrel_capacity as perc ,freight_grease_traps.grease_no,account_ID,Name, address, city, state, zip, latitude, longitude, SQRT( POW( 69.1 * ( latitude - $cords[0] ) , 2 ) + POW( 69.1 * (  $cords[1] - longitude ) * COS( latitude / 57.3 ) , 2 ) ) AS distance
    FROM freight_accounts INNER JOIN freight_grease_traps ON freight_accounts.account_ID = freight_grease_traps.account_no WHERE ( freight_accounts.division IN ($fac) AND freight_grease_traps.route_status ='scheduled'  AND freight_grease_traps.code_red=1   ) OR DATEDIFF( freight_grease_traps.service_date, NOW( ) ) >=3
    HAVING distance <25 AND perc>=.75
    ORDER BY perc,distance ASC");
    
    
}

if(count($acc)<= $avg[0]['avg_stops']){
    $limit = count($acc);
}else{
    $limit = $avg[0]['avg_stops'];
}

?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="Ede Dizon" />

	<title>Untitled 2</title>
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript">
var fixHelper = function(e, ui) {
	ui.children().each(function() {
		$(this).width($(this).width());
	});
	return ui;
};

$(function() {
    $( "tbody.sortable" ).sortable({    
            helper: fixHelper,
            connectWith:"tbody.sortable"
     }).disableSelection();
    
});
</script>
<style type="text/css">
td.col{
    width:35px;
    font-size:12px;
    height:39px;
     white-space: nowrap;
}
td.check{
    width:10px;
    white-space: nowrap;
    padding:0px 0px 0px 0px;
    margin: 0px 0px 0px 0px;
}

td.address{
    width:320px;
    
}
input[type='submit']{
    float: right;
}
table#sortable{
    border-collapse: collapse;
}
tr.ui-state-default{
    cursor:pointer;
}

   body{
        padding:10px 10px 10px 10px;
        margin:10px 10px 10px 10px;
   }
   </style>
</head>

<body style="margin-top:95px;">
<a href="summary.php">6 month Grease Traps summary</a><br />
<h2>Route Predicter Beta</h2>
<form action="predict.php" method="POST">
<table>
<tr><td>Home Facility</td><td><?php if($person->facility !=99){ echo " Set as ".numberToFacility($person->facility). "(Default)"; echo "<input type='hidden' name='home_fac' value='$person->facility' />";  } else { echo " Set as ".numberToFacility(24)." (Default)"; ?> 
        <select name="home_fac">
        <option <?php if(isset($_POST['modify'])){ if($_POST['home_fac']== 24){ echo " selected "; }  } else { if($person->facility == 24){  echo " selected "; }   } ?>  value="24">LA (UC Division)</option>
        <option <?php if(isset($_POST['modify'])){ if($_POST['home_fac']== 30){ echo " selected "; }  } else { if($person->facility == 30){  echo " selected "; }   } ?>  value="30">LA (UC Division-Tony)</option>
        <option <?php if(isset($_POST['modify'])){ if($_POST['home_fac']== 31){ echo " selected "; }  } else { if($person->facility == 31){  echo " selected "; }   } ?>  value="31">LA (UC Division-Ramon)</option>
        <option <?php if(isset($_POST['modify'])){ if($_POST['home_fac']== 32){ echo " selected "; }  } else { if($person->facility == 32){  echo " selected "; }   } ?>  value="32">LA (UC Division-Chato)</option>
        <option <?php if(isset($_POST['modify'])){ if($_POST['home_fac']== 33){ echo " selected "; }  } else { if($person->facility == 33){  echo " selected "; }   } ?>  value="33">LA (UC Division-Chuck)</option>
        <option <?php if(isset($_POST['modify'])){ if($_POST['home_fac']== 99){ echo " selected "; }  } else { if($person->facility == 99){  echo " selected "; }   } ?>   value="99" >All UC</option>
        </select> <?php  } ?></td></tr>
<tr><td>Limit Raidus ( default is 25mi) : </td><td><input type="text" placeholder="Distance" name="dist_radius" value="<?php if(isset($_POST['dist_radius'])){ echo $_POST['dist_radius']; }else {  echo "25";} ?>"/></td></tr>
<tr><td>Stops per Route(default is 7)</td><td><input type="text" placeholder="Stops Per Route" name="spr" value="<?php if(  isset( $_POST['spr']) ){ echo $_POST['spr'];}else { echo "7"; } ?>"/></td></tr>
<tr><td>Search by amount full from:</td><td><input type="text"  name="perc_full" placeholder=".xx" value="<?php if(isset($_POST['perc_full'])){ echo $_POST['perc_full']; }else {  echo ".75";} ?>" /></td></tr>

<tr><td>Search by amount full to:</td><td><input type="text"  name="perc_full_to" placeholder=".xx" value="<?php if(isset($_POST['perc_full_to'])){ echo $_POST['perc_full_to']; } ?>" /></td></tr>
<tr><td colspan="2"> <input type="submit" name="modify" value="Modify Search"/></td></tr>

</table>

</form>

<?php
$string ="";
$scheds="";
$acccc = "";
echo "<div style='width:1300px;height:auto;margin:auto;'>";
if(count($acc)>0){
    $route_counter = 0;
   
    echo "<table style='table-layout:fixed;display:inline-block;margin-left:10px;margin-bottom:5px;border:1px solid #bbb;width:600px;'>";
    echo "<tr><td colspan='4'><form target='_blank' action='oil_routing.php' method='post'></td></tr>";
    echo "<tr>
            <td class='check' style='width:10px;'>DIST</td>
            <td class='col'>Name</td>
            <td class='col'>% full</td>
            <td class='col'>EOS</td>
            <td style='width:320px;'>Address</td>
         </tr>";
    echo "<tbody class='sortable'>";
    foreach($acc as $coder){        
        if($account->barrel_cap($coder['account_ID']) >0){
           
            
           
                $route_counter++;
                 $string .="$coder[address], $coder[city], $coder[state] $coder[zip]-";
                 $scheds .="$coder[grease_no]|";
                 $acccc .="$coder[account_ID]|";
                echo  "<tr class='ui-state-default' title='$coder[grease_no]' xlr='$coder[account_ID]'><td class='check' style='width:10px;'>".number_format($coder['distance'],1)."mi</td><td class='col'>".$coder['Name']."</td> <td class='col'>% ".number_format($coder['perc'],2)."</td><td>".number_format($coder['estimated_volume'],2)."</td><td class='address'>$coder[address] $coder[city] $coder[state] $coder[zip]</td></tr>";
                if($route_counter !=0 && $route_counter%$mod == 0){
                    echo "</tbody>";
                    echo "<tr><td colspan='5'><input type='hidden' name='from_schoipu' value='1'/><input  class='account_nums' type='text' value='$acccc' name='accounts_checked'/>
                                    
                                              <input type='hidden' value='$scheds' name='schecheduled_ids' class='schecheduled_ids'/><input type='submit' value='Route Now'/></td></tr>";
                    echo "<tr><td colspan='5'></form>
                    
                    
                    <form action='preview_route.php' target='_blank' method='post'><input type='hidden' value='$string' class='test' name='test'/><input type='hidden' name='facility' value='$facils[$single]'/><input type='hidden' name='prvw' value='1'/><input type='submit' value='Preview Route'/></form></td></tr><tr><td  colspan='5'><form action='oil_routing.php' method='post' target='_blank'>"; atr(); echo "  <input type='hidden' name='from_routed_oil_pickups' id='from_routed_oil_pickups' value='1'/><input type='hidden' id='third' class='third' value='$scheds' name='schecheduled_ids'/><input type='hidden' name='extra_mode' value='1'/><input  class='account_nums' type='text' value='$acccc' name='accounts_checked'/><input type='submit' value='Add to Route' class='add_to_route'/></form></td></tr></table>";
                    
                    
                    
                    echo "<table style='table-layout:fixed;display: inline-block;margin-left:10px;margin-bottom:5px;border:1px solid #bbb;width:600px;'>";
                    echo "<tr><td colspan='5'><form target='_blank' action='oil_routing.php' method='post'></td></tr>";
                    echo "<tr><td class='check' style='width:10px;'>DIST</td><td class='col'>Name</td><td class='col'>% full</td><td class='col'>EOS</td><td style='width:320px;'>Address</td></tr><tbody class='sortable'>";
                    $route_counter = 0;
                    $string ="";
                    $scheds = "";
                    $acccc ="";
                }
                
             
        }
    }
    echo "</tbody>";
    echo "<tr><td colspan='5'><input type='hidden' name='from_schoipu' value='1'/><input class='account_nums'  type='text' value='$acccc' name='accounts_checked'/><input type='hidden' value='$scheds' name='schecheduled_ids' class='schecheduled_ids'/><input type='submit' value='Route Now'/></td></tr>";
    echo "<tr><td colspan='5'><form action='preview_route.php' target='_blank' method='post'><input type='hidden' value='$string' class='test' name='test'/><input type='hidden' name='facility' value='$facils[$single]'/><input type='hidden' name='prvw' value='1'/><input type='submit' value='Preview Route'/></form></td></tr><tr><td colspan='5'><form action='oil_routing.php' method='post'  target='_blank'>"; atr(); echo "
    <input type='hidden' name='from_routed_oil_pickups' id='from_routed_oil_pickups' value='1'/><input type='hidden' id='third' class='third' value='$scheds' name='schecheduled_ids'/><input type='hidden' name='extra_mode' value='1'/><input type='text' class='account_nums' value='$acccc' name='accounts_checked'/>  <input type='submit' value='Add to Route' class='add_to_route'/></form></td></tr></table>";
}
echo "<div style='clear:both;'/></div>";
?>
<div id="debug"></div>
<script>
/**/
$( "tbody.sortable" ).on( "sortbeforestop", function( event, ui ) {
   
    $("tbody.sortable").each(function(){
        var string="";
        var scheddd="";
        var ac = "";
        var si="";
        var adr="";
        var acos=""
       $(this).find("tr").each(function(){
            string +=$(this).find('.address').html()+"-";          
            scheddd +=$(this).attr('title')+"|";
            acos += $(this).attr('xlr')+"|";            
       });
       var si = scheddd.replace("undefined","");
       var adr = string.replace("undefined","");
       var ac = acos.replace("undefined","");
       $(this).next('tbody').find('.schecheduled_ids').val(si);
       $(this).next('tbody').find('.third').val(si);
       $(this).next('tbody').find('.test').val(adr); 
       $(this).next('tbody').find('.account_nums').val(ac);
       
       
    });
   
     
     
    
   
      
        
});


</script>

</body>
</html><?php } ?>