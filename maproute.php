<?php
include "protected/global.php";
$ikg_info = new IKG($_GET['ikg']);
$account_info = new Account();
$person = new Person();
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//echo "person: ". $person->facility;
$old[] = str_replace(" ","+",$facils[$person->facility]);

foreach($ikg_info->account_numbers as $numbs){
    
    $address ="";
    $address .= str_replace(" ","+", $account_info->singleField($numbs,"address") ).",+";
    $address = str_replace("/","",$address);
    $address .= str_replace(" ","+", $account_info->singleField($numbs,"city")  ).",+";
    
    $address .= $account_info->singleField($numbs,"state")."+";
    $address .= $account_info->singleField($numbs,"zip");
    //echo $address."<br/>";
    $old[] = $address;
    
}

$link2 ="https://www.google.com/maps/dir/".implode("/",$old)."/".$coords[$person->facility];


header("location:$link2");
?>