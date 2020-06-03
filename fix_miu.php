<?php
include "protected/global.php";
ini_set('display_errors',1);

$fix = $db->query("SELECT name,index_percentage,account_ID FROM freight_accounts WHERE index_percentage >1");

foreach($fix as $thesex){
    echo $thesex['index_percentage']." $thesex[name]<br/>";
    $kl = $thesex['index_percentage']/100;
    $new_miu = number_format($kl,2);
    $db->query("UPDATE freight_accounts SET index_percentage = $new_miu WHERE account_ID = $thesex[account_ID]");
}

?>