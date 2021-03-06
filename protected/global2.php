<?php
session_start();

date_default_timezone_set('America/Los_Angeles');
ini_set('max_execution_time', -1);
 
static $debug = 2;
static $dbprefix="gt";
static $fixed_lbs = 7.56;
global $db;
global $facils;
global $coords;

if($debug == 1){ 
    $cfg['connection'] = array( 
        "host"=>"localhost",
        "username"=>"reddawg",
        "password"=>"quyle714",
        "database"=>"grease_trap"
    );
}
else if ($debug == 2){
     $cfg['connection'] = array( 
        "host"=>"localhost",
        "username"=>"root",
        "password"=>"password1",
        "database"=>"grease_trap"
    );
}

else if($debug == 31){ 
    $cfg['connection'] = array( 
        "host"=>"mysql51-027.wc1.ord1.stabletransit.com",
        "username"=>"853429_dawgy",
        "password"=>"Quyle714",
        "database"=>"853429_nhcx"
    );
}

//var_dump($cfg);


include "Database.class.php";
include "Person.class.php";
include "x.class.php";
include "Containers.class.php";
include "Scheduled.class.php";
include "Vehicles.class.php";
include "IKG.class.php";
include "Data_Table.class.php";
include "IKG.Grease.class.php";
include "Grease_Sched.class.php";
include "IKG.Utility.class.php";
include "Util_scheds.class.php";
include "Trailers.class.php";
$db = new Database();

function solids_table($percent){
    if($percent<=.20 && $percent>=.01){
        return .12;
    }else if($percent>.20 && $percent<=.35){
        return .16;
    }else if($percent>.35 && $percent<=.50){
        return .22;
    }else if($percent>.50 && $percent<=.65){
        return .28;
    }else if($percent>.65 ){
        return .34;
    }else{
        return 0;
    }
}

function expected_for_route($route_id){
    global $db,$dbprefix;
    $account  = new Account();
    $total = 0;
    $acco_no = $db->where("route_id",$route_id)->get($dbprefix."_data_table","account_no");
    if(count($acco_no)>0){
        foreach($acco_no as $numbers){
            $total =  $account->singleField($numbers['account_no'],"estimated_volume") + $total;
        }
    }
    return $total;
}

function get_zero_collections($route_id){
    global $dbprefix,$db;   
     $route_collected_zero = $db->where("inches_to_gallons",0)->where("route_id",$route_id)->get($dbprefix."_data_table");
    
    return count($route_collected_zero);
}

function collected_for_route($route_id){
    global $dbprefix,$db;
    $total = 0;
    $route_collected = $db->where("route_id",$route_id)->get($dbprefix."_data_table");
    if(count($route_collected)>0){
        foreach($route_collected as $collected){
            $total = $total + $collected['inches_to_gallons'];
        }
    }
    return $total;    
}

function date_different($beginning,$latest){
        $diff = 0;
        if($beginning !="0000-00-00"){    
            $now = strtotime("$latest"); // or your date as well
            $your_date = strtotime("$beginning");
            $datediff = $now - $your_date;
            if($datediff>0){
                return floor($datediff/(60*60*24));
            } else {
                return 0;
            }
        }else {
            return 0;
        }
     
}

function addDayswithdate($date,$days){    
   $date = date_create($date);
    date_add($date, date_interval_create_from_date_string("$days days"));
    return date_format($date, 'Y-m-d');
}
function subDayswithdate($date,$days){    
    $mod_date = strtotime($date."- $days days");
    return date("Y-m-d",$mod_date);
}



function account_issue($issuenumber){
    switch($issuenumber){
        case  1: 
            return "Needs Cancelation letter";
            break;
       case  2: 
            return "Damaged Tote";
            break;
        case  3: 
            return "Need GCP Poster";
            break;
            
        case  4: 
            return "Needs To Be Swapped/Dirty Tote";
            break;
            
        case  5: 
            return "Oil Theft";
            break;
            
        case  6: 
            return "Competitor Onsite";
            break;
            
        case  7: 
            return "Broken Lock";
            break;
            
        case  8: 
            return "Out Of Business";
            break;
        case  9: 
            return "Container Missing";
            break;
        case  60: 
            return "Location Needs Attention";
            break;
        case  70: 
            return "Customer Request";
            break;
        case  72: 
            return "In House Request";
            break;
        case  140: 
            return "Sales Team";
            break;
            
        case  90: 
            return "Competitor On Site";
            break;
        case  100: 
            return "Location Closing";
            break;
    }
}


function issueDecode($number){
    switch($number){
        case 6:
            echo "Site Cleanup";
            break;
        case 3:
            echo "Container Delivery";
            break;
        case 4:
            echo "Container Retrieval";
            break;
        case 7:
            echo "Lid Delivery";
            break;
        case 8:
            echo "Wheels: Add/Modify";
            break;
        case 10:
            echo "Lock: Add/Modify";
            break;
        case 24:
            echo "Sensors: Add/Modify";
            break;
        case 20:
            echo "Other";
            break;
        default:
            echo "";
            break;
    }
}


