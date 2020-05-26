<?php
if(isset($_POST['usercreate'])){ 
 
 $data = Array ( 
    "first"=>$_POST['first_name'],
    "last"=>$_POST['last_name'],
    "middle"=>$_POST['middle_name'],
    "email"=>$_POST['email'],
    "address"=>$_POST['address'],
    "city"=>$_POST['city'],
    "state"=>$_POST['state'],
    "areacode"=>$_POST['areacode'],
    "phone"=>$_POST['phone'],
    "carrier"=>$_POST['carrier'],
    "roles"=>"$_POST[roles]",
    "title"=>$_POST['title'],
    "facility"=>$_POST['facility'],
    "staff_id"=>"",
    "account_created"=>date("Y-m-d H:i:s"),
    "last_login"=>"",
    "duty"=>$_POST['duty'],
    "login_name"=>$_POST['username'],
    "password"=>md5("$_POST[email]$_POST[password]"),
    "facility_restriction"=>$_POST['facility_restriction'],
    "division_restriction"=>$_POST['division_restriction'],
    "driver_hourly_pay"=>"",
    "notes"=>""
 );
 $db->insert($dbprefix."_users",$data); 
    
}
?>