<?php
include "protected/global.php";
$account = new Account();
//echo $account->singleField(($_GET['id']),"latitude");


?>
  <iframe width="900" height="900" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?php echo $account->singleField(($_GET['id']),"latitude"); ?>,<?php echo $account->singleField(($_GET['id']),"longitude"); ?>&hl=es;z=14&amp;output=embed"></iframe><br /><small><a href="https://maps.google.com/maps?q=<?php echo $account->singleField(($_GET['id']),"latitude"); ?>,<?php echo $account->singleField(($_GET['id']),"longitude"); ?>&hl=es;z=14&amp;output=embed" style="color:#0000FF;text-align:left" target="_blank">See map bigger</a></small>