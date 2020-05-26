<?php
include "protected/global.php";
$person = new Person();

$data = Array(
    "route_id"=> $_POST['route_id'],
    "schedule_id"=>$_POST['schedule_number'],
    "inches_entered"=>$_POST['inches_entered'],
    "inches_to_gallons"=>$_POST['picked_up'],
    "expected_gallons"=>$_POST['gallons_expected'],
);

?>