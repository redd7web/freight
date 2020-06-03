<?php
class Vehicle {
    public $truck_id;
    public $truck_year;
    public $truck_make;
    public $truck_model;
    public $truck_no;
    public $lp_no;
    public $ikg_decal;
    public $max_capacity;
    public $coa;
    public $rm_total;
    public $start_mileage;
    public $current_mileage;
    public $mpg;
    public $facility;
    public $facility_no;
    public $division;
    public $overhead;
    public $misc_cost;
    public $status;
    public $name;
    public $type;
    public $acquired;
    public $renewed;
    public $expires;
    public $vin;
    public $plates;
    public $description;
    public $state_acquired;
    public $notes;
    public $placard;
    public $lic_require;
    public $insurance_id;
    public $carrier;
    public $w_empty;
    public $ikg_code;
    public $r_m;
    public $dep;
    public $cap;
    public $camera;
    public $gps;
    public $opacity;
    public $service;
    public $renew;
    public $due_90;
    public $annual;
    public $registration;
    public $other_permit;
    public $repair;
    public $module;
    public $endis;
    public $typex;
    public $mileage_for_next_service;
    
    function __construct($id= NULL){
        $db = new Database();
        global $dbprefix;
        if(isset($id)){
            $result = $db->where("truck.truck_id",$id)->get("assets.truck");   
            if(count($result)>0){
                $this->truck_id = $result[0]['truck_id'];
                $this->truck_year = $result[0]['truck_year'];
                $this->truck_make = $result[0]['truck_make'];
                $this->truck_model = $result[0]['truck_model'];
                $this->truck_no = $result[0]['truck_no'];
                $this->lp_no = $result[0]['plates'];
                $this->ikg_decal = $result[0]['ikg_decal'];
                $this->max_capacity = $result[0]['max_capacity'];
                $this->coa = $result[0]['coa'];
                $this->rm_total = $result[0]['rm_total'];
                $this->start_mileage = $result[0]['start_mileage'];
                $this->current_mileage = $result[0]['current_mileage'];
                $this->mpg = $result[0]['mpg'];
                $this->facility_no = $result[0]['facility'];
                $this->facility = numberToFacility($result[0]['facility']);
                $this->division = $result[0]['division'];
                $this->overhead = $result[0]['overhead'];
                $this->misc_cost = $result[0]['misc_cost'];
                $this->status = $result[0]['status'];
                $this->name = $result[0]['name'];
                $this->type = $result[0]['type'];
                $this->acquired = $result[0]['acquired'];
                $this->renewed = $result[0]['renewed'];
                $this->expires = $result[0]['expires'];
                $this->vin = $result[0]['vin'];
                $this->plates = $result[0]['plates']; 
                $this->description = $result[0]['description'];
                $this->state_acquired = $result[0]['state_acquired'];
                $this->notes = $result[0]['notes'];   
                $this->placard = $result[0]['placard'];
                $this->lic_require = $result[0]['lic_requirement'];
                $this->insurance_id = $result[0]['insurance_id'];
                $this->carrier = $result[0]['insurance_carrier']; 
                $this->w_empty = $result[0]['weight_empty']; 
                $this->ikg_code = $result[0]['ikg_code'];
                $this->r_m = $result[0]['r_m'];
                $this->dep = $result[0]['dep_rate'];
                $this->camera = $result[0]['camera_installed'];
                $this->gps = $result[0]['gps_installed'];
                $this->opacity = $result[0]['opacity_due'];
                $this->service = $result[0]['service_due'];
                $this->renew = $result[0]['ikg_renewed'];
                $this->due_90 = $result[0]['due_90'];
                $this->annual = $result[0]['annual'];
                $this->registration = $result[0]['registration'];
                $this->repair = $result[0]['repair_date'];
                $this->other_permit = $result[0]['other_permit_due'];
                $this->module = $result[0]['module'];
                $this->endis = $result[0]['enabled'];
                $this->typex = "truck";
                $this->mileage_for_next_service = $result[0]['service_at_next_mileage'];
            }
        }else{
            $this->truck_id = "";
            $this->truck_year = "";
            $this->truck_make = "";
            $this->truck_model  = "";
            $this->truck_no  = "";
            $this->lp_no  = "";
            $this->ikg_decal  = "";
            $this->max_capacity  = "";
            $this->coa = "";
            $this->rm_total = "";
            $this->start_mileage = "";
            $this->current_mileage = "";
            $this->mpg = "";
            $this->facility_no = "";
            $this->facility = "";
            $this->division = "";
            $this->overhead = "";
            $this->misc_cost = "";
            $this->status = "";
            $this->name = "";
            $this->type = "";
            $this->acquired = "";
            $this->renewed = "";
            $this->expires = "";
            $this->vin = "";
            $this->plates = ""; 
            $this->description = "";
            $this->state_acquired = "";
            $this->notes = "";   
            $this->placard = "";
            $this->lic_require = "";
            $this->insurance_id = "";
            $this->carrier = ""; 
            $this->w_empty = ""; 
            $this->ikg_code = "";
            $this->r_m = "";
            $this->dep = "";
            $this->camera = "";
            $this->gps = "";
            $this->opacity = "";
            $this->service = "";
            $this->renew = "";
            $this->due_90 = "";
            $this->annual = "";
            $this->registration = "";
            $this->repair = "";
            $this->other_permit = "";
            $this->module = "";
            $this->endis = "";
            $this->typex = "";
            $this->mileage_for_next_service = "";
        }
    }    
    
    function SelectList(){
        $db = new Database();
        $select ="<select id='vehicle' name='vehicle'>";
        $ryt = $db->get("freight_truck_id");
        if(count($ryt) !=0){
            foreach($ryt as $truck){
                $select .="<option value='$truck[truck_no]'>$truck[name]</option>";
            }
        }    
        $select .="</select>";
        
        return $select;
    }
    
}



?>