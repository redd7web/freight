<?php
include "protected/global.php";
ini_set("display_errors",1);
$person = new Person();
function last_pickup($account_no){
    global $db;
    
    $iuc = $db->query("SELECT date_of_pickup FROM sludge_grease_data_table WHERE account_no = $account_no ORDER BY date_of_pickup DESC LIMIT 0,1");
    if(count($iuc)>0){
        return $iuc[0]['date_of_pickup'];
    } else{
        return '0000-00-00';
    }
    
}
$dupe = array();
$k = 0;
$yuc = $db->query("SELECT Name,account_ID,grease_freq FROM sludge_accounts 
 WHERE account_ID NOT IN (SELECT account_no FROM sludge_grease_traps WHERE route_status IN('enroute','scheduled')) AND status IN('New','Active') ORDER BY Name ASC");
    if(count($yuc)>0){
        
        foreach($yuc as $jv){
            $ant = new Account($jv['account_ID']);
            $k++;
            $string .="$jv[account_ID],";
            echo $jv['Name']." ".$jv['account_ID']." ".$jv['grease_freq']." ".last_pickup($jv['account_ID']);
                if(  last_pickup($jv['account_ID']) == '0000-00-00'){
                    echo " frequency days from now";
                    $new_date = new DateTime(date("Y-m-d"));
                    $new_date->modify("+$ant->grease_freq day");
                    $new_grease_sched = array(
                        "account_no"=>$ant->acount_id,
                        "date"=>date("Y-m-d"),
                        "notes"=>$ant->notes,
                        "created_by"=>$person->user_id,
                        "grease_trap_size"=>$ant->grease_volume,
                        "frequency"=>$ant->grease_freq,
                        "price_per_gallon"=>number_format($ant->grease_ppg,2),
                        "grease_name"=>$ant->grease_label,
                        "grease_route_no"=>null,
                        "volume"=>$ant->grease_volume,
                        "service_date"=>$new_date->format("Y-m-d"),
                        "route_status"=>'scheduled',
                        "active"=>1,
                        "credit_notes"=>$ant->credit_notes,
                        "credits"=>$ant->credits,
                        "prepay"=>$ant->prepay,
                        "facility_origin"=>$ant->division
                    );
                } else {
                    echo " frequency days from last pickup";
                    $new_date = new DateTime( last_pickup($jv['account_ID']) );
                    $new_date->modify("+$ant->grease_freq day");
                    $new_grease_sched = array(
                        "account_no"=>$ant->acount_id,
                        "date"=>date("Y-m-d"),
                        "notes"=>$ant->notes,
                        "created_by"=>$person->user_id,
                        "grease_trap_size"=>$ant->grease_volume,
                        "frequency"=>$ant->grease_freq,
                        "price_per_gallon"=>number_format($ant->grease_ppg,2),
                        "grease_name"=>$ant->grease_label,
                        "grease_route_no"=>null,
                        "volume"=>$ant->grease_volume,
                        "service_date"=>$new_date->format("Y-m-d"),
                        "route_status"=>'scheduled',
                        "active"=>1,
                        "credit_notes"=>$ant->credit_notes,
                        "credits"=>$ant->credits,
                        "prepay"=>$ant->prepay,
                        "facility_origin"=>$ant->division
                    );
                }
                echo "<pre>";
                print_r($new_grease_sched);
                $db->insert("sludge_grease_traps",$new_grease_sched);
                echo "</pre>";
                echo "<br/>";
        }
    }


$iop = $db->query("SELECT account_no,grease_no,route_status,sludge_accounts.Name,sludge_accounts.grease_freq,grease_route_no FROM sludge_grease_traps LEFT JOIN sludge_accounts ON sludge_grease_traps.account_no = sludge_accounts.account_ID WHERE route_status ='enroute' AND grease_route_no NOT IN (SELECT route_id FROM sludge_list_of_grease WHERE status IN ('enroute','completed') ) AND account_no NOT IN (SELECT account_no FROM sludge_grease_traps WHERE route_status='scheduled')");
    foreach($iop as $po){
         $ant = new Account($po['account_no']);
        $k++;
        $string .="$po[account_no],";
        echo $po['Name']." ".$po['account_no']." ".$po['grease_freq']." ".last_pickup($po['account_no']);
            if(  last_pickup($po['account_no']) == '0000-00-00'){
                echo " frequency days from now";
                $new_date = new DateTime(date("Y-m-d"));
                    $new_date->modify("+$ant->grease_freq day");
                    $new_grease_sched = array(
                        "account_no"=>$ant->acount_id,
                        "date"=>date("Y-m-d"),
                        "notes"=>$ant->notes,
                        "created_by"=>$person->user_id,
                        "grease_trap_size"=>$ant->grease_volume,
                        "frequency"=>$ant->grease_freq,
                        "price_per_gallon"=>number_format($ant->grease_ppg,2),
                        "grease_name"=>$ant->grease_label,
                        "grease_route_no"=>null,
                        "volume"=>$ant->grease_volume,
                        "service_date"=>$new_date->format("Y-m-d"),
                        "route_status"=>'scheduled',
                        "active"=>1,
                        "credit_notes"=>$ant->credit_notes,
                        "credits"=>$ant->credits,
                        "prepay"=>$ant->prepay,
                        "facility_origin"=>$ant->division
                    );
            } else {
                echo " frequency days from last pickup";
                $new_date = new DateTime( last_pickup($po['account_no']) );
                    $new_date->modify("+$ant->grease_freq day");
                    $new_grease_sched = array(
                        "account_no"=>$ant->acount_id,
                        "date"=>date("Y-m-d"),
                        "notes"=>$ant->notes,
                        "created_by"=>$person->user_id,
                        "grease_trap_size"=>$ant->grease_volume,
                        "frequency"=>$ant->grease_freq,
                        "price_per_gallon"=>number_format($ant->grease_ppg,2),
                        "grease_name"=>$ant->grease_label,
                        "grease_route_no"=>null,
                        "volume"=>$ant->grease_volume,
                        "service_date"=>$new_date->format("Y-m-d"),
                        "route_status"=>'scheduled',
                        "active"=>1,
                        "credit_notes"=>$ant->credit_notes,
                        "credits"=>$ant->credits,
                        "prepay"=>$ant->prepay,
                        "facility_origin"=>$ant->division
                    );
            }
            echo " (Are labeled enroute but missing route) <br/>";
            echo "<pre>";
            print_r($new_grease_sched);
            $db->insert("sludge_grease_traps",$new_grease_sched);
            echo "</pre>";
            echo "<br/>";
            $db->query("DELETE FROM sludge_grease_traps WHERE grease_no = $po[grease_no]");
    }

echo "<br/><br/>$k";
?>