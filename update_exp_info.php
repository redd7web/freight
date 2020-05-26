<?php
include 'protected/global.php';

switch($_POST['mode']){
    case "payment_method":
        $db->query("UPDATE sludge_grease_data_table SET $_POST[mode]='$_POST[value]' WHERE entry_number=$_POST[entry_number]");
    break;
    case "ppg":
        $db->query("UPDATE sludge_grease_data_table SET $_POST[mode]=$_POST[value] WHERE entry_number=$_POST[entry_number]");
    break;
    case "inches_to_gallons":
        $db->query("UPDATE sludge_grease_data_table SET $_POST[mode]=$_POST[value] WHERE entry_number=$_POST[entry_number]");
    break;
    case "volume":
        $db->query("UPDATE sludge_grease_data_table SET $_POST[mode]=$_POST[value] WHERE entry_number=$_POST[entry_number]");
    $break;
    case "invoice":
        $db->query("UPDATE sludge_grease_data_table SET $_POST[mode]=$_POST[value] WHERE entry_number=$_POST[entry_number]");
    $break;
    case "ppg":
        $db->query("UPDATE sludge_grease_data_table SET $_POST[mode]=$_POST[value] WHERE entry_number=$_POST[entry_number]");
    $break;
    case "paid":
        $db->query("UPDATE sludge_grease_data_table SET $_POST[mode]=$_POST[value] WHERE entry_number=$_POST[entry_number]");
    $break;
}

?>