function payment_label(&$class_object){
    
    switch($class_object->payment_method){
        case "Charge Per Pickup": case "Index":
            //echo "jacobe hit!";
            echo "Charge Per Pickup";
            break;
        case "Per Gallon": case "Normal":
                echo "Per Gallon";
            break;
        case "One Time Payment":case "O.T.P.":
            echo "One Time Payment";
            break;
        case "O.T.P. Per Gallon": case "One Time Payment Per Gallon": case "O.T.P. PG": 
            echo "O.T.P. PG";
            break;
        case "No Pay": case "Free": case "Normal": case "NULL": case "Do Not Pay":
            echo "No Pay";
            break;
        case "Split Account":
            break;
        case "Cash On Delivery":
            echo "Cash On Delivery";
            break;
        default:    
            echo "No Pay";
            break;
    }
};


function payment_method(&$class_object){
    $db = new Database();
    global $dbprefix;
    global $fixed_lbs;
    switch($class_object->payment_method){
        case "Charge Per Pickup": case "Index":
            //echo "jacobe hit!";
            
                echo "$ ".$class_object->index_percentage;
            break;
        case "Per Gallon": case "Normal":
               $totalgs = 0;
               $tc = $db->where("account_no",$class_object->acount_id)->get($dbprefix."_data_table");
               if(count($tc)>0){
                    foreach($tc as $value){
                        $totalgs = $totalgs +$value['inches_to_gallons'];
                    }
               }
               echo  round(($totalgs - ($totalgs * $class_object->miu))  * $class_object->ppg_jacobsen_percentage ,2) ; 
               
            break;
         case "O.T.P. Per Gallon": case "One Time Payment Per Gallon": case "O.T.P. PG":
            //display one time payment value
            echo $class_object->ppg_jacobsen_percentage;
            break;
         case "No Pay": case "Free": 
            echo "No Pay";
            break;
        case "Split Account":
            break;
    }
}

function containerList($compare = NULL,$name = NULL){
    
    if($name == NULL){
        $post_name = "container_size";
        $id = "container_size";
    }
    else {
        $post_name = $name;
        $id = $name;
    }
    
    $db = new Database();
    global $dbprefix;
    $jk = $db->get($dbprefix."_list_of_containers","*");
    if(count($jk)>0){
        $select ="";
        echo '<select  id="'.$id.'" name="'.$post_name.'" style="font-size:11px;">';
        foreach($jk as $value){
                if($value['container_id'] == $compare){
                    $select = "selected";
                }
            echo "<option $select value='$value[container_id]'>$value[container_label]</option>";
        }
        echo '</select>';
    }
}

function containerNumToName($number){
    $db = new Database();
    global $dbprefix;
    $jk = $db->where("container_id",$number)->get($dbprefix."_list_of_containers");
    
    return $jk[0]['container_label'];
}


function issueConverter($issue){
    switch($issue){
        case 1:
            echo "Needs Cancelation letter";
            break;
        case 2:
            echo "Damaged Tote";
            break;
        case 3:
            echo "Need GCP Poster";
            break;
        case 4:
                echo "Needs To Be Swapped/Dirty Tote";
            break;
        case 5:
            echo "Oil Theft";
            break;
        case 6:
            echo "Competitor Onsite";
            break;
        case 7:
            echo "Broken Lock";
            break;
        case 8:
            echo "Out Of Business";
            break;
        case 9:
            echo "Container Missing";
            break;
        case 60:
            echo "Location Needs Attentio";
            break;
        case 70:
            echo "Customer Request";
            break;
        case 72:
            echo "In House Request";
            break;
        case 140:
            echo "Sales Team";
            break;
        case 90:
            echo "Competitor On Site";
            break;
        case 100:
            echo "Location Closing";
            break;
    }
}

function priorityConverter($pri){
    switch($pri){
        case 20:
            return "Normal";
        break;
        case 10:
            return "Urgent"; 
        break;
    }
}

function uNumToName($number){
    
    $db = new Database();
    $answer = $db->where("user_id",$number)->get("sludge_users","first,last,user_id");
    if(count($answer) >0){
        return "<a href='viewUser.php?id=$number'>".$answer[0]['first']." ".$answer[0]['last']."</a>";
    }
    
}

function user_info($number,$field){
    
    $db = new Database();
    $answer = $db->where("user_id",$number)->get("sludge_users","$field");
    if(count($answer) >0){
        return $answer[0][$field];
    }
    
}

function account_NumtoName($number){
    global $dbprefix;
    $db = new Database();
    $request = $db->where("account_ID",$number)->get($dbprefix.'_accounts','name,account_ID');
    
   if (count($request)>0){
     foreach($request as $vlue){
         $t = $vlue['account_ID'];
        $name = $vlue['name'];
        return "<a style='color:blue;' href='viewAccount.php?id=$t' target='_blank'>".$name."</a>";
     }
     
   }else {
        return "No such account - $number";
     }
    
}

function statusColors($status,$id){
    $account = new Account();
    $status = strtolower($status);
    if (strlen($status)== 0){
        $status = "scheduled";
    }
      $color = "black";
    if( $account->barrel_cap($id) >0 ){
        $percent =  $account->singleField($id,"estimated_volume")/$account->barrel_cap($id);
        
        if($percent<.25){
            $color = "green";
        } else if ($percent>=.25 && $percent <.5){
            $color = "#999900";
        } else if ($percent>=.5 && $percent <.75){
            $color = "orange";
        }  else if ($percent>=.75){
            $color = "red";
        }
    } 
    
      return "<span style='color:$color;font-weight:bold;'>$status</span>";
}


