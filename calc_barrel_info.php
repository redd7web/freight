<?php
include "protected/global.php";

$req1 = $db->get($dbprefix."_accounts","account_ID");

if(count($req1) > 0){
    
    foreach($req1 as $barrel_count) { 
        
        $total_barrel_capacity = 0;
        $jh = $db->where("account_no",$barrel_count['account_ID'])->get($dbprefix."_containers","container_no");
        $barrel_number =count($jh);
        
        if( count($jh)>0){                
            foreach($jh as $calue ){
                $tq = $db->where("container_id",$calue['container_no'])->get($dbprefix."_list_of_containers","amount_holds");
                $total_barrel_capacity = $tq[0]['amount_holds']+$total_barrel_capacity; 
            }
        }
        
        $insert_new_  = array( 
            "account_no"=>$barrel_count['account_ID'],
            "number_of_barrels"=>count($jh),
            "total_barrel_cap"=>$total_barrel_capacity
        );
        var_dump($insert_new_);
        
        $check = $db->where("account_no",$barrel_count['account_ID'])->get($dbprefix."_account_barrelinfo");
        
        if(count($check) ==0){
            $db->insert($dbprefix."_account_barrelinfo",$insert_new_);    
        }
        else {
            $db->where("account_no",$barrel_count['account_ID'])->update($dbprefix."_account_barrelinfo",$insert_new_);
        }
        
        
        
    }
}
 

?>