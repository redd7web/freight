<?php
include "protected/global.php";

function number_to_role($num){
    switch($num){
        case 1:
        return "IT";
        break;
        case 2:
        return "ADMIN";
        break;
        case 3:
        return "TECH";
        break;
    }
}
if(isset($_POST['searched'])){
    foreach($_POST as $name=>$value){
        switch($name){
            case "division":
                if($_POST['division'] !="-" ){
                    $arrFields[] = " division = '$value'";
                }
            break;
            case "group":
                if($_POST['group'] !="-") {
                    $arrFields[]= " group =  $value";
                }
            break;
            case "status":
                if($_POST['status'] !="-"){
                    $arrFields[] = " status = '$value'";
                }
            break;
            case "ass_to":
                if($_POST['ass_to'] !="-"){
                    $arrFields[]= " to_user =$value";
                }
            break;
            case "ass_from":
                if($_POST['ass_from'] !="-"){
                    $arrFields[] = " from_user =$value";
                }
            break;
            case "tit_contains":
                if(isset($_POST['tit_contains']) && strlen($_POST['tit_contains'])>0){
                    $nv = str_replace(" ","%",$value);
                    
                    
                    $arrFields[] = "title like '%$nv%' OR modifier like '%$nv%'";
                }
            break;
            
            case "date_end":
                if(isset($_POST['date_end']) AND strlen($_POST['date_end'])>0  ){
                    $arrFields[] =" date >= '$value' ";
                }
            break;
            case  "date_start":
                if(isset($_POST['date_start']) AND strlen($_POST['date_start'])>0  ){
                    $arrFields[] =" date <= '$value' ";
                }
            break;
        }
    }
    
    $search_string ="";
    if(!empty($arrFields)){
        $search_string = " AND ".implode(" AND ",$arrFields);
    }
    echo "SELECT * FROM notes WHERE 1".$search_string."<br/>";
    $result = $db->query("SELECT * FROM notes WHERE 1".$search_string);    
} else if(isset($_GET['search'])){
    switch($_GET['search']){
                case "iwp":
                   $string = "SELECT * FROM notes WHERE role =1";
                break;
                case "oil":
                    $string = "SELECT * FROM notes WHERE division ='oil'";
                break;
                case "bakery":
                    $string = "SELECT * FROM notes WHERE division ='bak'";
                break;
                case "organic":
                    $string = "SELECT * FROM notes WHERE division ='org'";
                break;
                case "it":
                   $string = "SELECT * FROM notes WHERE role=1";
                break;
                case "admin":
                    $string = "SELECT * FROM notes WHERE role=2";
                break;
                case "tech":
                    $string = "SELECT * FROM notes WHERE role=3";
                break;
            }
            echo $string."<br/>";
    $result = $db->query($string);
} else {
    $result = $db->query("SELECT * FROM notes ORDER BY date DESC");
}

?>

<html>
<head>
    <meta charset="UTF-8" />
    <title>IWP TICKET QUEUES</title>
    
    <?php 
    include "source/scripts.php";
    include "source/css.php";
    function listx(){
        global $db;
        $k = $db->get("sludge_users","user_id,first,last");
        foreach($k as $user){
         echo "<option value='$user[user_id]'>$user[last], $user[first]</option>";   
        }
    }
    
    ?>
<script>

