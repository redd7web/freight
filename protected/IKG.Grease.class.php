<?php
ini_set("display_errors",0);
class Grease_IKG {
    public $ikg_manifest_route_number;
    public $scheduled_date;
    public $completed_date;
    public $pick_upID;
    public $route_id;
    public $account_numbers = Array();
    public $acount_numbers_full_string;
    public $inventory_code;
    public $lot_no;
    public $recieving_facility;
    public $recieving_facility_no;
    public $facility_address;
    public $facility_rep;
    public $driver;
    public $driver_no;
    public $ikg_transporter;
    public $number_days_route;
    public $tank1;
    public $tank2;
    public $truck;
    public $truck_name;
    public $license_plate;
    public $ikg_decal;
    public $ikg_collected;
    public $location;
    public $ikg_gross_weight;
    public $tare_weight;
    public $net_weight;
    public $time_start;
    public $start_mileage;
    public $first_stop;
    public $first_stop_mileage;
    public $last_stop;
    public $last_stop_mileage;
    public $end_time;
    public $end_mileage;
    public $fuel;
    public $vehicle;
    //***** FROM LIST OF ROUTES DB ******//
    public $list_of_routes_id;
    public $route_status;
    public $created_date;
    public $created_by;
    public $stops;
    public $expected;
    public $collected;
    public $gallons_to_lbs;
    public $lbs_per_mile;
    //************* SCHEDULED ROUTES ASSOCIATED WITH THIS ROUTE **///
    public $scheduled_routes = array();
    public $scheduled_route_notes;
    public $driver_complete_dated;
    public $bol;
    public $wtn;
    public $corresp_net_mileage_diff = array();
    public $sum_net_route_mileage;
    public $sum_total_mileage;
    public $net_gallons;
    public $avg_gal_per_stop;
    public $avg_mile_per_stop;
    public $avg_min_per_stop;
    public $var_op_cost;
    public $fixed_overhead;
    public $total_cost_gal;
    public $total_pickup_hours;
    public $total_pu_mileage;
    public $route_notes;
    public $percent_fluid;
    public $trailer;
    public $trailer_name;
    public $total_billed_amount;
    public $inc;
    public $total_stops;
    public $total_mileage_day1;
    public $total_mileage_day2;
    public $avg_fuel;
    public $fuel_per_gallon;
    public $fuel_cost;
    public $water_treatment_cost;
    public $other_expense_value;
    public $labor_cost;
    public $total_hours;
    public $reg_hours;
    public $ot_hours;
    public $dt_hours;
    public $trailer_decal;
    public $trailer_lp;
    public $vic;
    public $labels = array();
    public $conduct;
    public $customer_name;
    //************* SCHEDULED ROUTES ASSOCIATED WITH THIS ROUTE **///
    function __construct($route_id = NULL){
            global $dbprefix;
            $db = new Database();
            if (is_numeric($route_id)){
             
             $ikg_table = "sludge_ikg_grease";
             $from_oilrouted = $db->query("SELECT * FROM $ikg_table WHERE route_id=$route_id");
             $bop = $db->query("SELECT SUM(inches_to_gallons) as all_collected FROM sludge_grease_data_table WHERE route_id = $route_id");
             $this->collected = $bop[0]['all_collected'];
             
             $db->query("UPDATE sludge_list_of_grease SET collected ='$this->collected' WHERE route_id=$route_id");
             $overhead =  $db->query("SELECT * FROM overhead_value");
             
             if(count($from_oilrouted) >0){
               foreach($from_oilrouted as $value){
                   $this->scheduled_date = $value['scheduled_date'];
                   $this->ikg_manifest_route_number = $value['ikg_manifest_route_number'];
                  
                   $this->route_id = (int)$route_id;
                   //$this->pick_upID = $value['entry_number'];
                   $this->acount_numbers_full_string = str_replace("||","|",$value['account_numbers']);
                   //********* SPLITS account numbers into member array
                  
                   
                   
                   $this->account_numbers =  explode("|",$value['account_numbers']);
                   array_pop($this->account_numbers);
                   $this->account_numbers = array_filter($this->account_numbers);
                   //$this->account_numbers = array_unique($this->account_numbers);
                   
                   //*********                         ***********//
                   
                   $this->inventory_code = $value['inventory_code'];
                   $this->lot_no = $value['lot_no'];
                   $this->recieving_facility = numberToFacility($value['recieving_facility']);
                   $this->recieving_facility_no = $value['recieving_facility'];
                   $this->facility_address = $value['facility_address'];
                   $this->facility_rep = $value['facility_rep'];
                   $this->driver = uNumToName($value['driver']);
                   $this->ikg_transporter = $value['ikg_transporter'];
                   $this->number_days_route = $value['number_days_route'];
                   $this->tank1 = $value['tank1'];
                   $this->tank2 = $value['tank2'];
                   $this->truck = $value['truck'];
                   $this->license_plate = $value['license_plate'];
                   $this->ikg_decal = $value['ikg_decal'];
                   $this->location = $value['location'];
                   $this->ikg_collected = $value['ikg_collected'];
                   $this->ikg_gross_weight = $value['gross_weight'];
                   $this->tare_weight = $value['tare_weight'];
                   $this->net_weight = $value['net_weight'];
                   $this->time_start = $value['time_start'];
                   $this->start_mileage = $value['start_mileage'];
                   $this->first_stop = $value['first_stop'];
                   $this->first_stop_mileage = $value['first_stop_mileage'];
                   $this->last_stop = $value['last_stop'];
                   $this->last_stop_mileage = $value['last_top_mileage'];
                   $this->end_time = $value['end_time'];
                   $this->end_mileage = $value['end_mileage'];
                   $this->fuel = $value['fuel'];     
                   $this->driver_no = $value['driver'];  
                   $this->vehicle = $value['truck'];  
                   $this->driver_complete_dated = $value['driver_completed_date']; 
                   $this->wtn = $value['wtn'];
                   $this->bol = $value['bol'];
                   $this->route_notes = $value['route_notes'];
                   $this->percent_fluid = $value['percent_fluid'];
                   $this->trailer = $value['trailer'];
                   $this->trailer_decal = $value['trailer_decal'];
                   $this->trailer_lp = $value['trailer_lp'];
                   $this->fuel_per_gallon = $value['fuel_per_gallon'];
                   $this->net_gallons = number_format($value['net_weight']/8.34,2);
                   $this->other_expense_value = $value['other_expense_value'];
                   $this->vic =     $value['vic'];
                   $this->conduct = $value['conductivity'];
                   $this->customer_name = $value['customer_name'];
               }
                 $truck = new Vehicle($this->truck);
                 $trailer = new Trailer($this->trailer);
                 $person = new Person($this->driver_no);
                 $this->truck_name = $truck->name;
                 $this->trailer_name = $trailer->name;    
                //***************** LIST OF ROUTES DB INFO ************************//
               $upo = $db->where('route_id',$route_id)->get($dbprefix.'_list_of_grease');
                foreach ($upo as $value2){
                    $this->list_of_routes_id = $value2['list_of_routes_id'];
                    $this->route_status = $value2['status'];
                    $this->created_date = $value2['created_date'];
                    $this->created_by = uNumToName($value2['created_by']);
                    $this->stops = $value2['stops'];
                    $this->expected = $value2['expected'];
                    $this->collected = $value2['collected'];   
                    $this->completed_date = $value2['completed_date'];
                }
                
                $this->gallons_to_lbs = $this->collected * 7.56;
                
                $this->total_mileage_day1 =  end_mileage($this->route_id,1) - start_mileage($this->route_id,1);
                $this->total_mileage_day2 = end_mileage($this->route_id,2) - start_mileage($this->route_id,2);  
                $this->sum_total_mileage = $this->total_mileage_day1+ $this->total_mileage_day2; 
                $this->water_treatment_cost = solids_table($this->percent_fluid) * $this->net_gallons;
                if( ($this->end_mileage - $this->start_mileage) >0 ){                
                    $this->lbs_per_mile = number_format($this->gallons_to_lbs / $this->sum_total_mileage,2);
                }else {
                    $this->lbs_per_mile = 0;
                }

                //************** SCHEDULED ROUTES / NOTES DB **********************//
                

                foreach($this->account_numbers as $act){
                    $ik = $db->query("SELECT grease_no FROM sludge_grease_traps WHERE grease_route_no = $this->route_id AND account_no = $act");
                    
                    if(count($ik)>0){
                       $buff[] =  $ik[0]['grease_no'];
                    }
                }
                
                if(!empty($buff)){
                    $this->scheduled_routes = array_unique($buff);
                }
                
                
                $this->scheduled_route_notes = $db->where('route_id',$this->route_id)->get($dbprefix."_notes");
                
                
             }
             switch($this->recieving_facility_no){
                case 16:
                 $this->labels[0] = "Credit";
                 $this->labels[1] = "Debit";
                break;
                default:
                 $this->labels[0] = "Location";
                 $this->labels[1] = "INVENTORY CODE";
             }
            
             
             $route_info = $db->query("SELECT * FROM sludge_ikg_grease WHERE route_id = $this->route_id");             
             $stops = $db->query("SELECT schedule_id,TIME(arrival) as arrival,mileage,TIME(departure) as departure, HOUR( TIMEDIFF(departure, arrival)) as `difference`,inches_to_gallons * ppg as billable FROM sludge_grease_data_table WHERE route_id = $this->route_id ORDER BY arrival,departure ASC");
             if(count($stops)>0){
                $prev = null;
                foreach($stops as $calc){
                    $this->total_billed_amount += $calc['billable'];
                    if($prev == null){//first stop 
                        $x = $route_info[0]['first_stop_mileage']- $route_info[0]['start_mileage'];
                        $this->corresp_net_mileage_diff[] =$x;
                        $prev = $x;
                        $db->query("UPDATE sludge_grease_data_table SET net_mileage =$x WHERE schedule_id = $calc[schedule_id]");
                    } else {
                        $x = $calc['mileage'] - $prev;
                        $this->corresp_net_mileage_diff[] =$x;
                        $db->query("UPDATE sludge_grease_data_table SET net_mileage =$x WHERE schedule_id =  $calc[schedule_id]");
                        $prev = $calc['mileage'];
                    }
                    
                    
                }
             }
             
             $this->total_pickup_hours = $this->last_stop - $this->first_stop;
             $this->sum_net_route_mileage = array_sum($this->corresp_net_mileage_diff);
            
             
             
             if($this->stops >0){
                 $this->avg_gal_per_stop = number_format($this->net_gallons/$this->stops,2);
                 $this->avg_mile_per_stop = number_format($this->sum_net_route_mileage/$this->stops,2);
                 $this->avg_min_per_stop = number_format(($this->total_pickup_hours*60)/$this->stops,2);
             }
             
             
            $fiov = $db->query("SELECT value FROM overhead_value WHERE id=1");
            if(count($fiov)>0){
                $this->fixed_overhead = $fiov[0]['value'];
            }else {
                $this->fixed_overhead = 0;
            }
            
            
            //***************************************************************
            $this->total_hours =  time_end($this->route_id,1) - time_start($this->route_id,1);
            if($this->total_hours>8){
                $this->reg_hours = 8;
            } else {
                $this->reg_hours = $this->total_hours;
            }
            
            if($this->total_hours >12){
                $this->dt_hours = $this->total_hours - 12;
            } else {
                $this->dt_hours = 0;
            }
            
            if($this->total_hours>8){
                $this->ot_hours = $this->total_hours - 8 - $dt;
            }else{
                $this->ot_hours = 0;
            }
            
            
            
            $this->labor_cost =( ($this->reg_hours*$person->driver_hourly_pay)+($this->ot_hours*$person->driver_hourly_pay*1.5)+($this->dt_hours*$person->driver_hourly_pay*2) );
            
            
            
            if($truck->mpg >0){
                $this->avg_fuel = $this->sum_total_mileage / $truck->mpg;
            }else {
                $this->avg_fuel = 0;
            }
            
            $this->fuel_cost = $this->avg_fuel * $this->fuel_per_gallon;
            
            
            
            $this->var_op_cost = variable_operating(
                $this->labor_cost,
                $this->sum_total_mileage,
                $truck->mpg,
                $this->net_gallons,
                $truck->r_m,
                $trailer->r_m,
                $this->fuel_cost,
                ( ($truck->dep/21)/10 ) ,
                $this->water_treatment_cost,
                ( ($trailer->dep/21)/10 ),
                $this->other_expense_value, 
                $this->reg_hours);             
            
            //****************************************************************
            
            
             
              $inc = $db->query("SELECT COUNT(DISTINCT(account_no)) as inc FROM sludge_grease_traps WHERE route_status IN ('enroute') AND grease_route_no = $this->route_id");
              if(count($inc)>0){
                $this->inc =$inc[0]['inc'];
              } else {
                $this->inc =0;
              }
              
              
              $total_stops = $db->query("SELECT COUNT(DISTINCT(account_no)) as total_stops FROM sludge_grease_traps WHERE grease_route_no = $this->route_id");
              if(count($total_stops)>0){
                $this->total_stops = $total_stops[0]['total_stops'];
              } else {
                $this->total_stops = 0;
              }
              $db->query("UPDATE sludge_list_of_grease SET inc = $this->inc, stops = $this->total_stops WHERE route_id = $this->route_id");
              
              
             
        }
        
        
    }
    
    
}



?>