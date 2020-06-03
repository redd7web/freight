<?php
include "protected/global.php";
    ini_set("display_errors",0);
    ini_set('memory_limit','200M');
    //echo "query: $_POST[param]<br/>";
    $picknpay = $db->query($_POST['param']);

    /**/header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Description: File Transfer");
    header("Content-type: text/csv");
    $fileName = "export_import".date("Ymdhis").".csv";
    header("Content-Disposition: attachment; filename={$fileName}");
    header("Expires: 0");
    header("Content-Transfer-Encoding: binary");
    header("Pragma: public");
     if(count($picknpay)>0){    
       $dataString="AR.ACCTTYPE,AR.ACCOUNT#,AR.QTYDESC,AR.ENTRYDATE,AR.QUANTITY,AR.PRICEPER,AR.QTP,AR.MISCDESC[0],AR.INVOICEDATE,AR.INVOICE#,AR.MISCDESC[1],AR.MISCDESC[2],AR.MISCDESC[3],AR.MISCDESC[4],AR.SUBTOTAL,AR.TOTAL,AR.ARFINACCT#,AR.FINACCT#,AR.SHIPTRAN#,AR.MISCDESC[5],AR.MISCDESC[6],AR.MISC[6],AR.DISTACCT#[6],AR.MISCDESC[9],AR.MISC[9],AR.DISTACCT#[9],AR.MISCDESC[7],AR.MISCDESC[8]\r\n";
        foreach($picknpay as $pick){
            $desc_5 = 0;
            $acctt = new Account($pick['account_ID']);
            $com_loc = $db->query("SELECT IFNULL(location,NULL) as location,IFNULL(inventory_code,NULL) as inventory_code,IFNULL(wtn,NULL) as wtn FROM freight_ikg_grease WHERE route_id = $pick[route_id]");
            
            if($pick['grease_no'] != NULL AND $pick['route_id'] !=NULL AND $pick['account_ID'] !=NULL ){
                $add_desc_amou = $db->query("SELECT IFNULL(addtional_cost_description,NULL) as additional_cost_description,IFNULL(additional_cost_amount,0.00) as additional_cost_amount,IFNULL(jetting,0) as jetting,IFNULL(jet_cost,0.00) as jet_cost,IFNULL(import_note,NULL) as import_note,date_of_pickup FROM freight_grease_data_table WHERE route_id =$pick[route_id] AND account_no=$pick[account_ID] AND schedule_id = $pick[grease_no]");
            }
            if($pick['account_ID']==42869 || $pick['account_ID'] == 28637){
                $serv = "SLUDGE";//AR.MISCDESC[7]
            }else{
                $serv = "GREASE TRAP SERVICE";
            }
            
            
            $dataString .="B,";
            $dataString .="$pick[new_bos],";
            $dataString .="$serv,";
            $dataString .=date("m/d/Y").",";
            switch($acctt->payment_method){
                case "Charge Per Pickup": case "Index":
                    $desc_5 = 1;
                    //echo "jacobe hit!";
                    $dataString .="1,";
                    $dataString .=$acctt->index_percentage.",";
                    break;
                case "Per Gallon": case "Normal":
                    $desc_5 = 0;
                     $xx = str_replace(",","",$pick['inches_to_gallons']);
                    $dataString .="$xx,";
                    $dataString .="$pick[ppg],";
                    break;
                case "One Time Payment":case "O.T.P.":
                    $desc_5 = 1;
                     $dataString .="1,";
                     $dataString .="$pick[ppg],";
                    break;
                case "O.T.P. Per Gallon": case "One Time Payment Per Gallon": case "O.T.P. PG":
                    $desc_5 = 1; 
                      $dataString .="1,";
                      $dataString .="$pick[ppg],";
                    break;
                case "No Pay": case "Free": case "Normal": case "NULL": case "Do Not Pay":
                    $desc_5 = 1;
                     $dataString .="1,";
                     $dataString .="$pick[ppg],";
                    break;
                case "Split Account":
                    $desc_5 = 1;
                      $dataString .="1,";
                      $dataString .="$pick[ppg],";
                    break;
                case "Cash On Delivery":
                    $desc_5 = 1;
                      $dataString .="1,";
                      $dataString .="0,";
                    break;
                default:                    
                    $desc_5 = 0;
                    $dataString .= "1,";
                    $dataString .="$pick[ppg],";
                    break;
            }
            
            
            
            $dataString .="Q,";            
                $acntnme = preg_replace("/[^ \w]+/", "", account_NumtoNamePlain($pick['account_ID']));//
            $dataString .= "$acntnme,";//AR.MISCDESC[0]
            $dataString .=date("Y-m-d").",";//AR.INVOICEDATE
            $dataString .=",";//AR.INVOICE#,
                $addy = preg_replace("/[^ \w]+/", "", $pick['address']);
            $dataString .="$addy,";//AR.MISCDESC[1]
            $dataString .="$pick[city] $pick[state] $pick[zip],";// AR.MISCDESC[2]
                $old_date_timestamp = strtotime("$pick[service_date]");
                $new_date = date('m-d-y', $old_date_timestamp); 
            $dataString .="SRVC DATE $new_date,";//AR.MISCDESC[3]
            $dataString .="IKG $pick[route_id]";//AR.MISCDESC[4]
            $dataString .=",";//armiscdesc [5]
            $dataString .="$pick[paid],";//AR.SUBTOTAL
            $dataString .="$pick[paid],";//AR.TOTAL
            
            
            $dataString .= $com_loc[0]['location'].",";//AR.ARFINACCT#
            
            
            if($pick['account_ID'] == 28637 ||$pick['account_ID'] == 42869 ){
                $dataString .= "W3946,";//AR.FINACCT#
            } else{
                $dataString .= $com_loc[0]['inventory_code'].",";//AR.FINACCT#
            }
            
            
            
            $dataString .= ",";//AR.SHIPTRAN#           
            if($desc_5 ==1){//AR.MISCDESC[5]
                $dataString .= "$pick[grease_volume] GAL,";
            }else{
                $dataString .= ",";
            }
            
            if($pick['additional_cost_amount']==0.00){
                $dataString .= ",";//AR.MISCDESC[6]
            }else{
                $dataString .= "$pick[additional_cost_amount],";//AR.MISCDESC[6]
            }
            
            
            $dataString .= "$pick[addtional_cost_description],";//AR.MISC[6]
            
            
            
           
            if($pick['account_ID']==42552){
                $dataString .= "WC ".$com_loc[0]['wtn'].",";//AR.MISCDESC[7]
            }else{
                $dataString .=",";////AR.MISCDESC[7]
            }
            
            
        }
    }
    
    /**/$fh = @fopen( "php://output", 'w' );
    fwrite($fh, $dataString);
    fclose($fh);
    //echo $dataString;

?>