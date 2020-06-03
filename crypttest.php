<?php
include "protected/global.php";


$update = $db->get("freight_users","login_name,password,user_id");

if(count($update)>0){
    foreach($update as $newcred){
        $md5 = crypt("Pass123",'$105Biotane');
        $pack = array(
            "password"=>$md5
        );
        $db->where("user_id",$newcred['user_id'])->update("freight_users",$pack);
    }
}







?>