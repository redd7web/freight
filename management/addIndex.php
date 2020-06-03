<?php 
include "../protected/global.php";
$conn = mysql_connect("localhost","root","password1");
mysql_select_db("iwp",$conn);
$person = new Person();
?>
<link rel="stylesheet" href="css/style.css" />
<style type="text/css">
body{
    padding:9px 9px 9px 9px;
    text-align:center;
}
</style>
<form action="addIndex.php" method="post">

Price: <input type="text" placeholder=".01" name="price" style="width: 40%;"/>&nbsp;<input type="submit" value="Add Jacobsen" name="addjake"/>
</form>

<?php


if( isset(  $_POST['addjake'])  ) {    
    $price = floatval ($_POST['price']);
    $dd = date('Y-m-d');
    mysql_query("INSERT into freight_jacobsen values('','$dd',$price,$person->user_id,'')") or die (mysql_error());
    $track = array(
        "date"=>date("Y-m-d H:i:s"),
        "user"=>$person->user_id,
        "actionType"=>"Index Updated",
        "descript"=>"Index added $price",
        "account"=>$_POST['account'],
        "pertains"=>7,
        "type"=>15
    );
    $db->insert($dbprefix."_activity",$track);
     
    
    ?>
    <script>
        window.parent.location.href ='../management.php?task=indices';  
        window.parent.Shadowbox.close(); 
    </script>    
    <?php
}
?>