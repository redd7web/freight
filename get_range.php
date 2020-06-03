<?php
ini_set("display_errors",0);
include "protected/global.php";
$criteria = "";
$account = new Account($_POST['account_no']);

foreach($_POST as $name=>$value){
    switch($name){
        case "from":
            if(strlen($value)>0){
                $arrFields[]=" date_of_pickup >='$value'";
            }
            break;
        case "to":
            if( strlen($value)>0 ){
                $arrFields[]= " date_of_pickup <='$value'";
            }
            break;
    }
    
    if(!empty($arrFields)){
        $criteria = " AND ".implode(" AND ", $arrFields);
    }
}


 
 
?>

            <div id="leftlastx" style="float:left;width:150px;min-height:250px;padding:10px 10px 10px 10px;background:transparent;height:auto;">
            <style>
            table#overview td {
                text-align:left;
            }
            </style>
                <?php 
                    $gv = $db->query("SELECT * FROM freight_grease_data_table WHERE account_no=$_POST[account_no] $criteria ORDER BY date_of_pickup DESC");
                ?>
                <table style="width: 175px;height:100px;border:0px solid #bbb;table-layout: fixed;font-size:12px;" id="overview">
                    <tr><td style="text-align: center;" colspan="2"><span style="color: green;font-size:13px;font-weight:bold;">GT Collection Overview</span></td></tr>
                    <tr><td style="height: 9px;vertical-align:top;">Total Services:</td><td>
                    <?php echo count($gv);  ?></td></tr>

                    <tr><td style="height: 9px;vertical-align:top">Gallons Retrieved:</td><td style="text-align: left;vertical-align:top;"><?php $ret = 0; $tp = 0;
                        if(count($gv)>0){
                        
                            foreach($gv as $su){
                                $ret += $su['inches_to_gallons'];
                                $tp += $su['inches_to_gallons']*$su['ppg'];
                            }
                            
                        } 
                        echo $ret;
                        ?></td></tr>
                    <tr><td style="height: 9px;vertical-align:top">Gallons Adjusted</td><td style="text-align: left;vertical-align:top;"><?php echo ""; ?></td></tr>
                    <tr><td style="height: 9px;vertical-align:top:">Value:</td><td style="text-align: left;vertical-align:top;"><?php
                    echo number_format($account->grease_ppg,2);
                      ?></td></tr>
                    
                    <tr><td style="height: 9px;vertical-align:top:">Total Paid:</td><td style="text-align: left;vertical-align:top;">
                    <?php echo number_format($tp,2); ?>
                    </td></tr>
                   
                </table>
            </div>
            
            <div id="rightlast" style="float:left;width:290px;min-height:250px;padding:10px 10px 10px 10px;background:transparent;height:auto;">
                <style type="text/css">
                    table#payment {
                        border-collapse:collapse;
                    }
                </style>
                <div id="paymentbox" style="width: 284px;margin-left:10px;border:1px solid green;border-radius:5px;height:250px;overflow-x:hidden;overflow-y:auto;">
                <table style="width: 100%;font-size:13px;font-size:12px;" id="payment">
                <thead>
                <tr style="background: #E2E5DE;border-bottom:1px solid black;">
                    <td>Date</td>
                    <td>Payment</td>
                    <td>Gallons<br/>(adj)</td>
                    <td>PPG</td>
                    <td>Status</td>
                </tr>
                </thead>
                <tbody id="meat">
                <?php                
                if(count($gv)>0){
                    $occurred_once[]="";
                    foreach($gv as $lo){
                        echo "<tr>";
                        echo "<td>$lo[date_of_pickup]</td>";
                        echo "<td>".number_format($lo['inches_to_gallons']*$lo['ppg'],2)."</td>";
                        echo "<td>".$lo['inches_to_gallons']."</td>";
                        echo "<td>".number_format($lo['ppg'],2)."</td>";
                        echo "<td>$account->status</td>";
                        echo "</tr>";
                    }
                }
                ?>
                </tbody>
                </table>
                <div style="clear: both;"></div>
                </div>
            </div>
