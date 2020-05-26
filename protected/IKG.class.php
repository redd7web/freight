<?php
ini_set("display_errors",0);
class IKG {
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
    public $difference_weight;
    public $collected_Weight;
    
    //************* SCHEDULED ROUTES ASSOCIATED WITH THIS ROUTE **///
    public $scheduled_routes = array();
    public $scheduled_route_notes;
    public $can_close;
    
    
    
    public $r_total_lb_per_mile;
    public $lb_per_mile;
    public $total_mileage;
    public $total_net;
    public $time_elapsed;
    public $net_route_miles;
    public $route_elapsed;
    public $unique_friendly = array();
    public $driver_completed_date;
    function __construct($route_id = NULL){
            global $dbprefix;
            $data_table = $dbprefix."_data_table";
            $db = new Database();
            $buff = array();
            if (is_numeric($route_id)){
             
             $ikg_table = $dbprefix."_ikg_manifest_info";
             $from_oilrouted = $db->query("SELECT * FROM $ikg_table WHERE route_id=$route_id");
             
              //UPDATE FIRST STOP/ MILEAGE INFO 
              $fs = $db->query("SELECT DISTINCT(schedule_id),mileage,date_of_pickup FROM sludge_data_table WHERE route_id= $route_id ORDER BY date_of_pickup DESC");
              $ls = $db->query("SELECT DISTINCT(schedule_id),mileage,date_of_pickup FROM sludge_data_table WHERE route_id= $route_id ORDER BY date_of_pickup ASC");
              
              
              
              
              
              //UPDATE FIRST STOP/ MILEAGE INFO 
              
             if(count($from_oilrouted) >0){
               foreach($from_oilrouted as $value){
                   $this->scheduled_date = $value['scheduled_date'];
                   $this->ikg_manifest_route_number = $value['ikg_manifest_route_number'];
                   $this->completed_date = $value['completed_date'];
                   $this->route_id = $value['route_id'];
                   //$this->pick_upID = $value['entry_number'];
                   $this->acount_numbers_full_string = $value['account_numbers'];
                   //********* SPLITS account numbers into member array
                   $this->account_numbers =  explode("|",$value['account_numbers']);
                   array_pop($this->account_numbers);
                   //*********                         ***********//
                  
                   
                   $this->inventory_code = $value['inventory_code'];
                   $this->lot_no = $value['lot_no'];
                   $this->recieving_facility = numberToFacility($value['recieving_facility']);
                   $this->recieving_facility_no = $value['recieving_facility'];
                   $this->facility_address = $value['facility_address'];
                   $this->facility_rep = $value['facility_rep'];
                   $this->driver = $value['driver'];
                   $this->ikg_transporter = $value['ikg_transporter'];
                   $this->number_days_route = $value['number_days_route'];
                   $this->tank1 = $value['tank1'];
                   $this->tank2 = $value['tank2'];
                   $this->truck = $value['truck'];
                   $this->license_plate = $value['license_plate'];
                   $this->ikg_decal = $value['ikg_decal'];
                   $this->location = $value['location'];                   
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
                   $this->difference_weight = $value['differences'] ;
                   $this->can_close = $value['can_close'];
                   $this->collected_Weight = $value['collected'];
                   $this->driver_completed_date = $value['driver_completed_date'];
               }
                
               if(count($fs)>1){// more than one stop
                $last = count($fs) - 1;
                $packa = array(
                    "last_top_mileage"=>$fs[0]['mileage'],
                    "last_stop"=>$fs[0]['date_of_pickup'],
                    "first_stop"=>$fs[$last]['date_of_pickup'],
                    "first_stop_mileage"=>$fs[$last]['mileage']
                );
                
                
                $db->where("route_id",$route_id)->update("$ikg_table",$packa);
                
                $this->net_route_miles = 0;
                
                $uyc = $db->query("SELECT DISTINCT (
schedule_id
), mileage, route_id, date_of_pickup
FROM `sludge_data_table`
WHERE route_id =$route_id 
GROUP BY schedule_id ORDER BY date_of_pickup DESC LIMIT 0,1");
                $cyu = $db->query("SELECT DISTINCT (
schedule_id
), mileage, route_id, date_of_pickup
FROM `sludge_data_table`
WHERE route_id =$route_id 
GROUP BY schedule_id ORDER BY date_of_pickup ASC LIMIT 0,1");
                
                $this->net_route_miles = $uyc[0]['mileage'] - $cyu[0]['mileage'];
                
                if(count($fs)>0){//calculate running route miles
                    
                    $checkTime = strtotime($this->last_stop);
                    $loginTime = strtotime($this->first_stop);
                    $diff = $checkTime - $loginTime;
                    $this->route_elapsed =  abs(number_format($diff/3600,2))." Hours";
                }     
                $this->total_mileage = $this->end_mileage - $this->start_mileage;
              }else if(count($fs) == 1){// if there is currently only 1 stop in the route then the first is the last
                $packa = array(
                    "first_stop_mileage"=>$fs[0]['mileage'],
                    "last_top_mileage"=>$fs[0]['mileage'],
                    "first_stop"=>$fs[0]['date_of_pickup'],
                    "last_stop"=>$fs[0]['date_of_pickup']
                    
                );
                $db->where("route_id",$route_id)->update("$ikg_table",$packa);
                
                
                $checkTime = strtotime($this->first_stop);
                $loginTime = strtotime($this->scheduled_date." ".$this->time_start);
                $diff = $checkTime - $loginTime;
                $this->route_elapsed =  abs(number_format($diff/3600,2))." Hours";
                $this->total_mileage = $this->first_stop_mileage - $this->start_mileage;
              } 
               
              
                
                //********************collected gallons for this  route ******///
                
                $buf4 = $db->query("SELECT SUM(inches_to_gallons) as tote FROM $data_table WHERE route_id=$this->route_id");
                if(count($buf4)>0){
                     $this->collected_Weight  = round($buf4[0]['tote'] *7.56,2);
                     $this->collected = $buf4[0]['tote'];
                } else {
                    $this->collected = 0;
                    $this->collected_Weight = 0;
                }
                     
                
                if($this->total_mileage>0){
                    $this->lb_per_mile = $this->collected_Weight/ $this->total_mileage   ;
                }
                
                 //***********************************************************//
                 if($this->net_route_miles !=0){ 
                    $this->r_total_lb_per_mile =  number_format($this->collected_Weight / $this->net_route_miles,2);       
                 } else {
                    $this->r_total_lb_per_mile = 0;
                 }
                 
                
                //***************** LIST OF ROUTES DB INFO ************************//
               $upo = $db->where('route_id',$route_id)->get($dbprefix.'_list_of_routes');
               if(count($upo)>0){
                    foreach ($upo as $value2){
                        $this->list_of_routes_id = $value2['list_of_routes_id'];
                        $this->route_status = $value2['status'];
                        $this->created_date = $value2['created_date'];
                        $this->created_by = uNumToName($value2['created_by']);
                        $this->stops = $value2['stops'];
                        $this->expected = $value2['expected'];
                    }
                }
                
                //************** SCHEDULED ROUTES / NOTES DB **********************//
                
                if(strlen($this->acount_numbers_full_string) >0  && $this->acount_numbers_full_string !="|"  ){
                    foreach($this->account_numbers as $act){
                        $yuc = $db->query("SELECT friendly FROM sludge_accounts WHERE account_ID = $act");
                        if( !in_array($yuc[0]['friendly'],$buff)  ){
                             $buff[]= $yuc[0]['friendly'];
                        }   
                        
                        
                       $ik = $db->where('route_id',$this->route_id)->where('account_no',$act)->get($dbprefix."_scheduled_routes","schedule_id,account_no,route_id");
                       if(count($ik)>0){
                        $this->scheduled_routes[] = $ik[0]['schedule_id']; 
                       }
                    }
                    $this->unique_friendly = array_unique($buff);
                }
                
             }
        }
        else { 
             $ikg_table = $dbprefix."_ikg_manifest_info";
             $from_oilrouted = $db->query("SELECT * FROM $ikg_table WHERE ikg_manifest_route_number ='$route_id'");
        }
        
        
        
        
        
        if(strlen($this->time_start)>0 && strlen($this->end_time)>0){
            $checkTime = strtotime($this->time_start);
            $loginTime = strtotime($this->end_time);
            $diff = $checkTime - $loginTime;
            $this->time_elapsed = abs(round($diff/3600,2));
        }
        
        
        
    }
    
    
    
