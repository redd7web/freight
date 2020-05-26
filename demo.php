
	
	<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
    <script type="text/javascript" src="tablesorter/jquery.tablesorter.js"></script>
	<script type="text/javascript">
	
	$(function() {
		$("#myTable").tablesorter({debug: true})
	});
	
	</script>	


<?php
include "protected/global.php";
$alter =0;
    
    if(!isset($_GET['filter'])){
        $result =$db->get($dbprefix."_accounts","*"); 
    }
    
    if(count($result)>0){
        ?>
        
        <table style="width: 100%;margin:auto;" id="myTable" class="tablesorter">
        <thead>
       
        <tr><td colspan="10"><a href="addAcount.php"><img src="img/add_item.big.gif" />&nbsp;<span style="font-size: 12px;">Add Account</span></a></td></tr>
        
         
        
        <tr style="background:-moz-linear-gradient(center top , #e7edf7, #adbef7) repeat scroll 0 0 rgba(0, 0, 0, 0);"><th class="cell_label">ID</th><th class="cell_label">Status</th><th class="cell_label">Class</th><th class="cell_label">Name</th><th class="cell_label">City</th><th class="cell_label">State</th><th class="cell_label">Created</th><th class="cell_label">Expires</th><th class="cell_label">Locations</th></tr>
        </thead>
        
        <tbody>
        <?php
        foreach($result as $account){
            $alter++;
            
            if($alter%2 == 0){
                $bg = '-moz-linear-gradient(center top , #F7F7F9, #E5E5E7) repeat scroll 0 0 rgba(0, 0, 0, 0)';
            }
            else { 
                $bg = 'trnsparent';
            }
            echo "<tr style='background:$bg'><td>$account[account_ID]</td><td>$account[status]</td><td>$account[class]</td><td><a href='viewAccount.php?id=$account[account_ID]' style='color:blue;text-decoration:underline;'>$account[name]</a></td><td>$account[city]</td><td>$account[state]</td><td>$account[created]</td><td>$account[expires]</td><td>$account[locations]</td>";
        }
    }                    

?></tbody></table>
