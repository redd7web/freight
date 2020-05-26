<?php
include "protected/global.php";
ini_set("display_errors",1);
$person = new Person($_SESSION['sludge_id']);
if(isset($_POST['search_upc'])){
    if($_POST['facility'] !="ignore"){
        $facility = "$_POST[facility]";
    }else  if ( $value == 99){
        $facility = "24,30,31,32,33";
    } 
}else{
    if($person->facility  !=99){
        $facility = $person->facility;
    } else{
        $facility = "24,30,31,32,33";
    }
}
$check_uc = array(
    24,30,31,32,33
);


function show_facility_year_month($month,$year,$facility){
    global $db;    
    $output[] ="";
    $line1= "";
    $hc = $db->query("SELECT SUM(sludge_accounts.grease_volume) as vol_at_pickup, IFNULL( COUNT( sludge_grease_traps.grease_no ) , 0 )  as active FROM sludge_accounts LEFT JOIN sludge_grease_traps ON sludge_accounts.account_ID = sludge_grease_traps.account_no WHERE MONTH(sludge_grease_traps.service_date)='$month' AND YEAR( sludge_grease_traps.service_date) ='$year' AND division IN($facility) AND sludge_grease_traps.route_status IN('scheduled','enroute')");
    $total = 0;
    if(count($hc)>0){
        $line1 .= "Stops enroute /scheduled ".$hc[0]['vol_at_pickup']." | ".$hc[0]['active']." stops<br/>";
        $total += $hc[0]['vol_at_pickup'];
    }
    $com = $db->query("SELECT SUM(sludge_accounts.grease_volume) as all_pickedup, IFNULL( COUNT( sludge_grease_traps.grease_no ) , 0 )  as picked_up FROM sludge_accounts LEFT JOIN sludge_grease_traps ON sludge_accounts.account_ID = sludge_grease_traps.account_no WHERE MONTH(sludge_grease_traps.service_date)='$month' AND YEAR( sludge_grease_traps.service_date) ='$year' AND division IN($facility) AND sludge_grease_traps.route_status IN('completed')");
    if(count($com)>0){
        $total +=$com[0]['all_pickedup'];
        $line1 .= "Current gallons collected: ".$com[0]['all_pickedup']." | ".$com[0]['picked_up']." stops<br/>Total:$total";
    }    
    $output[0] = $line1;
    $output[1] = $hc[0]['active'];
    $output[2] = $com[0]['picked_up'];
    return  $output;
}

$this_month = mktime(0, 0, 0, date('m'), 1, date('Y'));

$current =  date("M Y m", strtotime("+0 month", $this_month));
$hh = explode(" ",$current);
$plus_one =  date("M Y m", strtotime("+1 month", $this_month));
$h1 = explode(" ",$plus_one);
$plus_two = date("M Y m", strtotime("+2 month", $this_month)) . '<br/>';
$h2 = explode(" ",$plus_two);
$plus_three = date("M Y m", strtotime("+3 month", $this_month)) . '<br/>';
$h3 = explode(" ",$plus_three);
$plus_four = date("M Y m", strtotime("+4 month", $this_month)) . '<br/>';
$h4 = explode(" ",$plus_four);
$plus_five = date("M Y m", strtotime("+5 month", $this_month)) . '<br/>';
$h5 = explode(" ",$plus_five);
$plus_six = date("M Y m", strtotime("+6 month", $this_month)) . '<br/>';
$h6 = explode(" ",$plus_six);
if(  ( isset($_SESSION['sludge_id']) && in_array($person->facility ,$check_uc) && $person->isFacilityManager() ) || ( isset($_SESSION['sludge_id']) && (  $person->user_id ==149 || $person->user_id ==137 || $person->user_id == 138  ) ) ) {
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Tabs - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <style>
  body{
    padding:10px 10px 10px 10px;
    margin:10px 10px 10px 10px;
  }
  td{
    vertical-align:top;
    
  }
  </style>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#tabs" ).tabs({
  active: 7
});
  } );
  
  </script>
