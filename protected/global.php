<?php
session_start();
 ini_set("display_errors",1);
date_default_timezone_set('America/Los_Angeles');
ini_set('max_execution_time', -1);
 
static $debug = 2;
static $dbprefix="freight";
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
        "username"=>"phpmyadmin",
        "password"=>"IwpSoftware1!",
        "database"=>"freight"
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
        case 101:
            return "Schedule Grease Trap Service";
            break;
        case 102:
            return "Schedule Jetting";
            break;
        case 103:
            return "Schedule Confined Space";
            break;
        case 104:
            return "Receipt/Invoice Request";
            break;
        case 105:
            return "Contact Customer";
            break;
        case 106:
            return "Other";
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
    $answer = $db->where("user_id",$number)->get("freight_users","first,last,user_id");
    if(count($answer) >0){
        return "<a href='viewUser.php?id=$number'>".$answer[0]['first']." ".$answer[0]['last']."</a>";
    }
    
}

function user_info($number,$field){
    
    $db = new Database();
    $answer = $db->where("user_id",$number)->get("freight_users","$field");
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

function account_NumtoNamePlain($number){
    global $dbprefix;
    $db = new Database();
    $request = $db->where("account_ID",$number)->get($dbprefix.'_accounts','name,account_ID');
    
   if (count($request)>0){
     foreach($request as $vlue){
         $t = $vlue['account_ID'];
        $name = $vlue['name'];
        return $name;;
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
    $request = $db->orderby("id","desc")->get("freight_jacobsen");    
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


function outboundFacility( $list_name = NULL ,$compare = NULL){
    global $db;
    $selected ="";
    if(strlen($list_name)>0){
        
        $name = $list_name;
    }
    else {
        $name = "facility";
    }
    
    $select = "<select id='$name' name='$name'><option value='ignore' required>--</option>";
    $select .='<option value="16" />Victorville</option>
    <option '; if($compare ==17){ $select .='selected'; }   $select .='  value="17" />RC Waste Resources BL</option>
    <option '; if($compare ==18){ $select .='selected'; }   $select .='  value="18" />RC Waste Resources LC</option>
    ';
    $select .="</select>";
    echo $select;
}

function inboundFacility( $list_name = NULL ,$compare = NULL){
    global $db;
    $selected ="";
    if(strlen($list_name)>0){
        
        $name = $list_name;
    }
    else {
        $name = "facility";
    }
    
    $fr1 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =201");
    $fr2 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =202");
    $fr3 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =203");
    $fr4 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =204");
    $fr5 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =205");
    $fr6 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =206");
    $fr7 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =207");
    $fr8 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =208");
    $fr9 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =209");
    $fr10 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =210");
    $fr11 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =211");
    
    $tmp1 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =212");
    $tmp2 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =213");
    $tmp3 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =214");
    $tmp4 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =215");
    $tmp5 = $db->query("SELECT locked,master_lock FROM freight_accounts WHERE account_ID =216");
    
    $select = "<select id='$name' name='$name'><option value='ignore' required>--</option>";
        $select  .='<option'; 
            if( $fr1[0]['locked'] ==1 || $fr1[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 201){  $select .= " selected "; }
        $select .=' value="201">Frack Tank 1</option>';
        $select  .='<option'; 
            if(  $fr2[0]['locked'] ==1 || $fr2[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 202){  $select .= " selected "; }
        $select .='  value="202">Frack Tank 2</option>';
        $select  .='<option value="203"'; 
            if(  $fr3[0]['locked'] ==1 || $fr3[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 203){  $select .= " selected "; }
        $select .=' >Frack Tank 3</option>';
        $select  .='<option value="204"'; 
            if(  $fr4[0]['locked'] ==1 || $fr4[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 204){  $select .= " selected "; }
        $select .=' >Frack Tank 4</option>';
        $select  .='<option'; 
            if(  $fr5[0]['locked'] ==1 || $fr5[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 205){  $select .= " selected "; }
        $select .='  value="205">Frack Tank 5</option>';
        $select  .='<option value="206"'; 
            if(  $fr6[0]['locked'] ==1 || $fr6[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 206){  $select .= " selected "; }
        $select .=' >Frack Tank 6</option>';
        $select  .='<option value="207"'; 
            if(  $fr7[0]['locked'] ==1 || $fr7[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 207){  $select .= " selected "; }
        $select .=' >Frack Tank 7</option>';
        $select  .='<option value="208"'; 
            if(  $fr8[0]['locked'] ==1 || $fr8[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 208){  $select .= " selected "; }
        $select .=' >Frack Tank 8</option>';
        $select  .='<option value="209"'; 
            if(  $fr9[0]['locked'] ==1 || $fr9[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 209){  $select .= " selected "; }
        $select .=' >Frack Tank 9</option>';
        $select  .='<option'; 
            if(  $fr10[0]['locked'] ==1 || $fr10[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 210){  $select .= " selected "; }
        $select .='  value="210">Frack Tank 10</option>';
        $select  .='<option value="211"'; 
            if(  $fr11[0]['locked'] ==1 || $fr11[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 211){  $select .= " selected "; }
        $select .=' >Frack Tank 11</option>';
        
        $select  .='<option value="211"'; 
            if(  $fr11[0]['locked'] ==1 || $fr11[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 211){  $select .= " selected "; }
        $select .=' >Frack Tank 11</option>';
        
        $select  .='<option value="212"'; 
            if(  $tmp1[0]['locked'] ==1 || $tmp1[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 212){  $select .= " selected "; }
        $select .=' >Temp Frac1</option>';
        
        $select  .='<option value="213"'; 
            if(  $tmp2[0]['locked'] ==1 || $tmp2[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 213){  $select .= " selected "; }
        $select .=' >Temp Frac2</option>';
        
        $select  .='<option value="214"'; 
            if(  $tmp3[0]['locked'] ==1 || $tmp3[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 214){  $select .= " selected "; }
        $select .=' >Temp Frac3</option>';
        
        $select  .='<option value="215"'; 
            if(  $tmp4[0]['locked'] ==1 || $tmp4[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 215){  $select .= " selected "; }
        $select .=' >Temp Frac4</option>';
        
        $select  .='<option value="216"'; 
            if(  $tmp5[0]['locked'] ==1 || $tmp5[0]['master_lock']==1 ) { $select  .= " disabled ='disabled' "; }
            if( $compare == 216){  $select .= " selected "; }
        $select .=' >Temp Frac5</option>';
        
     $select .='<option '; if($compare ==15){ $select .='selected'; }   $select .='  value="15" />W Division</option>';
    $select .="</select>";
    echo $select;
}


function getFacilityList( $list_name = NULL ,$compare = NULL){   
    global $db;
    $selected ="";
    if(strlen($list_name)>0){
        
        $name = $list_name;
    }
    else {
        $name = "facility";
    }
    $select = "<select id='$name' name='$name' ";  
        if( $compare != NULL){
             $select .=" rel='$list_name' ";
        }
    
    $select .= "><option value='ignore' required>--</option>";
    
         $select .=' <option '; if($compare == 22){$select .= 'selected'; }  $select.=' value="22">San Diego (US Division))</option>
                <option '; if($compare == 23){$select .= 'selected'; }  $select.=' value="23">Imperial Western Products</option>
                <option '; if($compare == 99){$select .= 'selected'; }  $select.=' value="99">ALL UC</option>
                <option '; if($compare == 24){$select .= 'selected'; }  $select.=' value="24">UC Division (Corporate)</option>
                <option '; if($compare == 30){$select .= 'selected'; }  $select.=' value="30">UC Division (San Bernadino)</option>
                <option '; if($compare == 31){$select .= 'selected'; }  $select.=' value="31">UC Division (Los Angeles)</option>
                <option '; if($compare == 32){$select .= 'selected'; }  $select.='  value="32">UC Division (Riverside)</option>
                <option '; if($compare == 33){$select .= 'selected'; }  $select.='  value="33">UC Division (Orange County)</option>
                <option '; if($compare == 8){$select .= 'selected'; }  $select.='  value="8">Arizona (4 Division)</option>
                 <option '; if($compare == 5){$select .= 'selected'; }  $select.='  value="5">VSLM (V Division)</option>
                <option '; if($compare ==10){ $select .='selected'; }   $select .='  value="10">V-BAK</option>
                <option '; if($compare ==11){ $select .='selected'; }   $select .='  value="11">V-Fres</option>
                <option '; if($compare ==12){ $select .='selected'; }   $select .='  value="12">V-North</option>
                <option '; if($compare ==13){ $select .='selected'; }   $select .='  value="13">V-Vis</option>
                <option '; if($compare ==14){ $select .='selected'; }   $select .='  value="14" >L Division</option>
                <option '; if($compare ==15){ $select .='selected'; }   $select .='  value="15">Co West</option>
                <option '; if($compare ==35){ $select .='selected'; }   $select .='   value="35">Arizona Zone 1</option>
                <option '; if($compare ==36){ $select .='selected'; }   $select .='   value="36">Arizona Zone 2</option>
                <option '; if($compare ==37){ $select .='selected'; }   $select .='   value="37">Arizona Zone 3</option>
                <option '; if($compare ==38){ $select .='selected'; }   $select .='   value="38">Arizona Zone 4</option>
                <option '; if($compare ==39){ $select .='selected'; }   $select .='   value="39">Arizona Zone 5</option>
                <option '; if($compare ==40){ $select .='selected'; }   $select .='   value="40">Arizona Zone 6</option>
                <option '; if($compare ==41){ $select .='selected'; }   $select .='   value="41">Arizona Zone 7</option>
                <option '; if($compare ==42){ $select .='selected'; }   $select .='   value="42">Arizona Zone 8</option>
                <option '; if($compare ==43){ $select .='selected'; }   $select .='   value="43">Arizona Zone 9</option>
                <option '; if($compare ==44){ $select .='selected'; }   $select .='   value="44">Arizona Zone 10</option>
                <option '; if($compare ==45){ $select .='selected'; }   $select .='   value="45">Arizona Zone 11</option>
                <option '; if($compare ==46){ $select .='selected'; }   $select .='   value="46">Arizona Zone 12</option>
                <option '; if($compare ==47){ $select .='selected'; }   $select .='   value="47">Arizona Zone 13</option>
                <option '; if($compare ==48){ $select .='selected'; }   $select .='   value="48">Arizona Zone 14</option>
                <option '; if($compare ==49){ $select .='selected'; }   $select .='   value="49">Arizona Zone 15</option>
                <option '; if($compare ==50){ $select .='selected'; }   $select .='   value="50">Arizona Zone Temp</option>';
                
               
    $select .="</select>";
    echo $select;  
  
}


$facils[22]="15777 Old Milky Way, Escondidio, CA 92025 USA";
$facils[23]="86-600 Ave 54, Coachella, CA 92236 USA";
$facils[24]="4085 Bain St., Mira Loma, CA 91752 USA";         
$facils[31]="4085 Bain St., Mira Loma, CA 91752 USA";
$facils[25]="4085 Bain St., Mira Loma, CA 91752 USA";
$facils[32]="4085 Bain St., Mira Loma, CA 91752 USA";
$facils[33]="4085 Bain St., Mira Loma, CA 91752 USA";
$facils[30]="4085 Bain St., Mira Loma, CA 91752 USA";
$facils[8]="7401 S. Wilson Rd., Buckeye, AZ 85326 USA";
$facils[5]="3766 E. Conejo, Selma, CA 93662";
$facils[10]="3766 E. Conejo, Selma, CA 93662 USA";
$facils[11]="3766 E. Conejo, Selma, CA 93662 USA";
$facils[12]="20500 Holly Dr., Tracy, CA 95304 USA";
$facils[13]="3766 E. Conejo, Selma, CA 93662 USA";
$facils[14]="Coachella, CA";
$facils[15]="4085 Bain St., Mira Loma, Ca 91752";
$facils[201]="4085 Bain St., Mira Loma, Ca 91752";
$facils[202]="4085 Bain St., Mira Loma, Ca 91752";
$facils[203]="4085 Bain St., Mira Loma, Ca 91752";
$facils[204]="4085 Bain St., Mira Loma, Ca 91752";
$facils[205]="4085 Bain St., Mira Loma, Ca 91752";
$facils[206]="4085 Bain St., Mira Loma, Ca 91752";
$facils[207]="4085 Bain St., Mira Loma, Ca 91752";
$facils[208]="4085 Bain St., Mira Loma, Ca 91752";
$facils[209]="4085 Bain St., Mira Loma, Ca 91752";
$facils[210]="4085 Bain St., Mira Loma, Ca 91752";
$facils[211]="4085 Bain St., Mira Loma, Ca 91752";
$facils[212]="4085 Bain St., Mira Loma, Ca 91752";
$facils[213]="4085 Bain St., Mira Loma, Ca 91752";
$facils[214]="4085 Bain St., Mira Loma, Ca 91752";
$facils[215]="4085 Bain St., Mira Loma, Ca 91752";
$facils[16]="20111 Shay Rd. Victorville, CA, 92394 USA";
$facils[17]="1000 Midland Rd, Blythe CA 92225 USA";
$facils[18]="16411 Lamb Canyon Rd. Beaumont, CA 92223  USA";
$facils[19]="4020 Bandini Blvd, Vernon, CA 90058 USA";
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
$coords[12]="37.771777,-121.424310";
$coords[13]="36.519258,-119.722510";
$coords[15]="34.173228,-117.346416";
$coords[201]="34.173228,-117.346416";
$coords[202]="34.173228,-117.346416";
$coords[203]="34.173228,-117.346416";
$coords[204]="34.173228,-117.346416";
$coords[205]="34.173228,-117.346416";
$coords[206]="34.173228,-117.346416";
$coords[207]="34.173228,-117.346416";
$coords[208]="34.173228,-117.346416";
$coords[209]="34.173228,-117.346416";
$coords[210]="34.173228,-117.346416";
$coords[211]="34.173228,-117.346416";
$coords[212]="34.173228,-117.346416";
$coords[213]="34.173228,-117.346416";
$coords[214]="34.173228,-117.346416";
$coords[215]="34.173228,-117.346416";
$coords[16]="34.615009,-117.357446";
$coords[17]="33.868468,-114.790322";
$coords[18]="33.883844,-116.996794";
$coords[19]="34.004314,-118.193268";
function numberToFacility($number){
   
    switch($number){       
        case 15:
            return "San Bernadino (Division W)";      
            break;
        case 16: 
            return "Victorville";
            break;  
        case 17:
            return "RC Waste Resources BL";  
            break;      
        case 18:
            return "RC Waste Resources LC";
            break;    
       case 201:
            return "Frack Tank 1";
            break;     
       case 202:
            return "Frack Tank 2";
            break;
       case 203:
            return "Frack Tank 3";
            break;
       case 204:
            return "Frack Tank 4";
            break;
       case 205:
            return "Frack Tank 5";
            break;
       case 206:
            return "Frack Tank 6";
            break;
       case 207:
            return "Frack Tank 7";
            break;
       case 208:
            return "Frack Tank 8";
            break;
       case 209:
            return "Frack Tank 9";
            break;
       case 210:
            return "Frack Tank 10";
            break;
       case 211:
            return "Frack Tank 11";
            break;
        
        case 212:
            return "Temp Frac1";
            break;
        case 213:
            return "Temp Frac2";
            break;
        case 214:
            return "Temp Frac3";
            break;
        case 215:
            return "Temp Frac4";
            break;
        case 216:
            return "Temp Frac5";
            break;                                                  
    }
}


function reverseTranslate($facName){
    switch($facName){
        case "Baker Com":
            return 19;
        break;
        case "Frack Tank 1":
            return 201;
            break;
        case "Frack Tank 2":
            return 202;
            break;
        case "Frack Tank 3":
            return 203;
            break;
        case "Frack Tank 4":
            return 204;
            break;
        case "Frack Tank 5":
            return 205;
            break;
        case "Frack Tank 6":
            return 206;
            break;
        case "Frack Tank 7":
            return 207;
            break;
        case "Frack Tank 8":
            return 208;
            break;
        case "Frack Tank 9":
            return 209;
            break;
        case "Frack Tank 10":
            return 210;
            break;
        case "Frack Tank 11":
            return 211;
            break;
        case "Temp Frac1":
            return 212;
        break;
        
        case "Temp Frac2":
            return 213;
        break;
        
        case "Temp Frac3":
            return 214;
        break;
        case "Temp Frac4":
            return 215;
        break;
        
        case "Temp Frac5":
        return 216;
        break;
        case "San Bernadino (Division W)":
            return 15;
            break;
        case "Victorville":
            return 16;
        case "RC Waste Resources BL":
            return 17;
            break;
        case "RC Waste Resources LC":
            return 18;
            break;
        default:
            return 0;
            break;
    }
}


function getVehiclesList($compare = NULL){
    global $dbprefix;
    $select ="<select id='vehicle' name='vehicle'><option>--</option>";    
    $db = new Database();    
     $request = $db->query("SELECT truck_id,name FROM assets.truck WHERE truck.enabled=1 AND truck.is_freight = 1  AND truck.sold = 0");
    if(count($request) !=0 ){
        foreach($request as $truck){
            $select .= "<option "; if($compare == $truck['truck_id']) { $select .="selected";}else{ $select .="";  }  $select .="  value='$truck[truck_id]'>$truck[name]</option>";
        }
    }    
    $select .="</select>";
    return $select;
}


function getfreight_trailers($compare = NULL){
    global $db;
    $select ="<select id='trailers' name='trailers' style='text-align:center;'><option style='text-align:center;' value='-'>-</option>";
    $request = $db->query("SELECT * FROM assets.trailer WHERE trailer.is_sludge=1 AND trailer.enabled=1  AND truck.sold = 0");
    if(count($request) !=0 ){
        foreach($request as $truck){
                        
            $select .= "<option "; if($compare == $truck['truck_id']) { $select .="selected";} else { $selected .=""; }  $select .="  value='$truck[truck_id]'>$truck[name]</option>";
        }
    }    
    $select .="</select>";
    echo $select;
}

function vehicle_name($truck_no){
    global $dbprefix;    
    global $db;
    if($truck_no  != NULL){
        $truck = $db->query("SELECT truck.name FROM assets.truck WHERE truck.truck_id = $truck_no");
        if(count($truck)>0){
            return $truck[0]['name'];
        } else {
            return "N/A";
        }
    }else{
        return "N/A";
    }
}

function trailer_name($trailer_no = NULL){
    global $db;
    if($trailer_no != NULL){
        $truck = $db->query("SELECT trailer.name FROM assets.trailer WHERE trailer.truck_id = $trailer_no");
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
    echo ' value="10">No oil</option>
    
    <option '; 
    if($compare == 12) { 
        echo 'selected';
    }
    echo 'value="12">Skipped: Driver Choice</option>
    
    <option '; 
    if($compare == 14) { 
        echo 'selected';
    }
    echo ' value="14">Skipped: Truck Full</option>
    
    <option '; 
    if($compare == 16) { 
        echo 'selected';
    }
    echo ' value="16">Skipped: Other</option>
    
    <option '; 
    if($compare == 22) { 
        echo 'selected';
    }
    echo ' value="22">Locked: No Key</option>
    
    <option '; 
    if($compare == 24) { 
        echo 'selected';
    }
    echo ' value="24">Locked: Our key did not work</option>
    
    <option '; 
    if($compare == 26) { 
        echo 'selected';
    }
    echo ' value="26">Blocked</option>
    
    <option '; 
    if($compare == 32) { 
        echo 'selected';
    }
    echo ' value="32">Missed time window</option>
    
    <option '; 
    if($compare == 40) { 
        echo 'selected';
    }
    echo ' value="40">Oil Frozen</option>
    
    <option '; 
    if($compare == 42) { 
        echo 'selected';
    }
    echo ' value="42">Garbage in container</option>
    
    <option '; 
    if($compare == 44) { 
        echo 'selected';
    }
    echo ' value="44">Container damaged</option>
    
    <option '; 
    if($compare == 52) { 
        echo 'selected';
    }
    echo ' value="52">Oil Theft: Suspected</option>
    
    <option '; 
    if($compare == 54) { 
        echo 'selected';
    }
    echo ' value="54">Oil Theft: Confirmed</option>
    
    <option '; 
    if($compare == 62) { 
        echo 'selected';
    }
    echo ' value="62">Location Closed: Temporary</option>
    
    <option '; 
    if($compare == 64) { 
        echo 'selected';
    }
    echo ' value="64">Location Closed: Out of business</option>
    
    <option '; 
    if($compare == 66) { 
        echo 'selected';
    }
    echo ' value="66">Lost Account - Confirmed</option>
    
    <option '; 
    if($compare == 68) { 
        echo 'selected';
    }
    echo ' value="68">Manager refused pickup</option>


    
    <option '; 
    if($compare == 72) { 
        echo 'selected';
    }
    echo ' value="72">Added in Error</option>

<option '; 
    if($compare == 68) { 
        echo 'selected';
    }
    echo ' value="99">Emergency Stop Add</option>
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
    $t = $db->query("SELECT gpi FROM freight_list_of_containers WHERE container_id = $id");
    
    if(count($t)>0){
        return $t[0]['gpi'];
    } else {
        return 0;
    }
    
}


function container_amountHolds($number){
    $db = new Database();
    $jk = $db->where("container_id",$number)->get("freight_list_of_containers","amount_holds");
    return $jk[0]['amount_holds'];
}

function container_amountHolds_from_containers($entry_number){
    $db = new Database();
    $jk = $db->where("entry",$entry_number)->get("freight_containers","container_no");
    
    $lp = $db->where("container_id",$jk[0]['container_no'])->get("freight_list_of_containers","amount_holds");
    return $lp[0]['amount_holds']; 
    
    
}

function container_own_label($entry_number){
    $db = new Database();
    $jk = $db->where("entry",$entry_number)->get("freight_containers","container_no");
    
    $lp = $db->where("container_id",$jk[0]['container_no'])->get("freight_list_of_containers","container_id");
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
    $sched = $db->query("SELECT DATE(start_date) as Date FROM freight_rout_history_grease WHERE route_no = $route_id AND what_day =$day");
    if(count($sched)>0){
        return $sched[0]['Date'];
    }else {
       return "0000-00-00";
    }
}

function time_start($route_id,$day){
    $fs_stop ="00:00:00";
    global $db;
    $f_stop = $db->query("SELECT time_start FROM freight_rout_history_grease WHERE route_no=$route_id AND what_day = $day ORDER BY time_start ASC LIMIT 0,1");
    if(count($f_stop)>0){
        $fs_stop = $f_stop[0]['time_start'];
    }
    return $fs_stop;
}

function time_end($route_id,$day){
    $ls_stop = "00:00:00";
    global $db;
    $l_stop = $db->query("SELECT time_end FROM freight_rout_history_grease WHERE route_no=$route_id  AND what_day = $day ORDER BY time_end DESC LIMIT 0,1");
    if(count($l_stop)>0){
        $ls_stop = $l_stop[0]['time_end'];
    }
    return $ls_stop;
}


function start_time_from_date($route_id,$day){
    global $db;
    $start_time = "00:00:00";
     $sched = $db->query("SELECT first_stop as Time FROM freight_rout_history_grease WHERE route_no =$route_id AND what_day =$day");
    if(count($sched)>0){
        $start_time=  $sched[0]['Time'];
    }
    return $start_time;
}

function end_time_from_date($route_id,$day){
    global $db;
    $end_time = "00:00:00";
    $end1 = $db->query("SELECT last_stop as ETime FROM freight_rout_history_grease WHERE route_no = $route_id AND what_day =$day");
    if(count($end1)>0){
        $end_time= $end1[0]['ETime'];
    } 
    return $end_time;
}

function total_hours($route_id,$day){
    global $db;
    $total_day = 0;
    $day1 = $db->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(last_stop, first_stop)))) AS totalhours FROM `freight_rout_history_grease` WHERE route_no=$route_id AND what_day =$day GROUP BY what_day");
    if(count($day1)>0){
        $total_day = $day1[0]['totalhours'];
    }
    return $total_day;
}


function first_stop_mileage($route_id,$day){
    global $db;
    $f_mileage = 0; 
    if($route_id>0 && $route_id !="" && $route_id !=" "){
        $first_stop_mileage = $db->query("SELECT first_stop_mileage FROM freight_rout_history_grease WHERE route_no=$route_id  AND what_day =$day");
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
        $last_stop_mileage = $db->query("SELECT last_stop_mileage FROM freight_rout_history_grease WHERE route_no=$route_id AND what_day =$day");
        if(count($last_stop_mileage)>0){
            $l_mileage = $last_stop_mileage[0]['last_stop_mileage'];
        }
    }
    return $l_mileage;
}


function start_mileage($route_id,$day){
    global $db;
    $start = 0;
    $s = $db->query("SELECT start_mileage FROM freight_rout_history_grease WHERE route_no = $route_id AND what_day =$day");
    if(count($s)>0){
       return $s[0]['start_mileage'];
    } else {
        return $start; 
    }
}

function end_mileage($route_id,$day){
    global $db;
    $end = 0;
    $e = $db->query("SELECT end_mileage FROM freight_rout_history_grease WHERE route_no = $route_id AND what_day =$day");
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
    $data = $db->query("SELECT ppg,inches_to_gallons FROM freight_grease_data_table WHERE route_id = $route_id");
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
