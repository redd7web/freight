<?php
    include "protected/global.php";
    include "protected/xlsfunctions.php";	
    $date = date("Ymdhis");
    $acnt_info = new Account();
    $grease_r = new Grease_IKG($_GET['route_no']);
    
   	header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");    
    header("Content-Disposition: attachment;filename=grease_route-$_GET[route_no]-$date.xls");
    header("Content-Transfer-Encoding: binary ");
    $xlsRow = 0;
	$xlsCol = 0;
    xlsBOF(); 
    
     xlsWriteLabel($xlsRow,0,"Address:");
    xlsWriteLabel($xlsRow,2,"City:");
    xlsWriteLabel($xlsRow,4,"State:"); 
    xlsWriteLabel($xlsRow,6,"Zip:"); 
    xlsWriteLabel($xlsRow,8,"Company:");
    xlsWriteLabel($xlsRow,10,"Trap size:");
    $xlsRow++;
    $account = new Account();
    if(count($grease_r->scheduled_routes)>0){
        foreach($grease_r->scheduled_routes as $sched){
            $g_stop = new Grease_Stop($sched);
            xlsWriteLabel($xlsRow,0, $acnt_info->singleField($g_stop->account_number,"address")  );
            xlsWriteLabel($xlsRow,2, $acnt_info->singleField($g_stop->account_number,"city")  );
            xlsWriteLabel($xlsRow,4, $acnt_info->singleField($g_stop->account_number,"state")  );
            xlsWriteLabel($xlsRow,6, $acnt_info->singleField($g_stop->account_number,"zip")  );
            xlsWriteLabel($xlsRow,8, $acnt_info->singleField($g_stop->account_number,"name")  );
            xlsWriteLabel($xlsRow,10, $g_stop->volume  );
            $xlsRow++;
        }
        
    }
    xlsEOF();

?>