function getJake(){
    global $dbprefix;
    $db = new Database();
    $request = $db->orderby("id","desc")->get("sludge_jacobsen");    
    return $request[0]['percentage'];
}


function getFriendLists($compare = NULL){
    global $dbprefix;
    $table_friendlies = $dbprefix."_friendly";
   $select = "<select id='friendly' class='field' name='friendly' rel='friendly'><option value='null'>--</option>";
   $db = new Database();
   $request = $db->get($table_friendlies);
   if(count($request) !=0){
        foreach($request as $friendly){
            if(trim($compare) == $friendly['comp_name']){
                $selected = " selected ";
            } else {
                $selected = "  ";
            }
            $select .= "<option $selected value='$friendly[comp_name]'>$friendly[comp_name]</option>";
        } 
       
   }
   $select .="</select>";
   echo $select; 
}



function getFacilityList( $list_name = NULL ,$compare = NULL){   
    
    $selected ="";
    if(strlen($list_name)>0){
        
        $name = $list_name;
    }
    else {
        $name = "facility";
    }
    $select = "<select id='$name' name='$name'><option value='ignore' required>--</option>";
    
         $select .=' <option '; if($compare == 22){$select .= 'selected'; }  $select.=' value="22">San Diego (US Division))</option>
                <option '; if($compare == 23){$select .= 'selected'; }  $select.=' value="23">Coachella (UD Division)</option>
                <option '; if($compare == 99){$select .= 'selected'; }  $select.=' value="99">ALL UC</option>
                <option '; if($compare == 24){$select .= 'selected'; }  $select.=' value="24">LA (UC Division)</option>
                <option '; if($compare == 30){$select .= 'selected'; }  $select.=' value="30">LA (UC Division-Tony)</option>
                <option '; if($compare == 31){$select .= 'selected'; }  $select.=' value="31">LA (UC Division-Ramon)</option>
                <option '; if($compare == 32){$select .= 'selected'; }  $select.='  value="32">LA (UC Division-Chato)</option>
                <option '; if($compare == 33){$select .= 'selected'; }  $select.='  value="33">LA (UC Division-Chuck)</option>
                <option '; if($compare == 8){$select .= 'selected'; }  $select.='  value="8">Arizona (4 Division)</option>
                <option '; if($compare == 5){$select .= 'selected'; }  $select.='  value="5">Selma (V Division)</option>
                <option '; if($compare ==10){ $select .='selected'; }   $select .='  value="10"/>V-BAK</option>
                <option '; if($compare ==11){ $select .='selected'; }   $select .='  value="11"/>V-Fresno</option>
                <option '; if($compare ==12){ $select .='selected'; }   $select .='  value="12"/>V-North</option>
                <option '; if($compare ==13){ $select .='selected'; }   $select .='  value="13"/>V-Visalia</option>
                <option '; if($compare ==14){ $select .='selected'; }   $select .='  value="14" />L Division</option>
                <option '; if($compare ==15){ $select .='selected'; }   $select .='  value="15" />W Division</option>
                ';
                
               
    $select .="</select>";
    echo $select;

}


$facils[22]="15777 Old Milky Way, Escondidio, CA 92025";
$facils[23]="86-600 Ave 54, Coachella, CA 92236";
$facils[24]="4085 Bain St., Mira Loma, CA 91752";         
$facils[31]="4085 Bain St., Mira Loma, CA 91752";
$facils[25]="4085 Bain St., Mira Loma, CA 91752";
$facils[32]="4085 Bain St., Mira Loma, CA 91752";
$facils[33]="4085 Bain St., Mira Loma, CA 91752";
$facils[30]="4085 Bain St., Mira Loma, CA 91752";
$facils[8]="7401 S. Wilson Rd., Buckeye, AZ 85326";
$facils[5]="3766 E. Conejo, Selma, CA 93662";
$facils[10]="3766 E. Conejo, Selma, CA 93662";
$facils[11]="3766 E. Conejo, Selma, CA 93662";
$facils[12]="3766 E. Conejo, Selma, CA 93662";
$facils[13]="3766 E. Conejo, Selma, CA 93662";
$facils[14]="Coachella, CA";
$facils[15]="2586 Shenandoah Way, San Bernadino, CA";

