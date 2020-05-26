<?php

include "protected/global.php";
ini_set("display_errors",0);
ini_set('memory_limit', '-1');

$string ="";
$x= "";
if(isset($_GET['clear'])){
    unset($_SESSION['exlude_list']);
}

if(isset($_SESSION['sludge_id'])){
    $self = new Person();
}


function start_date($route_id,$day){
    global $db;
    $sched = $db->query("SELECT DATE(start_date) as Date FROM sludge_rout_history_grease WHERE route_no = $route_id AND what_day =$day");
    if(count($sched)>0){
        return $sched[0]['Date'];
    }else {
       return "0000-00-00";
    }
}

function time_start($route_id,$day){
    $fs_stop ="00:00:00";
    global $db;
    $f_stop = $db->query("SELECT time_start FROM sludge_rout_history_grease WHERE route_no=$route_id AND what_day = $day ORDER BY time_start ASC LIMIT 0,1");
    if(count($f_stop)>0){
        $fs_stop = $f_stop[0]['time_start'];
    }
    return $fs_stop;
}

function time_end($route_id,$day){
    $ls_stop = "00:00:00";
    global $db;
    $l_stop = $db->query("SELECT time_end FROM sludge_rout_history_grease WHERE route_no=$route_id  AND what_day = $day ORDER BY time_end DESC LIMIT 0,1");
    if(count($l_stop)>0){
        $ls_stop = $l_stop[0]['time_end'];
    }
    return $ls_stop;
}


function start_time_from_date($route_id,$day){
    global $db;
    $start_time = "00:00:00";
     $sched = $db->query("SELECT first_stop as Time FROM sludge_rout_history_grease WHERE route_no =$route_id AND what_day =$day");
    if(count($sched)>0){
        $start_time=  $sched[0]['Time'];
    }
    return $start_time;
}

function end_time_from_date($route_id,$day){
    global $db;
    $end_time = "00:00:00";
    $end1 = $db->query("SELECT last_stop as ETime FROM sludge_rout_history_grease WHERE route_no = $route_id AND what_day =$day");
    if(count($end1)>0){
        $end_time= $end1[0]['ETime'];
    } 
    return $end_time;
}

function total_hours($route_id,$day){
    global $db;
    $total_day = 0;
    $day1 = $db->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(last_stop, first_stop)))) AS totalhours FROM `sludge_rout_history_grease` WHERE route_no=$route_id AND what_day =$day GROUP BY what_day");
    if(count($day1)>0){
        $total_day = $day1[0]['totalhours'];
    }
    return $total_day;
}


function first_stop_mileage($route_id,$day){
    global $db;
    $f_mileage = 0; 
    if($route_id>0 && $route_id !="" && $route_id !=" "){
        $first_stop_mileage = $db->query("SELECT first_stop_mileage FROM sludge_rout_history_grease WHERE route_no=$route_id  AND what_day =$day");
        if(count($first_stop_mileage)>0){
            $f_mileage = $first_stop_mileage[0]['first_stop_mileage'];
        }
    }
    return $f_mileage;
}

function last_stop_mileage($route_id,$day){
    global $db;
    $l_mileage = 0;
    
    if($route_id>0&& $route_id !="" && $route_id !=" "){
        $last_stop_mileage = $db->query("SELECT last_stop_mileage FROM sludge_rout_history_grease WHERE route_no=$route_id AND what_day =$day");
        if(count($last_stop_mileage)>0){
            $l_mileage = $last_stop_mileage[0]['last_stop_mileage'];
        }
    }
    return $l_mileage;
}


function start_mileage($route_id,$day){
    global $db;
    $start = 0;
    $s = $db->query("SELECT start_mileage FROM sludge_rout_history_grease WHERE route_no = $route_id AND what_day =$day");
    if(count($s)>0){
       return $s[0]['start_mileage'];
    } else {
        return $start; 
    }
}

