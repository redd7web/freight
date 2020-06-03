<?php

ini_set("display_errors",0);
ini_set('memory_limit', '-1');

$string ="";
$x= "";
if(isset($_GET['clear'])){
    unset($_SESSION['exlude_list']);
}

if(isset($_SESSION['freight_id'])){
    $self = new Person();
}





if(isset($_POST['search_now'])){
    
    foreach($_POST as $name=>$value){
        switch($name){
            case "facility":
                if($value !="ignore"){
                    if ( $value == 99){
                        $arrField[] = " freight_ikg_grease.recieving_facility IN(24,31,30,32,33)";
                    }else{
                        $arrField[] = " freight_ikg_grease.recieving_facility = $value";
                    }
                }
            break;
            case "drivers":
                if($value !="-"){
                    $arrField[] = "freight_ikg_grease.driver = $value";
                }
            break;
            case "to":
                if(strlen($value)>0 && $value !="" && $value !=" "){
                    $arrField[] = "DATE(freight_rout_history_grease.start_date) <='$value'";
                }
            break;
            case "from":
                if(strlen($value)>0 && $value !="" && $value !=" "){
                    $arrField[] = "DATE(freight_rout_history_grease.start_date) >='$value'";
                }
            break;
        }
    }
    
    if(!empty($arrField)){
        $string = "AND ".implode(" AND ",$arrField);
    }
    $exl ="";
    if(isset($_POST['rid'])){
        $_SESSION['exlude_list'][] = $_POST['rid'];
        $_SESSION['exlude_list'] = array_unique($_SESSION['exlude_list']);
        if(!empty($_SESSION['exlude_list'])){
            $string .= $exl = " AND freight_ikg_grease.route_id NOT IN (".implode(",",$_SESSION['exlude_list']).")";
        }    
    }
    
    
    
    
    
    
    $x = $db->query("SELECT 
                    freight_list_of_grease.stops,
                    freight_ikg_grease.route_id,
                    freight_ikg_grease.ikg_manifest_route_number,
                    COALESCE(freight_ikg_grease.net_weight,NULL,0.00) as net_weight,
                    COALESCE(freight_ikg_grease.tare_weight,NULL, 0.00) as tare_weight,
                    freight_ikg_grease.tank1,
                    freight_ikg_grease.tank2,
                    COALESCE(freight_ikg_grease.gross_weight,NULL,0.00) as gross_weight,
                    freight_ikg_grease.end_mileage,
                    freight_ikg_grease.start_mileage,
                    freight_ikg_grease.fuel,
                    freight_ikg_grease.route_id,
                    freight_ikg_grease.end_time,
                    freight_ikg_grease.time_start,
                    freight_ikg_grease.driver,
                    freight_ikg_grease.inventory_code,
                    freight_ikg_grease.truck,
                    freight_ikg_grease.other_expense_desc, 
                    freight_ikg_grease.route_notes,
                    freight_ikg_grease.percent_fluid,  
                    freight_ikg_grease.trailer,   
                    COALESCE(freight_ikg_grease.other_expense_value,NULL,0.00) as other_expense_value,    
                    freight_ikg_grease.fuel_per_gallon,         
                    TIME(freight_ikg_grease.first_stop) as Time FROM freight_list_of_grease  LEFT JOIN freight_ikg_grease  ON freight_ikg_grease.route_id = freight_list_of_grease.route_id LEFT JOIN freight_rout_history_grease ON freight_rout_history_grease.route_no = freight_ikg_grease.route_id  WHERE freight_list_of_grease.status IN ('completed') AND freight_ikg_grease.route_id >0 AND freight_ikg_grease.route_id IS NOT NULL $string
");
} 


?>
<style type="text/css">
.tableNavigation {
    width:1000px;
    text-align:center;
    margin:auto;
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

tr.odd{
    background:transparent;
}
.setThisRoute{ 
    z-index:9999;
}
</style>
<script>

$(document).ready(function(){
   $('#myTable').dataTable({
        "lengthMenu": [ [500], [500] ]
   }); 
});
</script> 
 
<table style="width:800px;margin:auto;border: 1px solid #bbb;margin-top:10px;">
<tr><td style="vertical-align: top;">Driver</td><td  style="vertical-align: top;"><form action="management.php?task=driverslog" method="post"><?php if(isset($_POST['search_now'])){ 
    getDrivers($_POST['drivers']);
} else {
    getDrivers("");
} ?></td><td  style="vertical-align: top;">Scheduled Start Date</td><td  style="vertical-align: top;"><input type="text" value="<?php if(isset($_POST['search_now'])){
    echo $_POST['from'];
    
} ?>" placeholder="From"  name="from" id="from"/><br /><input type="text" placeholder="To" value="<?php 
        if(isset($_POST['search_now'])){
            echo $_POST['to'];
        }
 ?>" name="to" id="to"/></td></tr>
<tr><td  style="vertical-align: top;">Recieving Facility</td><td  style="vertical-align: top;"><?php if(isset($_POST['search_now'])){  
    getFacilityList("",$_POST['facility']);
    
}else {
    getFacilityList("","");
} ?></td></tr>
<tr><td colspan="3">&nbsp;</td><td  style="text-align: right;"><a href="management.php?task=driverslog&clear=1">Default Data View</a>&nbsp;<input type="submit" name="search_now" value="Search Now"/></form>

</td></tr>

<tr>
    <td>Daily Supply</td>
    <td><input type="text" value="<?php $jhcv = $db->query("SELECT COALESCE(value,NULL,0) as value FROM overhead_value WHERE id =2"); echo $jhcv[0]['value'];  ?>" name="d_supply" id="d_supply"/></td>
    <td>Worker's Comp</td>
    <td><input type="text" id="w_comp" name="w_comp" value="<?php $jhcv = $db->query("SELECT COALESCE(value,NULL,0) as value FROM overhead_value WHERE id =3"); echo number_format($jhcv[0]['value'],2);  ?>"/></td>
</tr>



<form action="export_log.php" method="post" target="_blank">
<tr><td>&nbsp;</td><td>&nbsp;</td><td>Export: </td><td><select name="format"><option value="csv">CSV</option><option value="xls">XLS</option></select>&nbsp;<input type="hidden" value="<?php echo $string; ?>" readonly="" id="params" name="params"/><input type="submit" value="Export Now" name="exp_log" id="exp_log"/></td></tr>
</form>
</table>



<table id="myTable" style="width: 2500px;background:white;">
    <thead>
    <tr>
       
        <td class="cell_label" colspan="17" style="background: yellow;text-align:center;vertical-align:center;"> Day 1</td>
        <td class="cell_label"  style='background:black;padding:0px 0px 0px 0px;'>&nbsp;</td>
        <td class="cell_label"  colspan="17" style="background: yellow;text-align:center;vertical-align:center;"> Day 2</td>
        <td class="cell_label"  style='background:black;padding:0px 0px 0px 0px;'>&nbsp;</td>
        <td class="cell_label"  colspan="8" style="background: white;text-align:center;vertical-align:center;">&nbsp;</td>
        <td class="cell_label"  style='background:black;padding:0px 0px 0px 0px;'>&nbsp;</td>     
        <td class="cell_label"  colspan="42" style="background: gray;text-align:center;vertical-align:center;">&nbsp;</td>   
    </tr>
    <tr>
        
        <td class="cell_label" >Driver</td>
        <td class="cell_label" >Date</td>
        <td class="cell_label" >Start Time</td>
        <td class="cell_label" >End Time</td>
        <td class="cell_label" >Total Hrs</td>
        <td class="cell_label" >Reg Hrs</td>
        <td class="cell_label" >O.T. Hours</td>
        <td class="cell_label" >D.T. Hours</td>
        <td class="cell_label" >First Stop</td>
        <td class="cell_label" >Last Stop</td>
        <td class="cell_label" >Total P/U Hrs</td>       
        <td class="cell_label" >Start Mileage</td>
        <td class="cell_label" >End Mileage</td>
        <td class="cell_label" >Total Mileage</td>
        <td class="cell_label" >1st Stop Mileage</td>
        <td class="cell_label" >Last Stop Mileage</td>
        <td class="cell_label" >Total P/U Mileage</td>
        <td class="cell_label"  style='background:black;padding:0px 0px 0px 0px;'>&nbsp;</td>
        
        <td class="cell_label" >Driver</td>
        <td class="cell_label" >Date</td>
        <td class="cell_label" >Start Time</td>
        <td class="cell_label" >End Time</td>
        <td class="cell_label" >Total Hrs</td>
        <td class="cell_label" >Reg Hrs</td>
        <td class="cell_label" >O.T. Hours</td>
        <td class="cell_label" >D.T. Hours</td>
        <td class="cell_label" >First Stop</td>
        <td class="cell_label" >Last Stop</td>
        <td class="cell_label" >Total P/U Hrs</td>       
        <td class="cell_label" >Start Mileage</td>
        <td class="cell_label" >End Mileage</td>
        <td class="cell_label" >Total Mileage</td>
        <td class="cell_label" >1st Stop Mileage</td>
        <td class="cell_label" >Last Stop Mileage</td>
        <td class="cell_label" >Total P/U Mileage</td>
        <td class="cell_label"  style='background:black;padding:0px 0px 0px 0px;'>&nbsp;</td>
        
        
        <td class="cell_label" >Total Hrs</td>
        
        <td class="cell_label">Labor Cost</td>
        <td class="cell_label" >Reg Hrs</td>
        <td class="cell_label" >O.T. Hours</td>
        <td class="cell_label" >D.T. Hours</td>
        <td class="cell_label" >Total P/U Hrs</td>
        <td class="cell_label" >Total Mileage</td>
        <td class="cell_label" >Total P/U Mileage</td>
        <td style='background:black;padding:0px 0px 0px 0px;'>&nbsp;</td>
        
        
        <td class="cell_label" >Fuel Gal</td>
        <td class="cell_label" >Fuel $/ Gal</td>
        <td class="cell_label" >Fuel Cost</td> 
        <td class="cell_label" >Invent Loc.</td>
        <td class="cell_label" >Route</td>
        <td class="cell_label" >Truck</td>
        <td class="cell_label" >Gross Weight</td>
        <td class="cell_label" >Light Weight</td>
        <td class="cell_label" >Net Weight</td>
        <td class="cell_label" >Net Gallons</td>
        <td class="cell_label" >Treatment Rate/Gal</td>
        <td class="cell_label" >Water Treatment Cost</td>
        <td class="cell_label" >Truck Total Cost</td>
        <td class="cell_label" >Trailer Total Cost</td>
        <td class="cell_label" >Total Stops</td>
        <td class="cell_label" >Percent Solids</td>
        <td class="cell_label" >Zero Yield Stops</td>
        <td class="cell_label" >E Stops</td>
        <td class="cell_label" >skipped</td>
        <td class="cell_label" >Other Expenses</td>
        <td class="cell_label" >Avg Gal/Stops</td>        
        <td class="cell_label" >Avg Miles/Stops</td>
        <td class="cell_label" >Avg Min/Stops</td>
        <td class="cell_label" >Truck Monthly Depre Cost</td>
        <td class="cell_label" >Truck Hourly Depre Cost</td>
        <td class="cell_label" >Truck R&M Per Mile </td>
        <td class="cell_label">Truck Total R&M Cost</td>
        <td class="cell_label">Trailer Monthly Depre Cost</td>
        <td class="cell_label">Trailer Hourly Depre Cost</td>
        <td class="cell_label">Trailer R&M per Mile</td>
        <td class="cell_label">Trailer Total R&M Cost</td>
        <td class="cell_label" >Variable Operating Costs/gal.</td>
        <td class="cell_label" >Fixed Overhead Cost/Gal<br />
            <input style="width: 50px;" type="text" id="overhead" name="overhead" value="<?php 
            $fiov = $db->query("SELECT value FROM overhead_value WHERE id=1");
            if(count($fiov)>0){
                echo $fiov[0]['value'];
            }else {
                echo 0;
            }
        ?>"/><br />
        <input style="width: 50px;" type="text" id="over2" name="over2" value="<?php 
            
            echo count($x);
        
        ?>"/>
        
        </td>
        <td class="cell_label" >Total Cost/Gal</td>
        <td class="cell_label" >Total Cost</td>
        <td class="cell_label" >Billed Per Gallon</td>
        <td class="cell_label" >Paid Amount</td>
        <td class="cell_label">TOTAL BILLED AMOUNT</td>
         <td class="cell_label" >Net Income</td>
        <td class="cell_label" >Net Income(Fully Recieved)</td>
        <td  class="cell_label">Income % of Billed</td>
        <td class="cell_label">Route Notes</td>
    </tr>
    </thead>
    <tbody>
    <?php
    if(count($x)>0){
        $sum_total_hours1 = 0;
        $sum_reg_hours1 = 0;
        $sum_dt_hours1 = 0;
        $sum_ot1 = 0;
        $sum_pu_hours1 = 0;
        $sum_total_mileage1 = 0;
        $sum_pu_mileage1 = 0;
        $sum_total_hours2= 0;
        $sum_reg_hours2 = 0;
        $sum_dt_hours2 = 0;
        $sum_ot_hours2 = 0;
        $sum_total_mileage2 = 0;
        $sum_pu_mileage2 = 0;
        $sum_hours_all = 0;
        $sum_reg_all = 0;
        $sum_ot_all = 0;
        $sum_dt_all = 0;
        $sum_pu_hours_all = 0;
        $sum_all_milaege = 0;
        $sum_pu_mileage_all = 0;
        $sum_avg_miles = 0;
        $sum_gal_per_stop = array();
        $sum_avg_hours_stops = 0;
        $sum_fuel = 0;
        $sum_net_weight = 0;
        $sum_stops = 0;
        $sum_zero = 0;
        $sum_emergency = 0;
        $sum_no_oil = 0;
        $sum_expense = 0;
        $sum_var_op = 0;
        $sum_over_head = 0;
        $sum_total_cost = 0;
        foreach($x as $log){
            $truck = new Vehicle($log['truck']);
            $person = new Person($log['driver']);
            $trailer = new Trailer($log['trailer']);
            $net_gallons = $log['net_weight']/8.34;
            
            

            if($log['driver'] !=0){
               $driver = $person->first_name." ".$person->last_name;
            } else {
                $driver = "N/A";
            }
            $zero = $db->query("SELECT distinct(schedule_id) FROM freight_grease_data_table WHERE route_id = $log[route_id] AND inches_to_gallons = 0");
            
            $skipped = $db->query("SELECT DISTINCT(schedule_id) FROM freight_grease_data_table WHERE route_id=$log[route_id] AND zero_gallon_reason IN(12,14,16)");
            
            $emergency = $db->query("SELECT DISTINCT(schedule_id) FROM freight_grease_data_table WHERE route_id=$log[route_id] AND zero_gallon_reason =99");
            
            $no_oil = $db->query("SELECT DISTINCT(schedule_id) FROM freight_grease_data_table WHERE route_id=$log[route_id] AND zero_gallon_reason = 10");
            
            //************************* DAY 1***************************************************************//      
            $total_hours =  time_end($log['route_id'],1) - time_start($log['route_id'],1);
            
            
            
            $sum_total_hours1 +=$total_hours;
            
            
            if($total_hours>8){
                $reg_hours = 8;
            } else {
                $reg_hours = $total_hours;
            }
            
            $sum_reg_hours1 += $reg_hours;
            if($total_hours >12){
                $dt = $total_hours - 12;
            } else {
                $dt = 0;
            }
            $sum_dt_hours1 +=$dt;
            
            if($total_hours>8){
                $ot = $total_hours - 8 - $dt;
            }else{
                $ot = 0;
            }
            
            $sum_ot1 +=$ot;
            $fs_stop = start_time_from_date($log['route_id'],1);
            $ls_stop = end_time_from_date($log['route_id'],1);
            $total_day_1 = $ls_stop - $fs_stop;//total pickup hours
            $sum_pu_hours1 +=$total_day_1;
            $total_mileage = end_mileage($log['route_id'],1) - start_mileage($log['route_id'],1);
            
            
            $sum_total_mileage1 +=$total_mileage;
            
            if(last_stop_mileage($log['route_id'],1)  ==  first_stop_mileage($log['route_id'],1) ){
                $total_pu_mileage = last_stop_mileage($log['route_id'],1) - start_mileage($log['route_id'],1);
            } else {
                $total_pu_mileage = last_stop_mileage($log['route_id'],1) - first_stop_mileage($log['route_id'],1);
            }
            
            $sum_pu_mileage1 +=$total_pu_mileage;
            //*************************Day 1********************************************************//
            
            
            
            //***************************************Day 2******************************************//
            
            $total_hours2 =  time_end($log['route_id'],2) - time_start($log['route_id'],2);
            
            $sum_total_hours2 +=$total_hours2;
            
            if($total_hours2>8){
                $reg_hours2 = 8;
            } else {
                $reg_hours2 = $total_hours2;
            }
            
            
            
            $sum_reg_hours2 +=$reg_hours;
            
            if($total_hours2 >12){
                $dt2 = $total_hours2 - 12;
            } else {
                $dt2 = 0;
            }
            
            $sum_dt_hours2 +=$dt2;
            
            if($total_hours2>8){
                $ot2 = $total_hours2 - 8 - $dt2;
            }else{
                $ot2 = 0;
            }
            
            $sum_ot_hours2 +=$ot2;
            
            $total_day_2 = end_time_from_date($log['route_id'],2) - start_time_from_date($log['route_id'],2);
            
            $sum_total_hours2 +=$total_day_2;
            
            
            
            $total_mileage2 = end_mileage($log['route_id'],2) - start_mileage($log['route_id'],2);
            
            $sum_total_mileage2 +=$total_mileage2;
            $total_pu_mileage2 = last_stop_mileage($log['route_id'],2) - first_stop_mileage($log['route_id'],2);
            $sum_pu_mileage2 += $total_pu_mileage2;
            
            //***************************************Day 2******************************************//
            
            $all_hours = $total_hours+$total_hours2;
            $sum_hours_all +=$all_hours;
            
            $all_reg = $reg_hours + $reg_hours2;
            $sum_reg_all +=$all_reg;
            
            $all_ot = $ot+$ot2;
            $sum_ot_all +=$all_ot;
            
            $all_dt = $dt+$dt2;
            $sum_dt_all +=$all_dt;
            
            $total_pu_hours = total_hours($log['route_id'],1) + total_hours($log['route_id'],2);
            $sum_pu_hours_all += $total_pu_hours;
            
            
            
            
            $all_mileage = $total_mileage+$total_mileage2;
            $sum_all_milaege +=$all_mileage;
            
            $all_pu_mileage = $total_pu_mileage+$total_pu_mileage2;
            $sum_pu_mileage_all +=$all_pu_mileage;
             
            $skip = $db->query("SELECT DISTINCT(schedule_id) FROM freight_grease_data_table WHERE route_id=$log[route_id] AND zero_gallon_reason !=0");
            $avg_lb_stop = lb_per_stop($log['net_weight'],$log['stops']);
            $avg_fuel = $all_mileage/$truck->mpg;            
            //$fuel_per_money = avg_fuel_money($log['net_weight'],$log['stops']); 
            $avg_miles_stop = avg_miles_per_stop($total_pu_mileage,$log['stops']);            
            $sum_avg_miles +=$avg_miles_stop;
            
            
            
            $avg_hours_stops = avg_hours_stop($log['time_start'],$total_pu_hours,$log['stops']);
            $sum_avg_hours_stops +=$avg_hours_stops;
            
            
            $truck_total_cost = ( (($truck->dep/21)/10) * $all_hours ) +  ( $truck->r_m *  $all_mileage ); 
            $trailer_total_cost =( (($trailer->dep/21)/10) * $all_hours ) +  ( $trailer->r_m *  $all_mileage );
            $water_treatment_cost =solids_table($log['percent_fluid']) * $net_gallons;
            
                
        echo "<tr>
        <td>$driver</td>
        <td>".start_date($log['route_id'],1)."</td>
        <td>".time_start($log['route_id'],1)."</td>
        <td>".time_end($log['route_id'],1)."</td>
        <td>$total_hours</td>
        <td>$reg_hours</td>
        <td>$ot</td>
        <td>$dt</td>
        <td>".start_time_from_date($log['route_id'],1)."</td>
        <td>".end_time_from_date($log['route_id'],1)."</td>
        <td>$total_day_1</td>
        <td>".start_mileage($log['route_id'],1)."</td>
        <td>".end_mileage($log['route_id'],1)."</td>
        <td>$total_mileage</td>
        <td>".first_stop_mileage($log['route_id'],1)."</td>
        <td>".last_stop_mileage($log['route_id'],1)."</td>
        <td>$total_pu_mileage</td>
        
        
        <td style='background:black;padding:0px 0px 0px 0px;'>&nbsp;</td>
        
        
                
        <td>$driver</td>
        <td>".start_date($log['route_id'],2)."</td>
        <td>".time_start($log['route_id'],2)."</td>
        <td>".time_end($log['route_id'],2)."</td>
        <td>$total_hours2</td>
        <td>$reg_hours2</td>
        <td>$ot2</td>
        <td>$dt2</td>
        <td>".start_time_from_date($log['route_id'],2)."</td>
        <td>".end_time_from_date($log['route_id'],2)."</td>
        <td>$total_day_2</td>        
        <td>".start_mileage($log['route_id'],2)."</td>
        <td>".end_mileage($log['route_id'],2)."</td>
        <td>$total_mileage2</td>
         <td>".first_stop_mileage($log['route_id'],2)."</td>
        <td>".last_stop_mileage($log['route_id'],2)."</td>
        <td>$total_pu_mileage2</td>
        <td style='background:black;padding:0px 0px 0px 0px;'>&nbsp;</td>
        
        
        
        <td>$all_hours</td>
        <td>"; 
        
            $labor_cost =( ($reg_hours*$person->driver_hourly_pay)+($ot*$person->driver_hourly_pay*1.5)+($dt*$person->driver_hourly_pay*2) );
            if( $self->isPayroll() ) {    
                echo number_format($labor_cost,2);
           } else {
                echo "Payroll Manager<br/> Only";
           }
        echo "</td>
        <td>$all_reg</td>
        <td>$all_ot</td>
        <td>$all_dt</td>
        <td>$total_pu_hours</td>
        <td>$all_mileage</td>
        <td>$all_pu_mileage</td>
        <td style='background:black;padding:0px 0px 0px 0px;'>&nbsp;</td>
        <td>$avg_fuel</td>
        <td>
            <input type='text' class='fpm' value='".number_format($log['fuel_per_gallon'],2)."' rel='$log[route_id]' style='width:40px;'/>
        </td>";
            $fuel_cost = $avg_fuel*$log['fuel_per_gallon'];
            $sum_fuel +=$fuel_cost; 
        echo "<td>".number_format($fuel_cost,2)."</td>
        <td>$log[inventory_code]</td>
        <td>
            <form method='post' target='_blank' action='grease_ikg.php' class='form'>
            <span style='cursor:pointer;text-decoration:underline;'>$log[ikg_manifest_route_number]</span>
            <input type='hidden' name='util_routes' value='$log[route_id]'/>
            <input type='hidden' name='from_routed_grease_list' value='1'/>
            </form>
            <br/>
            
            <form action='management.php?task=driverslog' method='post'>
            <input type='hidden' name='rid' value='$log[route_id]'/>
            <input type='hidden' id='driver' name='drivers' value='$_POST[drivers]'/>
            <input type='hidden' id='to' name='to' value='$_POST[to]'/>
            <input type='hidden' id='from' name='from' value='$_POST[from]'/>
            <input type='hidden' id'fac' name='facility' value='$_POST[facility]'/>
            <input type='submit' value='Exclude Route' name='search_now'/>
            </form>
        </td>
        <td>".vehicle_name($log['truck'])."</td>
        <td>$log[gross_weight]</td>
        <td>$log[tare_weight]</td>
        <td>$log[net_weight]</td>";
        $sum_net_weight +=$log['net_weight'];
        
        
        echo "<td>"./* gallons*/number_format($net_gallons,2)/* gallons*/."</td>
        <td>".solids_table($log['percent_fluid'])."</td>
        <td>"; //water treatment cost
            echo number_format( $water_treatment_cost ,2);
        echo "</td>
        <td>";//truck total cost
            echo number_format($truck_total_cost,2);
        echo "</td>
        <td>"; // trailer total cost
            echo number_format($trailer_total_cost,2);
        echo "</td>
        <td>$log[stops]</td>";
        echo "<td>$log[percent_fluid]</td>";
        $sum_stops +=$log['stops'];
        
        //# zero stops
        echo "<td>".count($zero)."</td>"; 
        $sum_zero += count($zero);
        //# emergency stops
        echo "<td>".count($emergency)."
        </td>"; 
        $sum_emergency +=count($emergency);
        //# no grease
        echo "<td>".count($no_oil)."</td>"; 
        $sum_no_oil +=count($no_oil);
        echo "<td><input style='width:50px;' type='text' placeholder='Other Expense Price' class='other_expense_value' rel='$log[route_id]' value='".number_format($log['other_expense_value'],2)."' /><br/>
        <textarea style='width:150px;' class='expense_desc' placeholder='Expense Description'  rel='$log[route_id]'>$log[other_expense_desc]</textarea></td>";
        $sum_expense += $log['other_expense_value'];
        echo "
        <td>"; 
         $gal_per_stop = $net_gallons/$log['stops'];
         $sum_gal_per_stop[] =$gal_per_stop;
        echo number_format($gal_per_stop,2)."</td>        
        <td>$avg_miles_stop</td>
        <td>"./*avg min per stop*/number_format($avg_hours_stops,2)./*avg min per stop*/"</td>
        <td>"./*Truck Depreciation*/$truck->dep./*Truck Depreciation*/"</td>
        <td>"./*Truck Hourly Depre Cost */number_format(($truck->dep/21)/10,2)/*Truck Hourly Depre Cost */."</td>
        <td>"./*Truck R&M Per Mile */number_format($truck->r_m,2)./*Truck R&M Per Mile */"</td>
        <td>"./*Truck Total R&M Cost*/number_format($truck->r_m*$all_mileage,2)/*Truck Total R&M Cost*/."</td>
        
        <td>"./*Trailer Depreciation*/$trailer->dep/*Trailer Depreciation*/."</td>
        <td>"./*Trailer Hourly Depre Cost */number_format( ($trailer->dep/21)/10,2 )/*Trailer Hourly Depre Cost */."</td>
        <td>"./*Trailer R&M Per Mile */number_format($trailer->rm_total,1)/*Trailer R&M Per Mile */."</td>
        <td>"./*Trailer Total R&M Cost*/number_format($trailer->rm_total*$all_mileage,1)/*Trailer Total R&M Cost*/."</td>";
        
        $variable_operating = variable_operating($labor_cost,$total_mileage,$truck->mpg,$net_gallons,$truck->r_m,$trailer->r_m,$fuel_cost,($truck->dep/21)/10,$water_treatment_cost,($trailer->dep/21)/10,$log['other_expense_value'],$reg_hours);
        echo"<td>"./*variable operting cost*/number_format($variable_operating,4)/*variable operting cost*/."</td>";
        
        $sum_var_op +=number_format($variable_operating,4);
        
        //fixed overhead
        $over_head = ($fiov[0]['value']/ count($x))/$net_gallons   ;
        $sum_over_head +=$over_head;
        echo "<td>".number_format($over_head,2)."</td>";//fixed overhead
        //overhead
        
        echo "<td>"; //Total Cost/Gal
       
        if($net_gallons>0){
            echo number_format( $over_head + $variable_operating ,2);
        } else {
            echo "0";
        }
        echo"</td>";
        echo "<td>"; //Total Cost
                $total_cost = ($over_head + $variable_operating)*$net_gallons;
            echo number_format( $total_cost ,2  );
        echo "</td>
        <td>"; //billed/gallon
            if($net_gallons>0){
                echo number_format(charged_amount($log['route_id'])/  $net_gallons,2);
            } else {
                echo number_format(0,2);
            }
        
        echo "</td>
        <td>"; //paid amount
            $yu = $db->query("SELECT COALESCE(SUM(total_price),NULL,0.00) as total_charge FROM freight_pay_trace WHERE route_id=$log[route_id] AND status =1");
            echo $yu[0]['total_charge'];
            $total_price = $yu[0]['total_charge'];
        echo "</td>
        <td>".//total billed amount 
            number_format(charged_amount($log['route_id']),2)
            ."</td> 
            <td>".number_format($total_price - $total_cost,2)."</td>
        <td>"; //Net Income
            echo number_format( charged_amount($log['route_id']) - $total_cost  ,2) ;
        echo "</td>";
        echo "<td>"; 
            if(charged_amount($log['route_id']) >0){
                echo number_format(( charged_amount($log['route_id']) - $total_cost )/ charged_amount($log['route_id']),2);
            }else {
                echo number_format(0,2);
            }
        
        echo "</td>";
        echo "<td>$log[route_notes]</td>";
        echo "</tr>";
        $sum_total_cost +=$variable_operating  + $over_head;
        //echo "vo formula: ".$variable_operating."<br/>";
        }
    }
    ?>
    </tbody>
    <tr><td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $sum_total_hours1; ?></td>
        <td><?php echo $sum_reg_hours1; ?></td>
        <td><?php echo $sum_ot1; ?></td>
        <td><?php echo $sum_dt_hours1; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $sum_pu_hours1; ?></td>       
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $sum_total_mileage1; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $sum_pu_mileage1; ?></td>
        <td style='background:black;padding:0px 0px 0px 0px;'>&nbsp;</td>
         <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $sum_total_hours2; ?></td>
        <td><?php echo $sum_reg_hours2; ?></td>
        <td><?php echo $sum_ot_hours2; ?></td>
        <td><?php echo $sum_dt_hours2; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>       
        <td><?php echo $sum_total_hours2; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $sum_total_mileage2; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $sum_pu_mileage2; ?></td>
        <td style='background:black;padding:0px 0px 0px 0px;'>&nbsp;</td>
        <td><?php echo $sum_hours_all; ?></td>
        <td>&nbsp;</td>
        
        <td><?php echo $sum_reg_all; ?></td>
        <td><?php echo $sum_ot_all; ?></td>
        <td><?php echo $sum_dt_all;  ?></td>
        <td><?php echo $sum_pu_hours_all; ?></td>
        <td><?php echo $sum_all_milaege; ?></td>
        <td><?php echo $sum_pu_mileage_all; ?></td>
        <td style='background:black;padding:0px 0px 0px 0px;'>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $sum_fuel; ?></td>
        <td>&nbsp;</td>
        <td><?php echo count($x); ?></td>

        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $sum_net_weight; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $sum_stops; ?></td>
        <td>&nbsp;</td>
        <td><?php echo $sum_zero; ?></td>
        <td><?php echo $sum_emergency; ?></td>
        <td><?php echo$sum_no_oil,2 ?></td>
        <td><?php echo $sum_expense; ?></td>
        <td><?php  echo  number_format(array_sum($sum_gal_per_stop),2);
        ?></td>
        <td><?php echo $sum_avg_miles; ?></td>
        <td><?php echo number_format($sum_avg_hours_stops,4); ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo number_format($sum_var_op/count($x),4 ); ?></td>
        <td><?php echo number_format($sum_over_head/count($x),4); ?></td>
        <td><?php echo number_format($sum_total_cost/count($x),4); ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
</table>

<script>
$(".fpm").change(function(){
    $.post("update_fpm.php",{route_id:$(this).attr('rel'), new_fpm:$(this).val() },function(data){
        alert(data);
    }) 
});

$(".other_expense_value").change(function(){
    $.post("update_expense.php",{mode:1,oev:$(this).val(),route_id:$(this).attr('rel')},function(data){
        alert("Expense Price updated! "+data);
    });
});

$(".expense_desc").change(function(){
    $.post("update_expense.php",{mode:2,oed:$(this).val(),route_id:$(this).attr('rel')},function(data){
        alert("Expense Description updated! "+data);
    });
});

$("#overhead").change(function(){
    $.post('change_overhead.php',{ value:$(this).val(),id:1 },function(data){
       alert("Over Head Variable changed!"); 
    });
});

$("#d_supply").change(function(){
    $.post('change_overhead.php',{ value:$(this).val(),id:2 },function(data){
       alert("Over Head Variable changed!"); 
    });
});

$("#w_comp").change(function(){
    $.post('change_overhead.php',{ value:$(this).val(),id:3 },function(data){
       alert("Over Head Variable changed!"); 
    });
});

$("#r_m").change(function(){
    $.post('change_overhead.php',{ value:$(this).val(),id:4 },function(data){
       alert("Over Head Variable changed!"); 
    });
});
$("#mpg").change(function(){
    $.post('change_overhead.php',{ value:$(this).val(),id:5 },function(data){
       alert("Over Head Variable changed!"); 
    });
});




$(".form").click(function(){
   $(this).submit(); 
});
</script>