$coords[22]="33.087907,-116.998791";
$coords[23]="33.656551,-116.190472";
$coords[24]="34.012972,-117.507924";
$coords[31]="34.012972,-117.507924";
$coords[25]="34.012972,-117.507924";
$coords[32]="34.012972,-117.507924";
$coords[33]="34.012972,-117.507924";
$coords[8]="33.379422,-112.659971";
$coords[5]="36.519258,-119.722510";
$coords[10]="36.519258,-119.722510";
$coords[11]="36.519258,-119.722510";
$coords[12]="36.519258,-119.722510";
$coords[13]="36.519258,-119.722510";
$facils[14]="0,0";
$coords[15]="34.173228,-117.346416";
function numberToFacility($number){
   
    switch($number){
        case 23: 
            return "Coachella (Division)";
            break;
        case 22:
            return "San Diego (Division)";
            break;
        case 24:
            return "LA (Division UC)";
            break;
        case 30:
            return "LA (Division UC-Tony)";
            break;
        case 31:case 25:
            return "LA (Division-Ramon)";
            break;
        case 32:
            return "LA (Division UC-Chato";
            break;
        case 33:
            return "LA (Division UC-Chuck";
            break;
        case 8:
            return "Arizona (Division 4)";
            break;
        case 5:
            return "Selma (Division V)";
            break;
        
        case 10:
            return "V-BAK";
            break;
            
        case 11:
            return "V-Fresno";
            break;
        case 12:
            return "V-North";
            break;
        case 13:
            return "V-Visalia";
            break;      
        case 14:
            return "Coachella (Division L)";
            break;
        case 15:
            return "San Bernadino (Division W)";                    
            
    }
}


function reverseTranslate($facName){
    switch($facName){
        case "San Diego (Division US)":
            return 22;
            break;
//case "L Division":
  //          return 14;
    //        break;
        case "Coachella (Division R)":
            return 23;
            break;
        case "LA (Division UC)":
            return 24;
            break;
        case "LA (Division UC-Tony)":case "LA (Division UC-Norm)":
            return 31;
            break;
        case "LA (Division UC-Ramon)":
            return 25;
            break;
        case "LA (Division UC-Chato)":
            return 32;
            break;
        case "LA (Division UC-Chuck)":
            return 33;
            break;
        case "Arizona (Division 4)":
            return 8;
            break;
        case "Selma (Division V)":
            return 5;
            break;
        case "Coachella (Division L)":
            return 14;
            break;
        case "San Bernadino (Division W)":
            return 15;
            break;
        default:
            return 0;
            break;
    }
}


function getVehiclesList($compare = NULL){
    global $dbprefix;
    $table_vehicles = $dbprefix."_truck_id";
    $select ="<select id='vehicles' name='vehicles'>";    
    $db = new Database();    
    $request = $db->get("sludge_truck_id");
    if(count($request) !=0 ){
        foreach($request as $truck){
            $vehicle = new Vehicle($truck['truck_no']);            
            echo "<option "; if($compare == $driver['truck_no']) { $select .="selected";}  $select .="  value='$vehicle->truck_no'>$vehicle->name</option>";
        }
    }    
    $select .="</select>";
    return $select;
}

function vehicle_name($truck_no){
    global $dbprefix;
    $table_vehicles = $dbprefix."_truck_id";
    global $db;
    $truck = $db->query("SELECT name FROM $table_vehicles WHERE truck_id = $truck_no");
    if(count($truck)>0){
        return $truck[0]['name'];
    } else {
        return "N/A";
    }
}

function trailer_name($trailer_no = NULL){
    global $dbprefix;
    $table_vehicles = $dbprefix."_trailers";
    global $db;
    if($trailer_no != NULL){
        $truck = $db->query("SELECT name FROM $table_vehicles WHERE truck_id = $trailer_no");
        if(count($truck)>0){
            return $truck[0]['name'];
        } else {
            return "N/A";
        }  
    }else{
        return "N/A";
    }
}

function getDrivers($compare = NULL){   
   //echo $compare;
    global $dbprefix;
    global $db;
    $selected = "";
    $table = $dbprefix."_users";
    $request = $db->query("SELECT user_id,first,last FROM $table WHERE roles LIKE '%driver%' OR roles LIKE '%cowestdriver%'");
    echo "<select name='drivers' id='drivers'><option value='-'>--</option>";    
        if(count($request) >0){
            foreach ($request as $driver){
                $selected="";
                if($compare == $driver['user_id']){
                    $selected = "selected";
                    
                }
                echo "<option $selected value='$driver[user_id]'>$driver[first] $driver[last]</option>";
                
            }
        }
    
    echo "</select>";
   
}


function getAcctRep($compare = NULL){
    global $dbprefix;
    $table = $dbprefix."_users";
    $select ="<select id='accntrep' name='accntrep'><option value='-'>--</option>";
    $db= new Database();
    $request = $db->query("SELECT * FROM $table WHERE roles like '%account%represntative%'");
    if(count($request) !=0){
        foreach ($request as $acctrep){
            $select .="<option value='$acctrep[user_id]'>$acctrep[first] $acctrep[last]</option>";
        }
    }
    $select .="</select>";
    return $select;
}


function getSalesRep($compare = NULL){   
    global $dbprefix;
    $table = $dbprefix."_users";
    echo "<select id='salesrep' name='salesrep' rel='account_rep' class='field'>";
    
    echo "<option value=''>ALL</option>";
    $db= new Database();
    $request = $db->query("SELECT * FROM $table WHERE roles like '%Sales%Representative%'");
    if(count($request) !=0){
        foreach ($request as $sreps){
            
            echo "<option ";  
                if($compare == $sreps['user_id']){
                    echo " selected ";
                }
            
            echo "value='$sreps[user_id]'>$sreps[first] $sreps[last]</option>";
        }
    }
    echo "</select>";
    
}

