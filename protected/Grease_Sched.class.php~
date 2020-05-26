<?php

class Grease_Stop{ 
    public $grease_no;
    public $grease_route_no;
    public $service_date;
    public $facility_number;
    public $facility_origin;
    public $route_status;
    public $created_by;
    public $driver;
    public $account_name;
    public $account_number;
    public $code_red;
    public $date_created;
    public $notes;
    public $special_instructions;
    public $note_author;
    public $note_created_date;
    public $gals;
    public $created_date;
    public $volume;
    public $addt_info;
    public function __construct($schedule_id) {        
        if($schedule_id != NULL){
            $db = new Database();
            global $dbprefix;
            $select = $db->where("grease_no",$schedule_id)->get($dbprefix."_grease_traps");
            if(count($select) >0){
                foreach($select as $value){
                    $this->grease_no =              $value['grease_no'];
                    $this->grease_route_no =        $value['grease_route_no'];
                    $this->service_date =           $value['service_date'];
                    $this->volume =                 $value['volume'];
                    $this->route_status =           $value['route_status'];
                    $this->created_by =             uNumToName($value['created_by']);
                    
                    if(  isset($value['driver'] ) ){
                        $this->driver =             uNumToName($value['driver']);    
                    } else { 
                        $this->driver = "";
                    }
                    $this->account_name =             account_NumtoName($value['account_no']);
                    $this->account_number =         $value['account_no'];
                    $this->code_red =               $value['fire'];
                    
                    $this->date_created =           $value['date'];
                    $this->created_date =           $value['date'];
                    $this->notes =                  $value['notes'];
                    $this->addt_info =              $value['addt_info'];            
                }
            }
        }
        else {
            
        }
    }
    
    
   
    
}

?>