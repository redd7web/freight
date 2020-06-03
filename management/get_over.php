<?php
include "../protected/global.php";
ini_set("display_errors",1);

if(isset($_POST['searched'])){
    
    $dates ="";
    if(isset($_POST['dates'])){
        $dates = $_POST['dates'];
    }
    
    $created ="";
    if(isset($_POST['created'])){
        $created = $_POST['created'];
    }
    
    $ser = "SELECT COUNT(account_ID) as serviced FROM freight_accounts WHERE status='Active' AND account_ID IN( SELECT DISTINCT(account_no) FROM freight_data_table WHERE 1 $dates)  $_POST[facs]";  
      
    $not_p = "SELECT COUNT(DISTINCT(account_ID)) as not_served FROM freight_accounts WHERE status='Active' AND account_ID NOT IN( SELECT DISTINCT(account_no) FROM freight_data_table WHERE 1 $dates) $_POST[facs]";    
    
    $ne  = "SELECT COUNT(account_ID) as news FROM freight_accounts WHERE status='New' $_POST[facs] $created";
    
    $all_p = "SELECT COUNT(freight_data_table.account_no) as total_picks FROM freight_data_table INNER JOIN freight_accounts ON freight_accounts.account_ID = freight_data_table.account_no WHERE 1 $_POST[facs] $dates";
} else {
    $month = date("m");
    $year = date("Y");
    $ser = "SELECT COUNT(account_ID) as serviced FROM freight_accounts WHERE status='Active' AND account_ID IN( SELECT DISTINCT(account_no) FROM freight_data_table WHERE YEAR (date_of_pickup)= '$year' AND  MONTH(date_of_pickup) = '$month')";
    
    $not_p = "SELECT COUNT(DISTINCT(account_ID)) as not_served FROM freight_accounts WHERE status='Active' AND account_ID NOT IN( SELECT DISTINCT(account_no) FROM freight_data_table WHERE YEAR (date_of_pickup)= '$year' AND  MONTH(date_of_pickup) = '$month' )";
    
    $ne  = "SELECT COUNT(account_ID) as news FROM freight_accounts WHERE status='New' AND YEAR(created)='$year' AND MONTH(created)='$month'";
    $all_p = "SELECT COUNT(account_no) as total_picks FROM freight_data_table WHERE YEAR(date_of_pickup) ='$year' AND MONTH(date_of_pickup) = '$month'";
    
}

$serv = $db->query($ser);
$not_picked_up = $db->query($not_p);
$new = $db->query($ne);
$all_pickups = $db->query($all_p); 
?>

 <table class="datatable" id="oloc">
                <tr><td colspan="2">Accounts</td></tr>
                <tr><td style="width: 50%;">Serviced</td><td><?php 
                
                echo $serv[0]['serviced'];
                //number of accounts of 1 or more pickups ( this month default)  ?></td></tr>
                <tr><td style="width: 50%;">Not Serviced</td><td><?php 
                   
                    echo $not_picked_up[0]['not_served'];
                //accounts not completed pickups not in date range ( this month default) ?></td></tr>
                <tr><td>New Accounts</td><td><?php
               
                echo $new[0]['news'];
                 //accounts created date range ( this month default) ?></td></tr>
                
                <tr><td style="width: 50%;">All</td><td><?php echo  $serv[0]['serviced'] + $not_picked_up[0]['not_served'];  ?></td></tr>
                <tr><td>Service Pickups</td><td><?php 
                    
                     echo $all_pickups[0]['total_picks'];
                // every completed pickup ( this month default) ?></td></tr>
            </table>