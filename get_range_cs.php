  <?php
ini_set("display_errors",0);
include "protected/global.php";
$criteria = "";
$account = new Account($_POST['account_no']);



foreach($_POST as $name=>$value){
    switch($name){
        case "from":
            if(strlen($value)>0){
                $arrFields[]=" date_created >='$value'";
            }
            break;
        case "to":
            if( strlen($value)>0 ){
                $arrFields[]= " date_created <='$value'";
            }
            break;
    }
    
    if(!empty($arrFields)){
        $criteria = " AND ".implode(" AND ", $arrFields);
    }
}
//echo "SELECT * FROM Inetforms.ap_form_28181 WHERE ap_form_28181.element_52 = $account->acount_id $criteria <br/>";
?>
  
  <div id="cslastx" style="float:left;width:150px;min-height:250px;padding:10px 10px 10px 10px;background:transparent;height:auto;">
            <style>
            table#overview td {
                text-align:left;
            }
            </style>
                <?php 
                    $cs = $db->query("SELECT * FROM Inetforms.ap_form_28181 WHERE ap_form_28181.element_52 = $account->acount_id $criteria");
                ?>
                <table style="width: 175px;height:100px;border:0px solid #bbb;table-layout: fixed;font-size:12px;" id="overview">
                    <tr><td style="text-align: center;" colspan="2"><span style="color: green;font-size:13px;font-weight:bold;">Confined Space Overview</span></td></tr>
                    <tr><td style="height: 9px;vertical-align:top;">Total Pickups:</td><td>
                    <?php echo count($cs);  ?></td></tr>

                    <tr><td style="height: 9px;vertical-align:top">Total Charged:</td><td style="text-align: left;vertical-align:top;"><?php $retcs = 0; $tpcs = 0;
                        if(count($cs)>0){                        
                            foreach($cs as $su){
                                $retcs += $su['element_10'];
                                $tpcs += $su['element_5'];
                            }
                            
                        } 
                        echo $retcs;
                        ?></td></tr>
                    <tr><td style="height: 9px;vertical-align:top">Total Hours</td><td style="text-align: left;vertical-align:top;"><?php echo number_format($tpcs,2); ?></td></tr>
                    
                   
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
                if(count($cs)>0){
                    $occurred_once[]="";
                    foreach($cs as $lo){
                        echo "<tr>";
                        echo "<td>$lo[date_created]</td>";
                        echo "<td>".number_format($lo['element_5'],2)."</td>";
                        echo "<td>$lo[element_10]</td>";
                        echo "<td>$lo[element_52]</td>";
                        echo "</tr>";
                    }
                }
                ?>
                </tbody>
                </table>
                <div style="clear: both;"></div>
                </div>