<?php
    include "protected/global.php";
    ini_set("display_errors",1);
    $password = "Pass123";
    
     $md5 = crypt($password,'$105Biotane');
    
    
    $db->query("UPDATE freight_users SET password='$md5' WHERE user_id=165");
    
?>