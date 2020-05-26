<?php
class Container_Route{
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
   
    function __construct($route_id = NULL){ 
        if($route_id != NULL){
            global $dbprefix;
            global $facils;
            
            $container_info = $dbprefix."_list_of_containers";
            $utility = $dbprefix."_ikg_utility";
            $db = new Database();   
            
            if( !is_numeric( $route_id) ){
                $results = $db->where("ikg_manifest_route_number",$route_id)->get($dbprefix."_ikg_utility","*");  
            }
            else {
                $results = $db->where("route_id",$route_id)->get($dbprefix."_ikg_utility","*");  
                //echo "integer!"; 
            }
            
            
                        
            if(count($results)>0){
                
                
                $this->ikg_manifest_route_number = $results[0]['ikg_manifest_route_number'];
                $this->scheduled_date = $results[0]['scheduled_date'];
                $this->completed_date = $results[0]['completed_date'];
                $this->route_id = $results[0]['route_id'];
                $this->acount_numbers_full_string = $results[0]['account_numbers'];
                
                $buffer = explode("|",$this->acount_numbers_full_string);
                foreach($buffer as $kju){
                    $this->account_numbers[] = $kju;
                }
                array_pop($this->account_numbers);
                $this->inventory_code = $results[0]['inventory_code'];
                $this->lot_no = $results[0]['lot_no'];
                $this->recieving_facility_no = $results[0]['recieving_facility'];
                $this->recieving_facility =  numberToFacility($results[0]['recieving_facility']);
                $this->facility_address = $facils[$results[0]['recieving_facility']];
                $this->facility_rep = $results[0]['facility_rep'];
                $this->driver_no = $results[0]['driver'];
                $this->driver = 
                $this->ikg_transporter = $results[0]['ikg_transporter'];
                $this->number_days_route = $results[0]['number_days_route'];
                $this->tank1 = $results[0]['tank1'];
                $this->tank2 = $results[0]['tank2'];
                $this->truck = $results[0]['truck'];
                $this->license_plate = $results[0]['license_plate'];
                $this->ikg_decal = $results[0]['ikg_decal'];
                $this->location = $results[0]['location'];
                $this->ikg_collected =$results[0]['ikg_collected'];
                $this->ikg_gross_weight = $results[0]['gross_weight'];
                $this->tare_weight = $results[0]['tare_weight'];
                $this->net_weight = $results[0]['net_weight'];
                $this->time_start = $results[0]['time_start'];
                $this->start_mileage = $results[0]['start_mileage'];
                $this->first_stop = $results[0]['first_stop'];
                $this->first_stop_mileage = $results[0]['first_stop_mileage'];
                $this->last_stop = $results[0]['last_stop'];    
                $this->last_stop_mileage = $results[0]['last_top_mileage'];
                $this->end_time = $results[0]['end_time'];
                $this->end_mileage = $results[0]['end_mileage'];
                $this->fuel = $results[0]['fuel'];       
                
                
            }
            
        }
    }
}

?>