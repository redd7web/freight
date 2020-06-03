<?php
ini_set("display_errors",0);
include "protected/global.php";
$criteria = "";
$account = new Account($_POST['account_no']);

foreach($_POST as $name=>$value){
    switch($name){
        case "from_jet":
            if(strlen($value)>0){
                $arrFields[]=" date_created >='$value'";
            }
            break;
        case "to_jet":
            if( strlen($value)>0 ){
                $arrFields[]= " date_created <='$value'";
            }
            break;
    }
    
    if(!empty($arrFields)){
        $criteria = " AND ".implode(" AND ", $arrFields);
    }
}

?>
<div id="jetlastx" style="float:left;width:150px;min-height:250px;padding:10px 10px 10px 10px;background:transparent;height:auto;">
            <style>
            table#overview td {
                text-align:left;
            }
            </style>
                <?php 
                    echo $criteria."<br/>";
                    $gv = $db->query("SELECT * FROM Inetforms.ap_form_29942 WHERE ap_form_29942.element_1 = $account->acount_id $criteria");
                ?>
                <table style="width: 175px;height:100px;border:0px solid #bbb;table-layout: fixed;font-size:12px;" id="overview">
                    <tr><td style="text-align: center;" colspan="2"><span style="color: green;font-size:13px;font-weight:bold;">Jetting Overview</span></td></tr>
                    <tr><td style="height: 9px;vertical-align:top;">Total Pickups:</td><td>
                    <?php echo count($gv);  ?></td></tr>

                    <tr><td style="height: 9px;vertical-align:top">Total Charged:</td><td style="text-align: left;vertical-align:top;"><?php $ret = 0; $tp = 0;
                        if(count($gv)>0){                        
                            foreach($gv as $su){
                                $ret += $su['element_12'];
                                $tp += $su['element_8'];
                            }
                            
                        } 
                        echo $ret;
                        ?></td></tr>
                    <tr><td style="height: 9px;vertical-align:top">Total Hours</td><td style="text-align: left;vertical-align:top;"><?php echo number_format($tp,2); ?></td></tr>
                    
                   
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
                    <td>Hours</td>
                    <td>Price Charged</td>
                    <td>Route Id</td>
                </tr>
                </thead>
                <tbody id="meat">
                <?php                
                if(count($gv)>0){
                    $occurred_once[]="";
                    foreach($gv as $lo){
                        echo "<tr>";
                        echo "<td>$lo[date_created]</td>";
                        echo "<td>".number_format($lo['element_8'],2)."</td>";
                        echo "<td>$lo[element_12]</td>";
                        echo "<td>$lo[element_2]</td>";
                        echo "</tr>";
                    }
                }
                ?>
                </tbody>
                </table>
                <div style="clear: both;"></div>
                </div>