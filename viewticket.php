<?php
include "protected/global.php";
ini_set("display_errors",1);

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

if(isset($_GET['search'])){
     $search="";
     switch($_GET['search']){
                case "iwp":
                   $search = " AND role = 1";
                break;
                case "oil":
                    $search = " AND division = 'oil'";
                break;
                case "bakery":
                    $search = " AND division = 'bak'";
                break;
                case "organic":
                    $search = " AND division = 'org'";
                break;
                case "it":
                    $search = " AND role =1";
                break;
                case "admin":
                    $search = " AND role =2";
                break;
                case "tech":
                    $search = " AND role =3";
                break;
            }
     
     
     $x = $db->query("SELECT * FROM ( SELECT * FROM notes UNION ALL SELECT * FROM notes_reply WHERE modifier='$_GET[id]'  $search) subquery WHERE modifier='$_GET[id]' $search");
} else if( isset($_POST['searched']) ){
    $new_buf = str_replace(" ","%",$_POST['tit_contains']);
    $x = $db->query("SELECT * FROM ( SELECT * FROM notes UNION ALL SELECT * FROM notes_reply WHERE modifier='$_GET[id]'  AND title like '%$new_buf%' OR modifier like '%$new_buf%') subquery WHERE modifier='$_GET[id]' AND title like '%$new_buf%' OR modifier like '%$new_buf%'");
} else {
    $x = $db->query("SELECT * FROM ( SELECT * FROM notes UNION ALL SELECT * FROM notes_reply WHERE modifier='$_GET[id]' ) subquery WHERE modifier='$_GET[id]'");    
}




?>
<html>
<head>
    <meta charset="UTF-8" />
    <?php 
        include "source/scripts.php";
        include "source/css.php";
    ?>
    <script type="text/javascript" src="plugins/nicedit/nicEdit.js"></script>
    <script type="text/javascript">
    	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
    </script>
    <link rel="stylesheet" href="plugins/nicedit/niceEdit.css" />
    <style type="text/css">
    body{
        padding:10px 10px 10px 10px;
    }
     .nicEdit-main {
         background:white;
         height:230px;   
    }
  
    #sb-container{
        display:none;
    }
    </style>
</head>
<body>
<table style="width: 100%;margin-bottom:20px;">
<tr>
<td colspan="10" style="text-align: center;">
<div style="width:100%;height:102px;background: url(img/ticket/headerstrip.jpg) repeat-x left top;">
    <div id="textsearhc" style="float: left;width:449px;height:64px;background: url(img/ticket/topleft.jpg) no-repeat left top;">
    <form action="viewticket.php?id=<?php echo $_GET['id']; ?>" method="post">
    <input type="text" style="width: 335px;height:35px;margin-left:55px;margin-top:15px;background:transparent;border:0px solid #bbb;" value="<?php if(isset($_POST['tit_contains'])){ echo $_POST['tit_contains'];} else { echo "";} ?>" placeholder="Title Contains or ticket number" name="tit_contains" id="tit_contains"/>
    </div>
    <div id="searchbutton" style="width: 70px;height:64px;float:left;">
        <input type="submit" value="Search" name="searched"/>
        </form><br />
        <a href="viewticket.php?id=<?php echo $_GET['id']; ?>">Reset</a>
    </div>
    
    <div id="oil" style="float: left;width:59px;height:64px;cursor:pointer;">
        <a href="viewticket.php?search=oil&id=<?php echo $_GET['id']; ?>"><img src="img/ticket/th2.jpg"/></a>
    </div>
    <div id="bakery" style="float: left;width:77px;height:64px;cursor:pointer;">
        <a href="viewticket.php?search=bakery&id=<?php echo $_GET['id']; ?>"><img src="img/ticket/th3.jpg"/></a>    
    </div>
     <div id="orgnics" style="float: left;width:77px;height:64px;cursor:pointer;">
        <a href="viewticket.php?search=organic&id=<?php echo $_GET['id']; ?>"><img src="img/ticket/th4.jpg"/></a>    
    </div>
   
    <div id="admin" style="float: left;width:55px;height:64px;cursor:pointer;">
        <a href="viewticket.php?search=admin&id=<?php echo $_GET['id']; ?>"><img src="img/ticket/th6.jpg"/></a>    
    </div>
    
    <div id="tech" style="float: left;width:35px;height:64px;cursor:pointer;">
        <a href="viewticket.php?search=tech&id=<?php echo $_GET['id']; ?>"><img src="img/ticket/th7.jpg"/></a>    
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

