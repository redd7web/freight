<?php
include "protected/global.php";
error_reporting(1);
$person = new Person();
if(isset($_POST['chpw'])){
   //echo $person->user_id."<br/>";
    $hashed =  crypt("Pass123",'$105Biotane');

    $package = array(
        "password"=>$hashed
    );
    
    if(strlen($person->user_id) <0 ){
        $id = 99;
    } else {
        $id = $person->user_id;
    }
    
    if($db->where("user_id",$id)->update("sludge_users",$package)){
        echo "<span style='color:green;'>Password Changed successfully!</span><br/>";
    }
}

?>

<div style="width: 200px;height:100px;margin:auto;margin-top:10px;">
<form action="updatepw.php" method="post">
<input type="text" name="kh"  placeholder="enter new password here"/><br />
<input type="submit" name="chpw" value="Change Now" />
</form>
</div>