<?php

$account_table = $dbprefix."_accounts";
$utility_table = $dbprefix."_utility";
$addtn ='';
/*
if($person->isFriendly()){
    $addtn = " AND $account_table.friendly like '%$person->first_name%'";
} else if($person->isCoWest()){
    $addtn = "AND $account_table.division = 15";
}else{
    $addtn =" AND $account_table.division = $person->facility";
}
*/



if(isset($_POST['search_now'])){
     foreach($_POST as $name=>$value){
            switch($name){    
                case "timesearch":
                    if(strlen($value)>0){
                        $arrFields[] = " date_of_service like '%".$value."%'";
                    }
                    break;
                case "id":
                    if(strlen($value)>0){
                        $arrFields[] = " schedule_id=".$value;
                    }
                    break;
                case "name":
                    if(strlen($value)>0){
                        $arrFields[] = " name like '%".$value."%'";
                    }
                    break;
                case "address":
                    if(strlen($value)>0){
                        $arrFields[] = " address='".$value."'";
                    }
                    break;
                case "city":
                    if(strlen($value)>0){
                        $value = str_replace(" ","%",$value);
                        $arrFields[] = " city like '%".$value."%'";
                    }
                    break;
                case "state":
                    if(strlen($value)>0){
                        $arrFields[] = " state = '".$value."'";
                    }
                    break;   
                case "zip":
                    if(strlen($value)>0){
                        $arrFields[] = " zip = $value";
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
            }
        }
         
        $criteria1 = "";
        $criteria2 = "";
         
        if(!empty($arrFields)){
             $criteria1 = " AND ( ". implode (" AND ",$arrFields)." )";             
        }
        
        if(!empty($facField)) {
            $criteria2 = " AND ( ".implode (" OR ", $facField)." )";
        }
        
        $search_string = "SELECT  $utility_table.*,$account_table.address, $account_table.division, $account_table.name ,$account_table.city, $account_table.state, $account_table.zip,$account_table.account_ID,$account_table.state_date,$account_table.expires FROM $utility_table INNER JOIN $account_table  ON $utility_table.account_no = $account_table.account_ID WHERE (route_status ='' || route_status='scheduled' || route_status='new') $addtn". $criteria1." ".$criteria2 ;
        //echo $search_string;
         $check  = $db->query($search_string);
        
} else if( isset($_POST['scrs']) ){
    $search_string = "SELECT  $utility_table.*,$account_table.address,$account_table.division, $account_table.name,$account_table.city, $account_table.state, $account_table.zip FROM $utility_table INNER JOIN $account_table  ON $utility_table.account_no = $account_table.account_ID WHERE (route_status ='' || route_status='scheduled' || route_status='new') && $utility_table.code_red =1 $addtn" ;
    $check  = $db->query($search_string);
} else  {
    $search_string = "SELECT  $utility_table.*,$account_table.address, $account_table.division, $account_table.name,$account_table.city, $account_table.state, $account_table.zip FROM $utility_table INNER JOIN $account_table  ON $utility_table.account_no = $account_table.account_ID WHERE (route_status ='' || route_status='scheduled' || route_status='new') $addtn" ;
    echo $search_string;
    $check  = $db->query($search_string);
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

<table  style="width: 100%;margin:auto;" id="myTable" >
<thead>
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
    <td class="cell_label">&nbsp;</td>
    <td class="cell_label">&nbsp;</td>
    <td class="cell_label" style='width:50px;'>Code Red</td>
    <td class="cell_label">Status</td>
    <td class="cell_label">ID</td>
    <td class="cell_label"><span title="Time Of Day">T.O.D</span></td>
    
    <td class="cell_label">Address</td>
    <td class="cell_label">City</td>
    <td class="cell_label">State</td>
    <td class="cell_label">Zip</td>
    <td class="cell_label">Scheduled</td>
    <td class="cell_label">Name</td>
    
    <td class="cell_label">Facility</td>
</tr>
</thead>
<tbody>
<?php
if(count($check)>0){
    $acnt_info = new Account();
    foreach($check as $utility){
        $newtod = explode(" ",$utility['date_of_service']);
        echo "<tr>
            <td><img src='img/delete-icon.jpg' style='cursor:pointer;' rel='$utility[utility_sched_id]' class='delutil'/></td>       
            <td><input type='checkbox' class='setThisRoute' xlr='".$utility['account_no']."' rel='".$utility['utility_sched_id']."' title='account | ".$utility['account_no']." - sched_id | ".$utility['utility_sched_id']."  '/></td>
            <td>".code_red($utility['code_red'])."</td>
            <td>$utility[route_status]</td>
            <td>"; 
           echo "$utility[utility_sched_id]"; 
             echo "</td>
            <td>$newtod[1]</td>
            <td>$utility[address]</td>
            <td>$utility[city]</td>
            <td>$utility[state]</td>
            <td>$utility[zip]</td>
            <td>$newtod[0]</td>
             
            <td>".account_NumtoName($utility['account_no'])."</td>
           
            <td>".numberToFacility($utility['division'])."</td>
        </tr>";
    }
}
?>
</tbody>
</table>
<script>
$("table").on("click",".setThisRoute",function(){    
  
   if($(this).is(":checked")  ){
        $(".schecheduled_ids").val( $(".schecheduled_ids").val() + $(this).attr('rel')+"|"  );
        $(".accounts_checked").val( $(".accounts_checked").val() + $(this).attr('xlr')+"|" );
    }else {
        
        var replace =$(this).attr('rel')+"|";
        var newVal =  $(".schecheduled_ids").val().replace(replace,"");
        $(".schecheduled_ids").val(newVal);
        var replace1 = $(this).attr('xlr')+"|";
        var newVal2 =  $(".accounts_checked").val().replace(replace1,"");
        $(".accounts_checked").val(newVal2);
    }
     
});

$(".existing").click(function(){
    $(".new").prop('checked', false);
});

$(".new").click(function(){
    $(".existing").prop('checked', false);
});

$("#schedule_us").click(function(e){
    if($(".existing").is(":checked")){
        $("form#add_to_form").submit();  
    }
    
    
    if( $(".new").is(":checked") ){
         $("form#newutil").submit();
    }
    
    e.preventDefault();
});

$("#todsearch").timepicker();

$("#reset").click(function(){   
   window.location='scheduling.php?task=suc'; 
});

function numberColumnJq(){
    $("#myTable tr td:nth-child(4)").each(function () {        
        var sched_id = $(this).html();
        var row =$(this).closest("tr");
        $.ajax({
            type: "POST",
            url: "remove_util_unavail.php",
            data: { sched:sched_id }
            })
            .done(function( msg ) {
                 if(msg == "unavai"){// check if route has been routed, if it has been, dynamically remove it from the visible list
                    $(row).remove();
                    $("#debug").append("routed and removed - "+ sched_id+"<br/>");
                }
        });
    });
}

setInterval("numberColumnJq();",5000);

$(".delutil").click(function(){
    $.get("adminDelUtil.php",{ util_id: $(this).attr('rel') },function(data){
        alert("Stop deleted "+data);
        location.reload();
    });
});

</script>