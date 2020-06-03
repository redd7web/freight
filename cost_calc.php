  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?v=3&sensor=true"></script>

  <script>
  $(function() {
    $("#accordion").accordion({ header: "h3", collapsible: true, active: false });
  });

    
    
    
  </script>
  <style type="text/css">
  .ui-accordion-content{
    height:400px;
    overflow-y:overflow;
  }
  </style>
<?php
include "protected/global.php";
ini_set("display_errors",1);
session_start();

if(isset($_GET['clear'])){
    unset($_SESSION['ex']);
}

$fiov = $db->query("SELECT value FROM overhead_value WHERE id=1");





function getDistance($addressFrom, $addressTo, $unit){
	//Change address format
    $formattedAddrFrom = str_replace(' ','+',$addressFrom);
    $formattedAddrTo = str_replace(' ','+',$addressTo);
    
	//Send request and receive json data
    $geocodeFrom = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false');
    $outputFrom = json_decode($geocodeFrom);
    $geocodeTo = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false');
    $outputTo = json_decode($geocodeTo);
    
	//Get latitude and longitude from geo data
    $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
    $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
    $latitudeTo = $outputTo->results[0]->geometry->location->lat;
    $longitudeTo = $outputTo->results[0]->geometry->location->lng;
    
	//Calculate distance from latitude and longitude
    $theta = $longitudeFrom - $longitudeTo;
    $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);
    if ($unit == "K") {
        return number_format(($miles * 1.609344),2).' km ';
    } else if ($unit == "N") {
        return ($miles * 0.8684).' nm ';
    } else {
        return number_format($miles,2).' mi ';
    }
}


//"COALESCE(SUM(paid),NULL,0)  as total_for_fac, facility_origin  FROM freight_grease_data_table WHERE facility_origin IS NOT NULL GROUP BY facility_origin";
$test = $db->query("SELECT DISTINCT(facility_origin) FROM freight_grease_data_table WHERE facility_origin IS NOT NULL AND facility_origin  !=0");
/**/

?>

