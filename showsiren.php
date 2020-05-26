<?php
include "protected/global.php";
$account = new Account($_POST['id']);

echo $account->siren; ?>
<br /><br />
<?php echo"<span style='font-size:20px;font-weight:bold;font-familt:tahoma'>". round($account->avg_gallon)."/".$account->total_barrel_capacity."</span>"; ?>