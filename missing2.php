<?php

include "protected/global.php";
$account_table =  $dbprefix."_accounts";


function user_id($name){
    global $db;
    
    
   
        $first_last = explode(" ",$name);
        $k = $db->query("SELECT user_id FROM freight_users WHERE first like '%$first_last[0]%' AND last like '%$first_last[1]%'");
        
        if(count($k)>0){
            return $k[0]['user_id'];
        } else {
            return 0;
        }
    
}

$i = 0;
$file = "biotane20141204/accounts.txt";// Your Temp Uploaded file
if(($handle = fopen($file,"r"))!==FALSE){
    /*Skip the first row*/
    fgetcsv($handle, 0,chr(9));
    while(($data = fgetcsv($handle,0,chr(9)))!==FALSE){
        
       var_dump($data);
    }
}

echo "$i accounts not in system";
?>