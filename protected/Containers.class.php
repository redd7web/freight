<?php
Class Container { 
    
    public $container_no ;
    public $account_no;
    public $request_date ;
    public $delivery_date ;
    public $removal_date ;
   // public $container_label;
    public $notes;
    public $serial_number ;
    public $assigned_by ;    
    
    public $container_label ;
    public $amounts_holds ;
    public $gpi ;
    public $number_of_containers_assigned;
    function __contruct($account_no){
       $db = new Database();
       global $dbprefix;
       
       
    
    }
    
    
   
   function updateRequestDate($value,$container){ 
        $db = new Database();
        $data = array (
            "request_date"=>$value
        );
        $db->where("container_no",$this->account_no)->where("container_no",$container)->update("sludge_containers",$data);
   }
   
   function updateDeliveryDate($value,$container){
        $db= new Database();
        $data = array (
            "delivery_date"=>$value        
        );
        $db->where("account_no",$this->account_no)->where("container_no",$container)->update("sludge_containers",$data);
   }
   
   function updateRemovalDate($value,$container){ 
        $db = new Database();
        $data = array(
            "removal_date"=>$value
        );
        $db->where("account_no",$this->account_no)->where("container_no",$container)->update("sludge_containers",$data);
   }
   
   function updateContainer($value,$container){
        $db = new Database();
        $data = array(
            "container"=>$value
        );
        $db->where("account_no",$this->account_no)->where("container_no",$container)->update("sludge_containers",$data);
   }
   
   function updateNotes($value,$container) { 
        $db = new Database();
        $data = array(
            "notes"=>$value
        );
        $db->where("account_no",$this->account_no)->where("container_no",$container)->update("sludge_containers",$data);
   }
   
   function updateSerial($value,$container){ 
        $db = new Database();
        $data = array(
            "serial_number"=>$value
        );
        $db->where("account_no",$this->account_no)->where("container_no",$container)->update("sludge_containers",$data);
   }
   
   function updateAssignedBy($value,$container){ 
        $db = new Database();
        $data = array(
            "container"=>$value
        );
        $db->where("account_no",$this->account_no)->where("container_no",$container)->update("sludge_containers",$data);
   }
    
}


?>