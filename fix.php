<?php
include "protected/global.php";
ini_set('memory_limit', '512M');

function aname_to_number($name){
    $db = new Database();
    global $dbprefix;
    $kl = $db->where("name",$name)->get($dbprefix."_accounts","account_ID");
    if(count($kl)>0){
        return $kl[0]['account_ID'];
    }else {
        return NULL;
    }
}


function total_barrel_cap($number){
     $db= new Database();
     global $dbprefix;
     $total_barrel_capacity = 0;
     $jh = $db->where("account_no",$number)->get($dbprefix."_containers","container_no");
                            
    
    if(count($jh)>0){                
        foreach($jh as $calue ){
            $tq = $db->where("container_id",$calue['container_no'])->get($dbprefix."_list_of_containers","*");
           
            $total_barrel_capacity = $tq[0]['amount_holds']+$total_barrel_capacity; 
        }
    }
    return $total_barrel_capacity;
}

function getRoute($name){
    $db = new Database();
    global $dbprefix;
    $kl = $db->where("location_name",$name)->get("temp_pickup","route_id");
    $routes;
    foreach($kl as $query){
        if($query['route_id'] !=0){
            $routes[] = $query['route_id'];
        }
    }
    return $routes;
}

$code_red;

$ask = $db->get("iwp_accounts","account_ID");

foreach($ask as $act){
    $bufer = array(
        "pickup_frequency"=>30
    );
    
   $db->where("account_ID",$act['account_ID'])->update("iwp_accounts",$bufer);
}









?>