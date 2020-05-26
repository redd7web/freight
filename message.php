<?php
include "protected/global.php";
if(isset($_GET['account'])){
    $acnt = new Account($_GET['account']);
}
$act[]="";
$usr[]="";
$ac = $db->get($dbprefix."_accounts");
if(count($ac) !=0){   
    foreach($ac as $value){
        $act[] = "\"".htmlspecialchars($value['name'])." - $value[account_ID]\" ";
    }    
}

//var_dump($act);

?>
<meta charset="UTF-8"/>
<link rel="stylesheet" href="css/style.css"/>
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>

<style type="text/css">
body{
    margin:0px 0px 0px 0px;
    padding:10px 10px 10px 10px;
    background:rgb(250, 250, 250);
    font-family: arial;
}
</style>

<p style="text-align: center;width:100%;">
<h1 style="width:100%;margin:auto;text-align:center;text-transform:uppercase;">Service Issues</h1>
<p style="margin: auto;width:150px;">Add a Message or Issue</p>

Please do not use this to add to an existing issue or discussion.
This is for starting a new topic.
For an existing discussion choose "Back to List" above and use the View button for that topic. <br /></p>
<input type="text" style="width: 99%;" id="to"  placeholder="To:"/>
<br /><br />
<input type="text" style="width: 99%;" placeholder="subject" id="subject" name="subject" /><br /><br />



Select Account: <input type="text" placeholder="Account Name"  <?php
if(isset($_GET['account'])){
    echo "value=\"$acnt->name_plain - $acnt->acount_id\"";
}

 ?> name="acntname" id="acntname"/>
<input type="hidden" name="user_id" id="user_id" readonly=""/>
<input type="hidden"  placeholder="accountnumber" readonly="" name="idanumber" <?php
if(isset($_GET['account'])){
    echo "value='$acnt->acount_id'";
}

 ?> id="anumber"/>
<br /><br />
Priority Level: <select id="priority_id" name="priority_id">
<option value="20">Normal</option>

<option value="10">Urgent</option>
</select>
<br/><br />
Issue: <select id="message_category_id" name="message_category_id">
<option value="1">Needs Cancelation letter</option>
<option value="2">Damaged Tote</option>
<option value="3">Need GCP Poster</option>

<option value="4">Needs To Be Swapped/Dirty Tote</option>
<option value="5">Oil Theft</option>

<option value="6">Competitor Onsite</option>
<option value="7">Broken Lock</option>
<option value="8">Out Of Business</option>
<option value="9">Container Missing</option>


<option value="60">Location Needs Attention</option>

<option value="70">Customer Request</option>

<option value="72">In House Request</option>

<option value="140">Sales Team</option>

<option value="90">Competitor On Site</option>

<option value="100">Location Closing</option>

<option value="101">
Schedule Grease Trap Service
</option>
<option value="102">
Schedule Jetting
</option>
<option value="103">
Schedule Confined Space
</option>
<option  value="104">
Receipt/Invoice Request
</option>
<option value="105">
Contact Customer
</option>
<option value="106">
Other
</option>
</select>
<br /><br />

<textarea style="width: 99%;height:120px;" placeholder="Please write your message here." id="mesg"></textarea>
<br /><br />
<input type="submit" value="Send Message" id="ght" style="float: right;"/>
<span style="float: right;margin-right:30px;">
<input type="radio" name="msg_type" class="msg_type" value="1"  />&nbsp;Issue&nbsp;&nbsp; 

<input type="radio" name="msg_type"  class="msg_type" value="2"/>&nbsp;Message&nbsp;&nbsp; 

<input type="radio" name="msg_type" class="msg_type" value="3"/>&nbsp;Private Message&nbsp;&nbsp; </span>



<div id="debug"></div>


<script>
var typ;
    $(".msg_type").click(function(){
        typ = $(this).val(); 
    });

$("#ght").click(function(){
    alert( $("input#user_id").val()+" "+ $("#anumber").val()+" "+typ );
    $.post('sendMessage.php',{ 
            user_id: $("input#user_id").val(), //person sent to
            title:$("input#subject").val(), 
            acnt:$("#anumber").val(),                
            priority: $("#priority_id").val(),  
            issue:$("#message_category_id").val(),                              
            mesg: $("#mesg").val(), //message
            type: typ
        },function(data){
        $("#debug").html(data);
        alert(data);
    });
});



<?php
 echo "var accountlist = [";
 echo implode(",",array_filter($act) )."]; ";

?>



$("#acntname").autocomplete({
    
     minLength: 3, 
     source: function(req, responseFn) {
          var matches = new Array();
          var needle = req.term.toLowerCase();            
          var len = accountlist.length;
          for(i = 0; i < len; ++i){
              var haystack = accountlist[i].toLowerCase();
              if(haystack.indexOf(needle) === 0 || haystack.indexOf(" " + needle) != -1)
              {
                  matches.push(accountlist[i]);
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
            buffer = k.split("-");     
            var o,y;
            y= buffer[0];
            o = y.toUpperCase();   
            $("input#acntname").val(o);              
            $("input#acntname").val(o);
            $("input#anumber").val(buffer[1]);
        } else {
            var k = ui.item.value;
            buffer = k.split("-");     
            var o,y;
            y= buffer[0];
            o = y.toUpperCase();   
            $("input#acntname").val(o);              
            $("input#acntname").val(o);
            $("input#anumber").val(buffer[1]);
        }
    }
});  

var wordlist = [
<?php

$results = $db->get($dbprefix."_users","first,last,user_id");
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