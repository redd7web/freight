<?php 
include "source/css.php";
ini_set("display_errors",0);
$logger = new Person();
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
   $('#myTable').dataTable(); 
});
</script>




<table style="width: 100%;margin:auto;" >
  <tr><th colspan="3" style="height: 20px;"><a href="management/addUser.php" target="_blank"><img src="img/add_item.big.gif" />&nbsp;<span style="font-size: 12px;">Add User</span><br /></th></tr>
</table>


<table style="width: 100%;margin:auto;" id="myTable">

<thead>
  <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
      <th style="border:1px solid #000000;padding:0px 0px 0px 0px;">User ID</th>
      <th style="border:1px solid #000000;padding:0px 0px 0px 0px;">Staff ID</th>
      <th style="width:5%;border:1px solid #000000;padding:0px 0px 0px 0px;">Name</th>
      <th style="width:7%;underline;border:1px solid #000000;padding:0px 0px 0px 0px;">Last Login</th>
      <th style="width:5%;border:1px solid #000000;padding:0px 0px 0px 0px;">Facility</th>
      <th style="padding:0px 0px 0px 0px;" class="cell_label"><span style="font-size:9px;" title="User can enter data: new accounts, collection/trip reports">Data Entry</span></th>

        <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="Schedule pickups and service calls, Add fires, add a message, etc">Customer Support (Basic)</span></th>
                    
                    
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="Change settings, sharing, flags, address and other customer data - locations and accounts ">Customer Support (Full)</span></th>
                    
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="User can use sales leads system">Sales</span></th>
                    
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="View routes, enter collection/trip reports">Driver</span></th>
                    
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="User can perform routing functions">Routing</span></th>
                    
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="Some users may have extra searching options, see full list of facilties, etc">Advanced Searching</span></th>
                    
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="Can see reports">Report Access</span></th>
                    
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="Full access to routing - includes exporting">Routing (Advanced)</span></th>
                    
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="Full access to customer records - includes archiving, etc">Customer Support (Advanced)</span></th>
                    
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="Manage, edit and assign Sales Leads">Sales Management</span></th>
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="Create and edit Staff Members">Staff Management</span></th>
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="Create and edit Users">User Management</span></th>
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="Overall data integrity - delete notes, fix completed pickups, etc">Data Management</span></th>
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="Can assign Advanced and Management access">User Management (Advanced)</span></th>
                    <th class="cell_label"><span style="font-size:9px;font-weight:bold;" title="Executive and financial functions - see reports, export data">Business Management</span>
                 </tr>
                 </thead>
                 <tbody>
                 
                  <?php 
                  $alter ="";
                  $result = $db->query("SELECT * FROM `freight_users` ");
                  //print_r($result);
                  $header_again = 0;
                  if(count($result)) { 
                    foreach($result as $user){
                        $alter++;
            
                        if($alter%2 == 0){
                            $bg = '-moz-linear-gradient(center top , #F7F7F9, #E5E5E7) repeat scroll 0 0 rgba(0, 0, 0, 0)';
                        }
                        else { 
                            $bg = 'trnsparent';
                        }
                        
                        $person = new Person($user['user_id']);
                        echo "<tr style='background:$bg;'>
                        
                            <td><span style='font-size:12px;'>"; 
                            if($logger->isAdmin()){
                                echo "<span class='chngpw' style='color:blue;text-decoration:blue;cursor:pointer;' rel='$person->user_id'> ";
                            }
                            echo $person->user_id; 
                            
                            if($logger->isAdmin()){
                                echo "</span>";
                            }
                            
                            echo "</td>
                            
                            <td><span style='font-size:12px;'>$person->staff_id</span></td>
                            
                            <td style='padding:0px 0px 0px 0px;'><a style='font-size:12px;text-decoration:underline;color:blue;' href='viewUser.php?id=$person->user_id'>$person->fullname</td>
                            
                            <td><span style='font-size:12px;'>$person->last_login</span></td>
                            
                            <td><span style='font-size:12px;'>$person->facility</span></td></td>
                            
                            <td id='data_entry'>"; if( $person->isDataEntry() ){ echo "<img src='img/check_green_2s.png'  style='width:25px;height:25px;'/>" ;} echo "</td>
                            
                            <td id='cust_support_basic'>"; if( $person->isCustSupport_Basic() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            
                            <td id='cust_support_full'>"; if( $person->isCSupport_Full() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            
                            
                            <td id='sales'>"; if( $person->isSalesRep() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            
                            <td id='driver'>"; if( $person->isOilDriver() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            
                            <td id='routing'>"; if( $person->isRouting() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            
                            <td id='adv_search'>"; if( $person->isSearch() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            
                            <td id='report'>"; if( $person->isReport() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            
                            <td id='cust_support_adv'>"; if( $person->isCSupport_Adv() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            <td id='sales_manage'>"; if( $person->isSalesLeads() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            <td id='staff_manage'>"; if( $person->isStaffManagement() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            <td id='user_manage'>"; if( $person->isUserManagement() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            <td id='data_manage'>"; if( $person->isDataManage() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            <td id='user_manage_adv'>"; if( $person->isUserManagement_Adv() ){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            <td id='business_manage'>"; if( $person->isBusinessManagement()){ echo "<img src='img/check_green_2s.png' style='width:25px;height:25px;' />" ;} echo "</td>
                            <td></td>
</tr>
";
                        
    }
  } 
?>
 </tbody>                 
</table>
<script>
$(".chngpw").click(function(){
    if(confirm("Are you sure you want to reset the password for this user?")){
        $.get("manual_pw.php",{chpw:$(this).attr('rel')},function(data){
            alert("Password Reset " + data);
        });
    }
});
</script>