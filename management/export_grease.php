<?php
include "../protected/global.php";
ini_set("display_errors",1);
function plain($account_no){
    global $db;
    $ret = $db->query("SELECT name FROM freight_accounts WHERE account_ID = $account_no");
    return $ret[0]['name'];
}

function name_plain($user_id){
    global $db;
    $ret = $db->query("SELECT first,last FROM freight_users WHERE user_id = $user_id");
    return $ret[0]['first']." ".$ret[0]['last'];
}

ini_set("display_errors",0);
//echo "query: $_POST[param]<br/>";
$picknpay = $db->query($_POST['param']);

if(isset($_POST['export_now'])){
    switch($_POST['format']){
        case "csv":
          
            /**/
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Description: File Transfer");
            header("Content-type: text/csv");
            $fileName = "grease_trap_export".date("Ymdhis").".csv";
            header("Content-Disposition: attachment; filename={$fileName}");
            header("Expires: 0");
            header("Content-Transfer-Encoding: binary");
            header("Pragma: public");
          
            if(count($picknpay)>0){    
                $dataString="";
                foreach($picknpay as $pick){
                    if(isset($_POST['search_now'])){
                         switch($_POST['my_group']){
                            case "account_rep":
                                $dataString .= uNumToName($pick['account_rep']).",";
                            break;
                            case "division":
                                $dataString .=  numberToFacility($pick['division']).",";
                            break;
                            case "original_sales_person":
                                $dataString .=  uNumToName($pick['original_sales_person']).",";
                            break;
                         }
                    }
                
                
                
                    $dataString .=  "$pick[account_no]";
                  
                    $dataString .=  ",$pick[schedule_id]";
                    $dataString .=  ",$pick[route_id]";
                    $dataString .=  ",".plain($pick['account_no']);           
                    $dataString .=  ",$pick[address]";
                    $dataString .=  ",$pick[city]";
                    $dataString .=  ",$pick[state]";
                    $dataString .=  ",".number_format($pick['ppg'],2);
                    $dataString .=  ",$pick[inches_to_gallons]";
                    $dataString .=  ",$pick[paid]";
                    $dataString .=  ",$pick[volume]";
                        
                    if(isset($_POST['my_group'])){
                        switch($_POST['my_group']){
                            case "account_ID": case "division": case "account_rep":case "original_sales_person":
                                $dataString .=  ",$pick[num]";
                            break;
                        }
                    }
                        
                    
                     
                    if(isset($_POST['my_group'])){
                        switch($_POST['my_group']){
                             case "account_ID": case "division": case "account_rep":case "original_sales_person":
                                $dataString .=  ",".number_format($pick['avg'],2);
                             break;
                        }
                    }else{
                        $ui = $db->query("SELECT AVG(inches_to_gallons) as avx FROM freight_grease_data_table WHERE account_no = $pick[account_no]");
                        if(count($ui)>0){
                            $dataString .=  ",".number_format($ui[0]['avx'],2);
                        } else {
                            $dataString .=  ",0";
                        }
                    }
                    
                    
                    $dataString .=  ",$pick[service_date]";
                    $dataString .=  ",".numberToFacility("$pick[division]");
                    $dataString .=  ",$pick[completed_date]";
                    $dataString .=  ",$pick[payment_method]";
                    $dataString .=  ",$pick[cc_on_file]"; 
                    $dataString .=  ",$pick[invoice]";
                    $dataString .=  ",$pick[new_bos]\r\n"; 
                }
                //echo "output: $dataString";
                
                $fh = @fopen( "php://output", 'w' );
                fwrite($fh, $dataString);
                fclose($fh);
                /**/
            }
                
        break;
        case "xls":
            $file = "grease_trap_export".date("YmdHm").".xls";
            include "../protected/xlsfunctions.php";
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
            switch($_POST['my_group']){
                case "account_rep":
                    xlsWriteLabel($xlsRow,0,"Account Rep:");
                break;
                case "division":
                    xlsWriteLabel($xlsRow,0,"Facility:");
                break;
                case "original_sales_person":
                    xlsWriteLabel($xlsRow,0,"Original Sales Person:");
                break;
                default:
                    xlsWriteLabel($xlsRow,0," ");
                break;
             }
             xlsWriteLabel($xlsRow,2,"Act ID");
             xlsWriteLabel($xlsRow,4,"Schedule ID");
             xlsWriteLabel($xlsRow,6,"Route ID");
             xlsWriteLabel($xlsRow,8,"Acct Name");
             xlsWriteLabel($xlsRow,10,"Address");             
             xlsWriteLabel($xlsRow,12,"City");
             xlsWriteLabel($xlsRow,14,"State");
             xlsWriteLabel($xlsRow,16,"PPG");
             xlsWriteLabel($xlsRow,18,"Gallons");
             xlsWriteLabel($xlsRow,20,"Paid");
             xlsWriteLabel($xlsRow,22,"Size");
             switch($_POST['my_group']){
                case "account_ID": case "division": case "account_rep":case "original_sales_person":
                    xlsWriteLabel($xlsRow,24,"Pickups");
                    break;
                default:
                    xlsWriteLabel($xlsRow,24," ");
                    break;
             } 
           xlsWriteLabel($xlsRow,26,"Service Date");
           xlsWriteLabel($xlsRow,28,"Facility");           
           xlsWriteLabel($xlsRow,30,"Completed Date");
           xlsWriteLabel($xlsRow,32,"Payment Type");
           xlsWriteLabel($xlsRow,34,"CC on File");
           xlsWriteLabel($xlsRow,36,"Invoice"); 
            xlsWriteLabel($xlsRow,38,"New Bos #"); 
           
             if(count($picknpay)>0){
                foreach($picknpay as $pickups) {
                    $count++;
                    $xlsRow++;
                     switch($_POST['my_group']){// grouped ?
                        case "account_rep":
                             xlsWriteLabel($xlsRow,0,$pickups['account_rep']);
                        break;
                        case "division":
                            xlsWriteLabel($xlsRow,0,numberToFacility($pickups['division']));
                        break;
                        case "original_sales_person":
                            xlsWriteLabel($xlsRow,0,name_plain($pickups['original_sales_person']));                            
                        break;
                        default:
                            xlsWriteLabel($xlsRow,0," ");
                        break;
                     } 
                     xlsWriteLabel($xlsRow,2,"$pickups[account_ID]");
                     xlsWriteLabel($xlsRow,4,"$pickups[schedule_id]");
                     xlsWriteLabel($xlsRow,6,"$pickups[route_id]");
                     xlsWriteLabel($xlsRow,8,plain($pickups['account_no']));
                     xlsWriteLabel($xlsRow,10,"$pickups[address]");
                     xlsWriteLabel($xlsRow,12,"$pickups[city]");
                     xlsWriteLabel($xlsRow,14,"$pickups[state]");
                     xlsWriteLabel($xlsRow,16,"$pickups[ppg]");
                     xlsWriteLabel($xlsRow,18,round("$pickups[inches_to_gallons]",2) );
                     xlsWriteLabel($xlsRow,20,number_format("$pickups[paid]",2));
                     xlsWriteLabel($xlsRow,22,"$pickups[volume]");
                     switch($_POST['my_group']){
                        case "account_ID": case "division": case "account_rep":case "original_sales_person":
                            xlsWriteLabel($xlsRow,24,"$pickups[num]");
                        break;
                        default:
                            xlsWriteLabel($xlsRow,24,"");
                        break;
                     }
                    
                    
                     
                    
                    xlsWriteLabel($xlsRow,26,"$pickups[service_date]" );
                    xlsWriteLabel($xlsRow,28,numberToFacility("$pickups[division]") );
                    xlsWriteLabel($xlsRow,30,"$pickups[completed_date]");
                    xlsWriteLabel($xlsRow,32,"$pickups[payment_method]");
                    xlsWriteLabel($xlsRow,34,"$pickups[cc_on_file]");
                    xlsWriteLabel($xlsRow,36,"$pickups[invoice]");
                    xlsWriteLabel($xlsRow,38,"$pickups[new_bos]");
                    
                }
             }else {
                $xlsRow++; 
                xlsWriteLabel($xlsRow,0,"Empty");
             }   
            xlsEOF();
            break;
    }    
}

?>