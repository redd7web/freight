<?php
    include "protected/global.php";
    ini_set("display_errors",1);
    $password = "Pass123";
    
     $md5 = crypt($password,'$105Biotane');
    
    $array = array(
        "first"=>"Garret",
        "last"=>"Ruff",
        "middle"=>"",
        "email"=>"gruff@iwpusa.com",
        "address"=>"",
        "city"=>"Coachella",
        "state"=>"CA",
        "zip"=>"",
        "areacode"=>"",
        "phone"=>"",
        "carrier"=>"Verizon",
        "roles"=>"admin~",
        "title"=>"Admin",
        "facility"=>99,
        "user_id"=>"",
        "staff_id"=>"",
        "account_created"=> date("Y-m-d H:i:s"),
        "last_login"=>"",
        "duty"=>"",
        "login_name"=>"GRuff",
        "password"=>$md5,
        "facility_restriction"=>"",
        "division_restriction"=>"",
        "driver_hourly_pay"=>"",
        "notes"=>""
    );
    var_dump($array);
    if($db->insert("sludge_users",$array)){
        echo "User $_POST[fname] $_POST[lname] INSERTED !";
    }
    
?>