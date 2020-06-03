<?php
$last_month = date("m")-1;
$current_year = date("Y");
$current_month = date("m");

$user_table = $dbprefix."_users";
$data_table = $dbprefix."_data_table";
$string = "";
if(isset($_POST['search_now'])){
    
    foreach($_POST as $name=>$value){
         switch($name){
            case "facility":
                 if($value !="ignore"){
                    if ($value == 99){
                        $arrFields[] = "facility_restriction in(24,30,31,32,33)";
                    }
                    else{
                        $arrFields[] = "facility_restriction = ".$value;
                    }    
                 } 
            break;
         }
    }
    
    $string ="SELECT user_id,first,last FROM $user_table WHERE roles LIKE '%driver%' AND ";
    
     if(!empty($arrFields)){
            $criteria = implode(" AND ", $arrFields);
    }
    $string = $string.$criteria;
     //echo $string."<br/>";
    $result = $db->query($string);    
} else {
    
    $string ="SELECT user_id,first,last FROM $user_table WHERE roles LIKE '%driver%'"; 
    $result = $db->query($string);    
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

<table style="width: 100%;margin:auto;" >


<tr>
<th colspan="2" style="width:30px;"></th>
<th align="center"  style="background: #ccc;" colspan="3">This Month</th>

<th align="center" style="background: #bbb;"  colspan="3">Last Month</th>

<th align="center" style="background: #eee;"  colspan="3">Year to Date</th>


<th align="center"  colspan="3">Total</th></tr>
<tr>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>&nbsp;</th>

</tr>
</table>


<table  id="myTable" style="width: 100%;margin:auto;" id="myTable">
<thead>

<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">

<th >ID</th>

<th >Full name</th>

<th >Gallons</th>

<th >Pickups</th>

<th >Routes</th>

<th >Gallons</th>

<th >Pickups</th>

<th >Routes</th>

<th >Gallons</th>

<th >Pickups</th>

<th >Routes</th>

<th >Gallons</th>

<th >Pickups</th>

<th >Routes</th>

</tr>
</thead>
<tbody>
<?php
if(count($result)>0){
    foreach($result as $pickup){
         $query1 = "SELECT SUM(inches_to_gallons) as gallons, COUNT(driver) as pickups,date_of_pickup,COUNT(DISTINCT route_id) as routes FROM $data_table WHERE driver = $pickup[user_id] && ( (MONTH(date_of_pickup) = $last_month && YEAR(date_of_pickup) = $current_year) )";
         
         $query2 ="SELECT SUM(inches_to_gallons) as gallons, COUNT(driver) as pickups,date_of_pickup,COUNT(DISTINCT route_id) as routes FROM $data_table WHERE driver = $pickup[user_id] && ( (MONTH(date_of_pickup) = $current_month && YEAR(date_of_pickup) = $current_year) ) ";
         
         $query_year = "SELECT SUM(inches_to_gallons) as gallons, COUNT(driver) as pickups,date_of_pickup,COUNT(DISTINCT route_id) as routes FROM $data_table WHERE driver = $pickup[user_id] && YEAR(date_of_pickup) = $current_year";
         
         $query_total = "SELECT SUM(inches_to_gallons) as gallons, COUNT(driver) as pickups,date_of_pickup,COUNT(DISTINCT route_id) as routes FROM $data_table WHERE driver = $pickup[user_id]  ";
        
        echo "<tr>";
            echo"<td>$pickup[user_id]</td>";
            echo "<td>".uNumToName($pickup['user_id'])."</td>";
           
             $this_m = $db->query($query2);
           if(count($this_m)>0){
                foreach($this_m as $current){
                    echo "<td>".round($current['gallons'],2)."</td>";
                    echo "<td>$current[pickups]</td>";
                    echo "<td>$current[routes]";
                }
           }else {
                echo "<td>0</td>";
                echo "<td>0</td>";
                echo "<td>0</td>";    
            }
           
            $last_m = $db->query($query1);
            
            if(count($last_m)>0){
                foreach($last_m as $last){
                    echo "<td>".round($last['gallons'],2)."</td>";
                    echo "<td>$last[pickups]</td>";
                    echo "<td>$last[routes]";
                }
            }else {
                echo "<td>0</td>";
                echo "<td>0</td>";
                echo "<td>0</td>";    
            }
            
            
             
            $this_y = $db->query($query_year);
          
           if(count($this_y)>0){
                foreach($this_y as $year){
                    echo "<td>".round($year['gallons'],2)."</td>";
                    echo "<td>$year[pickups]</td>";
                    echo "<td>$year[routes]";
                } 
           } else {
                echo "<td>0</td>";
                echo "<td>0</td>";
                echo "<td>0</td>";    
           }
          $total = $db->query($query_total);
          
          if(count($total)>0){
                foreach($total as $all){
                    echo "<td>".round($all['gallons'],2)."</td>";
                    echo "<td>$all[pickups]</td>";
                    echo "<td>$all[routes]";
                } 
           } else {
                echo "<td>0</td>";
                echo "<td>0</td>";
                echo "<td>0</td>";    
           }
          
          
           
        echo "</tr>";
    }
}

?>
</tbody>
</table>