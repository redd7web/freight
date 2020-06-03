<?php
ini_set("display_errors",0);

$alter =0;
$addtn ='';
if($person->isFriendly()){
    $addtn = " AND friendly like '%$person->first_name%'";
}else if($person->isCoWest()){
    $addtn = " AND division IN( 15,24,30,31,32,33)";
}

$account_table = $dbprefix."_accounts";    
    
    $view_files = 0;    
    if(isset($_POST['search_now'])  ){
        
        foreach($_POST as $name=>$value){
            switch($name){
                case "get_files":
                    $view_files = 1;
                break;
                case "flag_id":
                    if($value==1){
                        $arrFields[] = "contract IS NULL";
                    }else if ($value ==6){
                        $arrFields[] = "billing_address IS NULL";
                        $arrFields[] = "billing_state IS NULL";
                        $arrFields[] = "billing_state IS NULL";
                    } else if($value ==7){
                        $arrFields[] = "address IS NULL"; 
                    } else if($value == 3){
                        $arrFields[]= "cancel_letter IS NULL";
                    } else if($value ==4){
                        $arrFields[] = " status ='Out of Business'";
                    } else if ($value == 8){
                        $arrFields[] = "cancel_letter IS NOT NULL";                    
                    }
                    
                break;
                case "disposition":
                    if($value !="all"){
                        if($value == "own"){
                            $arrFields[] = "account_rep = $person->user_id";
                        } else if ($value =="orig"){
                            $arrFields[] = "original_sales_person = $person->user_id";
                        } else if( $value == "own_not_orig"){
                            $arrFields[] =  " ( account_rep = $person->user_id AND friendly like '%The W%')";
                        }
                    } 
                 break;
                case "salesrep":
                    if(strlen($value)>0 && $value !=''){
                        $arrFields[] = " account_rep = ".$value;
                    }
                    break;
                case "orig":
                    if($value !='-'){
                        $arrFields[] = " original_sales_person =".$value;
                    }
                    break;
            	case "status":
                    if($value !="ignore"){
            		  $arrFields[] = "status = '".$value."'";
                    }
            		break;
            	case "payment_type_id":
                    if($value !="ignore"){
            		  $arrFields[] = "payment_method = '".$value."'";
                    }
            		break;
                case "prev_compet":
                    if($value !="ignore"){
                        $arrFields[] = "previous_provider = '".$value."'";
                    }
                    break;
                case "id":
                    if(strlen($value)>0){
                        $arrFields[] = "account_ID like  '%".$value."%'";
                    }
                    break;
                case "name":
                    if(strlen($value)>0){
                        $value = str_replace(" ","%",$value);
                        $arrFields[] = "name like '%".$value."%'";
                    }
                    break;
                case "address":
                    if(strlen($value)>0){
                        $value = str_replace(" ","%",$value);
                        $arrFields[] = "address like '%".$value."%'";
                    }
                    break;
                case "city":
                    if(strlen($value)>0){
                        $value = str_replace(" ","%",$value);
                        $arrFields[] = "city like '%".$value."%'";
                    }
                    break;
                case "state":
                    if(strlen($value)>0){
                        $arrFields[] = "state = '".$value."'";
                    }
                    break;   
                case "zip":
                    if(strlen($value)>0){
                        $arrFields[] = "zip = $value";
                    }
                    break;    
                case "area":
                    if(strlen($value)>0){
                        $arrFields[] = "area_code like '%".$value."%'";
                    }
                    break;  
                case "phone":
                    if(strlen($value)>0){
                        $arrFields[] = "phone like '%".$value."%'";
                    }
                    break; 
                case "fac1":
                if(isset($name)){                   
                    $facField[]=" division =".$value;} 

                break;
                case "fac2":
                    if(isset($name)){                    
                        $facField[]=" division =".$value;}
                    break;
                case "fac3":
                if(isset($name)){               
                    $facField[]=" division =".$value; }
                    break;
                case "fac4":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                case "fac5":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                case "fac6":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                case "fac7":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                case "fac8":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                case "fac9":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                case "fac10":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                break;                
                case "fac11":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                break;
                case "fac12":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                break;    
                case "fac13":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                break;    
                case "fac14":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                break;
                case "fac15":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                break;
                case "fac16":
                if(isset($name)){
                    $facField[]=" division =".$value;}
                break;
                case "friendly":
                    if($value !="null"){
                        $arrFields[] = "friendly like '%$value%'";
                    }
                break;
                
            }
        }
        
        $criteria1 ="";
        $criteria2 ="";
        
        if(!empty($arrFields)){
            echo "value<br/>";
             $criteria1 = " AND ". implode (" AND ",$arrFields);             
        }
        
        if(!empty($facField)) {
            
            $criteria2 = " AND ( ".implode (" OR ", $facField)." )";
        }
        $format = "SELECT previous_provider,area_code,division,payment_method,name,city,state,zip,phone,status,new_bos,expires,created,account_ID,class,address FROM $account_table WHERE 1 $addtn $criteria1 $criteria2";
        echo "<b>!debug purposes:!</b> ". $format;
        //echo $newformat."<br/>";
        $result = $db->query($format);
    }
    else{   
        $format = "SELECT previous_provider,area_code,division,payment_method,name,city,state,zip,phone,status,new_bos,expires,created,account_ID,class,address FROM ".$dbprefix."_accounts WHERE status IN ('active','new','pending') $addtn";
        echo $format;
        $result =$db->query($format);
    }
    
    // var_dump($result);
        
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
        "lengthMenu": [ [10, 25, 50,100,150, -1], [10, 25, 50,100,150, "All"] ]
   }); 
});
</script>
        <table style="width: 100%;margin:auto;">
        <tr><td colspan="10"><a href="customers.php?task=newaccount"><img src="img/add_item.big.gif" />&nbsp;<span style="font-size: 12px;">Add Account</span></a></td> <td><form action="printaccounts.php" method="post" target="_blank"><select name="format"><option value="csv">CSV</option><option value="xls">Excel</option></select><input type="text" name="criteria" value="<?php echo $addtn.$criteria1.$criteria2; ?>"/><input type="submit" value="Export" name="export"/></form></td></tr>
        </table>
        
        <table style="width: 100%;margin:auto;" id="myTable" >
       
        <thead>        
            <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
                <th style="width: 5%;">&nbsp;</th>
                <th class="cell_label">ID</th>
                <th class="cell_label">Status</th>
                <th class="cell_label">Payment Type</th>
                <th class="cell_label" style="width: 150px;">Name</th>
                <th class="cell_label">City</th>
                <th class="cell_label">State</th>
                <th class="cell_label">Created</th>
                <th class="cell_label">Expires</th>
                <th class="cell_label">New Bos</th>
                <th class="cell_label">Address</th>
                <th class="cell_label">Division</th>
                <?php
                    if($view_files ==1){
                        echo "<th class='cell_label'>Contract</th>";
                    }
                ?>
                <!--
                <th class="cell_label">scheduled date</th>
                <th class="cell_label">route_status</th>--!>
            </tr>
        </thead>
        
        <tbody>
        
        <?php
         if(count($result)>0){
          $start =1;        
          foreach($result as $account){
              echo "
            <tr>
                <td>$start</td>
                <td>$account[account_ID]</td>
                <td>$account[status]</td>
                <td>$account[payment_method]</td>
                <td><a href='viewAccount.php?id=$account[account_ID]' style='color:blue;text-decoration:underline;' target='_blank'>$account[name]</a>&nbsp;&nbsp;";
                    if($view_files ==1){
                        echo "<a href='view_files.php?account=$account[account_ID]' rel='shadowbox;width=600px;height=300px;'><img src='img/view_files.png' style='width:15px;height:15px;'/></a>";
                    }
                  echo "<span style='color:transparent;display:none;'> $account[payment_method] $account[area_code] $account[phone] $account[previous_provider]</span></td>
                <td>$account[city]</td>
                <td>$account[state]</td>
                <td>$account[created]</td>
                <td>$account[expires]</td>
                <td>$account[new_bos]</td>  
                <td>$account[address]</td>
                <td>".numberToFacility($account['division'])."</td>";
                if($view_files == 1){
                    echo "<td>"; 
                        if($account['contract'] != null && $account['contract'] != '' && $account['contract'] != ' '){
                            echo "<img src='img/check_green_2s.png' style='width:20px;height:20px;'/>";
                        }
                    echo "</td>";
                }
                
                
                /*
                echo "<td>"; 
                $g = $db->query("SELECT scheduled_start_date,route_status,route_id FROM freight_scheduled_routes WHERE account_no=$account[account_ID] AND route_status IN('scheduled','enroute') ORDER BY scheduled_start_date DESC LIMIT 0,1");
                if(count($g)>0){
                    echo "<span title='".$g[0]['route_id']."'>".$g[0]['scheduled_start_date']."</span>";
                }
                echo "</td> 
                
                <td>"; 
                echo $g[0]['route_status'];
                echo "</td>"; 
                */
                
                echo "</tr>";
            $start++;
          }
        }
        ?>
        </tbody></table>
                  
           