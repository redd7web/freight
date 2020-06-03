<?php 
include "protected/global.php"; 
$page = "home";
if(isset($_SESSION['freight_id'])){$person = new Person();}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="ReDDaWG" />
    <meta charset="UTF-8" />
   <?php include "source/css.php"; ?>
   <?php include "source/scripts.php"; ?>
	<title>Grease Trap Customer Management System</title>
</head>
<body>
<?php include "source/header.php"; ?>
<div id="wrapper">
<?php
$check_uc =  array(
    24,30,31,32,33
);

//if you are a facility manage of any uc facility or Richard Lopez
if(  ( isset($_SESSION['freight_id']) && in_array($person->facility ,$check_uc) && $person->isFacilityManager() ) || ( isset($_SESSION['freight_id']) && (  $person->user_id ==149 || $person->user_id ==168 || $person->user_id == 137  ) ) ){
    
    ?>
    
    <iframe style="width: 100%;border:0px solid #bbb;" frameborder="0" src="predict.php"></iframe>
    <script>
        $("#transparent").css("display","none");
        $('iframe').load(function () {
            $('iframe').height($('iframe').contents().height());
        });
    </script>
    <?Php
}
?>

</div>
<?php include "source/footer.php"; ?>
</body>
</html>