</head>
<body>

 <a href="predict.php">Grease Trap Predictor</a><br /><br />
 
 <?php 
    echo "<form action='summary.php' method='post' >";
    if(isset($_POST['search_upc'])){
        getFacilityList("",$_POST['facility']);
    }else{
        getFacilityList("","");
    }
    echo "<input type='submit' name='search_upc'/>";
    echo "</form>";
 ?>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1"><?php echo $hh[0]." ".$hh[1];  ?></a></li>
    <li><a href="#tabs-2"><?php echo $h1[0]." ".$h1[1]; ?></a></li>
    <li><a href="#tabs-3"><?php echo $h2[0]." ".$h2[1]; ?></a></li>
    <li><a href="#tabs-4"><?php echo $h3[0]." ".$h3[1];;  ?></a></li>
    <li><a href="#tabs-5"><?php echo $h4[0]." ".$h4[1];; ?></a></li>
    <li><a href="#tabs-6"><?php echo $h5[0]." ".$h5[1];; ?></a></li>
    <li><a href="#tabs-7"><?php echo $h6[0]." ".$h6[1];; ?></a></li>
   
    
  </ul>

  <div id="tabs-1">
    <table>
        <tr><td colspan="" style="text-align: left;" id="total_col"></td><td style="text-align: left;" id="not_picked"></td><td style="text-align: left;" id="completed"></td></tr>
            <tr><td><h2>Coachella (UD Division)</h2>
      <p>
      <?php 
        $cur =0;
        $not_picked =0;;
        $completed =0;
        $res = show_facility_year_month($hh[2],$hh[1],23);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
      ?>
      </p></td><td><h2>LA (UC Division-Ramon)</h2>
      <p><?php        
        $res = show_facility_year_month($hh[2],$hh[1],31);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    ?></p></td><td><h2>VSLM (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($hh[2],$hh[1],5);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    ?></p></td><td><h2>L-Division</h2>
      <p><?php        
        $res = show_facility_year_month($hh[2],$hh[1],14);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>San Diego (US Division)</h2>
      <p><?php        
        $res = show_facility_year_month($hh[2],$hh[1],22);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chato)</h2>
      <p><?php        
        $res = show_facility_year_month($hh[2],$hh[1],32);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    ?></p></td><td><h2>V-Fres (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($hh[2],$hh[1],11);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    ?></p></td><td><h2>W-Division</h2>
      <p><?php        
        $res = show_facility_year_month($hh[2],$hh[1],15);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>LA (UC Division)</h2>
      <p><?php        
        $res = show_facility_year_month($hh[2],$hh[1],24);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chuck)</h2>
      <p><?php        
        $res = show_facility_year_month($hh[2],$hh[1],33);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    ?></p></td><td><h2>V-North</h2>
      <p><?php        
        $res = show_facility_year_month($hh[2],$hh[1],12);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    ?></p></td><td></td></tr>
            <tr><td>  <h2>LA (UC Division-Tony)</h2>
      <p><?php        
        $res = show_facility_year_month($hh[2],$hh[1],30);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    ?></p></td><td><h2>Arizona (4 Division)</h2>
      <p><?php        
         $res = show_facility_year_month($hh[2],$hh[1],8);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    ?></p></td><td><h2>V-Vis</h2>
      <p><?php        
         $res = show_facility_year_month($hh[2],$hh[1],13);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked +=$res[1];
        $completed +=$res[2];
        $cur += $grab_number[1];
    
    ?></p></td><td></td></tr>
    
      </table>
  </div>
  <div id="tabs-2">
    <table>
        <tr><td colspan="" style="text-align: left;" id="may_total_col"></td><td style="text-align: left;" id="may_not_picked"></td><td style="text-align: left;" id="may_completed"></td></tr>
            <tr><td><h2>Coachella (UD Division)</h2>
      <p>
      <?php 
        $may =0;
        $not_picked_may =0;
        $completed_may =0;
        $res = show_facility_year_month($h1[2],$h1[1],23);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
      ?>
      </p></td><td><h2>LA (UC Division-Ramon)</h2>
      <p><?php        
        $res = show_facility_year_month($h1[2],$h1[1],31);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    ?></p></td><td><h2>VSLM (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h1[2],$h1[1],5);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    ?></p></td><td><h2>L-Division</h2>
      <p><?php        
        $res = show_facility_year_month($h1[2],$h1[1],14);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>San Diego (US Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h1[2],$h1[1],22);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chato)</h2>
      <p><?php        
        $res = show_facility_year_month($h1[2],$h1[1],32);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    ?></p></td><td><h2>V-Fres (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h1[2],$h1[1],11);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    ?></p></td><td><h2>W-Division</h2>
      <p><?php        
        $res = show_facility_year_month($h1[2],$h1[1],15);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>LA (UC Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h1[2],$h1[1],24);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chuck)</h2>
      <p><?php        
        $res = show_facility_year_month($h1[2],$h1[1],33);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    ?></p></td><td><h2>V-North</h2>
      <p><?php        
        $res = show_facility_year_month($h1[2],$h1[1],12);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    ?></p></td><td></td></tr>
            <tr><td>  <h2>LA (UC Division-Tony)</h2>
      <p><?php        
        $res = show_facility_year_month($h1[2],$h1[1],30);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    ?></p></td><td><h2>Arizona (4 Division)</h2>
      <p><?php        
         $res = show_facility_year_month($h1[2],$h1[1],8);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    ?></p></td><td><h2>V-Vis</h2>
      <p><?php        
         $res = show_facility_year_month($h1[2],$h1[1],13);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_may +=$res[1];
        $completed_may +=$res[2];
        $may += $grab_number[1];
    
    ?></p></td><td></td></tr>
    
      </table>
  </div>
  <div id="tabs-3">
    <table>
        <tr><td colspan="" style="text-align: left;" id="june_total_col"></td><td style="text-align: left;" id="june_not_picked"></td><td style="text-align: left;" id="june_completed"></td></tr>
            <tr><td><h2>Coachella (UD Division)</h2>
      <p>
      <?php 
        $june =0;
        $not_picked_june =0;
        $completed_june =0;
        $res = show_facility_year_month($h2[2],$h2[1],23);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
      ?>
      </p></td><td><h2>LA (UC Division-Ramon)</h2>
      <p><?php        
        $res = show_facility_year_month($h2[2],$h2[1],31);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    ?></p></td><td><h2>VSLM (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h2[2],$h2[1],5);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    ?></p></td><td><h2>L-Division</h2>
      <p><?php        
        $res = show_facility_year_month($h2[2],$h2[1],14);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>San Diego (US Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h2[2],$h2[1],22);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chato)</h2>
      <p><?php        
        $res = show_facility_year_month($h2[2],$h2[1],32);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    ?></p></td><td><h2>V-Fres (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h2[2],$h2[1],11);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    ?></p></td><td><h2>W-Division</h2>
      <p><?php        
        $res = show_facility_year_month($h2[2],$h2[1],15);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>LA (UC Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h2[2],$h2[1],24);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chuck)</h2>
      <p><?php        
        $res = show_facility_year_month($h2[2],$h2[1],33);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    ?></p></td><td><h2>V-North</h2>
      <p><?php        
        $res = show_facility_year_month($h2[2],$h2[1],12);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    ?></p></td><td></td></tr>
            <tr><td>  <h2>LA (UC Division-Tony)</h2>
      <p><?php        
        $res = show_facility_year_month($h2[2],$h2[1],30);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    ?></p></td><td><h2>Arizona (4 Division)</h2>
      <p><?php        
         $res = show_facility_year_month($h2[2],$h2[1],8);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    ?></p></td><td><h2>V-Vis</h2>
      <p><?php        
         $res = show_facility_year_month($h2[2],$h2[1],13);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_june +=$res[1];
        $completed_june +=$res[2];
        $june += $grab_number[1];
    
    ?></p></td><td></td></tr>
    
      </table>
  </div>
  <div id="tabs-4">
    <table>
        <tr><td colspan="" style="text-align: left;" id="jul_total_col"></td><td style="text-align: left;" id="jul_not_picked"></td><td style="text-align: left;" id="jul_completed"></td></tr>
            <tr><td><h2>Coachella (UD Division)</h2>
      <p>
      <?php 
        $jul =0;
        $not_picked_july =0;
        $completed_july =0;
        $res = show_facility_year_month($h3[2],$h3[1],23);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
      ?>
      </p></td><td><h2>LA (UC Division-Ramon)</h2>
      <p><?php        
        $res = show_facility_year_month($h3[2],$h3[1],31);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    ?></p></td><td><h2>VSLM (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h3[2],$h3[1],5);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    ?></p></td><td><h2>L-Division</h2>
      <p><?php        
        $res = show_facility_year_month($h3[2],$h3[1],14);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>San Diego (US Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h3[2],$h3[1],22);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
         $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chato)</h2>
      <p><?php        
        $res = show_facility_year_month($h3[2],$h3[1],32);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    ?></p></td><td><h2>V-Fres (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h3[2],$h3[1],11);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    ?></p></td><td><h2>W-Division</h2>
      <p><?php        
        $res = show_facility_year_month($h3[2],$h3[1],15);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>LA (UC Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h3[2],$h3[1],24);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chuck)</h2>
      <p><?php        
        $res = show_facility_year_month($h3[2],$h3[1],33);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    ?></p></td><td><h2>V-North</h2>
      <p><?php        
        $res = show_facility_year_month($h3[2],$h3[1],12);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    ?></p></td><td></td></tr>
            <tr><td>  <h2>LA (UC Division-Tony)</h2>
      <p><?php        
        $res = show_facility_year_month($h3[2],$h3[1],30);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    ?></p></td><td><h2>Arizona (4 Division)</h2>
      <p><?php        
         $res = show_facility_year_month($h3[2],$h3[1],8);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    ?></p></td><td><h2>V-Vis</h2>
      <p><?php        
         $res = show_facility_year_month($h3[2],$h3[1],13);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_july +=$res[1];
        $completed_july +=$res[2];
        $jul += $grab_number[1];
    
    ?></p></td><td></td></tr>
    
      </table>
  </div>
  <div id="tabs-5">
    <table>
        <tr><td colspan="" style="text-align: left;" id="august_total_col"></td><td style="text-align: left;" id="august_not_picked"></td><td style="text-align: left;" id="august_completed"></td></tr>
            <tr><td><h2>Coachella (UD Division)</h2>
      <p>
      <?php 
        $august =0;
        $not_picked_august =0;
        $completed_august =0;
        $res = show_facility_year_month($h4[2],$h4[1],23);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
      ?>
      </p></td><td><h2>LA (UC Division-Ramon)</h2>
      <p><?php        
        $res = show_facility_year_month($h4[2],$h4[1],31);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    ?></p></td><td><h2>VSLM (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h4[2],$h4[1],5);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    ?></p></td><td><h2>L-Division</h2>
      <p><?php        
        $res = show_facility_year_month($h4[2],$h4[1],14);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>San Diego (US Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h4[2],$h4[1],22);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chato)</h2>
      <p><?php        
        $res = show_facility_year_month($h4[2],$h4[1],32);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    ?></p></td><td><h2>V-Fres (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h4[2],$h4[1],11);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    ?></p></td><td><h2>W-Division</h2>
      <p><?php        
        $res = show_facility_year_month($h4[2],$h4[1],15);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>LA (UC Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h4[2],$h4[1],24);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chuck)</h2>
      <p><?php        
        $res = show_facility_year_month($h4[2],$h4[1],33);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    ?></p></td><td><h2>V-North</h2>
      <p><?php        
        $res = show_facility_year_month($h4[2],$h4[1],12);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    ?></p></td><td></td></tr>
            <tr><td>  <h2>LA (UC Division-Tony)</h2>
      <p><?php        
        $res = show_facility_year_month($h4[2],$h4[1],30);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    ?></p></td><td><h2>Arizona (4 Division)</h2>
      <p><?php        
         $res = show_facility_year_month($h4[2],$h4[1],8);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    ?></p></td><td><h2>V-Vis</h2>
      <p><?php        
         $res = show_facility_year_month($h4[2],$h4[1],13);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_august +=$res[1];
        $completed_august +=$res[2];
        $august += $grab_number[1];
    
    ?></p></td><td></td></tr>
    
      </table>
  </div>
  <div id="tabs-6">
    <table>
        <tr><td colspan="" style="text-align: left;" id="september_total_col"></td><td style="text-align: left;" id="september_not_picked"></td><td style="text-align: left;" id="september_completed"></td></tr>
            <tr><td><h2>Coachella (UD Division)</h2>
      <p>
      <?php 
        $september =0;
        $not_picked_september =0;
        $completed_september =0;
        $res = show_facility_year_month($h5[2],$h5[1],23);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
      ?>
      </p></td><td><h2>LA (UC Division-Ramon)</h2>
      <p><?php        
        $res = show_facility_year_month($h5[2],$h5[1],31);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
    ?></p></td><td><h2>VSLM (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h5[2],$h5[1],5);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
    ?></p></td><td><h2>L-Division</h2>
      <p><?php        
        $res = show_facility_year_month($h5[2],$h5[1],14);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>San Diego (US Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h5[2],$h5[1],22);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $september += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chato)</h2>
      <p><?php        
        $res = show_facility_year_month($h5[2],$h5[1],32);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
    ?></p></td><td><h2>V-Fres (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h5[2],$h5[1],11);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
    ?></p></td><td><h2>W-Division</h2>
      <p><?php        
        $res = show_facility_year_month($h5[2],$h5[1],15);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>LA (UC Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h5[2],$h5[1],24);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chuck)</h2>
      <p><?php        
        $res = show_facility_year_month($h5[2],$h5[1],33);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
    ?></p></td><td><h2>V-North</h2>
      <p><?php        
        $res = show_facility_year_month($h5[2],$h5[1],12);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
    ?></p></td><td></td></tr>
            <tr><td>  <h2>LA (UC Division-Tony)</h2>
      <p><?php        
        $res = show_facility_year_month($h5[2],$h5[1],30);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
    ?></p></td><td><h2>Arizona (4 Division)</h2>
      <p><?php        
         $res = show_facility_year_month($h5[2],$h5[1],8);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
    ?></p></td><td><h2>V-Vis</h2>
      <p><?php        
         $res = show_facility_year_month($h5[2],$h5[1],13);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_september +=$res[1];
        $completed_september +=$res[2];
        $september += $grab_number[1];
    
    ?></p></td><td></td></tr>
    
      </table>
  </div>
  <div id="tabs-7">
    <table>
        <tr><td colspan="" style="text-align: left;" id="oct_total_col"></td><td style="text-align: left;" id="oct_not_picked"></td><td style="text-align: left;" id="oct_completed"></td></tr>
            <tr><td><h2>Coachella (UD Division)</h2>
      <p>
      <?php 
        $october =0;
        $not_picked_oct =0;
        $completed_oct =0;
        $res = show_facility_year_month($h6[2],$h6[1],23);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
      ?>
      </p></td><td><h2>LA (UC Division-Ramon)</h2>
      <p><?php        
        $res = show_facility_year_month($h6[2],$h6[1],31);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    ?></p></td><td><h2>VSLM (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h6[2],$h6[1],5);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    ?></p></td><td><h2>L-Division</h2>
      <p><?php        
        $res = show_facility_year_month($h6[2],$h6[1],14);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>San Diego (US Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h6[2],$h6[1],22);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chato)</h2>
      <p><?php        
        $res = show_facility_year_month($h6[2],$h6[1],32);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    ?></p></td><td><h2>V-Fres (V Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h6[2],$h6[1],11);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    ?></p></td><td><h2>W-Division</h2>
      <p><?php        
        $res = show_facility_year_month($h6[2],$h6[1],15);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    ?></p></td></tr>
            <tr><td><h2>LA (UC Division)</h2>
      <p><?php        
        $res = show_facility_year_month($h6[2],$h6[1],24);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    ?></p></td><td><h2>LA (UC Division-Chuck)</h2>
      <p><?php        
        $res = show_facility_year_month($h6[2],$h6[1],33);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    ?></p></td><td><h2>V-North</h2>
      <p><?php        
        $res = show_facility_year_month($h6[2],$h6[1],12);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    ?></p></td><td></td></tr>
            <tr><td>  <h2>LA (UC Division-Tony)</h2>
      <p><?php        
        $res = show_facility_year_month($h6[2],$h6[1],30);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    ?></p></td><td><h2>Arizona (4 Division)</h2>
      <p><?php        
         $res = show_facility_year_month($h6[2],$h6[1],8);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    ?></p></td><td><h2>V-Vis</h2>
      <p><?php        
         $res = show_facility_year_month($h6[2],$h6[1],13);
        echo $res[0];
        $breakdown = explode("<br/>",$res[0]);
        $grab_number = explode(":",$breakdown[2]);
        $not_picked_oct +=$res[1];
        $completed_oct +=$res[2];
        $october += $grab_number[1];
    
    ?></p></td><td></td></tr>
    
      </table>
  </div>
