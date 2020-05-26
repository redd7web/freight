<?php

class Scheduled_Routes { 
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
    public $notes;
    public $special_instructions;
    public $note_author;
    public $note_created_date;
    public $gals;
    public $created_date;
    public $onsite;
    public $account_friendly;
    public $cs_reason;
    public $account_address;
    public $account_city;
    public $account_state;
    public $account_zip;
    public $division;
    public $code_red_email;
    public $cc_on_file;
    public function __construct($schedule_id) {
        
        if($schedule_id != NULL){
            $db = new Database();
            global $dbprefix;
            $select = $db->where("schedule_id",$schedule_id)->get($dbprefix."_scheduled_routes");
            if(count($select) >0){
                $account_info = new Account();
                
                foreach($select as $value){
                    $this->schedule_id = $value['schedule_id'];
                    $this->route_id = $value['route_id'];
                    $this->scheduled_start_date =   $value['scheduled_start_date'];
                    $this->facility_origin =        numberToFacility($value['facility_origin']);
                    $this->route_status =           $value['route_status'];
                    $this->created_by =             uNumToName($value['created_by']);
                    $this->facility_number =        $value['facility_origin'];
                    if(  isset($value['driver'] ) ){
                        $this->driver =             uNumToName($value['driver']);    
                    } else { 
                        $this->driver = "";
                    }
                    $this->account_name =             account_NumtoName($value['account_no']);
                    $this->account_number =         $value['account_no'];
                    $this->code_red =               $value['code_red'];
                    $this->en_route_id =            $value['en_route_id'];
                    $this->date_created =           $value['date_created'];
                    $this->cs_reason =              $value['cs_reason'];
                    $this->account_address =        $account_info->singleField($this->account_number,"address");
                    $this->account_city     =       $account_info->singleField($this->account_number,"city");
                    $this->account_state     =      $account_info->singleField($this->account_number,"state");
                    $this->account_zip     =        $account_info->singleField($this->account_number,"zip");
                    $this->division         =       $account_info->singleField($this->account_number,"division");
                    $this->code_red_email   =       $account_info->singleField($this->account_number,"code_red_email");
                    
                    $account_info = $db->query("SELECT friendly FROM sludge_accounts WHERE account_ID = $this->account_number AND (sludge_accounts.friendly IS NOT NULL AND sludge_accounts.friendly != 'null' AND sludge_accounts.friendly !=' ')");
                    if(count($account_info)>0){
                        $this->account_friendly = $account_info[0]['friendly'];
                    } else {
                        $this->account_friendly = "No";
                    }
                    
                    
                    $select2 = $db->where("schedule_id",$this->schedule_id)->get("sludge_notes");
                    
                    $note_spec = $db->query("SELECT * FROM sludge_notes WHERE schedule_id = $this->schedule_id");
                    if(count($note_spec)>0){
                        $buffer = explode("|",$note_spec[0]['notes']);
                        
                        if(count($buffer)>0){
                            if(strlen($buffer[0])>0){
                                $this->notes = $buffer[0];
                            }
                            
                            if(strlen($buffer[1]) ){
                                $this->special_instructions = $buffer[1];
                            }
                            
                        }
                        
                    }
                    
                    
                                   
                }
            }
            
            $kl = $db->where("schedule_id",$this->schedule_id)->get($dbprefix."_data_table","*");
            if(count($kl)>0){
                $this->gals = $kl[0]['inches_to_gallons'];
            } else {
                $this->gals = 0;
            }
        }
        else {
            
        }
    }
    
    
    function checkPickupCompleted($route_id){
        global $dbprefix;
        global $db;
        
        $jhg = $db->where("route_id",$route_id)->where("completed",1)->get($dbprefix."_data_table","date_of_pickup");
        
        if(count($jhg)>0){
            return $jhg[0]['date_of_pickup'];
        }
        return 0;
    }

    function getGallonsForPickup($route_id){
        global $dbprefix;
        global $db;
        $total_gallons = 0;
        $jhg = $db->where("route_id",$route_id)->where("completed",1)->get($dbprefix."_data_table","inches_to_gallons");
        
        if(count($jhg)>0){
            foreach($jhg as $pickups){
                $total_gallons = $total_gallons + $pickups['inches_to_gallons'];
            }
        }
        return $total_gallons;
    }
    
}

?>