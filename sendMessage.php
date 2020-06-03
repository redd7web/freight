<?php

include "protected/global.php";
$person = new Person();
$type= "";
switch($_POST['type']){
    case 1:
        $dc = date("Y-m-d")." ".date("H:i:s");
        $data = Array(
            "issue"=>"$_POST[issue]",
            "account_no"=>$_POST['acnt'],
            "date_created"=>$dc,
            "message"=>"$_POST[mesg]",
            "assigned_to"=>$_POST['user_id'],
            "reported_by"=> $person->user_id,
            "issue_status"=>"active",
            "priority_level"=>$_POST['priority']
        );
        var_dump($data);
        
        $db->insert($dbprefix.'_issues',$data);
        
        $iq = $db->where('message',$_POST['mesg'])->get($dbprefix.'_issues','issue_no');
        
        $issue_note = Array( // initial message in thread
            "issue_no"=>$iq[0]['issue_no'],
            "message"=>$_POST['mesg'],
            "message_date"=>$dc,
            "created_by"=>$person->user_id
        );
        $db->insert($dbprefix.'_issue_notes',$issue_note);
        $type = "Issue";
        $track = array(
            "date"=>date("Y-m-d H:i:s"),
            "user"=>$person->user_id,
            "actionType"=>"Message Created",
            "descript"=>$_POST['mesg'],
            "account"=>$_POST['account_no'],
            "type"=>13
        );
        $db->insert($dbprefix."_activity",$track);
        
        break;
    case 2: 
        $type = "Message";
        break;
    case 3:
        echo "dfsfds";
        $dd =date("Y-m-d");
        
        $data2 = Array(
            "to"=>$_POST['user_id'],
            "by"=>$person->user_id,
            "message"=>$_POST['mesg'],
            "date"=>$dd,
            "status"=>'active'
        );
        echo "<pre>";
        print_r($data2); 
        echo "</pre>";
        
        
        $db->query("INSERT INTO freight_private VALUES (0,$_POST[user_id],$person->user_id,'$_POST[mesg]','$dd','active')");
        $track = array(
            "date"=>date("Y-m-d H:i:s"),
            "user"=>$person->user_id,
            "actionType"=>"Message Created",
            "descript"=>$_POST['mesg'],
            "account"=>$_POST['account_no'],
            "pertains"=>3,
            "type"=>13
        );
        $db->insert($dbprefix."_activity",$track);

        

                
        break;
}

echo "$type Created!";




?>