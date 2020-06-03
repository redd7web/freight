<?php 
$conn = mysql_connect("localhost","root","password1");
mysql_select_db("iwp",$conn);
//$person = new Person();
?>
<link rel="stylesheet" href="css/style.css" />
<style type="text/css">
body{
    padding:9px 9px 9px 9px;
    text-align:center;
}
</style>
<?php
$query = mysql_query("SELECT * FROM freight_jacobsen WHERE id=$_GET[jacobnumber]") or die (mysql_error());

$answer = mysql_fetch_array($query);
//var_dump($answer);
;
?>

<form action="updateJake.php?jacobnumber=<?php echo $_GET['jacobnumber']; ?>" method="post">
Price: <input type="text" placeholder=".01" value="<?php echo $answer['percentage'];  ?>" name="price" style="width: 40%;"/>&nbsp;<input type="submit" value="Edit Jacobsen" name="addjake"/>
</form>

<?php


if( isset(  $_POST['addjake'])  ) {    
    $dd = date('Y-m-d');
    mysql_query("UPDATE freight_jacobsen set percentage=$_POST[price],modified='$dd' WHERE id=$_GET[jacobnumber]") or die (mysql_error());
    ?>
    <script>
        window.parent.location.href ='../management.php?task=indices';  
        window.parent.Shadowbox.close(); 
    </script>    
    <?php
    
}
?>