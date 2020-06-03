<?php
    include "protected/global.php";
    ini_set("display_errors",1);
    $password = $_POST['password'];
    
     $md5 = crypt($password,'$105Biotane');
    
    $array = array(
        "first"=>$_POST['fname'],
        "last"=>$_POST['lname'],
        "middle"=>$_POST['mname'],
        "email"=>$_POST['email'],
        "address"=>$_POST['address'],
        "city"=>$_POST['city'],
        "state"=>$_POST['state'],
        "zip"=>$_POST['zipcode'],
        "areacode"=>$_POST['areacode'],
        "phone"=>$_POST["phone"],
        "carrier"=>"verizon",
        "roles"=>$_POST['roles'],
        "title"=>$_POST['title'],
        "facility"=>$_POST['facility'],
        "user_id"=>"",
        "staff_id"=>"",
        "account_created"=> date("Y-m-d H:i:s"),
        "last_login"=>"",
        "duty"=>$_POST['duty'],
        "login_name"=>$_POST['username'],
        "password"=>$md5,
        "facility_restriction"=>"",
        "division_restriction"=>"",
        "driver_hourly_pay"=>$_POST['pay'],
        "notes"=>"",
        "new_bos_coa"=>$_POST['coa']
    );
    var_dump($array);
    if($db->insert("freight_users",$array)){
        echo "User $_POST[fname] $_POST[lname] INSERTED !";
    }
    
?>