<form action="cost_calc.php" method="post">
<table style="width: 1000px;margin:auto;margin-top:10px;">
    <tr>
    <td>Account Address &nbsp;<input type="text" id="address1" name="address1"  value="<?php echo $_POST['address1'] ?>"/></td>
    <td>Trap Size&nbsp; <input type="text" id="trap_size" name="trap_size" value="<?php echo $_POST['trap_size']; ?>"/>
   
    <td>
        Home Facility&nbsp;
        <input type="text" name="address2" id="address2" value="<?php echo $_POST['address2']; ?>" readonly="" />
    </td>
    
    <td>Division&nbsp;
        <?php 
        if(isset($_POST['facility'])){
            echo getFacilityList("",$_POST['facility']);
        }else{
            echo getFacilityList("","");
        }
        ?>
    </td>
    <td><a href="cost_calc.php?clear=yes">Clear excludes</a></td>
    <td><input type="submit" name="calc_distance" value="Calculate Distance"/></td>
    </form>
    <tr><td id="ddd" colspan="6">
    <?php    
        if(isset($_POST['calc_distance'])){
            $ex ='';
            if(isset($_POST['exclude'])){
                $_SESSION['ex'][] = $_POST['exclude']; 
            }
            
            if(!empty($_SESSION['ex'])){
                $ex = ' AND grease_no NOT IN('.implode(",",$_SESSION['ex']).')';
            }
            
            echo "Distance between $_POST[address1] and $_POST[address2]: ".getDistance($_POST['address2'],$_POST['address1'],"");
            $lat_long = explode(",",$coords[$_POST['facility']]);
            //print_r($lat_long);
            
            $x = $db->query("SELECT (
(
(
acos( sin( ( $lat_long[0] * pi( ) /180 ) ) * sin( ( freight_accounts.latitude * pi( ) /180 ) ) + cos( ( $lat_long[0] * pi( ) /180 ) ) * cos( ( freight_accounts.latitude * pi( ) /180 ) ) * cos( (
(
$lat_long[1] - freight_accounts.longitude
) * pi( ) /180 ) )
)
) *180 / pi( )
) *60 * 1.1515
) AS dist,
    freight_grease_traps.grease_no,
    freight_grease_traps.account_no,
    freight_grease_traps.grease_route_no,
    freight_grease_traps.volume,
    freight_grease_data_table.inches_to_gallons,
    freight_grease_data_table.driver, 
    freight_grease_data_table.net_mileage,
    freight_grease_data_table.date_of_pickup,
    freight_accounts.Name,
    freight_accounts.longitude,
    freight_accounts.latitude,
    freight_ikg_grease.truck,
    freight_ikg_grease.trailer,
    freight_ikg_grease.other_expense_value,
    freight_ikg_grease.fuel_per_gallon,
    freight_ikg_grease.percent_fluid
 FROM freight_grease_traps 
    INNER JOIN freight_accounts ON 
        freight_grease_traps.account_no = freight_accounts.account_ID 
    INNER JOIN freight_grease_data_table ON 
        freight_grease_data_table.schedule_id = freight_grease_traps.grease_no AND 
        freight_grease_data_table.route_id = freight_grease_traps.grease_route_no AND 
        freight_grease_data_table.account_no = freight_grease_traps.account_no 
    INNER JOIN freight_ikg_grease ON 
        freight_grease_traps.grease_route_no = freight_ikg_grease.route_id
    
    WHERE freight_accounts.division = $_POST[facility] AND route_status='completed' AND freight_grease_traps.completed_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() AND freight_accounts.grease_volume>200 $ex HAVING dist <=15 ");
            if(count($x)>0){
               $uuuuuu = 0;
                foreach($x as $log){
                    $truck = new Vehicle($log['truck']);
                    $person = new Person($log['driver']);
                    $trailer = new Trailer($log['trailer']);

                    $net_gallons = $log['volume'];
                    //************************* DAY 1***************************************************************//      
                    $total_hours =  time_end($log['grease_route_no'],1) - time_start($log['grease_route_no'],1);
                    
                    
                    
                    
                    
                    
                    if($total_hours>8){
                        $reg_hours = 8;
                    } else {
                        $reg_hours = $total_hours;
                    }
                    
                  
                    if($total_hours >12){
                        $dt = $total_hours - 12;
                    } else {
                        $dt = 0;
                    }
                    
                    
                    if($total_hours>8){
                        $ot = $total_hours - 8 - $dt;
                    }else{
                        $ot = 0;
                    }
                    
                    
                    $fs_stop = start_time_from_date($log['grease_route_no'],1);
                    $ls_stop = end_time_from_date($log['grease_route_no'],1);
                    $total_day_1 = $ls_stop - $fs_stop;//total pickup hours
                    
                    //*************************Day 1********************************************************//
                    
                    
                    
                    //***************************************Day 2******************************************//
                    
                    $total_hours2 =  time_end($log['grease_route_no'],2) - time_start($log['grease_route_no'],2);
                    
                    
                    
                    if($total_hours2>8){
                        $reg_hours2 = 8;
                    } else {
                        $reg_hours2 = $total_hours2;
                    }
                    
                    if($total_hours2 >12){
                        $dt2 = $total_hours2 - 12;
                    } else {
                        $dt2 = 0;
                    }
                    
                    
                    
                    if($total_hours2>8){
                        $ot2 = $total_hours2 - 8 - $dt2;
                    }else{
                        $ot2 = 0;
                    }              
                    
                    if($truck->mpg >0){
                        $avg_fuel = $log['net_mileage']/$truck->mpg;
                    } else {
                        $avg_fuel =0;
                    }   
                    $fuel_cost = $avg_fuel*$log['fuel_per_gallon'];
                    $water_treatment_cost =solids_table($log['percent_fluid']) * $net_gallons;
                    if(count($fiov)>0 && $net_gallons >0){                       
                       $over_head = ($fiov[0]['value']/ count($x))/$net_gallons;
                    }else {
                       $over_head = 0;
                    }
                     $labor_cost =( ($reg_hours*$person->driver_hourly_pay)+($ot*$person->driver_hourly_pay*1.5)+($dt*$person->driver_hourly_pay*2) );
                    $variable_operating = variable_operating($labor_cost,$log['net_mileage'],$truck->mpg,$net_gallons,$truck->r_m,$trailer->r_m,$fuel_cost,($truck->dep/21)/10,$water_treatment_cost,($trailer->dep/21)/10,$log['other_expense_value'],$reg_hours);
                    //***************************************Day 2******************************************//
                    
                    if( number_format( $over_head + $variable_operating ,2) <=0.00 ){ 
                        //echo "<span style='color:red;font-weight:bold;'>". number_format( $over_head + $variable_operating ,2)."</span>&nbsp;";  
                    } else { 
                        $list[] = "<tr>
                                        <td>$log[Name]</td>
                                        <td>".$log['account_no']."</td>
                                        <td>$log[grease_no]</td>
                                        <td>$log[grease_route_no] </td>
                                        <td>".number_format( $over_head + $variable_operating ,2)."</td>
                                        <td><form action='cost_calc.php' method='post' style='float:left;'>
                        <input type='hidden' value='$_POST[facility]' name='facility' /> 
                        <input type='hidden' value='$_POST[address1]' name='address1'/>
                        <input type='hidden' value='$_POST[address2]' name='address2'/>
                        <input type='hidden' value='$log[grease_no]'  name='exclude'/>
                        <input type='submit' style='background:url(img/delete-icon.jpg) no-repeat left top;width:25px;height:25px;float:left;cursor:pointer;' value='' name='calc_distance'/> 
                        
                        <input type='hidden' /></form></td></tr>";
                       $uuuuuu += ($over_head + $variable_operating);
                  } 
                    
                }
                echo "<br/>Average Cost per gallons for Stops: ".number_format( $uuuuuu/count($x),2)."<br/><br/>";
                
                
            }
    }
    ?>
    </td></tr>
    <tr><td>Average Cost Per Gallon:&nbsp;<input type="text" id="avg" placeholder="Average Cost Per Gallon" value="<?php echo number_format( $uuuuuu/count($x),2); ?>" readonly=""/></td><td>Cost per Pickup:&nbsp;<input placeholder="Cost Per Pickup" value="<?php echo number_format($_POST['trap_size'] * (  $uuuuuu/count($x) ),2);  ?>" type="text"/></td><td>Marginal %:&nbsp;<input type="text" placeholder="Marginal %" id="margin"/></td><td>Optimal PPG:&nbsp;<input placeholder="Optimal PPG" type="text" id="optimal" readonly=""/></td></tr>
    <tr><td colspan="6"><?php //echo "Average total cost/gal for facility: ".$sum/count($x); ?></td></tr>
    <tr><td colspan="6"><?php 
                echo "<table>";
                echo "<tr>
                        <td>Account Name</td>
                        <td>Account #</td>
                        <td>Schedule Id</td>
                        <td>Route Id</td>
                        <td>Cost/Gal</td>
                        <td>&nbsp;</td>
                    </tr>";
                foreach($list as $k){
                    echo $k;
                } 
                echo "</table>";
    ?></td></tr>
