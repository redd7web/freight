<?php
include "protected/global.php";
ini_set("display_errors",1);
    function randomKey($length) {
        $key = "";
        $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
    
        for($i=0; $i < $length; $i++) {
            $key .= $pool[mt_rand(0, count($pool) - 1)];
        }
        return $key;
    }
    $new_pw = randomKey(13);
    $encrypted = crypt("$new_pw",'$105Biotane');
    $header = "From: inet-user-creation@iwpusa.com\r\n";
    $header .= "Reply-To: No-reply@iwpusa.com\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";    
    $first = "Doug";
    $last = "Swartz";
    $f = str_split($first);
    $email = $f[0]."$last@iwpusa.com";
    $array = array(
        "first"=>"$first",
        "last"=>"$last",
        "middle"=>"",
        "email"=>"$email",
        "address"=>"",
        "city"=>"Mira Loma",
        "state"=>"CA",
        "zip"=>"92236",
        "areacode"=>"",
        "phone"=>"",
        "carrier"=>"",
        "roles"=>"shop crew~phone message~theft~call center~new fires~assigned issues~sales zone~data entry~data management~corporate manager~facility manager~scheduler~oil driver~service driver~sales leads~sales representative~account represntative~data management~user management~staff management~sales management~customer support advanced~report access~advanced searching~advancing routing~routing~customer support full~customer support~admin~",
        "title"=>"",
        "facility"=>23,
        "user_id"=>"",
        "staff_id"=>"",
        "account_created"=> date("Y-m-d H:i:s"),
        "last_login"=>"",
        "duty"=>"",
        "login_name"=>$f[0]."$last",
        "password"=>$encrypted,
        "facility_restriction"=>"",
        "division_restriction"=>"",
        "driver_hourly_pay"=>"",
        "notes"=>""
    );
   
    
    if($db->insert("sludge_users",$array)) { 
        mail("ABurkett@iwpusa.com,ttrawick@iwpusa.com,jtrawick@iwpusa.com,AParsons@iwpusa.com,edizon@iwpusa.com","New User added in Oil","New User added $first $last \r\n Please review pending user in Sludge - > Management - > Users \r\n Password is set to: $new_pw",$header);
        echo "User inserted successfully.";
    }


?>