</table>


 <?php 
        if(count($x)>0){
            $bg = 1;
            foreach($x as $ticket){
                if($bg%2 == 0){
                    $color = "#CCCCCC";
                }  else {
                    $color = "white";
                }
                $bg++;
                ?>
                    <table style="width:100%;margin-bottom:10px;background:<?php echo $color; ?>" class="myTable">
                        <thead>
                            <tr>
                               
                                <td class="cell_label">title</td>
                                <td class="cell_label">date</td>
                                <td class="cell_label">division</td>
                                <td class="cell_label">Group</td>
                                <td class="cell_label">Status</td>
                                <td class="cell_label">Assigned to</td>
                                <td class="cell_label">Created By</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            echo "<tr>";
                            echo "<td>$ticket[title]</td>";
                            echo "<td>$ticket[date]</td>";
                            echo "<td>$ticket[division]</td>";
                            echo "<td>".number_to_role($ticket['role'])."</td>";
                            echo "<td>$ticket[status]</td>";
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
                            echo "<tr><td style='text-align:right;vertical-align:top;' >Comment:&nbsp;</td><td colspan='6' style='padding:5px 5px 5px 5px;text-align:left;vertical-align:top;'>$ticket[notes]</td>";
                        ?>
                        </tbody>
                    </table>
                    <?php
                    }
        }
    ?>
    
    <table style="width: 100%;margin-top:20px;">
    <tr><td colspan="2"><input type="submit" id="stat" value="Mark ticket as completed" rel="<?php echo $_GET['id']; ?>" /></td></tr>
    <tr><td style="text-align: center;">REPLY:&nbsp;<input type="text" id="to" value="" placeholder="To:" /></td><td>Subject:&nbsp;<input value="" placeholder="Subject" type="text" name="subj" id="subj"/></td></tr>
    <tr><td style="text-align: center;" colspan="2"><textarea id="edit" style="width:99%;height:230px;border:0px solid red;" name="area2" placeholder="Describe your issue here"></textarea></td></tr>
    <tr><td style="text-align: right;" colspan="2"><input type="hidden" name="user_id" id="user_id" readonly=""/>
    <input type="hidden" id="division" name="division" value="<?php echo $x[0]['division']; ?>"  readonly=""/><input type="submit" id="reply" value="Reply" /></td></tr>
    </table>
    
    <script>
    
    $("#stat").click(function(){
        
       if(confirm("COMPLETE TICKET ?")){
            $.get("complete_ticket.php",{mod:$(this).attr('rel')},function(data){
                window.location='viewticket.php?id=<?php echo $_GET['id']; ?>'; 
           }); 
       } else {
        
       } 
        
    });
    
    $("#reply").click(function(){
        $.post("reply_ticket.php",{
                to:$("input#user_id").val(),
                from:<?php 
                if($x[0]['division']=="oil"){
                    echo $_SESSION['freight_id'];
                } else {
                    echo $_SESSION['freight_id'];
                }
                 ?>, 
                notes:$(".nicEdit-main").html(),
                subject:$("input#subj").val(),
                modifier:<?php echo $_GET['id']; ?>,
                division :$("input#division").val()
            },function(data){
                alert(data);
                $("#body").append(data);    
                //window.location='viewticket.php?id=<?php echo $_GET['id']; ?>'; 
        });    
    });
    
    var wordlist = [
    <?php
    
    
    if($x[0]['division']== "bak"){
        $results = $db->query("SELECT bakery.bakery_users.* FROM bakery.bakery_users");
    } else {
        $results = $db->get($dbprefix."_users","first,last,user_id");    
    }
    
    if(count($results) != 0) {
        str_replace(" ","",$results);
        foreach ($results as $key) {
            $icao = str_replace("\"","",$key['first']);
            $icao = trim($icao);
            $air = str_replace("\"","",$key['last']);
            $air = trim($air);
            $usr[] =" \"$icao $air $key[user_id]\"";
        }
        echo implode("," ,array_filter($usr));
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
                buffer = k.split(" ");     
                var o,y;
                y= buffer[0]+" "+buffer[1];
                o = y.toUpperCase();   
                $("input#to").val(o);              
                $("input#to").val(o);
                $("input#user_id").val(buffer[2]);
            } else {
                var k = ui.item.value;
                buffer = k.split(" ");     
                var o,y;
                y= buffer[0]+" "+buffer[1];
                o = y.toUpperCase();   
                $("input#to").val(o);              
                $("input#to").val(o);
                $("input#user_id").val(buffer[2]);
            }
        }
    }); 
</script>
</body>
</html>
