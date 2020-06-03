<?php
include "protected/global.php";
function getDates($id){
    global $db;
    global $dbprefix;
    $first_last = $db->query("SELECT (SELECT date_of_pickup FROM freight_data_table WHERE account_no=$id ORDER BY date_of_pickup LIMIT 1) as 'first', (SELECT date_of_pickup FROM freight_data_table WHERE account_no=$id ORDER BY date_of_pickup DESC LIMIT 1) as 'last'");
    return $first_last;
}

$account_table = $dbprefix."_accounts";
$data_table = $dbprefix."_data_table";



if(isset($_POST['export'])){
    $query = "SELECT freight_accounts.name, freight_accounts.account_ID, SUM( inches_to_gallons ) AS total_gallons, SUM( inches_to_gallons * .25 ) AS adj, freight_accounts.status, freight_accounts.friendly, COUNT( inches_to_gallons ) AS pickups, freight_accounts.address, freight_accounts.city, freight_accounts.state FROM freight_accounts INNER JOIN freight_data_table ON  freight_accounts.account_ID = freight_data_table.account_no WHERE 1 $_POST[param] GROUP BY freight_accounts.account_ID";
    $result = $db->query($query);
    
    //var_dump($ghu);
    switch($_POST['format']){
        case "csv":
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Description: File Transfer");
            header("Content-type: text/csv");
            $fileName = "oilperloc".date("Ymdhis").".csv";
            header("Content-Disposition: attachment; filename={$fileName}");
            header("Expires: 0");
            header("Content-Transfer-Encoding: binary");
            header("Pragma: public");
            
            if(count($result)>0){
                foreach($result as $pickups){
                    $dates = getDates($pickups['account_ID']);
                    $dataString .= "$pickups[account_ID],$pickups[friendy],$pickups[status],$pickups[name],$pickups[total_gallons],".round($pickups['total_gallons'] - $pickups['adj'],2).",$pickups[pickups],".$dates[0]['first'].",".$dates[0]['last']."$pickups[address],$pickups[city],$pickups[state]\r\n";    
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
            xlsWriteLabel($xlsRow,0,"#:");
            xlsWriteLabel($xlsRow,2,"ID:");
            xlsWriteLabel($xlsRow,4,"status:"); 
            xlsWriteLabel($xlsRow,6,"Affiliate");
            xlsWriteLabel($xlsRow,8,"Name");
            xlsWriteLabel($xlsRow,10,"Raw:");
            xlsWriteLabel($xlsRow,12,"Adj:");
            xlsWriteLabel($xlsRow,14,"# of Pickups:");            
            
            xlsWriteLabel($xlsRow,16,"First Pickup");
            xlsWriteLabel($xlsRow,18,"Last Pickup");
            xlsWriteLabel($xlsRow,20,"Address:");
            xlsWriteLabel($xlsRow,22,"City:");
            xlsWriteLabel($xlsRow,24,"State:");    
            
            if(count($result)>0){
                foreach($result as $pickups) {
                    $count++;
                    $xlsRow++;
                    $dates = getDates($pickups['account_ID']);
                    xlsWriteLabel($xlsRow,0,$count);
                    xlsWriteLabel($xlsRow,2,$pickups['account_ID']);
                    xlsWriteLabel($xlsRow,4,$pickups['status']);
                    xlsWriteLabel($xlsRow,6,$pickups['friendly']);
                    xlsWriteLabel($xlsRow,8,$pickups['name']);                    
                    xlsWriteLabel($xlsRow,10,$pickups['total_gallons']);
                    xlsWriteLabel($xlsRow,12,round($pickups['total_gallons'] - $pickups['adj'],2));
                    xlsWriteLabel($xlsRow,14,$pickups['pickups']);
                    xlsWriteLabel($xlsRow,16,$dates[0]['first']);
                    xlsWriteLabel($xlsRow,18,$dates[0]['last']); 
                    xlsWriteLabel($xlsRow,20,$pickups['address']);
                    xlsWriteLabel($xlsRow,22,$pickups['city']);
                    xlsWriteLabel($xlsRow,24,$pickups['state']);
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