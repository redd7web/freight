<?php
include "protected/global.php";
ini_set("display_errors",1);
$headers = 'From: ticket_reply@iwpusa.com' . "\r\n" .
    'Reply-To: no-reply@iwpusa.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

if( $_POST['division'] == "bak" ){
    $k = $db->query("SELECT  bakery.bakery_users.* FROM bakery.bakery_users WHERE user_id=$_POST[from]");
    $full = $k[0]['first']." ".$k[0]['last'];
} else {
    $person = new Person();
    $full = $person->fullname;    
}

if( $_POST['division'] == "bak" ){
    switch($_POST['to']){
        case 1:
        $group = 2;
        break;
        case 8:
        $group = 3;
        break;
    }
} else {
    

    switch($_POST['to']){
        case 16:case 44: case 101:case 42:
        $group = 2;
        break;
        case 99:
        $group = 3;
        break;
    }
}
$pack = array(
    "title"=>$_POST['subject'],
    "notes"=>$_POST['notes'],
    "date"=>date("Y-m-d H:i:s"),
    "division"=>$_POST['division'],
    "role"=>$group,
    "status"=>"pending",
    "priority"=>"",
    "to_user"=>$_POST['to'],
    "from_user"=>$_POST['from'],
    "modifier"=>$_POST['modifier']
);

echo "<pre>";
print_r($pack);
echo "</pre>";

$db->insert("notes_reply",$pack);

mail("redd7web@gmail.com,datastormdesigns@gmail.com,aparsons@iwpusa.com,gruff@iwpusa.com,aburkett@iwpusa.com","Reply Ticket $_POST[subject] Created $_POST[modifier]","Reply Ticket #$_POST[modifier] created by $full ",$headers);
?>