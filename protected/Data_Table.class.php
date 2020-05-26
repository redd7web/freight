<?php
class Dtable{
    
    
    function getAccount_Name( $id ){
        
        return  account_NumtoName($id);
    }    
    
    function get_all($id,$order){
        global $dbprefix;
        $db = new Database();        
        $hgj = $db->where("account_no",$id)->orderby("date_of_pickup","$order")->get($dbprefix."_data_table");
        return $hgj;
    }
        
    function get_field($id,$field){
        global $dbprefix;
        $db = new Database();
        
        $hgj = $db->where("account_no",$id)->get($dbprefix."_data_table","$field");
        return $hgj[0][$field];
    }
    
    function date_ranges($id = NULL){
    global $db;
    global $dbprefix;
    
    $dates = $db->orderby("date_of_pickup","DESC")->get($dbprefix."_data_table","date_of_pickup");
    if(count($dates)>0){        
        return $dates[0]['date_of_pickup']."|".$dates[count($dates)-1]['date_of_pickup'];
    }else {
        return "0|0";
    }
            
}


function total_gallons_for_route($rnumber,$schedule_id,$account_no) { 
    global $db;
    global $dbprefix;
    $data_table =  $dbprefix."_data_table";
    
    $sum = $db->query("SELECT SUM(inches_to_gallons) as gals_for_current_route FROM $data_table WHERE route_id=$rnumber AND schedule_id = $schedule_id AND account_no = $account_no");
    
    if(count($sum)!=0){        
        return round($sum[0]['gals_for_current_route'],2);
    } else {
        return "0";
    }
}


}
?>