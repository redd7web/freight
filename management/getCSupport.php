<?php
    $criteria1 ="";
    $account_table = $dbprefix."_accounts";
    $issues_table = $dbprefix."_issues";
    if(isset($_POST['search_now'])){
        foreach($_POST as $name=>$value){
            switch($name){
                case "from":
                if(strlen($value)>0){
                    $arrField[] = " date_created =< '".$value."'";
                }
                break;
            case "to":
                if(strlen($value)>0){
                    $arrField[] = " date_created >= '".$value."'";
                }
                break;    
                case "friendly":
                if(strlen($value)>0){
                    $value = str_replace(" ","%",$value);
                    $arrField[] = " previous_provider like '%".$value."%'";
                }
                break;
            case "facility";
                    if($value !="ignore"){
                        if ($value == 99){
                             $arrFields[] = "division in(24,30,31,32,33)";
                        }
                        else{
                            $arrFields[] = "division = ".$value;
                        } 
                    }
                break;
            case "state":
                if(strlen($value)>0){
                    $arrField[] = " state='".$value."'";
                }
                break; 
            }
        }
        
        
        if(!empty($arrField)){
            var_dump($arrField);
            $criteria1 = " AND ( ". implode (" AND ",$arrField)." )";
                         
        }
        $string = "SELECT $account_table.expires, $account_table.account_ID,$account_table.state_date,$account_table.state,$account_table.division,$issues_table.* FROM $issues_table INNER JOIN $account_table ON $account_table.account_ID = $issues_table.account_no WHERE 1 ". $criteria1;
        echo $string."<br/>";
        $result = $db->query($string);
    } else {
        $result = $db->get($issues_table);    
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
        "lengthMenu": [ [10, 25, 50,100,150, -1], [10, 25, 50,100,150, "All"] ]
   }); 
});
</script> 

<table style="width: 100%;margin:auto;" id="myTable">
<thead>
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">


<td class="cell_label">ID</td>

<td class="cell_label">Date</td>

<td class="cell_label">Type</td>

<td class="cell_label">Description</td>

<td class="cell_label">User</td>

<td class="cell_label">ON</td>

<td class="cell_label">Name</td>

</tr>
</thead>
<tbody>
<?php

    if(count($result)>0){
        foreach($result as $issues){
            $alter++;
            
            if($alter%2 == 0){
                $bg = '-moz-linear-gradient(center top , #F7F7F9, #E5E5E7) repeat scroll 0 0 rgba(0, 0, 0, 0)';
            }
            else { 
                $bg = 'transparent';
            }
            
            echo "<tr style='background:$bg'>";
            echo "<td>$issues[issue_no]</td>";            
            $ku = explode(" ",$issues['date_created']); echo "<td>$ku[0]</td>";
            echo "<td>".$issues['category']."</td>";
            echo "<td>".$issues['message']."</td>";
            echo "<td>".$issues['reported_by']."</td>";
            echo "<td>";
                if(strlen($issues['completed_date']) ==0){
                    echo "0";
                }
                else {
                    echo "1";
                }
            
            echo "</td>";
            
            echo "<td>".$issues['account_no']."</td>";
            echo "</tr>";
        }
    }

?>
</tbody>
</table>

<table style="width: 100%;margin:auto;">
        <tr><td colspan="9" style="text-align: center;padding:5px 5px 5px 5px;"><?php 
            
          
            $page= count($full)/150;
                 
             if($page%150 != 0){
                $page = $page +1;
             }
                 
                 
     for($i = 1;$i<$page;$i++){
        echo "<a href='management.php?task=csupport&start=";  
           
                if($i == 1){
                    echo 0;
                }
                else{
                    echo (($i-1)*150);
                }
            
        echo "'>$i</a> | ";
        
          echo "<span style='font-weight:bold;font-size:20px;'>Viewing: $start - "; 
            
            if($start+149 > count($full)){
                echo count($full);
            } 
            else {
                echo $start+149;
            }
            echo " / ".count($full)." <br/><br/></span>";
    }

?></td></tr>
        </table>
