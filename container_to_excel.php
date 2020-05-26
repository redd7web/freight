<?php
    include "protected/global.php";
    include "protected/xlsfunctions.php";	
    $date = date("Ymdhis");
    $acnt_info = new Account();
    $container_r = $db->where('rout_no',$_GET['route_no'])->get($dbprefix."_utility");
    
   	header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");    
    header("Content-Disposition: attachment;filename=Container_route-$_GET[route_no]-$date.xls");
    header("Content-Transfer-Encoding: binary ");
    $xlsRow = 0;
	$xlsCol = 0;
    xlsBOF(); 
    xlsWriteLabel($xlsRow,0,"Address:");
    xlsWriteLabel($xlsRow,2,"City:");
    xlsWriteLabel($xlsRow,4,"State:"); 
    xlsWriteLabel($xlsRow,6,"Zip:"); 
    xlsWriteLabel($xlsRow,8,"Company:");
    xlsWriteLabel($xlsRow,10,"Tote Size:");
    
     
    if( count($container_r)>0  ) { 
        foreach( $container_r as $sched ){
            $xlsRow++;
            xlsWriteLabel($xlsRow,0, $acnt_info->singleField($sched['account_no'],"address")  );
            xlsWriteLabel($xlsRow,2, $acnt_info->singleField($sched['account_no'],"city")  );
            xlsWriteLabel($xlsRow,4, $acnt_info->singleField($sched['account_no'],"state")  );
            xlsWriteLabel($xlsRow,6, $acnt_info->singleField($sched['account_no'],"zip")  );
            xlsWriteLabel($xlsRow,8, $acnt_info->singleField($sched['account_no'],"name")  );
            xlsWriteLabel($xlsRow,10,containerNumToName($sched['container_label']));
        }
    }
    
    xlsEOF();


?>