function getOrigRep($compare = NULL){
   
    global $dbprefix;
    $table = $dbprefix."_users";
    echo "<select id='orig' name='orig' rel='original_sales_person' class='field'><option value='-'>ALL</option>";
    $db= new Database();
    $request = $db->query("SELECT * FROM $table WHERE roles like '%Sales%Representative%'");
    if(count($request) !=0){
        foreach ($request as $sreps){
            
            echo "<option ";  
                if($compare == $sreps['user_id']){
                    echo " selected ";
                }
            
            echo "value='$sreps[user_id]'>$sreps[first] $sreps[last]</option>";
        }
    }
    echo "</select>";
    
}



function field_report_decode($number){
    switch($number){
        case 10:
            echo "X(A)";
            break;
        case 12:
            echo "X(B)";
            break;
        case 14:
            echo "1(A)";
            break;
        case 16:
            echo "1(B)";
            break;
        case 22:
            echo "1(C)";
            break;
        case 24:
            echo "1(D)";
            break;
        case 26:
             echo "1(E)";
            break;
        case 32:
            echo "1(F)";
            break;
        case 40:
            echo "2(A)";
            break;
        case 42:
            echo "2(B)";
            break;
        case 44:
            echo "2(C)";
            break;
        case 52:
            echo "2(D)";
            break;
        case 54:
            echo "2(E)";
            break;
        case 62:
            echo "2(F)";
            break;
        case 64:
            echo "2(G)";
            break;
        case 66:
            echo "3(A)";
            break;
        case 68:
            echo "3(B)";
            break;
        case 72:
            echo "3(C)";
            break;
        case 74:
            echo "3(D)";
            break;
        case 76:
            echo "3(E)";
            break;
        case 78:
            echo "3(F)";
            break;
        case 80:
            echo "3(G)";
            break;

       
    }
}

function zero_gallons_reasons($compare = NULL){
    echo '<select id="reason_for_skip_id" name="reason_for_skip_id"><option '; 
    if($compare == 0 || strlen($compare)== 0) { 
        echo 'selected';
    }
    echo ' value="0"> -- Please Choose a Reason -- </option>
    <option'; 
    if($compare == 10) { 
        echo 'selected';
    }
    echo ' value="10">X(A)</option>
    
    <option '; 
    if($compare == 12) { 
        echo 'selected';
    }
    echo 'value="12">X(B)</option>
    
    <option '; 
    if($compare == 14) { 
        echo 'selected';
    }
    echo ' value="14">1(A)</option>
    
    <option '; 
    if($compare == 16) { 
        echo 'selected';
    }
    echo ' value="16">1(B)</option>
    
    <option '; 
    if($compare == 22) { 
        echo 'selected';
    }
    echo ' value="22">1(C)</option>
    
    <option '; 
    if($compare == 24) { 
        echo 'selected';
    }
    echo ' value="24">1(D)</option>
    
    <option '; 
    if($compare == 26) { 
        echo 'selected';
    }
    echo ' value="26">1(E)</option>
    
    <option '; 
    if($compare == 32) { 
        echo 'selected';
    }
    echo ' value="32">1(F)</option>
    
    <option '; 
    if($compare == 40) { 
        echo 'selected';
    }
    echo ' value="40">2(A)</option>
    
    <option '; 
    if($compare == 42) { 
        echo 'selected';
    }
    echo ' value="42">2(B)</option>
    
    <option '; 
    if($compare == 44) { 
        echo 'selected';
    }
    echo ' value="44">2(C)</option>
    
    <option '; 
    if($compare == 52) { 
        echo 'selected';
    }
    echo ' value="52">2(D)</option>
    
    <option '; 
    if($compare == 54) { 
        echo 'selected';
    }
    echo ' value="54">2(E)</option>
    
    <option '; 
    if($compare == 62) { 
        echo 'selected';
    }
    echo ' value="62">2(F)</option>
    
    <option '; 
    if($compare == 64) { 
        echo 'selected';
    }
    echo ' value="64">2(G)</option>
    
    <option '; 
    if($compare == 66) { 
        echo 'selected';
    }
    echo ' value="66">3(A)</option>
    
    <option '; 
    if($compare == 68) { 
        echo 'selected';
    }
    echo ' value="68">3(B)</option>
    
    <option '; 
    if($compare == 72) { 
        echo 'selected';
    }
    echo ' value="72">3(C)</option>

<option '; 
    if($compare == 74) { 
        echo 'selected';
    }
    echo ' value="74">3(D)</option>
<option '; 
    if($compare == 76) { 
        echo 'selected';
    }
    echo ' value="76">3(E)</option>
<option '; 
    if($compare == 78) { 
        echo 'selected';
    }
    echo ' value="78">3(F)</option>
<option '; 
    if($compare == 80) { 
        echo 'selected';
    }
    echo ' value="80">3(G)</option>
    </select>';
}



