<?php
include "protected/global.php";
$picknpay = $db->query($_POST['param']);
function get_index(){
    global $db;
    $xo =  $db->query("SELECT date,percentage FROM sludge_jacobsen ORDER BY DATE DESC LIMIT 0,1 ");
    
    if(count($xo)>0){
        return $xo;
    } else {
        return 0;
    }
}
function pickups($account_no){
    global $db;
    global $dbprefix;
    $infox = "0000-00-00|0000-00-00";
    $data_table = $dbprefix."_data_table";
    
    $info = $db->query($_POST['param']);
    if(count($info)>0){
        //latest|first
        $infox = $info[0]['date_of_pickup']."|".$info[count($info)-1]['date_of_pickup'];
    }
    
    return $infox;
}

$ko =get_index();


if(isset($_POST['export'])){
    switch($_POST['format']){
        case "csv":
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Description: File Transfer");
            header("Content-type: text/csv");
            $fileName = "picknpay".date("Ymdhis").".csv";
            header("Content-Disposition: attachment; filename={$fileName}");
            header("Expires: 0");
            header("Content-Transfer-Encoding: binary");
            header("Pragma: public");
            if(count($picknpay)>0){    
                foreach($picknpay as $pick){
                    $dates  = explode("|", pickups($pick['account_ID']));
                    $dataString .="$pick[account_ID],$pick[status],".account_NumtoName($pick['account_ID']).",$pick[contact_name],$pick[ppg],".number_format($pick['gals'],2).",".number_format($pick['temp_miu']*100).",".number_format(   ( $pick['gals'] - ( $pick['gals'] * $pick['temp_miu'] ) )  ,2).",$pick[paid]";//Paid
                    if(isset($_POST['group'])){
                        if($_POST['group'] !="-"){
                            $dataString .= ",$dates[0]";
                            $dataString .= ",$dates[1]";
                        }else{
                            $dataString .= ",$pick[date_of_pickup]";//first pickup
                        }
                    }else {
                        $dataString .= ",$pick[date_of_pickup]";//first pickup
                    }
                    $dataString .= ",$pick[address],$pick[city],$pick[state],$pick[payment_method],$pick[rate],".numberToFacility($pick['division']).",".number_format($pick[''],2).",".$ko[0]['date'];  
                    
                    if(isset($_POST['group'])){
                        if($_POST['group'] !="-"){
                            $dataString .= ",".$pick['num_pickups'];
                        }
                    }
                   $dataString .= "\r\n";
                    
                    
                }
            }
            $fh = @fopen( "php://output", 'w' );
            fwrite($fh, $dataString);
            fclose($fh);
            break;
        case "excel":
            $file = "oilperloc".date("YmdHm").".xls";
            include "protected/xlsfunctions.php";
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
            xlsWriteLabel($xlsRow,0,"ID:");
            xlsWriteLabel($xlsRow,2,"Status:");
            xlsWriteLabel($xlsRow,4,"Payor:"); 
            xlsWriteLabel($xlsRow,6,"Name");
            xlsWriteLabel($xlsRow,8,"Payable");
            xlsWriteLabel($xlsRow,10,"PPG:");
            xlsWriteLabel($xlsRow,12,"Raw Gallons:");
            xlsWriteLabel($xlsRow,14,"MIU:");
            xlsWriteLabel($xlsRow,16,"Adj Gallons:"); 
            xlsWriteLabel($xlsRow,18,"Paid");
            if(isset($_POST['group'])){
                if($_POST['group'] !="-"){
                    xlsWriteLabel($xlsRow,20,"First Pickup");
                    xlsWriteLabel($xlsRow,22,"Last Pickup:");                    
                    xlsWriteLabel($xlsRow,24,"Payment Address:");                    
                    xlsWriteLabel($xlsRow,26,"Payment City:");
                    xlsWriteLabel($xlsRow,28,"Payment State:");
                    xlsWriteLabel($xlsRow,30,"Payment Type:");
                    xlsWriteLabel($xlsRow,32,"Rate:");
                    xlsWriteLabel($xlsRow,34,"Facility:");
                    xlsWriteLabel($xlsRow,36,"Index at Pickup:");
                    xlsWriteLabel($xlsRow,38,"Index Date:");
                    xlsWriteLabel($xlsRow,40,"Number of pickups:");
                }else {
                    xlsWriteLabel($xlsRow,20,"Pickup Date");     
                    xlsWriteLabel($xlsRow,22,"Payment Address:");                    
                    xlsWriteLabel($xlsRow,24,"Payment City:");
                    xlsWriteLabel($xlsRow,26,"Payment State:");
                    xlsWriteLabel($xlsRow,28,"Payment Type:");
                    xlsWriteLabel($xlsRow,30,"Rate:");
                    xlsWriteLabel($xlsRow,32,"Facility:");
                    xlsWriteLabel($xlsRow,34,"Index at Pickup:");
                    xlsWriteLabel($xlsRow,36,"Index Date:");               
                }
            }else {
                xlsWriteLabel($xlsRow,20,"Pickup Date"); 
                xlsWriteLabel($xlsRow,22,"Payment Address:");                    
                xlsWriteLabel($xlsRow,24,"Payment City:");
                xlsWriteLabel($xlsRow,26,"Payment State:");
                xlsWriteLabel($xlsRow,28,"Payment Type:");
                xlsWriteLabel($xlsRow,30,"Rate:");
                xlsWriteLabel($xlsRow,32,"Facility:");
                xlsWriteLabel($xlsRow,34,"Index at Pickup:");
                xlsWriteLabel($xlsRow,36,"Index Date:");           
            }
            
            
            
           
            
            if(count($result)>0){
                foreach($result as $pickups) {
                    $count++;
                    $xlsRow++;
                    $dates = getDates($pickups['account_ID']);
                    
                    xlsWriteLabel($xlsRow,0,$pickups['account_ID']);
                    xlsWriteLabel($xlsRow,2,$pickups['status']);
                    xlsWriteLabel($xlsRow,4,"Biotane");
                    xlsWriteLabel($xlsRow,6,$pickups['name']);
                    xlsWriteLabel($xlsRow,8,$pickups['contact_name']);                    
                    xlsWriteLabel($xlsRow,10,$pickups['price_per_gallon']);
                    xlsWriteLabel($xlsRow,12,number_format($pickups['gals'],2) );
                    xlsWriteLabel($xlsRow,16 ,number_format(   ( $pickups['gals'] - ( $pickups['gals'] * $pickups['temp_miu'] ) )  ,2) );
                    xlsWriteLabel($xlsRow,18,number_format($pick['paid'],2) );
                    
                    if(isset($_POST['group'])){
                        if($_POST['group'] !="-"){
                            xlsWriteLabel($xlsRow,20,$dates[0]);
                            xlsWriteLabel($xlsRow,22,$dates[1]);                    
                            xlsWriteLabel($xlsRow,24,"$pick[address]");                    
                            xlsWriteLabel($xlsRow,26,"$pick[city]");
                            xlsWriteLabel($xlsRow,28,"$pick[state]");
                            xlsWriteLabel($xlsRow,30,"$pick[payment_method]");
                            xlsWriteLabel($xlsRow,32,"$pick[rate]");
                            xlsWriteLabel($xlsRow,34,numberToFacility($pick['division']));
                            xlsWriteLabel($xlsRow,36,number_format($pick['index_at_pickup'],2));
                            xlsWriteLabel($xlsRow,38,$ko[0]['date']);
                            xlsWriteLabel($xlsRow,40, $pick['num_pickups'] );
                        } else {
                            xlsWriteLabel($xlsRow,20,$dates[0]);
                            xlsWriteLabel($xlsRow,22,$dates[1]);                    
                            xlsWriteLabel($xlsRow,24,"$pick[address]");                    
                            xlsWriteLabel($xlsRow,26,"$pick[city]");
                            xlsWriteLabel($xlsRow,28,"$pick[state]");
                            xlsWriteLabel($xlsRow,30,"$pick[payment_method]");
                            xlsWriteLabel($xlsRow,32,"$pick[rate]");
                            xlsWriteLabel($xlsRow,34,numberToFacility($pick['division']));
                            xlsWriteLabel($xlsRow,36,number_format($pick['index_at_pickup'],2));
                            xlsWriteLabel($xlsRow,38,$ko[0]['date']);
                        }
                    } else {
                        xlsWriteLabel($xlsRow,20,$dates[0]);
                        xlsWriteLabel($xlsRow,22,$dates[1]);                    
                        xlsWriteLabel($xlsRow,24,"$pick[address]");                    
                        xlsWriteLabel($xlsRow,26,"$pick[city]");
                        xlsWriteLabel($xlsRow,28,"$pick[state]");
                        xlsWriteLabel($xlsRow,30,"$pick[payment_method]");
                        xlsWriteLabel($xlsRow,32,"$pick[rate]");
                        xlsWriteLabel($xlsRow,34,numberToFacility($pick['division']));
                        xlsWriteLabel($xlsRow,36,number_format($pick['index_at_pickup'],2));
                        xlsWriteLabel($xlsRow,38,$ko[0]['date']);
                    }
                }
            }
            else {
                $xlsRow++; 
                xlsWriteLabel($xlsRow,0,"Empty");
            }                 
            xlsEOF();
            
            break;
        
    }    
}
?>