$(document).ready(function(){
   $('#myTable').dataTable({
        "lengthMenu": [ [10, 25, 50,100,150, -1], [10, 25, 50,100,150, "All"] ]
   }); 
   
   Shadowbox.init();
});
</script>
<style>
body{
    background:white;
}
</style>    
</head>
<body>
<form action="ticket_queue.php" method="post">
<table style="width: 100%;margin-bottom:10px;">
<!--
<tr>
    <td>Division: <select name="division" id="division"><option value="-">-</option><option value="oil">Oil</option><option value="bak">Bakery</option><option value="org">Organic</option></select></td>
    <td>Group: <select name="group" id="group"><option value="-">-</option><option value="1">IT</option><option id="2">Admin</option><option value="3">Tech</option></select></td>
    <td>Status: <select id="status" name="status"><option value="-">-</option><option value="pending">Pending</option><option value="completed">Completed</option><option value="closing">Closing</option></select></td>
    <td>Assigned By: <select name="ass_from"><option value="-">-</option><?php  
        listx();    
        
    ?> </select></td>
    <td>Assigned To:<select name="ass_to"><option value="-">-</option><?php  
       
        listx();
    ?> </select></td>
    <td>Title Contains <input value="" placeholder="phrase to search" name="tit_contains" type="text"/></td>
    <td>From: <input id="date_end" name="date_end" placeholder="From" type="text"/></td>
    <td>To: <input id="date_start" value="" placeholder="To:" name="date_start" type="text"/></td>
    
</tr>
-->
<tr>
<td colspan="10" style="text-align: center;">
<div style="width:100%;height:102px;background: url(img/ticket/headerstrip.jpg) repeat-x left top;">
    <div id="textsearhc" style="float: left;width:449px;height:64px;background: url(img/ticket/topleft.jpg) no-repeat left top;">
    <input type="text" style="width: 335px;height:35px;margin-left:55px;margin-top:15px;background:transparent;border:0px solid #bbb;" value="<?php if(isset($_POST['tit_contains'])){ echo $_POST['tit_contains'];} else { echo "";} ?>" placeholder="Title Contains or ticket number" name="tit_contains" id="tit_contains"/>
    </div>
    
    <div id="iwp" style="float: left;width:108px;height:64px;cursor:pointer;">
        <a href="ticket_queue.php?search=iwp"><img src="img/ticket/th1.jpg"/></a>    
    </div>
    <div id="oil" style="float: left;width:59px;height:64px;cursor:pointer;">
        <a href="ticket_queue.php?search=oil"><img src="img/ticket/th2.jpg"/></a>
    </div>
    <div id="bakery" style="float: left;width:77px;height:64px;cursor:pointer;">
        <a href="ticket_queue.php?search=bakery"><img src="img/ticket/th3.jpg"/></a>    
    </div>
     <div id="orgnics" style="float: left;width:77px;height:64px;cursor:pointer;">
        <a href="ticket_queue.php?search=organic"><img src="img/ticket/th4.jpg"/></a>    
    </div>
    <div id="question" style="float: left;width:34px;height:64px;cursor:pointer;">
        <a href="ticket_queue.php?search=it"><img src="img/ticket/th5.jpg"/></a>    
    </div>
    <div id="admin" style="float: left;width:55px;height:64px;cursor:pointer;">
        <a href="ticket_queue.php?search=admin"><img src="img/ticket/th6.jpg"/></a>    
    </div>
    
    <div id="tech" style="float: left;width:35px;height:64px;cursor:pointer;">
        <a href="ticket_queue.php?search=tech"><img src="img/ticket/th7.jpg"/></a>    
    </div>
    <div id="bottom" style="width:100%;margin:auto;height:38px;float:left;">
    <table style="width: 100%;"><tr><td style="text-align: center;vertical-align:middle;height:34px;">
    <?php
        if(isset($_GET['search'])){
            switch($_GET['search']){
                case "iwp":
                    echo "IWP";
                break;
                case "oil":
                    echo "OIL";
                break;
                case "bakery":
                    echo "BAKERY";
                break;
                case "organic":
                    echo "ORGANICS";
                break;
                case "it":
                    echo "IT";
                break;
                case "admin":
                    echo "ADMINSTRATION";
                break;
                case "tech":
                    echo "TECH";
                break;
            }
        }
    ?>
    </td></tr></table>
    </div>