function previousP($compare = NULL){
echo '<select name="prev_compet" id="prev_compet" class="field" rel="previous_provider">
    <option '; if($compare =="ignore" || $compare == NULL){ echo "selected";}   echo ' value="ignore">None</option>
    <option '; if($compare =="Advantage Bio"){ echo "selected";}   echo    ' value="Advantage Bio">Advantage Bio</option>
    <option '; if($compare =="Affordable Grease Pumping"){ echo "selected";}   echo    ' value="Affordable Grease Pumping">Affordable Grease Pumping</option>	
    <option '; if($compare =="BioDriven"){ echo "selected";}   echo    ' value="BioDriven">BioDriven</option>	
    <option '; if($compare =="Buster Bio"){ echo "selected";}   echo    ' value="Buster Bio">Buster Bio</option> 
    <option '; if($compare =="Baker Comm.	Darling Int."){ echo "selected";}   echo    ' value="Baker Comm.	Darling Int.">Baker Comm.	Darling Int.</option>	
    <option '; if($compare =="New Leaf"){ echo "selected";}   echo    ' value="New Leaf">New Leaf</option>	
    <option '; if($compare =="Promethian"){ echo "selected";}   echo    ' value="Promethian">Promethian</option> 
    <option '; if($compare =="HT Grease"){ echo "selected";}   echo    ' value="HT Grease">HT Grease</option>	
    <option '; if($compare =="Co-West"){ echo "selected";}   echo    ' value="Co-West">Co-West</option>	
    <option '; if($compare =="Industrial Bio"){ echo "selected";}   echo    ' value="Industrial Bio">Industrial Bio</option>	
    <option '; if($compare =="JK Collections"){ echo "selected";}   echo    ' value="JK Collections">JK Collections</option>	
    <option '; if($compare =="AWJ"){ echo "selected";}   echo    ' value="AWJ">AWJ</option>	
    <option '; if($compare =="Triple A"){ echo "selected";}   echo    ' value="Triple A">Triple A</option>	
    <option '; if($compare =="So-Cal Pumping"){ echo "selected";}   echo    ' value="So-Cal Pumping">So-Cal Pumping</option>
    <option '; if($compare =="Harbor"){ echo "selected";}   echo    ' value="Harbor">Harbor</option>	
    <option '; if($compare =="GCI"){ echo "selected";}   echo    ' value="GCI">GCI</option>	
    <option '; if($compare =="North County"){ echo "selected";}   echo    ' value="North County">North County</option>	
    <option '; if($compare =="OC Bio"){ echo "selected";}   echo    ' value="OC Bio">OC Bio</option>	
    <option '; if($compare =="Eco-Fry"){ echo "selected";}   echo    ' value="Eco-Fry">Eco-Fry</option>
    <option '; if($compare =="Grand Natural"){ echo "selected";}   echo    ' value="Grand Natural">Grand Natural</option>	
    <option '; if($compare =="Grease Masters"){ echo "selected";}   echo    ' value="Grease Masters">Grease Masters</option>	
    <option '; if($compare =="LA Grease Solutions"){ echo "selected";}   echo    ' value="LA Grease Solutions">LA Grease Solutions</option>	
    <option '; if($compare =="CGC"){ echo "selected";}   echo    ' value="CGC">CGC</option>	
    <option '; if($compare =="Coastal By-Products"){ echo "selected";}   echo    ' value="Coastal By-Products">Coastal By-Products</option>	
    <option '; if($compare =="SMC"){ echo "selected";}   echo    ' value="SMC">SMC</option>	
    <option '; if($compare =="HP Comm."){ echo "selected";}   echo    ' value="HP Comm.">HP Comm.</option>	
    <option '; if($compare =="All Pro"){ echo "selected";}   echo    ' value="All Pro">All Pro</option>	
    <option '; if($compare =="Belcito"){ echo "selected";}   echo    ' value="Belcito">Belcito</option>	
    <option '; if($compare =="AJAX Pumping"){ echo "selected";}   echo    ' value="AJAX Pumping">AJAX Pumping</option>	
    <option '; if($compare =="Green Dining Network"){ echo "selected";}   echo    ' value="Green Dining Network">Green Dining Network</option>
</select>';
}


function truck_service_decode($code){
    switch($code){
        case 10:
            echo "In Service";
        break;
        
        case 20:
            echo "Not In Service";
        break;
        
        case 30:
            echo "In the Shop";
        break;
        case 100:
            echo "Sold";    
        break;
    }
}

function truck_service($compare = NULL){
    echo '<select name="vehicle_status_id" id="vehicle_status_id">

<option value="10"'; if($compare == 10){ echo "selected";}  echo ' >In Service</option>

<option value="20" '; if($compare == 20){ echo "selected";}  echo '>Not In Service</option>

<option value="30" '; if($compare == 30){ echo "selected";}  echo '>In the Shop</option>

<option value="100" '; if($compare == 100){ echo "selected";}  echo '>Sold</option>
</select>';
}


function service_call_decode($number){ 
    switch($number){
        case 6:
            echo "Site Cleanup";
            break;
        case 392:
            echo "Verify Containment";
            break;
        case 3:
            echo "Container Delivery";
            break;
        case 4:
            echo "Container Retrieval";
            break;
        case 7:
            echo "Lid Delivery";
            break;
        case 8:
            echo "Wheels: Add/Modify";
            break;
        case 10:
            echo "Lock: Add/Modify";
            break;
        case 24:
            echo "Sensors: Add/Modify";
            break;
        case 100:
            echo "Swap";
            break;
        case 20:
            echo "Other";
            break;
    }
    
}


