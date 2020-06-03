<?php
    include "protected/global.php";
    $i=0; //so we can skip first row
    ini_set("display_errors",1);
    
    if( file_exists ("/home/iwp2/Desktop/INETcredit.csv")  ){
        $handle = fopen("/home/iwp2/Desktop/INETcredit.csv", "r");
        $check="";
        $ignore = array(
            "new_bos",
            "TEMP",
            "EMP",
            "DEAD FILE",
            "ADMIN",
            "PENDING",
            "S8492",
            "70012*",
            "DEAD",
            "STMT",
            "ÐÏà¡±á>þÿ	Åþÿÿÿbãdåfÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿýÿÿÿþÿÿÿ"
        );
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          
          if( !in_array($data[9],$ignore)){
              if(count($data) == 20){
                if($data[14]=="False"){
                    $lock = 0;
                } else {
                    $lock = 1;
                }
                
                if($data[15] == "False"){
                    $ccof = 0;
                } else {
                    $ccof = 1;
                }
                $package = array(
                   "credit_terms"=>$data[12],
                   "credits"=>$data[13],
                   "locked"=>$lock,
                   "cc_on_file"=>$ccof,
                   "credit_email"=>$data[16],
                   "credit_note"=>$data[17]
                   
                ) ; 
             }else {
                if($data[13]=="False"){
                    $lock = 0;
                } else {
                    $lock = 1;
                }
                
                if($data[14] == "False"){
                    $ccof = 0;
                } else {
                    $ccof = 1;
                }
                $package = array(
                   
                   "credit_terms"=>$data[11],
                   "credits"=>$data[12],
                   "locked"=>$lock,
                   "cc_on_file"=>$ccof,
                   "credit_email"=>$data[15],
                   "credit_note"=>$data[16]
                   
                ) ; 
             }
              echo "<pre>";
             print_r($package);
             echo "</pre>";    
             $db->where("new_bos",$data[9])->update("freight_accounts",$package);  
          }
          
        }
        
    }else{
        echo "Cannot find file.";
    }
    
    
?>
