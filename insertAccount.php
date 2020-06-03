<?php
include "protected/global.php";
ini_set("display_errors",1);

//Geocode facility
$person = new Person();
$head ='From: account-creation@iwpusa.com' . "\r\n" .
            'Reply-To: no-replyr@iwpusa.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion()."\r\n";
            $head .= "MIME-Version: 1.0\r\n";
            $head .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';




/*
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
*/


$prevcomp ="";

$cpp="";
$pg = "";
$cod="";
/*
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
}*/


$data= Array(
    "id"=>0,
    "status"=>"Active",
    "class"=>$person->user_id,
    "name"=>$_POST['accountname'],
    "city"=>$_POST['city'],
    "state"=>$_POST['state'],
    "created"=>$_POST['start_date'],
    "expires"=>$_POST['end_date'],
    "address"=>$_POST['address'],
    "zip"=>$_POST['zipcode'],
    "country"=>"USA"
);

var_dump($data);

$db->insert("freight_accounts",$data);


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