    function fix_self($route_id){
        global $db;
        
        
        
        //******************* FIX ACCOUNT NUMBERS LIST IF COMPLETELY EMPTY ***********************************//
        $check_empty = $db->query("SELECT account_numbers FROM sludge_ikg_manifest_info WHERE route_id=$route_id AND (account_numbers IS NULL OR account_numbers ='' OR account_numbers ='|')");
        
        
        if(count($check_empty)>0){
            $full = $db->query("SELECT account_no, GROUP_CONCAT(account_no SEPARATOR '|') as full
FROM sludge_scheduled_routes WHERE route_id=$route_id");
            if(count($full)>0){
                $string = $full[0]['full']."|";
                $db->query("UPDATE sludge_ikg_manifest_info SET account_numbers='$string' WHERE route_id=$route_id");
            }

        }
        //******************* FIX ACCOUNT NUMBERS LIST IF COMPLETELY EMPTY OR JUST PIPE ***********************************//
        
        
        //********************* FIX ACCOUNT NUMBERS LIST IF NECESSARY (MISSING PIPES)**********************************//        
        $uo = $db->query("SELECT account_numbers FROM sludge_ikg_manifest_info WHERE route_id=$route_id AND account_numbers NOT LIKE '%|%'");
        if(count($uo)>0){
            $ko = wordwrap($uo[0]['account_numbers'],5,'|',true);
            $ko = $ko."|";
            $ko = str_replace("||","|",$ko);
            //echo $ko."<br/><br/>";
            $db->query("UPDATE sludge_ikg_manifest_info SET account_numbers = '$ko' WHERE route_id=$route_id");
        }
        //********************* FIX ACCOUNT NUMBERS LIST IF NECESSARY (MISSING PIPES)**********************************//
        
        




        
        
        
        
        $db->query("UPDATE sludge_scheduled_routes SET route_status='completed' WHERE route_id=$route_id AND schedule_id IN( SELECT schedule_id FROM sludge_data_table WHERE route_id=$route_id)");
        //***************** FIND COMPLETED STOPS AND MARK AS COMPLETE ****************************************//
        
        $db->query("UPDATE sludge_scheduled_routes SET route_status='enroute' WHERE route_status NOT IN ('completed', 'complete'
        ) AND route_id =$route_id");
        
        
        
        //*******************  FIND UNCOMPLETED STOPS **********************************//
        $nj =  $db->query("SELECT count(schedule_id) as inkomp FROM sludge_scheduled_routes WHERE route_id= $route_id AND route_status='enroute'");
        $bud = $nj[0]['inkomp'];
        if(count($nj)>0){
            $db->query("UPDATE sludge_list_of_routes SET inc = $bud WHERE route_id=$route_id");    
        }
        
        
        
        
        $ko =0;
        $y = $db->query("SELECT SUM(inches_to_gallons) as cur_tot FROM sludge_data_table WHERE route_id=$route_id");
        
        
        if($y[0]['cur_tot']>0){
            $ko = $y[0]['cur_tot'];  
        }
        $db->query("UPDATE sludge_list_of_routes SET collected = $ko WHERE route_id=$route_id");
    }
}



?>