<?php
include "protected/global.php";
//Geocode facility
$person = new Person();
$head ='From: account-creation@iwpusa.com' . "\r\n" .
            'Reply-To: no-replyr@iwpusa.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion()."\r\n";
            $head .= "MIME-Version: 1.0\r\n";
            $head .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';

if(isset($_POST['account_sharing_host'])){
    $host = $_POST['account_sharing_host'];
}else {
    $host = "host";    
}


if(strlen($_POST['floorvalue'])>0){
    $floor = $_POST['floorvalue'];
} else {
    $floor = $_POST['floor_type'];
}

function get_lat_long($address){
    $region = "US";
    $address = str_replace(" ", "+", $address);
    
    $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
    $json = json_decode($json);

    $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
    return $lat.','.$long;
}







    $latlong    =   get_lat_long("$_POST[address] $_POST[city], $_POST[state] $_POST[zipcode] "); // create a function with the name "get_lat_long" given as below
    $map        =   explode(',' ,$latlong);
    $mapLat         =   $map[0];
    $mapLong    =   $map[1]; 
    


$prevcomp ="";

$cpp="";
$pg = "";
$cod="";
switch($_POST['payment_type']){
     case "Charge Per Pickup": case "Index":
         $cpp = $_POST['changer_input'];
         break;
     case "Per Gallon": case "Normal":
         $pg = $_POST['changer_input'];
         break;
     case "One Time Payment Per Gallon": case "O.T.P. Per Gallon":
         $cod = $_POST['otb'];
         $pg =$_POST['changer_input'];
         break;
     case "O.T.P.": case "One Time Payment": case"Cash On Delivery":     
         $cod =$_POST['changer_input'];
     break; 
}

if(!isset($_POST['type1']) && isset($_POST['type2'])   ){
    $stat = "active";
} else {
    $stat = "pending";
}

$data= Array(
    "status"=>"pending",
    "name"=>$_POST['accountname'],
    "class"=>$person->user_id,
    "city"=>$_POST['city'],
    "state"=>$_POST['state'],
    "created"=>$_POST['start_date'],
    "expires"=>$_POST['end_date'],
    "address"=>$_POST['address'],
    "zip"=>$_POST['zipcode'],
    "billing_address"=>$_POST['billing_address'],
    "billing_state"=>$_POST['billing_state'],
    "billing_city"=>$_POST['billing_city'],
    "billing_zip"=>$_POST['billing_zip'],
    "area_code"=>$_POST['areacode'],
    "phone"=>$_POST['phone'],
    "contact_name"=>$_POST['c_first_name']." ".$_POST['c_last_name'],
    "contact_title"=>$_POST['title'],
    "pickup_frequency"=>30,
    "payee_name"=>$_POST['payee'],
    "email_address"=>$_POST['email'],
    "original_sales_person"=>$_POST['sales_rep'],
    "account_rep"=>$_POST['sales_rep'],
    "state_date"=>$_POST['start_date'],
    "payment_method"=>$_POST['payment_type'],
    "guest_host"=>$host,
    "url"=>$_POST['website'],
    "floor"=> $floor,
    "miu"=>number_format($_POST['miu']/100,2),
    "latitude"=>$mapLat,
    "longitude"=>$mapLong,
    "division"=>$_POST['facility'],
    "is_oil"=>$_POST['type1'],
    "is_trap"=>$_POST['type2'],
    "grease_volume"=>$_POST['estimated_monthly_volume'],
    "new_bos"=>$_POST['new_bos'],
    "country"=>"USA",
    "grease_ppg"=>$pg,
    "index_percentage"=>$cpp,
    "ppg_jacobsen_percentage"=>$cod,
    "grease_freq"=>$_POST['frequency']    
);

//var_dump($data);



if(isset($_POST['type1']) || isset($_POST['type2'])){
    if($db->insert($dbprefix."_accounts",$data)){
        $id = $db->getInsertId();
        mail("KMickle@iwpusa.com, LBriseno@iwpusa.com, bgastelum@iwpusa.com, AParsons@iwpusa.com,GRuff@iwpusa.com","New Account created New Bos number needed","Name: <a href='https://inet.iwpusa.com/grease/viewAccount.php?id=$id' target='_blank'>$_POST[accountname]</a> \r\n Account id:$id \r\n Address: $_POST[address], $_POST[city], $_POST[state] $_POST[zipcode] \r\n Phone: $_POST[areacode] - $_POST[phone] \r\n Contact Name: $_POST[c_first_name] $_POST[c_last_name] \r\n Division: ".numberToFacility($_POST['facility'])."\r\n Created by: $person->first_name $person->last_name",$head);    
    }

 
    
    
    
    header("location:viewAccount.php?id=$id&sched_util=1");
    
} else {
    echo "Please check oil and/or grease for type";
}



 
 //echo "<br/>account id:".$id."<br/>";
 
 
 /*
 $contain = Array(
        "container_no"=>$_POST['container_amount'],
        "account_no"=>$id,
        "request_date"=>date("Y-m-d")
 );
 
  var_dump($contain);
  echo $db->insert($dbprefix."_containers",$contain); 
 
 */

    

?>
