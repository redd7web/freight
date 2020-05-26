<?php
include "protected/global.php";
if(isset($_POST['textnow'])){
    $ikg_grease = new Grease_IKG($_GET['route_id']);
    $person = new Person($ikg_grease->driver);
    switch($person->carrier){
        case "verizon":
            $carrier = "vtext.com";
            break;
        case "Tmobile":
            $carrier = "tmomail.net";
            break;
        default: 
            $carrier = "vtext.com";
            break;
    }
    $headers = "From: " . strip_tags("iwpcommandcenter@iwpusa.com") . "\r\n";
    $headers .= "Reply-To: ". strip_tags("no-reply@iwpusa.com") . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    mail("$person->area_code$person->phone@$carrier","New Stops added","$_POST[msg] \r\n <a href='grease_route_gateway.php?route_id=$_GET[route_id]' >$_GET[route_id]</a>",$headers);
}

?>
<style>
body{
    padding:10px 10px 10px 10px;
    margin:10px 10px 10px 10px;
}
</style>
<body>
<form action="textDriver.php?route_id=<?php echo $_GET['route_id']; ?>" method="post">
<textarea placeholder="Message for driver" style="width: 350px;margin:auto;height:180px;" name="msg">
</textarea>
<br/>
<input type="submit" value="Text Now" name="textnow" style="float: right;"/>
</form>
</body>