<?php
include "protected/global.php";
ini_set("display_errors",1);
if(isset($_GET['chpw'])){
   //echo $person->user_id."<br/>";
    $hashed =  crypt("5(G}9s&8",'$105Biotane');

    $package = array(
        "password"=>$hashed
    );
    
   echo "<pre>"; 
   print_r($package);
   echo "</pre>";
    
    if($db->where("user_id",$_GET['chpw'])->update("sludge_users",$package)){
        echo "<span style='color:green;'>Password Changed successfully!</span><br/>";
    }
}

?>
