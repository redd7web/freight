<!DOCTYPE html>
<html>
<head>
<?php

include "protected/global.php";

$person = new Person();
?>
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="plugins/nicedit/nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>
<link rel="stylesheet" href="css/auto.css" />
<link rel="stylesheet" href="plugins/nicedit/niceEdit.css" />
<style>
        body {
            text-align: center;
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
<div id="tickform" style="width: 800px;height:503px;margin:auto;background:url(img/form.jpg) no-repeat center top;"> 
    <div id="spacer" style="height: 110px;width:100%;background:transparent;float:left;overflow:hidden;"></div>
    <div id="spacer-left" style="width: 31px;height:30px;background:transparent;float:left;"></div>
    <div id="mid" style="width:573px ;float:left;height:400px;">
        <div id="toholder" style="width: 573px;height:39px;">
            <input id="to" type="text" style="width: 565px;height:35px;margin-top:3px;margin-left:2px;background:white;border:0px solid #bbb;font-weight:bold;font-size:16px;" placeholder="To:"/>
        </div>
        <div id="spacer-mid" style="height: 11px;width:573px;"></div>
        <div id="subjholder" style="width: 573px;height:39px;">
            <input id="subject" type="text" style="width: 565px;height:35px;margin-top:5px;margin-left:2px;background:white;border:0px solid #bbb;font-weight:bold;font-size:16px;" placeholder="Subject:"/>
        </div>
        <div id="spacer-mid" style="height: 11px;width:573px;"></div>
       <textarea id="edit" style="width: 573px;height:230px;border:0px solid red;" name="area2" placeholder="Describe your issue here"></textarea>
    </div>
    <div id="spacer-left" style="width: 30px;height:250px;background:transparent;float:left;"></div>
    <div id="side_button" style="width: 113px;height:252px;float:left;">
        <div id="top_buttons" style="height: 182px;width:113px;">
            <div id="Urgent" style="margin-top: 17px;margin-left:25px;width:70px;height:41px;cursor:pointer;" class="priority"></div>
            
            <div id="Low" style="margin-top: 15px;margin-left:25px;width:70px;height:41px;cursor:pointer;" class="priority"></div>
            
            <div id="Medium" style="margin-top: 15px;margin-left:25px;width:70px;height:41px;cursor:pointer;" class="priority"></div>
            
            
        </div>
        <div id="submit" style="width:113px ;height:70px;">
            <input type="submit" id="createticket" style="width: 100%;height:100%;background:transparent;border:0px solid #bbb;cursor:pointer;" value=""/>
        </div>
    </div>
</div>
<div id="debug"></div>

<input type="hidden" name="user_id" id="user_id" readonly=""/>

<script>
var priority = "";

$("#Urgent").click(function(){
    priority = $(this).attr('id');
    $('.priority').css("background","none");
    $(this).css("background","red"); 
});

$("#Low").click(function(){
    priority = $(this).attr('id');
    $('.priority').css("background","none");
    $(this).css("background","green"); 
});


$("#Medium").click(function(){
    priority = $(this).attr('id');
    $('.priority').css("background","none");
    $(this).css("background","yellow"); 
});

/**/
$("#createticket").click(function(){
    //alert("clicked"+ $(".nicEdit-main").html())
   $.post("create_ticket.php",{
        division:"oil",
        title:$("input#subject").val(),
        to:$("input#user_id").val(),
        from:<?php echo $person->user_id; ?>,
        notes:$(".nicEdit-main").html(),
        priority:priority
    },function(data){
        $("#debug").html(data);
        alert("Ticket Submitted!");
   }); 
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
</body>
</html>