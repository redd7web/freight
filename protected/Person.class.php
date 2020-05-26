<?php

class Person{
     public $first_name;
     public $last_name;
     public $middle_name;
     public $email;
     public $address;
     public $city;
     public $state;
     public $zipcode;
     public $areacode;
     public $phone;
     public $carrier;
     public $roles;
     public $title;
     public $facility;
     public $user_id;
     public $staff_id;
     public $account_created;
     public $last_login;
     public $duty;
     public $login_name;
     public $password;
     public $facility_restriction;
     public $division_restriction;
     public $driver_hourly_pay;
     public $notes;
     public $fullphone;
     public $fullname;
     public $friendly;
    
     function __construct($id = NULL){ 
        if($id == NULL){ 
            $ix = $_SESSION['sludge_id'];
        }else { 
            $ix = $id;
        }
        $db = new Database();  
        $pull = $db->where("user_id","$ix")->get("sludge_users","*");
        
        $this->first_name            = $pull[0]['first'];
        $this->last_name             = $pull[0]['last'];
        $this->middle_name           = $pull[0]['middle'];
        $this->email                 = $pull[0]['email'];
        $this->address               = $pull[0]['address'];
        $this->city                  = $pull[0]['city'];
        $this->state                 = $pull[0]['state'];
        $this->zipcode               = $pull[0]['zip'];
        $this->areacode              = $pull[0]['areacode'];
        $this->phone                 = $pull[0]['phone'];
        $this->carrier               = $pull[0]['carrier'];
        $this->roles                 = explode("~",$pull[0]['roles']);
        $this->title                 = $pull[0]['title'];
        $this->facility              = $pull[0]['facility'];
        $this->user_id               = $pull[0]['user_id']; 
        $this->staff_id              = $pull[0]['staff_id'];
        $this->account_created       = $pull[0]['account_created'];
        $this->last_login            = $pull[0]['last_login'];
        $this->duty                  = $pull[0]['duty'];
        $this->login_name            = $pull[0]['login_name'];
        $this->password              = $pull[0]['password'];
        $this->facility_restriction  = $pull[0]['facility_restriction'];
        $this->division_restriction  = $pull[0]['division_restriction'];
        $this->driver_hourly_pay     = $pull[0]['driver_hourly_pay'];
        $this->notes                 = $pull[0]['notes'];
        $this->fullphone             = "(".$pull[0]['areacode'].")-".$pull[0]['phone'];
        $this->fullname              = $pull[0]['first']." ".$pull[0]['last'];
        $this->friendly              = $pull{0}['friendly']; 
        
     }
     
       
      function isPayroll(){
        if(in_array("payrollmanager",$this->roles)){
            return true;
        }
      } 
       
      function isAdmin(){ 
        if(in_array("admin",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
    
    function isCustSupport_Basic(){
        if(in_array("customer support",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isShopCrew(){
        if(in_array("shop crew",$this->roles)){
            return true;
        }else {
            return false;
        }
     }
     
     function isFriendly(){
        if(in_array("Friendly",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isAccountRep(){
        if(in_array("account represntative",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     
    function isSalesRep(){
        if(in_array("sales representative",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
    
     function isSalesLeads(){
        if(in_array("sales leads",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isServiceDriver(){
        if(in_array("service driver",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isOilDriver(){
        if(in_array("oil driver",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isCoWest(){
        if(in_array("cowest",$this->roles)){
            return true;
        } else {
            return false;
        }
     }
     
     function isCoWestDriver(){
        if(in_array("cowestdriver",$this->roles)){
            return true;
        } else {
            return false;
        }
     }
     
     function isScheduler(){
        if(in_array("scheduler",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isFacilityManager(){        
        if(in_array("facility manager",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     
     function isCorporateManager(){
        if(in_array("corporate manager",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     
     function isDataManage(){
        if(in_array("data management",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isDataEntry(){
        if(in_array("data entry",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isZoneManager(){
        if(in_array("sales zone",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isAssignIssue(){
       if(in_array("assigned issues",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        } 
     }
     
     function MMSforFires(){        
         if(in_array("new fires",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        } 
     }
     
     
     function MMSCallCenter(){        
        if(in_array("call center",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        } 
     }
     
     function MMSforTheft(){
         if(in_array("theft",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        } 
     }
     
     function PhoneMsgs(){
        if(in_array("phone message",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        } 
     }
     
     function isCSupport_Full(){
        if(in_array("customer support full",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
    
   function isSubHauler(){
        if(in_array("sub hauler",$this->roles)){
            return true;
        } else {
            return false;
        }
   }
     
     
     
     function isRouting(){
        if(in_array("routing",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     
     
     
     function isSearch(){
      if(in_array("search",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }  
     }
     
     function isReport(){
        if(in_array("report",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isRouting_Adv(){
        if(in_array("routing_adv",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isCSupport_Adv(){
        if(in_array("cust_support_adv",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isSalesManagement(){
            if(in_array("sales_manage",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }        
     }
     
     function isStaffManagement(){
        if(in_array("staff management",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isUserManagement(){
        if(in_array("user_manage",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
   
     
     function isUserManagement_Adv(){
        if(in_array("user_manage_adv",$this->roles)  || in_array("admin",$this->roles) ){ 
            return true;
        }
        else { 
            return false;
        }
     }
     
     function isBusinessManagement(){
            if(in_array("bus_manage",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }        
     }
     
     function isDriver(){
            if(in_array("service driver",$this->roles)  ){ 
            return true;
        }
        else { 
            return false;
        }        
     }
     function isCreditManager(){
        if(in_array("creditmanager",$this->roles)){
            return true;
        }else{
            return false;
        }
     }
      function isMasterLock(){
        if(in_array("Master Lock",$this->roles)){
            return true;
        }else{
            return false;
        }
     }
}



?>