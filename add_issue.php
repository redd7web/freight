<?php
    
include "protected/global.php";




$file = "biotane20141204/issues.txt";// Your Temp Uploaded file
if(($handle = fopen($file,"r"))!==FALSE){
    /*Skip the first row*/
    fgetcsv($handle, 0,chr(9));
    while(($data = fgetcsv($handle,0,chr(9)))!==FALSE){
        
      $reported_by = 0;  
      $assigned = 0;   
      $completed = 0; 
      if(strlen($data[9])> 0 && $data[9] !=NULL){
          $last_first = explode(" ",$data[9]);
          $spec1 = $db->query("SELECT user_id FROM sludge_users WHERE last LIKE '%".$last_first[1]."%'");
          if(count($spec1)>0){
            $reported_by = $spec1[0]['user_id'];
          }  
      }
      
      
      if(strlen($data[10])> 0 && $data[10] !=NULL){
          $last_firstx = explode(" ",$data[10]);
          $spec12 = $db->query("SELECT user_id FROM sludge_users WHERE last LIKE '%".$last_firstx[1]."%'");
          if(count($spec12)>0){
            $assigned = $spec12[0]['user_id'];
          }  
      }
      
      if(strlen($data[11])> 0 && $data[11] !=NULL){
          $last_firsty = explode(" ",$data[11]);
          $spec13 = $db->query("SELECT user_id FROM sludge_users WHERE last LIKE '%".$last_firsty[1]."%'");
          if(count($spec13)>0){
            $completed = $spec13[0]['user_id'];
          }  
      }
        
        
        $packk = array (
            "issue_no"=>$data[0],
            "issue"=>$data[2],
            "account_no"=>$data[1],
            "date_created"=>$data[3],
            "category"=>$data[6],
            "message"=>$data[4],
            "issue_status"=>$data[5],
            "reported_by"=>$reported_by,
            "assigned_to"=>$assigned,
            "following"=>$data[12],
            "completed_by"=>$completed,
        );
        //var_dump($packk);
        $db->insert("sludge_issues",$packk);
    }
}



?>