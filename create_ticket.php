<?php
include "protected/global.php";
$headers = 'From: ticket_creation@iwpusa.com' . "\r\n" .
    'Reply-To: no-reply@iwpusa.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

//1 for it 2 for admin 3 for tech

$person = new Person();

$k = $person->fullname;

$packet = array(
    "title"=>$_POST['title'],
    "notes"=>trim($_POST['notes']),
    "date"=>date("Y-m-d H:i:s"),
    "division"=>$_POST['division'],
    "role"=>1,
    "status"=>"pending",
    "to_user"=>$_POST['to'],
    "from_user"=>$person->user_id,
    "priority"=>$_POST['priority']
);

echo "<pre>";
print_r($packet);
echo "</pre>";

$db->insert("notes",$packet);
$o =$db->getInsertId();
$mod = $o.date("mdyHis");

$db->query("UPDATE notes SET modifier = '$mod' WHERE id = $o");

mail("redd7web@gmail.com,datastormdesigns@gmail.com,aparsons@iwpusa.com,gruff@iwpusa.com,aburkett@iwpusa.com,$person->email","Ticket $_POST[title] Created","Ticket priority $_POST[priority] \r\n created by $k \r\n ticket #$mod ",$headers);
?>