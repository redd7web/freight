<?php

class Util_Stop { 
    public $schedule_id;
    public $route_id;
    public $scheduled_start_date;
    public $facility_number;
    public $facility_origin;
    public $route_status;
    public $created_by;
    public $driver;
    public $account_name;
    public $account_number;
    public $code_red;
    public $date_created;
    public $account_address;
    public $account_city;
    public $account_state;
    public $account_zip;
    public $division;
    public $code_red_email;
    public $cc_on_file;
    public function __construct($schedule_id = NULL) {
        
        if(is_numeric($schedule_id)){
            $db = new Database();
            global $dbprefix;
            $select = $db->query("SELECT * FROM freight_utility WHERE utility_sched_id = $schedule_id");
            
            if(count($select) >0){
                $account_info = new Account();
                foreach($select as $value){
                    $this->schedule_id =    $value['utility_sched_id'];
                    $this->route_id = $value['rout_no'];
                    $this->code_red =               $value['code_red'];
                    $this->scheduled_start_date =   $value['date_of_service'];
                    $this->route_status =           $value['route_status'];
                    $this->account_name =           account_NumtoName($value['account_no']);
                    $this->account_number =         $value['account_no'];
                    $this->account_address =        $account_info->singleField($this->account_number,"address");
                    $this->account_city     =       $account_info->singleField($this->account_number,"city");
                    $this->account_state     =      $account_info->singleField($this->account_number,"state");
                    $this->account_zip     =        $account_info->singleField($this->account_number,"zip");
                    $this->division         =       $account_info->singleField($this->account_number,"division");
                    $this->code_red_email   =       $account_info->singleField($this->account_number,"code_red_email");
                    $this->cc_on_file       =       $account_info->singleField($this->account_number,"cc_on_file");
                    
                    
                    //$this->created_date =           $value['date_created'];
                    //$this->facility_origin =        numberToFacility($value['facility_origin']);
                    //$this->created_by =             uNumToName($value['created_by']);
                    //$this->facility_number =        $value['facility_origin'];
                }
               
            }
            
            
        }
        else {
            
        }
    }
    
    
   
    
}

?>