function end_mileage($route_id,$day){
    global $db;
    $end = 0;
    $e = $db->query("SELECT end_mileage FROM sludge_rout_history_grease WHERE route_no = $route_id AND what_day =$day");
    if(count($e)>0){
        return $e[0]['end_mileage'];
    } else {
        return $end; 
    }
}

function lb_per_stop($net_weight,$stops){
    if($net_weight == 0 || $net_weight == null || $net_weight==" " || $net_weight =="" || $net_weight <= 0 || $stops <= 0){
       return $avg_lb_stop = 0.00;    
    } else {
       $avg_lb_stop =  number_format($net_weight/$stops,2);
       return $avg_lb_stop;
    }
    
    
    return $avg_lb_stop;
}



function fuel_gal($miles){
    return number_format($miles/7,2);
}

function avg_fuel_money($total_mile,$stops){
    $modifier = 7.56;
    if($total_mile == 0 || $total_mile == null || $total_mile==" " || $total_mile ==""  || $total_mile <= 0 || $stops <= 0){
       return $avg_fuel = 0.00;    
    } else {
        $avg_fuel =  number_format( ( $total_mile/$modifier )/$stops ,2);
        return $avg_fuel;
    }
}

function avg_miles_per_stop($total_pu_mileage,$stops){
    if($total_pu_mileage <=0 || $stops <= 0){
       return $avg_miles_stop = 0;
    }else{
       return $avg_miles_stop = number_format($total_pu_mileage/$stops,2);
    }
    return $avg_miles_stop;
}
function avg_hours_stop($time_start,$total_pu_hours,$stops){
    if($time_start == "00:00" || $time_start == null || $time_start==" " || $time_start =="" || $time_start <= 0 || $stops <= 0){
       return $avg_hours_stops = 0.00;
    }else {
       return $avg_hours_stops = ($total_pu_hours/$stops) * 60;
    }
}

function variable_operating($labor_cost,$total_mileage,$truck_mpg,$net_gallons,$truck_r_m,$trailer_r_m,$fuel_cost,$truck_dep_hour,$water_treatment_cost,$trailer_dep_hour,$other_expenses,$route_hours){
    global $db;
    
    $constants = $db->query("SELECT COALESCE(value,NULL,0) as value FROM overhead_value WHERE id IN(2,3,4,5) ORDER BY id ASC");
    
    $a = $labor_cost * round($constants[1]['value'],2);
    
    $b = $constants[0]['value'] + $a;
    $c = $total_mileage * $truck_r_m;
    $d = $c+ $b;
    $e = $d + $fuel_cost ;
    $f = $e +  ($truck_dep_hour*$route_hours) + ($trailer_dep_hour*$route_hours)+ $water_treatment_cost +$other_expenses;
    
    
    
   
    return $f/$net_gallons; 
    
}



function charged_amount($route_id){
    global $db;
    $data = $db->query("SELECT ppg,inches_to_gallons FROM sludge_grease_data_table WHERE route_id = $route_id");
    if(count($data)>0){
        $all_picked_up=0;
        foreach($data as $calc){
            $all_picked_up += $calc['ppg']*$calc['inches_to_gallons'];
        }
    }else {
       $all_picked_up = 0;
    }
    return $all_picked_up;
}

