<?php
 include "protected/global.php";
 include "protected/xlsfunctions.php";
 $account_table = $dbprefix."_accounts";
$data_table = $dbprefix."_data_table";
$count = 0;   
$running_total =0 ;
$running_adjust = 0;


$result = $db->query( "SELECT $account_table.name, $account_table.account_ID,$account_table.city,$account_table.state,$account_table.division,$data_table.inches_to_gallons,$data_table.entry_number,$data_table.date_of_pickup,$data_table.inches_to_gallons *.25 as adj FROM $data_table INNER JOIN $account_table ON $account_table.account_ID=$data_table.account_no WHERE 1 " . $_POST['params'] . " order by date_of_pickup" );
  
  
    switch($_POST['format']){
        case "csv":     
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Description: File Transfer");
            header("Content-type: text/csv");
            $fileName = "alloils".date("YmdHm").".csv";
            header("Content-Disposition: attachment; filename={$fileName}");
            header("Expires: 0");
            header("Content-Transfer-Encoding: binary");
            header("Pragma: public");
            
            if(count($result)>0){
                $count = 0;
                foreach($result as $pickups){
                    $count++;
                    $dataString .= "$count,$pickups[account_ID],$pickups[name],$pickups[date_of_pickup],$pickups[inches_to_gallons],".round($pickups['total_gallons'] - $pickups['adj'],2).",$pickups[city],$pickups[state]\r\n";    
                }    
            }
            $fh = @fopen( "php://output", 'w' );
            fwrite($fh, $dataString);
            
            fclose($fh);
             
            break;
        case "excel":     
                $file = "alloils".date("YmdHm").".xls";
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");           
                header("Content-Disposition: attachment;filename=$file");
                header("Content-Transfer-Encoding: binary "); 
                $xlsRow = 0;
                $xlsCol = 0;
                xlsBOF(); 	
                xlsWriteLabel($xlsRow,0,"#:");
                xlsWriteLabel($xlsRow,2,"ID:");
                xlsWriteLabel($xlsRow,4,"Account Name:"); 
                xlsWriteLabel($xlsRow,6,"Date:"); 
                xlsWriteLabel($xlsRow,8,"Raw:");    
                xlsWriteLabel($xlsRow,10,"Adj:");
                xlsWriteLabel($xlsRow,12,"City:");
                xlsWriteLabel($xlsRow,14,"State:");                
                //var_dump($result);
                if(count($result)>0){
                    foreach($result as $pickups) { 
                        $count++;
                        $xlsRow++;
                        xlsWriteLabel($xlsRow,0,$count);
                        xlsWriteLabel($xlsRow,2,$pickups['entry_number']);
                        xlsWriteLabel($xlsRow,4,$pickups['name']);
                        xlsWriteLabel($xlsRow,6,$pickups['date_of_pickup']);
                        xlsWriteLabel($xlsRow,8,$pickups['inches_to_gallons']);
                        xlsWriteLabel($xlsRow,10,round($pickups['total_gallons'] - $pickups['adj'],2));
                        xlsWriteLabel($xlsRow,12,$pickups['city']); 
                        xlsWriteLabel($xlsRow,14,$pickups['state']);
                    }
                    //xlsWriteLabel($xlsRow+1,8,$running_total,$running_adjust);
                }                 
                xlsEOF();
        
            break;
    }
    
?>