function code_red($number){
    if( $number == 1 ){
        return "<img src='img/graphics-flashing-light-245546.gif' style='width:25px;height:25px;' />";
    }
    else {
        return "<img src='img/redlight.jpg' style='width:25px;height:25px;' />";
    }
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

function get_schedule_route_info($route_id,$account_no,$field){
    global $db;
    global $dbprefix;
    
    $result = $db->where('route_id',$route_id)->where('account_no',$account_no)->get($dbprefix."_scheduled_routes",$field);
    if(count($result)>0){
        return $result[0][$field];
    } else {
        return "";
    }
}


function grease_info($grease_number,$field){
    global $db;
    global $dbprefix;
    $info = $db->where("grease_no",$grease_number)->get($dbprefix."_grease_traps",$field);
    if(count($info)>0 ){
        return $info[0][$field];
    }
    else {
        return "";
    }
    
}


function in_assoc($needle, $haystack) {
     if(in_array($needle, $haystack)) {
          return true;
     }
     foreach($haystack as $element) {
          if(is_array($element) && in_assoc($needle, $element))
               return true;
     }
   return false;
}


function gpi($id){
    global $db;
    $t = $db->query("SELECT gpi FROM sludge_list_of_containers WHERE container_id = $id");
    
    if(count($t)>0){
        return $t[0]['gpi'];
    } else {
        return 0;
    }
    
}


function container_amountHolds($number){
    $db = new Database();
    $jk = $db->where("container_id",$number)->get("sludge_list_of_containers","amount_holds");
    return $jk[0]['amount_holds'];
}

function container_amountHolds_from_containers($entry_number){
    $db = new Database();
    $jk = $db->where("entry",$entry_number)->get("sludge_containers","container_no");
    
    $lp = $db->where("container_id",$jk[0]['container_no'])->get("sludge_list_of_containers","amount_holds");
    return $lp[0]['amount_holds']; 
    
    
}

function container_own_label($entry_number){
    $db = new Database();
    $jk = $db->where("entry",$entry_number)->get("sludge_containers","container_no");
    
    $lp = $db->where("container_id",$jk[0]['container_no'])->get("sludge_list_of_containers","container_id");
    return $lp[0]['container_id'];
    
}

function service_list ($name = NULL,$compare =NULL){
    global $db;
    if($name == NULL){
        $namex = "service_list";
        $idx = "service_list";
    } else {
        $namex = $name;
        $idx = $name;
    }
    
    $list ="<select name='$namex' id='$idx'><option></option>";
    $list .="<option "; if($compare == 6) { $list .= " selected "; }  $list .=" value='6'>Site Cleanup</option>";
    $list .="<option "; if($compare == 392) { $list .= " selected "; }  $list .="  value='392'>Verify Containment</option>";
    $list .="<option "; if($compare == 3) { $list .= " selected "; }  $list .="  value='3'>Container Delivery</option>";
    $list .="<option "; if($compare == 7) { $list .= " selected "; }  $list .="  value='7'>Lid Delivery</option>";
    $list .="<option "; if($compare == 8) { $list .= " selected "; }  $list .="  value='8'>Wheels: Add/Modify</option>";
    $list .="<option "; if($compare == 10) { $list .= " selected "; }  $list .="  value='10'>Lock: Add/Modify</option>";
    $list .="<option "; if($compare == 24) { $list .= " selected "; }  $list .="  value='24'>Sensors: Add/Modify</option>";
    $list .="<option "; if($compare == 100) { $list .= " selected "; }  $list .="  value='100'>Swap</option>";
    $list .="<option "; if($compare == 20) { $list .= " selected "; }  $list .="  value='20'>Other</option>";
    $list .="<option "; if($compare == 4) { $list .= " selected "; }  $list .="  value='4'>Container Retrieval</option>";
    $list .="</option>";
    return $list;
}

function start_date($route_id,$day){
    global $db;
    $sched = $db->query("SELECT DATE(start_date) as Date FROM sludge_rout_history_grease WHERE route_no = $route_id AND what_day =$day");
    if(count($sched)>0){
        return $sched[0]['Date'];
    }else {
       return "0000-00-00";
    }
}

function time_start($route_id,$day){
    $fs_stop ="00:00:00";
    global $db;
    $f_stop = $db->query("SELECT time_start FROM sludge_rout_history_grease WHERE route_no=$route_id AND what_day = $day ORDER BY time_start ASC LIMIT 0,1");
    if(count($f_stop)>0){
        $fs_stop = $f_stop[0]['time_start'];
    }
    return $fs_stop;
}

function time_end($route_id,$day){
    $ls_stop = "00:00:00";
    global $db;
    $l_stop = $db->query("SELECT time_end FROM sludge_rout_history_grease WHERE route_no=$route_id  AND what_day = $day ORDER BY time_end DESC LIMIT 0,1");
    if(count($l_stop)>0){
        $ls_stop = $l_stop[0]['time_end'];
    }
    return $ls_stop;
}


function start_time_from_date($route_id,$day){
    global $db;
    $start_time = "00:00:00";
     $sched = $db->query("SELECT first_stop as Time FROM sludge_rout_history_grease WHERE route_no =$route_id AND what_day =$day");
    if(count($sched)>0){
        $start_time=  $sched[0]['Time'];
    }
    return $start_time;
}

function end_time_from_date($route_id,$day){
    global $db;
    $end_time = "00:00:00";
    $end1 = $db->query("SELECT last_stop as ETime FROM sludge_rout_history_grease WHERE route_no = $route_id AND what_day =$day");
    if(count($end1)>0){
        $end_time= $end1[0]['ETime'];
    } 
    return $end_time;
}

function total_hours($route_id,$day){
    global $db;
    $total_day = 0;
    $day1 = $db->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(last_stop, first_stop)))) AS totalhours FROM `sludge_rout_history_grease` WHERE route_no=$route_id AND what_day =$day GROUP BY what_day");
    if(count($day1)>0){
        $total_day = $day1[0]['totalhours'];
    }
    return $total_day;
}


