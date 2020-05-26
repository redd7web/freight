<?php
    include "protected/global.php";
    include "protected/xlsfunctions.php";	
    $ikg = new IKG($_GET['ikg']);
    $dte = date("Y-m-d");
 	header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");    
    header("Content-Disposition: attachment;filename=$ikg->ikg_manifest_route_number-$date.xls");
    header("Content-Transfer-Encoding: binary ");
    $xlsRow = 0;
	$xlsCol = 0;
    xlsBOF(); 	
    xlsWriteLabel($xlsRow,0,"Address:");
    xlsWriteLabel($xlsRow,2,"City:");
    xlsWriteLabel($xlsRow,4,"State:"); 
    xlsWriteLabel($xlsRow,6,"Zip:"); 
    xlsWriteLabel($xlsRow,8,"Company:");
    
    foreach($ikg->account_numbers as $nums){
        $acnt = new Account($nums);
        $xlsRow++;
        xlsWriteLabel($xlsRow,0,$acnt->address);
        xlsWriteLabel($xlsRow,2,$acnt->city);
        xlsWriteLabel($xlsRow,4,$acnt->state);
        xlsWriteLabel($xlsRow,6,$acnt->zip);
        xlsWriteLabel($xlsRow,8,$acnt->name_plain);
    }
    
    xlsEOF();

?>