</table>

<?php


echo '<div id="accordion">';
foreach($test as $facs){
     $tot = 0;
     $expected =0;
     $kcxa = $db->query("SELECT freight_grease_traps.volume,freight_grease_traps.payment_method,inches_to_gallons,ppg,volume,freight_grease_data_table.account_no,freight_grease_data_table.paid FROM freight_grease_data_table LEFT JOIN freight_grease_traps ON freight_grease_data_table.account_no = freight_grease_traps.account_no AND freight_grease_data_table.schedule_id = freight_grease_traps.grease_no AND freight_grease_data_table.route_id = freight_grease_traps.grease_route_no  WHERE freight_grease_data_table.facility_origin = $facs[facility_origin] ");
     if(count($kcxa)>0){

        foreach($kcxa as $stops){
            $tot += $stops['paid'];
        
            
            switch($stops['payment_method']){
                case"Per Gallon":
                    
                    $expected += $stops['volume'] * $stops['ppg'];            
                    break;
                case "Charge Per Pickup":
                    
                    $expected += $stops['ppg'];                    
                    break;
                case "Normal";
                   
                     $expected +=0;
                    break;
                default:
                    
                     $expected +=0;
                    break;
            }
        }
     }
     
    echo "<h3 rel ='$facs[facility_origin]'> $ ".number_format($tot,2)." / $".number_format($expected,2)." ".numberToFacility($facs['facility_origin'])." - expand to see individual pickups</h3>
         <div>
         </div>
    ";
}
echo '</div>';
?>
<script>

$("#accordion").accordion({ activate: function(event, ui) {
      $.get("indiviual_pickups.php",{fac:ui.newHeader.attr('rel')},function(data){
           $(ui.newHeader).next("div").html('<table style="table-layout:fixed;width:1600px;"><tr><td>'+data+'</td></tr></table>');  
      });
  }
});

$("#facility").change(function(){
    $.post("getFacilAddress.php",{facil: $(this).val() },function(data){
        $("input#address2").val(data);
    });
});

$("input#margin").change(function(){
    var y = ( $("input#margin").val() * $("input#avg").val() ) +   ($("input#avg").val() *1) ;
    $("input#optimal").val( y.toPrecision(2) );
});

</script>