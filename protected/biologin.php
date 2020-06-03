<?php
    include "global.php";
    $error  = "Invalid username/password, please <a href='../home.php'>Click here to try again</a>.";
    
    if(isset($_POST['gtsub'])){
       
        
        $md5 = crypt($_POST['freightpw'],'$105Biotane');
        //echo "SELECT user_id FROM freight_users WHERE password ='$md5' AND login_name='$_POST[freightuser]'<br/>";
        //echo $md5."<br/>";
        $k = $db->query("SELECT user_id FROM freight_users WHERE password ='$md5' AND login_name='$_POST[freightuser]'"); 
        
        //$db->where("login_name",trim($_POST['freightuser']))->where("password",$md5)->get($dbprefix."_users","user_id");        
        //print_r($k);
        if(count($k)>0){ 
            $_SESSION['freight_id']= $k[0]['user_id'];
            $_SESSION['freight_page_counter'] = 0;
            $data = array( 
                "last_login"=>date("Y-m-d H:i:s")
            );
            //$db->where('user_id',$_SESSION['freight_id'])->update($dbprefix."_users",$data);
            //$path = str_replace("protected/biologin.php","","$_SERVER[REQUEST_URI]");
            $track = array ( 
                "date" => date("Y-m-d H:i:s"),
                "user"=>$_SESSION['freight_id'],
                "actionType"=>"Log In",
                "descript"=>"Log In",
                "type"=>10
            );
            //$db->insert("xlogs.".$dbprefix."_activity",$track);
            header("Location:../");
        }
        else {
            echo "User not Found $_POST[SSfreightuser] $password<br/>";
            echo $error;
        }
    }else { 
        //echo "no post<br/>";
        echo $error;
    }


?>

