<?php
$schedule_table = $dbprefix."_scheduled_routes";
$account_table = $dbprefix."_accounts";
$addtn ='';
if($person->isFriendly()){
    $addtn = " AND $account_table.friendly like '%$person->first_name%'";
} else if($person->isCoWest()){
    $addtn = " AND $account_table.division IN( 15,24,30,31,32,33)";
}

if( isset($_POST['search_now'])  ){ //********************  POSTED SEARCH  ********************************
 
   foreach($_POST as $name=>$value){
            switch($name){            	
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
                case "from":
                    if(strlen($value)>0 && isset($value)){
                        $arrFields[] = " state_date <= '$value'";   
                    }
                    break;
                case "to":
                    if(strlen($value)>0 && isset($value)){
                        $arrFields[] = " expires >= '$value'";
                    }
                    break; 
                case "fac1":
                    if(isset($name)){               
                    $facField[]=" division =".$value; }
                    break;
                case "alluc":
                    $facField[] = " division IN (, 'new')";
                    break;
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
                case "fac22":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
               case "fac21":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;
                    
                case "fac23":
                if(isset($name)){                
                    $facField[]=" division =".$value;}
                    break;              
            }
        }
         
        $criteria1 = "";
        $criteria2 = ""; 
         
        if(!empty($arrFields)){
             $criteria1 = " AND  ". implode (" AND ",$arrFields)." ";             
        }
        
        if(!empty($facField)) {
            $criteria2 = " AND ( ".implode (" OR ", $facField)." )";
        }
         
        $ask = "SELECT $schedule_table.scheduled_start_date, 
                            $schedule_table.route_status,
                            $schedule_table.code_red, 
                            $schedule_table.schedule_id,
                            $schedule_table.account_no,
                            $schedule_table.cs_reason,
                            $schedule_table.created_by,
                            $schedule_table.facility_origin,
                            datediff( $schedule_table.scheduled_start_date, NOW( ) ) AS diff,
                            $account_table.status,
                            $account_table.account_ID,
                            $account_table.city,
                            $account_table.address,
                            $account_table.state,
                            $account_table.zip
                             FROM $schedule_table LEFT JOIN $account_table ON $schedule_table.account_no = $account_table.account_ID WHERE route_status='scheduled' ".$criteria1." ".$criteria2.' '.$addtn;  
        echo $ask;
        $full =  $db->query($ask);
                             
        //var_dump($full);

}
else { // ******************** DEFAULT ********************************
        
        
        /**/$ask = "SELECT $schedule_table.scheduled_start_date, 
                            $schedule_table.route_status,
                            $schedule_table.code_red, 
                            $schedule_table.schedule_id,
                            $schedule_table.account_no,
                            $schedule_table.cs_reason,
                            $schedule_table.created_by,
                            $schedule_table.facility_origin,
                            datediff( $schedule_table.scheduled_start_date, NOW( ) ) AS diff,
                            $account_table.status,
                            $account_table.account_ID,
                            $account_table.city,
                            $account_table.address,
                            $account_table.state,
                            $account_table.zip
                             FROM $schedule_table LEFT JOIN $account_table ON $schedule_table.account_no = $account_table.account_ID WHERE route_status='scheduled' $addtn";
        echo $ask;
        $full = $db->query($ask);        
        
}
 //**********************************************************************
 //var_dump($full);
 
  
?>

   
<style type="text/css">
.tableNavigation {
    width:1000px;
    text-align:center;
    margin:auto;
    overflow-x:auto;
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



input[type=checkbox]{
    width:10px;
}
</style>
   
<script>

$(document).ready(function(){
   $('#myTable').dataTable({
        "order": [ 3, 'asc' ],
        "lengthMenu": [ [10, 25, 50,100,150, -1], [10, 25, 50,100,150, "All"] ]
   }); 
});
</script>       
<table style="width: 100%;margin:auto;border-collapse:collapse;cell-padding:0px 0px 0px 0px;table-layout:fixed;"  id="myTable" >
    <thead>
        <tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">
        <th class="cell_label" style="width: 15px;">&nbsp;</th>
        <th class="cell_label" style="width: 10px;">&nbsp;</th>
        
        <th class="cell_label" style="width: 20px;">C\R</th>
        
        <th class="cell_label">Status</th>
        
        
        
        <th class="cell_label"><span title="Route Id">ID</span></th>
        
        <th class="cell_label">Scheduled</th>
        
        <th class="cell_label" style="width: 150px;">Name</th>
        <td class="cell_label">Address</td>
        <th class="cell_label">City</th>
        
        <th class="cell_label" style="width: 15px;">State</th>
        
        <th class="cell_label">Zip</th>
        
        <th class="cell_label">Facility</th>
        <th class="cell_label">Created By</th>
        <th class="cell_label">CS Reason</th>
        <th class="cell_label">Grease Route</th> 
        </tr>
    
    </thead>

<tbody>
    <?php

if( count($full)>0 ){
    $account = new Account();
    foreach($full as $scheduled){        
        echo "<tr id='p$scheduled[schedule_id]' class='kl'>
                <td style='width:15px;'><img src='img/delete-icon.jpg' class='delschedule' rel='".$scheduled['schedule_id']."' style='cursor:pointer;'/></td>
                <td style='vertical-align:middle;'>
                    <input type='checkbox' style='cursor:pointer;width:10px;height:50px;z-index:9999;' xlr='".$scheduled['account_no']."' rel='".$scheduled['schedule_id']."' type='checkbox' class='setThisRoute'  title='schedule id:$scheduled[schedule_id] | account id:".$scheduled['account_no']." '/>
                </td>
                <td style='width:20px;'>".code_red($scheduled['code_red'])."</td>
                <td>".statusColors($scheduled['route_status'],$scheduled['account_no'])."</td>";
                $tod = explode (" ",$scheduled['scheduled_start_date']);
                echo "
                <td>";  
                     echo $scheduled["schedule_id"];
                 echo "</td>
                <td>$tod[0]</td>
                <td style='width:150px;'>".account_NumtoName($scheduled['account_no'])."</td>
                <td>$scheduled[address]</td>
                <td>".$scheduled['city']."</td>
                <td style='width:15px;'>".$scheduled['state']."</td>
                <td>".$scheduled['zip']."</td>
                <td>".numberToFacility($scheduled['facility_origin'])."</td>
                <td>".uNumToName($scheduled['created_by'])."</td>
                <td>$scheduled[cs_reason]</td>
                <td>$scheduled[grease_route_id]</td>
                ";
            echo "</tr>";
    }
}
?>
</tbody>



</table>

<div id="debug"></div>


<script>

function  count_col(){
    $("#myTable tr td:nth-child(1)").each(function (i) {
          
    });
}




function numberColumnJq(){
    $("#myTable tr td:nth-child(4)").each(function () {
        
        var sched_id = $(this).html();
        var row =$(this).closest("tr");
        $.ajax({
            type: "POST",
            url: "remove_unavail.php",
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

$(".paginate_button").click(function(){//when clicking on a pagination check dynamically if routes were routed
    numberColumnJq();
});


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
         $("form#schedtoikg").submit();
    }
    
    e.preventDefault();
});



$("#reset").click(function(){   
   window.location='scheduling.php?task=schoipu'; 
});

$(".delschedule").click(function(){
   if(confirm("Are you sure you want to delete this stop?")){
    $.get("adminDeleteSched.php",{sched_id: $(this).attr('rel') },function(){
        location.reload();
    }); 
   }
});

</script>