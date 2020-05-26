<?php
ini_set("display_errors",0);
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
    public $credits;
    public $credit_notes;
    public $prepay;
    public $jetting;
    public $mult_day_route;
    public $ppg;
    public $locked;
    public $mlocked;
    public $charge;
    public $payment_method;
    public $manual_ok;
    public $zero_charge_pickup;
    public $zero_gallon_reason;
    public $custom_charge;
    public $emergency;
    public $jet_charge;
    public $jet_hours;
    public $account_address;
    public $account_city;
    public $account_state;
    public $account_zip;
    public $division;
    public $code_red_email;
    public $cc_on_file;
    public $percent_split;
    public function __construct($schedule_id) {        
        if($schedule_id != NULL){
            $db = new Database();
            global $dbprefix;
            $select = $db->where("grease_no",$schedule_id)->get("sludge_grease_traps");
            
            if(count($select) >0){
                $account_info = new Account();
                foreach($select as $value){
                    $this->grease_no =              $value['grease_no'];
                    $this->grease_route_no =        $value['grease_route_no'];
                    $this->service_date =           $value['service_date'];
                    //$this->volume =                 $value['volume'];
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
                    $this->credits =                $value['credits'];
                    $this->credit_notes =           $value['credit_notes'];
                    $this->prepay   =               $value['prepay'];
                    $this->jetting =                $value['jetting'];        
                    $this->mult_day_route =         $value['multi_day_stop'];                    
                    $this->payment_method =         $value['payment_method'];
                    $this->ppg =                    $value['price_per_gallon'];
                    $this->manual_ok =              $value['manual_ok'];
                    $this->zero_charge_pickup =     $value['zero_charge_pickup'];
                    $this->custom_charge    =       $value['custom_charge'];
                    $this->emergency =              $value['emergency'];
                    $this->account_address =        $account_info->singleField($this->account_number,"address");
                    $this->account_city     =       $account_info->singleField($this->account_number,"city");
                    $this->account_state     =      $account_info->singleField($this->account_number,"state");
                    $this->account_zip     =        $account_info->singleField($this->account_number,"zip");
                    $this->division         =       $account_info->singleField($this->account_number,"division");
                    $this->code_red_email   =       $account_info->singleField($this->account_number,"code_red_email");
                    $this->cc_on_file       =       $account_info->singleField($this->account_number,"cc_on_file");
                    $this->percent_split    =       $value['percent_split'];
                }
                $aq =  $db->query("SELECT zero_gallon_reason FROM sludge_data_table WHERE account_no = $this->account_number AND schedule_id = $this->grease_no");
                if( count($aq)>0){
                    $this->zero_gallon_reason = $aq[0]['zero_gallon_reason'];
                }else{
                    $this->zero_gallon_reason = "";
                }
                
                $routes = $db->query("SELECT net_weight FROM sludge_ikg_grease WHERE route_id = $this->grease_route_no");
                
                $this->volume = (($this->percent_split/100) * $routes[0]['net_weight'])/8.34;
                
               
            }
        }
        else {
            
        }
    }
    
    
   
    
}

?>