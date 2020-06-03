<?php
include "protected/global.php";
error_reporting(1);
$account_table = $dbprefix."_accounts";    

if(isset($_POST['export'])){
    $format = "SELECT account_ID,status,payment_method,name,city,state,created,expires,locations,address,division,new_bos FROM freight_accounts WHERE 1".$_POST['criteria'];
    $result = $db->query($format);
    if(count($result)>0){    
        switch($_POST['format']){
            case "csv":
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Description: File Transfer");
                header("Content-type: text/csv");
                $fileName = "account_export".date("Ymdhis").".csv";
                header("Content-Disposition: attachment; filename={$fileName}");
                header("Expires: 0");
                header("Content-Transfer-Encoding: binary");
                header("Pragma: public");
                if(count($result)>0){
                  $start =1;        
                  foreach($result as $account){
                    $start++;
                     $dataString .= "$start, $account[account_ID],$account[status], $account[payment_method],$account[name],$account[city],$account[state],$account[created],$account[expires],$account[locations],$account[address],".numberToFacility($account['division']).",$account[new_bos]\r\n";
                    
                  }
                }
                $fh = @fopen( "php://output", 'w' );
                fwrite($fh, $dataString);
                fclose($fh);
            break;
            case "xls":
                include "protected/xlsfunctions.php";
                 $file = "account_exports".date("YmdHm").".xls";
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");           
                header("Content-Disposition: attachment;filename=$file");
                header("Content-Transfer-Encoding: binary ");
                $count =0; 
                $xlsRow = 0;
                $xlsCol = 0;
                xlsBOF();
                xlsWriteLabel($xlsRow,0,"#:");
                xlsWriteLabel($xlsRow,2,"ID:");
                xlsWriteLabel($xlsRow,4,"status:"); 
                xlsWriteLabel($xlsRow,6,"Payment Type");
                xlsWriteLabel($xlsRow,8,"Name");
                xlsWriteLabel($xlsRow,10,"City:");
                xlsWriteLabel($xlsRow,12,"State:");
                xlsWriteLabel($xlsRow,14,"Created");  
                xlsWriteLabel($xlsRow,16,"Expires");
                xlsWriteLabel($xlsRow,18,"Locations");
                xlsWriteLabel($xlsRow,20,"Address:");
                xlsWriteLabel($xlsRow,22,"Division:");
                xlsWriteLabel($xlsRow,24,"New Bos #:");
                foreach($result as $account){
                    $count++;
                    $xlsRow++;
                    xlsWriteLabel($xlsRow,0,$count);
                    xlsWriteLabel($xlsRow,2,$account['account_ID']);
                    xlsWriteLabel($xlsRow,4,$account['status']);
                    xlsWriteLabel($xlsRow,6,$account['payment_method']);
                    xlsWriteLabel($xlsRow,8,$account['name']);                    
                    xlsWriteLabel($xlsRow,10,$account['city']);
                    xlsWriteLabel($xlsRow,12,$account['state']);
                    xlsWriteLabel($xlsRow,14,$account['created']);
                    xlsWriteLabel($xlsRow,16,$account['expires']);
                    xlsWriteLabel($xlsRow,18,$account['last']); 
                    xlsWriteLabel($xlsRow,20,$account['address']);
                    xlsWriteLabel($xlsRow,22,numberToFacility($account['division']) );
                    xlsWriteLabel($xlsRow,24,$account['new_bos']);
                }                
                xlsEOF();
        }
    }
}

?>