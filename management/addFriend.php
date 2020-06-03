<?php 
include "../protected/global.php";
$person = new Person();

?>
<link rel="stylesheet" href="css/style.css" />
<style type="text/css">
body{
    padding:9px 9px 9px 9px;
    text-align:center;
}
</style>
<form action="addFriend.php" method="post">

Name: <input type="text" placeholder="Friendly Name" name="frname" style="width: 40%;"/><br /><br />
Address: <input type="text" placeholder="Friendly Addresse" name="fraddress" style="width: 40%;"/><br /><br />
Rep First Name: <input type="text" placeholder="Representative First Name" name="frrepname" style="width: 40%;"/><br /><br />
Rep Last Name: <input type="text" placeholder="Representative Last Name" name="ltname" style="width: 40%;"/><br /><br />
Rep Email: <input type="text" placeholder="name@domain.com" name="repemail" style="width: 40%;"/><br /><br />
Rep Phone: <input type="text" placeholder="1112223333" name="repphone" style="width: 40%;"/><br /><br />

<input type="submit" value="Add Friendly" name="addfr"/>
</form>

<?php
if( isset(  $_POST['addfr'])  ) {    
    $buffer = Array(
       "comp_id"=>"",
       "comp_name"=>"$_POST[frname]",
       "comp_address"=>"$_POST[fraddress]",
       "comp_rep_fname"=>"$_POST[frrepname]",
       "com_rep_lname"=>"$_POST[ltname]",
       "com_rep"=>"$_POST[frname] $_POST[ltname]",
       "comp_rep_email"=>"$_POST[repemail]",
       "comp_rep_phone"=>"$_POST[repphone]"
    );    
    $dd  = date("Y-m-d");    
     if($db->insert($dbprefix."_friendly",$buffer)){
        ?>
    <script>
        window.parent.location.href ='../management.php?task=friendly';  
        window.parent.Shadowbox.close(); 
    </script>    
    <?php
     }
}

?>