$exl = "";
if(isset($_POST['exp_log'])){
    if(isset($_SESSION['exclude_list'])){
        $_SESSION['exlude_list'] = array_unique($_SESSION['exlude_list']);
        if(!empty($_SESSION['exlude_list'])){
            $exl = " AND sludge_ikg_grease.route_id NOT IN (".implode(",",$_SESSION['exlude_list']).")";
        }    
    }
     $x = $db->query("SELECT 
                    sludge_list_of_grease.stops,
                    sludge_ikg_grease.route_id,
                    sludge_ikg_grease.ikg_manifest_route_number,
                    COALESCE(sludge_ikg_grease.net_weight,NULL,0.00) as net_weight,
                    COALESCE(sludge_ikg_grease.tare_weight,NULL, 0.00) as tare_weight,
                    sludge_ikg_grease.tank1,
                    sludge_ikg_grease.tank2,
                    COALESCE(sludge_ikg_grease.gross_weight,NULL,0.00) as gross_weight,
                    sludge_ikg_grease.end_mileage,
                    sludge_ikg_grease.start_mileage,
                    sludge_ikg_grease.fuel,
                    sludge_ikg_grease.route_id,
                    sludge_ikg_grease.end_time,
                    sludge_ikg_grease.time_start,
                    sludge_ikg_grease.driver,
                    sludge_ikg_grease.inventory_code,
                    sludge_ikg_grease.truck,
                    sludge_ikg_grease.other_expense_desc, 
                    sludge_ikg_grease.route_notes,
                    sludge_ikg_grease.percent_fluid,  
                    sludge_ikg_grease.trailer,   
                    COALESCE(sludge_ikg_grease.other_expense_value,NULL,0.00) as other_expense_value,    
                    sludge_ikg_grease.fuel_per_gallon,         
                    TIME(sludge_ikg_grease.first_stop) as Time FROM sludge_list_of_grease  LEFT JOIN sludge_ikg_grease  ON sludge_ikg_grease.route_id = sludge_list_of_grease.route_id LEFT JOIN sludge_rout_history_grease ON sludge_rout_history_grease.route_no = sludge_ikg_grease.route_id  WHERE sludge_list_of_grease.status IN ('completed') AND sludge_ikg_grease.route_id >0 AND sludge_ikg_grease.route_id IS NOT NULL $_POST[params] $exl
");    
}

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
    
   
    
    
    if($_POST['format'] == "xls"){
        $xlsRow = 0;
        $xlsCol = 0;
        $file = "grease_trap_driverslog".date("YmdHm").".xls";
        include "protected/xlsfunctions.php";
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");           
        header("Content-Disposition: attachment;filename=$file");
        header("Content-Transfer-Encoding: binary ");
        xlsBOF();
        xlsWriteLabel($xlsRow,0,"Driver");
        xlsWriteLabel($xlsRow,2,"Date");
        xlsWriteLabel($xlsRow,4,"Start Time");
        xlsWriteLabel($xlsRow,6,"End Time");
        xlsWriteLabel($xlsRow,8,"Total Hrs");
        xlsWriteLabel($xlsRow,10,"Reg Hrs");
        xlsWriteLabel($xlsRow,12,"O.T. Hours");
        xlsWriteLabel($xlsRow,14,"D.T. Hours");
        xlsWriteLabel($xlsRow,16,"First Stop");
        xlsWriteLabel($xlsRow,18,"Last Stop");
        xlsWriteLabel($xlsRow,20,"Total P/U Hrs");
        xlsWriteLabel($xlsRow,22,"Start Mileage");
        xlsWriteLabel($xlsRow,24,"End Mileage");
        xlsWriteLabel($xlsRow,26,"Total Mileage");
        xlsWriteLabel($xlsRow,28,"First Stop Mileage");
        xlsWriteLabel($xlsRow,30,"Last Stop Mileage");
        xlsWriteLabel($xlsRow,32,"Total P/U Mileage");
        xlsWriteLabel($xlsRow,34,"");
        xlsWriteLabel($xlsRow,36,"Driver");
        xlsWriteLabel($xlsRow,38,"Date");
        xlsWriteLabel($xlsRow,40,"Start Time");
        xlsWriteLabel($xlsRow,42,"End Time");
        xlsWriteLabel($xlsRow,44,"Total Hrs");
        xlsWriteLabel($xlsRow,46,"D.T. Hours");
        xlsWriteLabel($xlsRow,48,"Reg Hrs");
        xlsWriteLabel($xlsRow,50,"O.T. Hours");
        xlsWriteLabel($xlsRow,52,"First Stop");
        xlsWriteLabel($xlsRow,54,"Last Stop");
        xlsWriteLabel($xlsRow,56,"Total P/U Hrs");
        xlsWriteLabel($xlsRow,58,"Start Mileage");
        xlsWriteLabel($xlsRow,60,"End Mileage");
        xlsWriteLabel($xlsRow,62,"Total Mileage");
        xlsWriteLabel($xlsRow,64,"First Stop Mileage");
        xlsWriteLabel($xlsRow,66,"Last Stop Mileage");
        xlsWriteLabel($xlsRow,68,"Total P/U Mileage");
        xlsWriteLabel($xlsRow,70,"");
        xlsWriteLabel($xlsRow,72,"Total Hrs");
        xlsWriteLabel($xlsRow,74,"Labor Cost");
        xlsWriteLabel($xlsRow,76,"Reg Hrs");
        xlsWriteLabel($xlsRow,78,"O.T. Hours");
        xlsWriteLabel($xlsRow,80,"D.T. Hours");
        xlsWriteLabel($xlsRow,82,"Total P/U Hrs");
        xlsWriteLabel($xlsRow,84,"Total Mileage");
        xlsWriteLabel($xlsRow,86,"Total P/U Mileage");
        xlsWriteLabel($xlsRow,88,"");
        xlsWriteLabel($xlsRow,90,"Fuel Gal");
        xlsWriteLabel($xlsRow,92,"Fuel $/ Gal");
        xlsWriteLabel($xlsRow,94,"Fuel Cost");
        xlsWriteLabel($xlsRow,96,"Invent Loc.");
        xlsWriteLabel($xlsRow,98,"Route");
        xlsWriteLabel($xlsRow,100,"Truck");
        xlsWriteLabel($xlsRow,102,"Gross Weight");
        xlsWriteLabel($xlsRow,104,"Light Weight");
        xlsWriteLabel($xlsRow,106,"Net Weight");
        xlsWriteLabel($xlsRow,108,"Net Gallons");
        xlsWriteLabel($xlsRow,110,"Treatment Rate/Gal");
        xlsWriteLabel($xlsRow,112,"Water Treatment Cost");
        xlsWriteLabel($xlsRow,114,"Truck Total Cost");
        xlsWriteLabel($xlsRow,116,"Trailer Total Cost");
        xlsWriteLabel($xlsRow,118,"Total Stops");
        xlsWriteLabel($xlsRow,120,"Percent Solids");
        xlsWriteLabel($xlsRow,122,"Zero Yield Stops");
        xlsWriteLabel($xlsRow,124,"E Stops");
        xlsWriteLabel($xlsRow,126,"Skipped");
        xlsWriteLabel($xlsRow,128,"Other Expenses");
        xlsWriteLabel($xlsRow,130,"Avg Gal/Stops");
        xlsWriteLabel($xlsRow,132,"Avg Miles/Stops");
        xlsWriteLabel($xlsRow,134,"Avg Min/Stops");
        xlsWriteLabel($xlsRow,136,"Truck Monthly Depre Cost");
        xlsWriteLabel($xlsRow,138,"Truck Hourly Depre Cost");
        xlsWriteLabel($xlsRow,140,"Truck R&M Per Mile");
        xlsWriteLabel($xlsRow,142,"Truck Total R&M Cost");
        xlsWriteLabel($xlsRow,144,"Trailer Monthly Depre Cost");
        xlsWriteLabel($xlsRow,146,"Trailer Hourly Depre Cost");
        xlsWriteLabel($xlsRow,148,"Trailer R&M per Mile");
        xlsWriteLabel($xlsRow,150,"Trailer Total R&M Cost");
        xlsWriteLabel($xlsRow,152,"Variable Operating Costs/gal.");
        xlsWriteLabel($xlsRow,154,"Fixed Overhead Cost/Gal");
        xlsWriteLabel($xlsRow,158,"Total Cost/Gal");
        xlsWriteLabel($xlsRow,160,"Total Cost");
        xlsWriteLabel($xlsRow,162,"Billed Per Gallon");
        xlsWriteLabel($xlsRow,164,"Paid Amount");
        xlsWriteLabel($xlsRow,166,"TOTAL BILLED AMOUNT");
        xlsWriteLabel($xlsRow,168,"Net Income");
        xlsWriteLabel($xlsRow,170,"Net Income(Fully Recieved)");
        xlsWriteLabel($xlsRow,172,"Income % of Billed");
        xlsWriteLabel($xlsRow,174,"Route Notes");
    }else {
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Description: File Transfer");
        header("Content-type: text/csv");
        $fileName = "grease_trap_driverslog".date("Ymdhis").".csv";
        header("Content-Disposition: attachment; filename={$fileName}");
        header("Expires: 0");
        header("Content-Transfer-Encoding: binary");
        header("Pragma: public");
    }
    
    
    
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
        $zero = $db->query("SELECT distinct(schedule_id) FROM sludge_grease_data_table WHERE route_id = $log[route_id] AND inches_to_gallons = 0");
        
        $skipped = $db->query("SELECT DISTINCT(schedule_id) FROM sludge_grease_data_table WHERE route_id=$log[route_id] AND zero_gallon_reason IN(12,14,16)");
        
        $emergency = $db->query("SELECT DISTINCT(schedule_id) FROM sludge_grease_data_table WHERE route_id=$log[route_id] AND zero_gallon_reason =99");
        
        $no_oil = $db->query("SELECT DISTINCT(schedule_id) FROM sludge_grease_data_table WHERE route_id=$log[route_id] AND zero_gallon_reason = 10");
        
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
        $skip = $db->query("SELECT DISTINCT(schedule_id) FROM sludge_grease_data_table WHERE route_id=$log[route_id] AND zero_gallon_reason !=0");
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
        $labor_cost =( ($reg_hours*$person->driver_hourly_pay)+($ot*$person->driver_hourly_pay*1.5)+($dt*$person->driver_hourly_pay*2) ); 
        $variable_operating = variable_operating($labor_cost,$total_mileage,$truck->mpg,$net_gallons,$truck->r_m,$trailer->r_m,$fuel_cost,($truck->dep/21)/10,$water_treatment_cost,($trailer->dep/21)/10,$log['other_expense_value'],$reg_hours);
        //fixed overhead
        $fiov = $db->query("SELECT value FROM overhead_value WHERE id=1");
        $over_head = ($fiov[0]['value']/ count($x))/$net_gallons   ;
        $sum_over_head +=$over_head;
        $sum_var_op +=number_format($variable_operating,4);
        $total_cost = ($over_head + $variable_operating)*$net_gallons;
        switch($_POST['format']){
            case "csv":
                $dataString .= "$driver,".start_date($log['route_id'],1).",".time_start($log['route_id'],1).",".time_end($log['route_id'],1).",$total_hours,$reg_hours,$ot,$dt,".start_time_from_date($log['route_id'],1).",".end_time_from_date($log['route_id'],1).",$total_day_1,".start_mileage($log['route_id'],1).",".end_mileage($log['route_id'],1).",$total_mileage,".first_stop_mileage($log['route_id'],1).",".last_stop_mileage($log['route_id'],1).",$total_pu_mileage,$driver,".start_date($log['route_id'],2).",".time_start($log['route_id'],2).",".time_end($log['route_id'],2).",$total_hours2,$reg_hours2,$ot2,$dt2,".start_time_from_date($log['route_id'],2).",".end_time_from_date($log['route_id'],2).",$total_day_2,".start_mileage($log['route_id'],2).",".end_mileage($log['route_id'],2).",$total_mileage2,".first_stop_mileage($log['route_id'],2).",".last_stop_mileage($log['route_id'],2).",$total_pu_mileage2,$all_hours,"; 
        
            
            if( $self->isPayroll() ) {    
                $dataString .=",".number_format($labor_cost,2);
            } else {
                    $dataString .= ",Payroll Manager Only";
            }
            $dataString .= ",$all_reg,$all_ot,$all_dt,$total_pu_hours,$all_mileage,$all_pu_mileage,$avg_fuel,".number_format($log['fuel_per_gallon'],2).",".number_format($fuel_cost,2).",$log[inventory_code],".vehicle_name($log['truck']).",$log[gross_weight],$log[tare_weight],$log[net_weight],"./* gallons*/number_format($net_gallons,2)/* gallons*/.",".solids_table($log['percent_fluid']).",".number_format( $water_treatment_cost ,2).",".number_format($trailer_total_cost,2).",$log[stops],$log[percent_fluid],".count($zero).",".count($emergency).",".count($no_oil).",".number_format($log['other_expense_value'],2).",$log[other_expense_desc],".number_format($gal_per_stop,2).","./*avg min per stop*/number_format($avg_hours_stops,2)./*avg min per stop*/","./*Truck Depreciation*/$truck->dep./*Truck Depreciation*/","./*Truck Hourly Depre Cost */number_format(($truck->dep/21)/10,2)/*Truck Hourly Depre Cost */.","./*Truck R&M Per Mile */number_format($truck->r_m,2)./*Truck R&M Per Mile */","./*Truck Total R&M Cost*/number_format($truck->r_m*$all_mileage,2)/*Truck Total R&M Cost*/.","./*Trailer Depreciation*/$trailer->dep/*Trailer Depreciation*/.","./*Trailer Hourly Depre Cost */number_format( ($trailer->dep/21)/10,2 )/*Trailer Hourly Depre Cost */.","./*Trailer R&M Per Mile */number_format($trailer->rm_total,1)/*Trailer R&M Per Mile */.","./*Trailer Total R&M Cost*/number_format($trailer->rm_total*$all_mileage,1)/*Trailer Total R&M Cost*/.",";
           
            $dataString .=  ","./*variable operting cost*/number_format($variable_operating,4);/*variable operting cost*/
            
            
            
    
            $dataString .=  ",".number_format($over_head,2); //Total Cost/Gal
           
            if($net_gallons>0){
                $dataString .= ",".number_format( $over_head + $variable_operating ,2);
            } else {
                $dataString .= ",0";
            }
            
            
            $dataString .= ",".number_format( $total_cost ,2  ); //billed/gallon
            if($net_gallons>0){
                $dataString .= ",".number_format(charged_amount($log['route_id'])/  $net_gallons,2);
            } else {
                $dataString .= ",".number_format(0,2);
            }
            
            $dataString .=  ","; //paid amount
            $yu = $db->query("SELECT COALESCE(SUM(total_price),NULL,0.00) as total_charge FROM sludge_pay_trace WHERE route_id=$log[route_id] AND status =1");
            $dataString .=  $yu[0]['total_charge'];
            $total_price = $yu[0]['total_charge'];
        $dataString .=  ",".number_format(charged_amount($log['route_id']),2).",".number_format($total_price - $total_cost,2).",".number_format( charged_amount($log['route_id']) - $total_cost  ,2); 
            if(charged_amount($log['route_id']) >0){
                $dataString .=  ",".number_format(( charged_amount($log['route_id']) - $total_cost )/ charged_amount($log['route_id']),2);
            }else {
                $dataString .=  ",".number_format(0,2);
            }
        
            $dataString .=  ",$log[route_notes] \r\n";
        
            break;
            case "xls":
                $xlsRow++;
                xlsWriteLabel($xlsRow,0,"$driver");
                xlsWriteLabel($xlsRow,2,start_date($log['route_id'],1));
                xlsWriteLabel($xlsRow,4,time_start($log['route_id'],1));
                xlsWriteLabel($xlsRow,6,time_end($log['route_id'],1));
                xlsWriteLabel($xlsRow,8,"$total_hours");
                xlsWriteLabel($xlsRow,10,"$reg_hours");
                xlsWriteLabel($xlsRow,12,"$ot");
                xlsWriteLabel($xlsRow,14,"$dt");
                xlsWriteLabel($xlsRow,16,start_time_from_date($log['route_id'],1));
                xlsWriteLabel($xlsRow,18,end_time_from_date($log['route_id'],2));
                xlsWriteLabel($xlsRow,20,"$total_day_1");
                xlsWriteLabel($xlsRow,22,start_mileage($log['route_id'],1));
                xlsWriteLabel($xlsRow,24,end_mileage($log['route_id'],1));
                xlsWriteLabel($xlsRow,26,"$total_mileage");
                xlsWriteLabel($xlsRow,28,first_stop_mileage($log['route_id'],1));
                xlsWriteLabel($xlsRow,30,last_stop_mileage($log['route_id'],1));
                xlsWriteLabel($xlsRow,32,"$total_pu_mileage");
                xlsWriteLabel($xlsRow,34,"");
                xlsWriteLabel($xlsRow,36,"$driver");
                xlsWriteLabel($xlsRow,38,start_date($log['route_id'],2));
                xlsWriteLabel($xlsRow,40,time_start($log['route_id'],2));
                xlsWriteLabel($xlsRow,42,time_end($log['route_id'],2) );
                xlsWriteLabel($xlsRow,44,"$total_hours2");
                xlsWriteLabel($xlsRow,46,"$reg_hours2");
                xlsWriteLabel($xlsRow,48,"$ot2");
                xlsWriteLabel($xlsRow,50,"$dt2");
                xlsWriteLabel($xlsRow,52,start_time_from_date($log['route_id'],2));
                xlsWriteLabel($xlsRow,54,end_time_from_date($log['route_id'],2));
                xlsWriteLabel($xlsRow,56,"$total_day_2");
                xlsWriteLabel($xlsRow,58,start_mileage($log['route_id'],2));
                xlsWriteLabel($xlsRow,60,end_mileage($log['route_id'],2));
                xlsWriteLabel($xlsRow,62,"$total_mileage2");
                xlsWriteLabel($xlsRow,64,first_stop_mileage($log['route_id'],2));
                xlsWriteLabel($xlsRow,66,last_stop_mileage($log['route_id'],2));
                xlsWriteLabel($xlsRow,68,"$total_pu_mileage2");
                xlsWriteLabel($xlsRow,70,"");
                xlsWriteLabel($xlsRow,72,"$all_hours");
                 $labor_cost =( ($reg_hours*$person->driver_hourly_pay)+($ot*$person->driver_hourly_pay*1.5)+($dt*$person->driver_hourly_pay*2) );
                if( $self->isPayroll() ) {    
                    xlsWriteLabel($xlsRow,74,number_format($labor_cost,2));
                    
               } else {
                    xlsWriteLabel($xlsRow,74,"Payroll Manager Only");
                    
               }
                
                
                xlsWriteLabel($xlsRow,76,"$all_reg");
                xlsWriteLabel($xlsRow,78,"$all_ot");
                xlsWriteLabel($xlsRow,80,"$all_dt");
                xlsWriteLabel($xlsRow,82,"$total_pu_hours");
                xlsWriteLabel($xlsRow,84,"$all_mileage");
                xlsWriteLabel($xlsRow,86,"$all_pu_mileage");
                xlsWriteLabel($xlsRow,88,"");
                xlsWriteLabel($xlsRow,90,"$avg_fuel");
                xlsWriteLabel($xlsRow,92,number_format($log['fuel_per_gallon'],2));
                xlsWriteLabel($xlsRow,94,number_format($fuel_cost,2));
                xlsWriteLabel($xlsRow,96,"$log[inventory_code]");
                xlsWriteLabel($xlsRow,98,"$log[ikg_manifest_route_number]");
                xlsWriteLabel($xlsRow,100,vehicle_name($log['truck']));
                xlsWriteLabel($xlsRow,102,"$log[gross_weight]");
                xlsWriteLabel($xlsRow,104,"$log[tare_weight]");
                xlsWriteLabel($xlsRow,106,"$log[net_weight]");
                xlsWriteLabel($xlsRow,108,number_format($net_gallons,2));
                xlsWriteLabel($xlsRow,110,solids_table($log['percent_fluid']));
                xlsWriteLabel($xlsRow,112,number_format( $water_treatment_cost ,2));
                xlsWriteLabel($xlsRow,114,number_format($truck_total_cost,2));
                xlsWriteLabel($xlsRow,116,number_format($trailer_total_cost,2));
                xlsWriteLabel($xlsRow,118,"$log[stops]");
                xlsWriteLabel($xlsRow,120,"$log[percent_fluid]");
                xlsWriteLabel($xlsRow,122,count($zero));
                xlsWriteLabel($xlsRow,124,count($emergency));
                xlsWriteLabel($xlsRow,126,count($no_oil));
                xlsWriteLabel($xlsRow,128,number_format($log['other_expense_value'],2)."\r\n $log[other_expense_desc]");
                xlsWriteLabel($xlsRow,130,number_format($gal_per_stop,2));
                xlsWriteLabel($xlsRow,132,"$avg_miles_stop");
                xlsWriteLabel($xlsRow,134,number_format($avg_hours_stops,2));
                xlsWriteLabel($xlsRow,136,$truck->dep);
                xlsWriteLabel($xlsRow,138,number_format(($truck->dep/21)/10,2));
                xlsWriteLabel($xlsRow,140,number_format($truck->r_m,2));
                xlsWriteLabel($xlsRow,142,number_format($truck->r_m*$all_mileage,2));
                xlsWriteLabel($xlsRow,144,$trailer->dep);
                xlsWriteLabel($xlsRow,146,number_format( ($trailer->dep/21)/10,2 ));
                xlsWriteLabel($xlsRow,148,number_format($trailer->rm_total,1));
                xlsWriteLabel($xlsRow,150,number_format($trailer->rm_total*$all_mileage,1));
                xlsWriteLabel($xlsRow,152,number_format($variable_operating,4));
                xlsWriteLabel($xlsRow,154,number_format($over_head,2));
                if($net_gallons>0){
                    xlsWriteLabel($xlsRow,158,number_format( $over_head + $variable_operating ,2));
                    xlsWriteLabel($xlsRow,162,number_format(charged_amount($log['route_id'])/  $net_gallons,2));
                } else {
                    xlsWriteLabel($xlsRow,158,"0");
                    xlsWriteLabel($xlsRow,162,"0");
                    
                }
                xlsWriteLabel($xlsRow,160, number_format( $total_cost ,2  ));
                
                xlsWriteLabel($xlsRow,164,$yu[0]['total_charge']);
                xlsWriteLabel($xlsRow,166,number_format(charged_amount($log['route_id']),2));
                xlsWriteLabel($xlsRow,168,number_format($total_price - $total_cost,2));
                xlsWriteLabel($xlsRow,170,number_format( charged_amount($log['route_id']) - $total_cost  ,2));
                 if(charged_amount($log['route_id']) >0){
                    
                    xlsWriteLabel($xlsRow,172,number_format(( charged_amount($log['route_id']) - $total_cost )/ charged_amount($log['route_id']),2));
                }else {
                    xlsWriteLabel($xlsRow,172,"0");
                }
                xlsWriteLabel($xlsRow,174,"$log[route_notes]");
            break;
        }
    }
    
    if($_POST['format']=="csv"){
        $fh = @fopen( "php://output", 'w' );
        fwrite($fh, $dataString);
        fclose($fh);        
    }else{
          xlsEOF();
    }
}


?>