</div>
 <script>
 $("#total_col").html('<?php echo "Current Month Facility totals:".number_format($cur,2); ?>');
 $("#not_picked").html('<?php echo "Total stops Scheduled or Enroute $not_picked"; ?>');
 $("#completed").html('<?php echo "Total Stops completed $completed"; ?>');
 
  $("#may_total_col").html('<?php echo "Current Month Facility totals:".number_format($may,2); ?>');
 $("#may_not_picked").html('<?php echo "Total stops Scheduled or Enroute $not_picked_may"; ?>');
 $("#may_completed").html('<?php echo "Total Stops completed $completed_may"; ?>');
 
  $("#june_total_col").html('<?php echo "Current Month Facility totals:".number_format($june,2); ?>');
 $("#june_not_picked").html('<?php echo "Total stops Scheduled or Enroute $not_picked_june"; ?>');
 $("#june_completed").html('<?php echo "Total Stops completed $completed_june"; ?>');
 
 $("#jul_total_col").html('<?php echo "Current Month Facility totals:".number_format($jul,2); ?>');
 $("#jul_not_picked").html('<?php echo "Total stops Scheduled or Enroute $not_picked_july"; ?>');
 $("#jul_completed").html('<?php echo "Total Stops completed $completed_july"; ?>');
 
 
 $("#august_total_col").html('<?php echo "Current Month Facility totals:".number_format($august,2); ?>');
 $("#august_not_picked").html('<?php echo "Total stops Scheduled or Enroute $not_picked_august"; ?>');
 $("#august_completed").html('<?php echo "Total Stops completed $completed_august"; ?>');
 
 $("#september_total_col").html('<?php echo "Current Month Facility totals:".number_format($september,2); ?>');
 $("#september_not_picked").html('<?php echo "Total stops Scheduled or Enroute $not_picked_september"; ?>');
 $("#september_completed").html('<?php echo "Total Stops completed $completed_september"; ?>');
 
 $("#oct_total_col").html('<?php echo "Current Month Facility totals:".number_format($october,2); ?>');
 $("#oct_not_picked").html('<?php echo "Total stops Scheduled or Enroute $not_picked_oct"; ?>');
 $("#oct_completed").html('<?php echo "Total Stops completed $completed_oct"; ?>');
 </script>
 
</body>
</html>
<?php } ?>