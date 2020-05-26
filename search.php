 <meta charset="UTF-8" />    
 <?php include "protected/global.php"; ?>
 <?php include "source/css.php"; ?>
 <?php include "source/scripts.php"; ?>
<style type="text/css">
body{
    margin:0px 0px 0px 0px;
    padding:10px 10px 10px 10px;
    background:rgb(250, 250, 250);
}
</style>


<input type="text" style="width: 99%;"  placeholder="Enter Search Criteria here." style="float: left;" id="quicks"/>
<script>
<?php

$act[]="";
$ac = $db->get($dbprefix."_accounts");
if(count($ac) !=0){   
    foreach($ac as $value){
        $name =str_replace("-","",htmlspecialchars($value['name']));
        $act[] = "\"".$name." - $value[account_ID]\" \r\n";
    }
}

echo "var accountlist = [";
 echo implode(",",array_filter($act) );
echo "]; ";
?>

$("#quicks").autocomplete({    
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
          responseFn(matches);
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
            var buffer = k.split("-");     
            var number = buffer[1].replace(/[^0-9]/g, '');
            var o,y;
            y= buffer[0];
            o = y.toUpperCase();
            $("input#quicks").val(buffer[0]);
            window.top.location.href = 'viewAccount.php?id='+number;
        } else {
            var k = ui.item.value;
            var buffer = k.split("-");     
            var number = buffer[1].replace(/[^0-9]/g, '');
            var o,y;
            y= buffer[0];
            o = y.toUpperCase();
            $("input#quicks").val(buffer[0]);   
            window.top.location.href = 'viewAccount.php?id='+number;
        }
    }
});
</script>