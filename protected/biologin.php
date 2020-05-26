<?php
    
    date_default_timezone_set ( "America/Los_Angeles" );
    ini_set("display_errors",1);
    $error  = "Invalid username/password, please <a href='../home.php'>Click here to try again</a>.";
    if(isset($_POST['gtsub'])){
        include "global.php";
        $user= $_POST['gtuser'];
        $password = $_POST['gtpw'];
        
        $md5 = crypt($password,'$105Biotane');
        echo $md5."<br/>";
        $k = $db->where("login_name",trim($user))->where("password","$md5")->get($dbprefix."_users","user_id");        
        if(count($k)>0){ 
            $_SESSION['sludge_id']= $k[0]['user_id'];
            $_SESSION['sludge_page_counter'] = 0;
            $_SESSION['sludge_history'] = array();
            $data = array( 
                "last_login"=>date("Y-m-d H:i:s")
            );
            $db->where('user_id',$_SESSION['sludge_id'])->update($dbprefix."_users",$data);
            //$path = str_replace("protected/biologin.php","","$_SERVER[REQUEST_URI]");
            $track = array ( 
                "date" => date("Y-m-d H:i:s"),
                "user"=>$_SESSION['sludge_id'],
                "actionType"=>"Log In",
                "descript"=>"Log In",
                "type"=>10
            );
            $db->insert("xlogs.".$dbprefix."_activity",$track);
            header("Location:../");
        }
        else {
            //echo "User not Found $user $password<br/>";
            echo $error;
        }
    }else { 
        //echo "no post<br/>";
        echo $error;
    }


?>