function first_stop_mileage($route_id,$day){
    global $db;
    $f_mileage = 0; 
    if($route_id>0 && $route_id !="" && $route_id !=" "){
        $first_stop_mileage = $db->query("SELECT first_stop_mileage FROM sludge_rout_history_grease WHERE route_no=$route_id  AND what_day =$day");
        if(count($first_stop_mileage)>0){
            $f_mileage = $first_stop_mileage[0]['first_stop_mileage'];
        }
    }
    return $f_mileage;
}

function last_stop_mileage($route_id,$day){
    global $db;
    $l_mileage = 0;
    
    if($route_id>0&& $route_id !="" && $route_id !=" "){
        $last_stop_mileage = $db->query("SELECT last_stop_mileage FROM sludge_rout_history_grease WHERE route_no=$route_id AND what_day =$day");
        if(count($last_stop_mileage)>0){
            $l_mileage = $last_stop_mileage[0]['last_stop_mileage'];
        }
    }
    return $l_mileage;
}


function start_mileage($route_id,$day){
    global $db;
    $start = 0;
    $s = $db->query("SELECT start_mileage FROM sludge_rout_history_grease WHERE route_no = $route_id AND what_day =$day");
    if(count($s)>0){
       return $s[0]['start_mileage'];
    } else {
        return $start; 
    }
}

function end_mileage($route_id,$day){
    global $db;
    $end = 0;
    $e = $db->query("SELECT end_mileage FROM sludge_rout_history_grease WHERE route_no = $route_id AND what_day =$day");
    if(count($e)>0){
        return $e[0]['end_mileage'];
    } else {
        return $end; 
    }
}

function lb_per_stop($net_weight,$stops){
    if($net_weight == 0 || $net_weight == null || $net_weight==" " || $net_weight =="" || $net_weight <= 0 || $stops <= 0){
       return $avg_lb_stop = 0.00;    
    } else {
       $avg_lb_stop =  number_format($net_weight/$stops,2);
       return $avg_lb_stop;
    }
    
    
    return $avg_lb_stop;
}



function fuel_gal($miles){
    return number_format($miles/7,2);
}

function avg_fuel_money($total_mile,$stops){
    $modifier = 7.56;
    if($total_mile == 0 || $total_mile == null || $total_mile==" " || $total_mile ==""  || $total_mile <= 0 || $stops <= 0){
       return $avg_fuel = 0.00;    
    } else {
        $avg_fuel =  number_format( ( $total_mile/$modifier )/$stops ,2);
        return $avg_fuel;
    }
}

function avg_miles_per_stop($total_pu_mileage,$stops){
    if($total_pu_mileage <=0 || $stops <= 0){
       return $avg_miles_stop = 0;
    }else{
       return $avg_miles_stop = number_format($total_pu_mileage/$stops,2);
    }
    return $avg_miles_stop;
}
function avg_hours_stop($time_start,$total_pu_hours,$stops){
    if($time_start == "00:00" || $time_start == null || $time_start==" " || $time_start =="" || $time_start <= 0 || $stops <= 0){
       return $avg_hours_stops = 0.00;
    }else {
       return $avg_hours_stops = ($total_pu_hours/$stops) * 60;
    }
}




function charged_amount($route_id){
    global $db;
    $data = $db->query("SELECT ppg,inches_to_gallons FROM sludge_grease_data_table WHERE route_id = $route_id");
    if(count($data)>0){
        $all_picked_up=0;
        foreach($data as $calc){
            $all_picked_up += $calc['ppg']*$calc['inches_to_gallons'];
        }
    }else {
       $all_picked_up = 0;
    }
    return $all_picked_up;
}

function variable_operating($labor_cost,$total_mileage,$truck_mpg,$net_gallons,$truck_r_m,$trailer_r_m,$fuel_cost,$truck_dep_hour,$water_treatment_cost,$trailer_dep_hour,$other_expenses,$route_hours){
    global $db;
    
    $constants = $db->query("SELECT COALESCE(value,NULL,0) as value FROM overhead_value WHERE id IN(2,3,4,5) ORDER BY id ASC");
    
    $a = $labor_cost * round($constants[1]['value'],2);
    
    $b = $constants[0]['value'] + $a;
    $c = $total_mileage * $truck_r_m;
    $d = $c+ $b;
    $e = $d + $fuel_cost ;
    $f = $e +  ($truck_dep_hour*$route_hours) + ($trailer_dep_hour*$route_hours)+ $water_treatment_cost +$other_expenses;
    
    
    
   
    if($net_gallons>0){
        return $f/$net_gallons;
    } else {
        return 0;
    } 
    
}

?>