</div>
</td>
</tr>
<tr><td colspan="10"><a href="ticket_queue.php">Default View</a>&nbsp;&nbsp;<input type="submit" value="Search Now" name="searched"/></td></tr>
</table>
</form>
<table style="width:100%;" id="myTable">
    <thead>
        <tr>
            <td class="cell_label">Ticket ID</td>
            <td class="cell_label">title</td>
            <td class="cell_label">date</td>
            <td class="cell_label">division</td>
            <td class="cell_label">Group</td>
            <td class="cell_label">Status</td>
            <td class="cell_label">Priority</td>
            <td class="cell_label">Assigned to</td>
            <td class="cell_label">Created By</td>
        </tr>
    </thead>
    <tbody>
    <?php 
        if(count($result)>0){
            foreach($result as $ticket){
                echo "<tr>";
                echo"<td><img src=img/reply-icon.gif style='cursor:pointer;width:25px;height:25px;' rel='$ticket[modifier]' class='reply' />&nbsp;$ticket[modifier]</td>";
                echo "<td>$ticket[title]</td>";
                echo "<td>$ticket[date]</td>";
                echo "<td>$ticket[division]</td>";
                echo "<td>".number_to_role($ticket['role'])."</td>";
                echo "<td>$ticket[status]</td>";
                echo "<td>$ticket[priority]</td>";
                echo "<td>"; 
                    if($ticket['division'] == "bak"){
                        $ko = $db->query("SELECT bakery.bakery_users.*  FROM bakery.bakery_users WHERE user_id=$ticket[to_user]");                        
                        echo $ko[0]['first']." ".$ko[0]['last'];
                    } else {
                        echo uNumToName($ticket['to_user']);
                    }
                
                echo "</td>";
                echo "<td>"; 
                    if($ticket['division'] == "bak"){
                        $ko = $db->query("SELECT bakery.bakery_users.*  FROM bakery.bakery_users WHERE user_id=$ticket[from_user]");                        
                        echo $ko[0]['first']." ".$ko[0]['last'];
                    } else {
                        echo uNumToName($ticket['from_user']);
                    }
                
                echo "</td>";
                echo "</tr>";
            }
        }
    ?>
    </tbody>
</table>
<script>
$("#date_end").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
$("#date_start").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});


var wordlist = [
<?php



$results = $db->get("notes","modifier");
if(count($results) != 0) {
    str_replace(" ","",$results);
    foreach ($results as $key) {
        
        $usr[] ="$key[modifier]";
    }
    echo implode("," ,$usr);
}
?>
];



$("#to").autocomplete({
     minLength: 3,
     source: function(req, responseFn) {
          var matches = new Array();
          var needle = req.term.toLowerCase();            
          var len = wordlist.length;
          for(i = 0; i < len; ++i){
              var haystack = wordlist[i].toLowerCase();
              if(haystack.indexOf(needle) === 0 || haystack.indexOf(" " + needle) != -1)
              {
                  matches.push(wordlist[i]);
              }
          }
          var test = "United States";//results containing the pattern united      
          var resultArray = matches.filter(function(d){
                return /United States/.test(d);
          });
          
          
          var second = matches.filter(function(d){ //results not containing United States
                return !/United States/.test(d);
          });
          
          //results containing united states first, then others
          var newlist = resultArray.concat(second);
          
          responseFn(  newlist);
          
    },
    select: function(event, ui) {
        var origEvent = event;
        while (origEvent.originalEvent !== undefined){
            origEvent = origEvent.originalEvent;
        }
        //console.info('Event type = ' + origEvent.type);
        //console.info('ui.item.value = ' + ui.item.value);
        if (origEvent.type == 'click'){
            var k = ui.item.value;
            $("input#tit_contains").val(k);
        } else {
            var k = ui.item.value;
           
            $("input#tit_contains").val(k);
        }
    }
}); 


$(".reply").click(function(){
    Shadowbox.open({
        content: 'viewticket.php?id='+$(this).attr('rel'),
        player:"iframe",
        width:900,
        height:500,
        title:"View Ticket"
    });
});
</script>
</body>

</html>