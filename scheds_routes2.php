<?php
include "protected/global.php";

$file = "biotane20141204/pickups2.txt";// Your Temp Uploaded file
if(($handle = fopen($file,"r"))!==FALSE){
    /*Skip the first row*/
    fgetcsv($handle, 0,chr(9));
    while(($data = fgetcsv($handle,0,chr(9)))!==FALSE){
      //DATA PREPARATION
      $rep_id = 0;
      if(strlen($data[11])> 0 && $data[11] !=NULL){
          $last_first = explode(" ",$data[11]);
          $spec1 = $db->query("SELECT user_id FROM freight_users WHERE last LIKE '%".$last_first[1]."%'");
          if(count($spec1)>0){
            $rep_id = $spec1[0]['user_id'];
          }  
      }
        
        
      $num = 0;
      if(strlen($data[10]) >0 && $data[10] !=NULL){
          $first_last = explode(" ",$data[10]);
          $query = "SELECT user_id FROM freight_users WHERE last LIKE '%".$first_last[1]."%";
          //echo $query;
          $spec = $db->query("SELECT user_id FROM freight_users WHERE last LIKE '%".$first_last[1]."%'");
          if(count($spec)>0){
            $num = $spec[0]['user_id'];
          }
          
      }
      
      if(strlen($data[4])>0){
        $fire = 1;
      } else {
        $fire = 0;
      }
      $facnum = 0;
      $address="";
      if(strlen($data[12]) && $data[12] !=0){
         $facnum = reverseTranslate($data[12]);
         $address = $facils[reverseTranslate($data[12])];
      }
       
      if(strlen($data[3])>0 && $data[3] !=''){
        $title = htmlspecialchars($data[3]);
      } else {
        $title ="Unknown";
      }
      //***********************************************
      
       $kj = $db->where("ikg_manifest_route_number",$title)->get("freight_ikg_manifest_info");
       
       
       if(count($kj)>0){
            $ikg_append = array(
                "account_numbers"=>$kj[0]['account_numbers'].$data[1]."|"
            );
           $db->where("route_id",$kj[0]['route_id'])->update("freight_ikg_manifest_info",$ikg_append);
           $db->query("UPDATE freight_list_of_routes set stops = stops+1 WHERE route_id = ".$kj[0]['route_id']);// update the amount of stops
       } else {
            //create route manifest
            if(strlen($data[2])>0 && $data[2] != NULL){
                $ikg = array(
                    "ikg_manifest_route_number"=>$title,
                    "route_id"=>$data[2],
                    "recieving_facility"=>$facnum,
                    "facility_address"=>$address,
                    "driver"=>$num,
                    "collected"=>"Yellow Grease",
                    "account_numbers"=>$data[1]
               );
               $db->insert("freight_ikg_manifest_info",$ikg);
               $rid = $data[2]; 
           } else {            
                //auto generate id if not listed
                $ikg = array(
                    "ikg_manifest_route_number"=>$title,
                    "recieving_facility"=>$facnum,
                    "facility_address"=>$address,
                    "driver"=>$num,
                    "collected"=>"Yellow Grease",
                    "account_numbers"=>$data[1]
                );
               $db->insert("freight_ikg_manifest_info",$ikg);
               $rid = getInsertId();
           }
           //************************
           
           
           $list_of_routes = array(
                "ikg_manifest_route_number"=>$title,
                "facility"=>$facnum,
                "driver"=>$num,
                "route_id"=>$rid,
                "created_by"=>$rep_id,
                "stops"=>1,
                "status"=>"completed"
           );
           
           $db->insert("freight_list_of_routes",$list_of_routes);
           
       }
       
       //create schedule && data table entry
       $sched_package = array(
            "schedule_id"=>$data[0],
            "route_id"=>$rid,
            "scheduled_start_date"=>$data[6],
            "facility_origin"=>$facnum,
            "created_by"=>$rep_id,
            "driver"=>$num,
            "account_no"=>$data[1],
            "code_red"=>$fire,
            "date_created"=>$data[6],
            "route_status"=>"scheduled"
       );       
       $db->insert("freight_scheduled_routes",$sched_package);
       //*****************************************
       
       $data_table = array(
            "route_id"=>$data[2],
            "schedule_id"=>$data[0],
            "inches_to_gallons"=>$data[7],
            "account_no"=>$data[1],
            "driver"=>$num,
            "fieldreport"=>$data[8]
       );
       $db->insert("freight_data_table",$data_table);
    }
}




?>