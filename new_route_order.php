
<?php
include "protected/global.php";
ini_set("display_errors",1);
//echo "*************** $_POST[orders]********************";

$list = explode("\n",$_POST['orders']);

array_pop($list);
array_pop($list);


$list = array_slice($list,1);
print_r($list);
$ij = "";
$vis ="";
foreach($list as $addy){
    $first = explode("|",$addy);
    $add_city = explode(",",$first[1]);
    $second_parse = explode(" ",$add_city[0]);
    $bx = $db->query("SELECT Name FROM sludge_accounts WHERE account_ID=$first[0]");
    $ij .=$first[0]."|";
    $vis .= $bx[0]['Name'].", ";
    
    
    
    
}

echo "new number string : $ij<br/>";
echo "visual: $vis";

$db->query("UPDATE sludge_ikg_grease SET account_numbers='$ij' WHERE route_id = $_POST[route_id]");
?>
