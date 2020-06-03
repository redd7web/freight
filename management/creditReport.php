<?php

ini_set("display_errors",0);
$critieria="";
function past_due($account_no , $from = NULL, $to = NULL){
    global $db;   
    $string="";
    if($from != NULL){
        $search[] = " date_scheduled >= '$from'";
    }
    
    if($to != NULL){
        $search[] = " date_scheduled <= '$to'";
    }
    if(!empty($search)){
        $string = " AND ".implode(" AND ", $search);
    }
    //match an enroute stop with a paytrace entry where it has not been paid yet 
    $hgc = $db->query("SELECT freight_pay_trace.date_scheduled FROM freight_pay_trace INNER JOIN freight_grease_traps ON 
    
    freight_grease_traps.account_no = freight_pay_trace.account_no AND 
    freight_grease_traps.grease_route_no = freight_pay_trace.route_id AND 
    freight_grease_traps.grease_no = freight_pay_trace.schedule_id  WHERE freight_pay_trace.account_no = $account_no AND freight_grease_traps.route_status IN ('enroute') AND status =0 $string ORDER BY freight_pay_trace.date_scheduled DESC LIMIT 0,1");
    
    if(count($hgc)>0){
        return date_different($hgc[0]['date_scheduled'],date("Y-m-d"));
    }else{
        return 0;
    }

}

function paid($account_no , $from = NULL, $to = NULL){
    global $db;
    $string="";
    if($from != NULL){
        $search[] = " date_scheduled >= '$from'";
    }
    
    if($to != NULL){
        $search[] = " date_scheduled <= '$to'";
    }
    if(!empty($search)){
        $string = " AND ".implode(" AND ", $search);
    }
    
    $hgc = $db->query("SELECT COALESCE( SUM(total_price),NULL,0.00 ) as total_price FROM freight_pay_trace WHERE account_no = $account_no $string");
    return $hgc[0]['total_price'];
}

function upcoming($account_no , $from = NULL, $to = NULL){
    global $db;
    $string="";
    if($from != NULL){
        $search[] = " service_date >= '$from'";
    }
    
    if($to != NULL){
        $search[] = " service_date <= '$to'";
    }
    if(!empty($search)){
        $string = " AND ".implode(" AND ", $search);
    }
    
    $hgc = $db->query("SELECT COALESCE(	service_date,NULL,'0000-00-00') as service_date FROM freight_grease_traps WHERE account_no = $account_no AND route_status='scheduled' $string  ORDER BY 	service_date  DESC  LIMIT 0,1");
    if(count($hgc)>0){
        return $hgc[0]['service_date'];    
    } else {
        return "0000-00-00";
    }
    
    
}

function last_function($account_no,$from = NULL,$to = NULL){
    global $db;
    $string ="";
    if($from != NULL){
        $search[] = " date_of_pickup >= '$from'";
    }
    
    if($to != NULL){
        $search[] = " date_of_pickup <= '$to'";
    }
    if(!empty($search)){
        $string = " AND ".implode(" AND ", $search);
    }
    $hgc = $db->query("SELECT COALESCE(date_of_pickup,NULL,'0000-00-00') as date_of_pickup FROM freight_grease_data_table WHERE account_no = $account_no $string ORDER BY date_of_pickup  DESC  LIMIT 0,1");
    if(count($hgc)>0){
        return $hgc[0]['date_of_pickup'];
    } else {
        return "0000-00-00";
    }
}

if(isset($_POST['search_now'])){
    foreach($_POST as $name=>$value){
        switch($name){
             case "friend":
                if($value == 1){
                    $arrFields[] = " friendly IS NULL ";
                } else if ($value ==2){
                    $arrFields[] = " (friendly IS NOT NULL  OR friendly !='' OR friendly !=' ')";
                }
                break;
            case "from":
                if(strlen($value)>0 && $value !=" "){
                    $dateField[]=" date_of_pickup >='$value'";
                }
                break;
            case "to":
                if(strlen($value)>0 && $value !=" "){
                    $dateField[]=" date_of_pickup <='$value'";
                }
                break;
            case "fromupc":
                if(strlen($value)>0 && $value !=" "){
                    $upcm[] = "service_date >='$value'";
                }
                break;
            case "toupc":
                if(strlen($value)>0 && $value !=" "){
                    $upcm[] = "service_date <='$value'";
                }
                break;
            case "min":
                if(strlen($value)>0 && $value !=" "){
                    $arrFields[] = " freight_accounts.credits >=$value";
                }
                break;
            case "max":
                if(strlen($value)>0 && $value !=" "){
                    $arrFields[] = " freight_accounts.credits <=$value";
                }
                break;
            case "facility":
                if($value !="" && $value !=" "){
                     if($value !="ignore" && $value !=99){
                        $arrFields[] = "freight_accounts.division = $value";
                    }else  if ( $value == 99){
                        $arrFields[] = "freight_accounts.division in (24,30,31,32,33)";
                    } 
                }
               
                break;
            case "cc":
                if(isset($_POST['cc'])){
                    $arrFields[] = "freight_accounts.cc_on_file = 1";
                }
            break;
            case "corp":
                if(isset($_POST['corp'])){
                    $arrFields[] = "freight_accounts.corp_account = 1";
                }
                break;
            case "locked":
                if(isset($_POST['locked'])){
                    $arrFields[] = "freight_accounts.locked = 1";
                }
                break;
        }
    }
    $criteria="";
    $crit = "";
    $upccrit="";
    
    if(!empty($upcm)){
        $upccrit = " AND ".implode(" AND ",$upcm);
        $upccrit = " AND account_ID IN ( SELECT account_no FROM freight_grease_traps WHERE 1 $upccrit)";
    }
    
    if(!empty($dateField)){
        $crit = " AND ".implode( " AND ",$dateField);
        $crit = "AND account_ID IN ( SELECT account_no FROM freight_grease_data_table WHERE 1 $crit)";
    }
    
    if(!empty($arrFields)  ){
        $criteria = " AND ".implode( " AND ", $arrFields);
    } 
    
    echo "SELECT account_ID, freight_accounts.credit_note, freight_accounts.credits, credit_terms, cc_on_file, new_bos, address, city, state, zip, out_standing_debts, grease_ppg * grease_volume AS charge, COUNT( freight_grease_data_table.date_of_pickup ) AS num_pickups
FROM freight_accounts
LEFT JOIN freight_grease_data_table ON freight_accounts.account_ID = freight_grease_data_table.account_no
WHERE STATUS IN (
'Active', 'New'
)  $criteria $crit $upccrit group by account_ID";

    $cred = $db->query("SELECT  credit_email,import_past_due,locked,account_ID, freight_accounts.credit_note, freight_accounts.credits, credit_terms, cc_on_file, new_bos, address, city, state, zip, out_standing_debts, grease_ppg * grease_volume AS charge, COUNT( freight_grease_data_table.date_of_pickup ) AS num_pickups
FROM freight_accounts
LEFT JOIN freight_grease_data_table ON freight_accounts.account_ID = freight_grease_data_table.account_no
WHERE STATUS IN (
'Active', 'New'
)  $criteria $crit $upccrit group by account_ID");
} else {
    $cred = $db->query("SELECT credit_email,import_past_due,locked,account_ID, freight_accounts.credit_note, freight_accounts.credits, credit_terms, cc_on_file, new_bos, address, city, state, zip, out_standing_debts, grease_ppg * grease_volume AS charge, COUNT( freight_grease_data_table.date_of_pickup ) AS num_pickups
FROM freight_accounts
LEFT JOIN freight_grease_data_table ON freight_accounts.account_ID = freight_grease_data_table.account_no
WHERE STATUS IN (
'Active', 'New'
) group by account_ID");
}

?>
<style type="text/css">
.tableNavigation {
    width:1000px;
    text-align:center;
    margin:auto;
}
.tableNavigation ul {
    display:inline;
    width:1000px;
}
.tableNavigation ul li {
    display:inline;
    margin-right:5px;
}

td{
    background:transparent;
    border:0px solid #bbb;  
    padding:0px 0px 0px 0px;  
}

tr.even{
    background:-moz-linear-gradient(center top , #F7F7F9, #E5E5E7);
}

tr.odd{
    background:transparent;
}
.setThisRoute{ 
    z-index:9999;
}
</style>
<script>
$(document).ready(function(){    
   $('#myTable').dataTable({
        "lengthMenu": [ [ 50,10, 25,100,150, -1], [50,10, 25, 100,150, "All"] ]
   }); 
});
</script>
<?php
if($person->isCreditManager()){
?>
<table id="myTable">
    <thead>
        <tr  style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
            <td class="cell_label">Account</td>
            
            <td class="cell_label">Credit Email</td>
            <td class="cell_label">Past Due</td>
            <td class="cell_label">Credit Note</td>
            <td class="cell_label">Credits</td>
            <td class="cell_label">Credit Terms</td>
            <td class="cell_label">Card on File</td>
            <td class="cell_label">New Bos</td>
            <td class="cell_label">Address</td>
            <td class="cell_label">City</td>
            <td class="cell_label">State</td>
            <td class="cell_label">Zip</td>
            <td class="cell_label">Past Due</td>
            <td class="cell_label"># of Services</td>
            <td class="cell_label">Charge Amount</td>
            <td class="cell_label">Paid</td>
            <td class="cell_label">Date of Last Service</td>
            <td class="cell_label">Route Status</td>
            <td class="cell_label">Upcoming Service Date</td>
            <td class="cell_label">Locked</td>
            
        </tr>
    </thead>
    <tbody>
    <?php
        if(count($cred)>0){
            foreach($cred as $k){
                $accnt = new Account($k['account_ID']);
                echo "<tr>
                    <td>".account_NumToName($k['account_ID'])."</td>
                    
                    <td><input class='cemail type='text' value='$k[credit_email]' rel='$k[account_ID]'/></td>
                    <td>$k[import_past_due]</td>
                    <td><textarea class='cn' rel='$k[account_ID]'>$k[credit_note]</textarea></td>
                    <td><input type='text' rel='$k[account_ID]' class='cr' value='$k[credits]' style='width:60px;'/></td>
                    <td><textarea class='terms'  rel='$k[account_ID]'>$k[credit_terms]</textarea></td>
                    <td><input  rel='$k[account_ID]' class='cc' type='checkbox'"; 
                        if($k['cc_on_file'] ==1){
                            echo " checked ";
                        }
                    echo "   /></td>
                    <td>$k[new_bos]</td>
                    <td>$k[address]</td>
                    <td>$k[city]</td>
                    <td>$k[state]</td>
                    <td>$k[zip]</td>
                    <td>".past_due($k['account_ID'],$_POST['from'],$_POST['to'])."</td>
                    <td>$k[num_pickups]</td>
                    <td>".number_format($k['charge'],2)."</td>
                    <td>".paid($k['account_ID'],$_POST['from'],$_POST['to'])."</td>
                    <td>".last_function($k['account_ID'],$_POST['from'],$_POST['to'])."</td>
                    <td>".$accnt->schedule['route_status']."</td>
                    <td>".upcoming($k['account_ID'],$_POST['fromupc'],$_POST['toupc'])."</td>
                    <td>"; 
                        if($k['locked']==1){
                            echo "<img src='img/lock-icon.png'  rel='$k[account_ID]' style='width:25px;height:25px;cursor:pointer;' class='tumbler' value='0'/>";
                        } else {
                             echo "<img src='img/unlock.jpg'  rel='$k[account_ID]' style='width:25px;height:25px;cursor:pointer;' class='tumbler' value='1'/>";
                        }
                    echo "</td>
                </tr>";
            }
        }
    ?>
    </tbody>
</table>
<?php
}
?>
<script>
$(".cn").change(function(){
   $.post("updateCN.php",{account:$(this).attr('rel'),value:$(this).val(),mode:1},function(data){
        alert("Credit Notes Updated!");
   }); 
});

$(".cr").change(function(){
    $.post("updateCN.php",{account:$(this).attr('rel'),value:$(this).val(),mode:2},function(data){
        alert("Credits Updated!");
   }); 
});


$(".terms").change(function(){
    $.post("updateCN.php",{account:$(this).attr('rel'),value:$(this).val(),mode:3},function(data){
        alert("Terms Updated!");
   }); 
});

$(".cc").click(function(){
    if($(this).is(":checked") ){
        $.post("updateCN.php",{account:$(this).attr('rel'),value:1,mode:4},function(data){
            alert("Credit Card Updated!");
        }); 
    }else {
        $.post("updateCN.php",{account:$(this).attr('rel'),value:0,mode:4},function(data){
            alert("Credit Card  Updated!");
        }); 
    }
});

$(".tumbler").click(function(){
    var tumb = $(this);
    $.post("updateCN.php",{account:$(this).attr('rel'),value:$(this).attr('value'),mode:5},function(data){
            alert("Lock Status Updated! ");
            
            if( tumb.attr('value') == 0){
                tumb.attr('src','img/unlock.jpg');
                tumb.attr('value',1);
            } else {
               tumb.attr('src','img/lock-icon.png');
               tumb.attr('value',0);
            }
        }); 
});

$(".cemail").change(function(){
     $.post("updateCN.php",{account:$(this).attr('rel'),value:$(this).val(),mode:6},function(data){
        alert("Credit Email Updated!");
   });
});



$("input#toupc").datepicker({dateFormat:"yy-mm-dd"});
$("input#fromupc").datepicker({dateFormat:"yy-mm-dd"});
</script>
