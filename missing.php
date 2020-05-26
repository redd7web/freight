<?php

include "protected/global.php";
$account_table =  $dbprefix."_accounts";


function user_id($name){
    global $db;
    
    
   
        $first_last = explode(" ",$name);
        $k = $db->query("SELECT user_id FROM sludge_users WHERE first like '%$first_last[0]%' AND last like '%$first_last[1]%'");
        
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
          $new_name = htmlentities($data[1]);
          $new_name = explode("'",$new_name);
          $new_address1 = explode("'",$data[10]);
        $new_address2 = explode ("'",$data[16]);
        
        $check = $db->query("SELECT name FROM sludge_accounts WHERE name like '%$new_name[0]%' || address like '%$new_address1[0]%' || address like '%$new_address2[0]%' ");

      if(count($check)>0){  
        
        
        if(strlen($data[27])>0){
            $res = user_id($data[27]);
        } else {
            $res = 0;
        }
        
        if(strlen($data[26])){
            $orig = user_id($data[26]);
        } else {
            $orig = 0;
        }
        
        if(strlen($data[9])>0){
            $index = $data[9];
        } else {
            $index = 0;
        }
        
        if(strlen($data[3])>0){
            
            $created = date( 'Y-m-d', strtotime( $data[3] ) );
        } else {
            $created ="0000-00-00 00:00:00";
        }
        
         if(strlen($data[4])>0){
            

            $state = date( 'Y-m-d', strtotime( $data[4] ) );
        } else {
            $state ="0000-00-00 00:00:00";
        }
        
        if(strlen($data[5])>0){
            $expires = date( 'Y-m-d', strtotime( $data[5] ) );
            
        } else {
            $expires ="0000-00-00 00:00:00";
        }
        
        if(strlen($data[15])>0 || $data[15] != NULL || $data[15] !=""){
            $b_zip = $data[15];
        }else {
            $b_zip = 0;
        }
        
        if(strlen($data[7])>0 || $data[7] != NULL || $data[7] !="" ){
            $method = $data[7];
        }else {
            $method = "";
        }
        
        echo "Name: ".$data[1]." in system original rep: $orig | current: $res<br/><br/>";
        $bil_ad = htmlentities($data[10]);
        $bil_ad = str_replace("'","&#39;","$bil_ad");
        echo "UPDATE sludge_accounts set created = '$created',state_date= '$state',expires = '$expires',price_per_gallon= $data[6],payment_method = '$method', index_percentage = $index, billing_address = '$bil_ad',billing_city = '$data[13]', billing_state ='$data[14]', billing_zip=$b_zip, original_sales_person = $orig, re_assigned_sales_person = $res, account_rep = $res WHERE name like '%$new_name[0]%' || address like '%$new_address1[0]%' || address like '%$new_address2[0]%' ";
            
            
            $db->query("UPDATE sludge_accounts set created = '$created',state_date= '$state',expires = '$expires',price_per_gallon= $data[6],payment_method = '$method', index_percentage = $index, billing_address = '$bil_ad',billing_city = '$data[13]', billing_state ='$data[14]', billing_zip=$b_zip, original_sales_person = $orig, re_assigned_sales_person = $res, account_rep = $res WHERE name like '%$new_name[0]%' || address like '%$new_address1[0]%' || address like '%$new_address2[0]%' ");
        
        
      }  else {
        echo "Name: ".$data[1]." not in system<br/><br/>";
      } 
      
    }
}

echo "$i accounts not in system";
?>