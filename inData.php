<?php
include "protected/global.php";
ini_set('xdebug.var_display_max_depth', 50);
ini_set('xdebug.var_display_max_children', 10000);
ini_set('xdebug.var_display_max_data', 10000);
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

function totalBarrelSize($num){
    $db = new Database();
    global $dbprefix;
    $total_cap = 0;
    $jh = $db->where("account_no",$num)->get($dbprefix."_containers","container_no");
            $num_of_barrels = count($jh);                        
            
    if($num_of_barrels>0){                
        foreach($jh as $calue ){
            $tq = $db->where("container_id",$calue['container_no'])->get($dbprefix."_list_of_containers","*");
            $total_cap = $tq[0]['amount_holds']+$total_cap; 
        }
    }
    
    return $total_cap;
    
}

$entry =0;
$query = $db->query("SELECT pickup_id,route_id,date_of_pickup,location_name,field_report_note,gallons from temp_pickup limit 99749,30000");

//get("temp_pickup","");

echo "num:". count($query)."<br/>";

if(count($query)>0){
    foreach($query as $data){
        
        if(strlen(aname_to_number($data['location_name']) )>0   ){  
            $anum = aname_to_number($data['location_name']);
            $req_sched = $db->where("account_no",$anum )->where("route_id",$data['route_id'])->get("iwp_scheduled_routes","schedule_id");
            if(count($req_sched)>0){
                $schedn = $req_sched[0]['schedule_id'];
            }
            else {
                $schedn = 0;
            }
            $data_table = array(
                "route_id"=>$data['route_id']
                ,"schedule_id"=>$schedn
                ,"inches_to_gallons"=>$data['gallons']
                ,"fieldreport"=>$data['field_report_note']
                ,"account_no"=>$anum
                ,"date_of_pickup"=>$data['date_of_pickup']
                ,"expected_gallons"=>totalBarrelSize(  aname_to_number($data['location_name']) )
                ,"completed"=>1
            );
            
            $db->insert("iwp_data_table",$data_table);
        }
    }
}

?>