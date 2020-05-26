<?php
include "protected/global.php";

$file = "biotane20141204/locations.txt";// Your Temp Uploaded file
if(($handle = fopen($file,"r"))!==FALSE){
    /*Skip the first row*/
    fgetcsv($handle, 0,chr(9));
    while(($data = fgetcsv($handle,0,chr(9)))!==FALSE){
        
        
            echo "location: ".account_NumtoName($data[0])." current division:".reverseTranslate($data[12])." - $data[12] <br/>";
            $package = array(
                "division"=>reverseTranslate($data[12])
            );
            
            $db->where("account_ID",$data[0])->update("sludge_accounts",$package);
        
        
    }
    
    
}


?>