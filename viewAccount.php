<?php include "protected/global.php"; 
    ini_set("display_errors",1);
$page = "customers"; 
$person = new Person();
    

    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="ReDDaWG" />
    <meta charset="UTF-8" />    
   <?php include "source/css.php"; ?>
   <?php include "source/scripts.php"; ?>
	<title>Customer Management System</title>
    <style>
    ul.navi {
        height:60px;
       position:relative;top:5px;
       left:-40px;
    }
    ul.navi li {
        list-style:none;
        font-style:italic;
        text-transform:uppercase;
        border:1px solid black;
        display: inline;
        height:38px;
        cursor:pointer;
        font-family:tahoma;
        font-size:18px;
        width:auto;
        margin-left:1px;
        padding:10px 10px 10px 10px;
    }
    
    #topfac{
        background:transparent;
        border:0px solid #bbb;
    }
    </style>
    
</head>

<body>
<?php include "source/header.php"; 

if(isset($_SESSION['sludge_id'])){
    $account = new Account($_GET['id']);
    if(isset($_POST['submitxy'])){
        $d = date("Y-m-d H:i:s");
        $package = array(
            "account_no"=>$account->acount_id,
            "note"=>$_POST['running_note'],
            "date"=>$d,
            "author"=>$person->user_id                                
        );
        print_r($package);
        $db->insert("sludge_account_notes",$package);
    }
    
    
    if(isset($_POST['file_up'])){
        $target_dir = "/$account->acount_id/sound_files/";
        $target_file = $target_dir .str_replace(" ","_",basename($_FILES["file_sound"]["name"]));
          echo "<br/><br/><br/><br/><br/>";
         echo $_FILES["file_sound"]["tmp_name"]." ".$target_file;
        if(!file_exists ( "/$account->acount_id/sound_files/")){
             mkdir("$account->acount_id/sound_files/", 0777);
        }
        if (move_uploaded_file($_FILES["file_sound"]["tmp_name"], "$account->acount_id/sound_files/".str_replace(" ","_",basename($_FILES["file_sound"]["name"])))) {
            $sound_package = array(
                "author"=>$person->user_id,
                "account_no"=>$account->acount_id,
                "date"=>date("Y-m-d"),
                "note"=>$_POST['file_note'],
                "file"=>"$account->acount_id/sound_files/".str_replace(" ","_",basename($_FILES["file_sound"]["name"]))
            );
            $db->insert("sludge_sound_files",$sound_package);
          echo "File Moved";
           
        }
    }
    
    /*
    $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if($_SERVER['HTTP_REFERER'] != $actual_link && !in_assoc($actual_link,$_SESSION['history']) ){
          $_SESSION['sludge_page_counter']++;  
        if($_SESSION['sludge_page_counter']<5){
            $_SESSION['sludge_history'][] = array(
                "url"=>$actual_link,
                "name"=>$account->name_plain
            );
        } else {
            if( $_SESSION['sludge_page_counter']%4 == 1  ){
                $_SESSION['sludge_history'][0] = array(
                    "url"=>$actual_link,
                    "name"=>$account->name_plain
                );
            } else if( $_SESSION['sludge_page_counter']%4 == 2  ){
                $_SESSION['sludge_history'][1] = array(
                        "url"=>$actual_link,
                        "name"=>$account->name_plain
                );
            } else if( $_SESSION['sludge_page_counter']%4 == 3  ){
                $_SESSION['sludge_history'][2] = array(
                        "url"=>$actual_link,
                        "name"=>$account->name_plain
                );
            } else if( $_SESSION['sludge_page_counter']%4 == 0  ){
                $_SESSION['sludge_history'][3] = array(
                        "url"=>$actual_link,
                        "name"=>$account->name_plain
                );
            }
        }
    }   
     */
    
    $person = new Person();
    //$value = $db->where("account_no",$account->acount_id)->get("sludge_containers");
    //echo "<br/>".$account->acount_id;


?>

<div id="wrapper" style="margin-top: 90px;">
<div id="spacer" style="width: 100%;height:10px;">&nbsp;</div>

<div class="content-wrapper" style="min-height:450px;height: auto;">
        
        <div id="fullgray" style="width: 100%;height:30px;background: linear-gradient(#F0F0F0, #CFCFCF) repeat scroll 0 0 rgba(0, 0, 0, 0);border-bottom: 1px solid #808080;margin-bottom:10px;">
            <div id="info_hold" style="width:100%;margin:auto;height:30px;background:transparent;padding">
            <table style="width:100%"><tr><td>Account:<span id="hold"><?php echo $account->acount_id; ?></span> </td><td>Created: <?php echo $account->created; ?></td><td>Modified:<?php  ?> </td><td>Record Creator: <?php echo uNumToName($account->class); ?> </td><td>Facility: </td><td id="facx"><?php getFacilityList("topfac",$account->division);  ?></td><td><?php echo "Address: ".$account->address." ".$account->city.", ".$account->state.", ".$account->zip." ".$account->country; ?></td></tr></table>
            </div>
        </div>
        
        <div id="topinfo" style="width: 900px;height:60px;margin:auto;margin-bottom:5px;">
            <div class="title-box" style="width: 450px;height:25px;font-weight:bold;font-size:20px;font-family:tahoma;border:1px solid black;padding :25px 10px 10px 10px;float:left;background:rgb(255,255,255);">
               <table style="width: 450px;margin-left:0px;height:25px;"><tr><td style="text-align: center;vertical-align:middle;padding:0px 0px 0px 0px;"> <?php echo trim($account->name); ?></td></tr></table>
            </div>
                
            <div class="tabs" style="width: 250px;height:60px;float:left;">
                <ul class="navi"><li id="main">Main</li><li id="map">Map</li><li id="admin">Admin</li></ul>
            </div>
            
            <div class="quicksearch" style="width: 150px;height:60px;float:left;">
                <input type="text" placeholder="Account ID or Name" id="quicks" class="quicks" style="width: 100%;" />
            </div>
        </div>
        
        
        <div id="info-box" style="width: 900px;min-height:900px;height:auto;border:1px solid #bbb;margin:auto;border:1px solid #000;margin-bottom:5px;">
          
          
          
        </div>        
        <div id="debug"></div>   
            
</div>
    <div style="clear: both;"></div> 
</div>

<div style="clear: both;"></div>

<script>
  $(window).load(function() { 
        <?php
         $hhh = $db->query("SELECT account_no FROM sludge_utility WHERE account_no = $account->acount_id");
         if( ( isset($_GET['sched_util'])  && strtolower($account->status) == "new" && count($hhh) == 0 ) || ( strtolower($account->status) == "new" && count($hhh) == 0 )   )
            {//coming from account creation or account is new and no barrels exist
        ?>        
               Shadowbox.open({
                    content:"onsite.php?account=<?php echo $account->acount_id; ?>&sched_util=1",
                    player:"iframe",
                    width: 500,
                    height:365
               });
               
                $.get("viewAccount-main.php",{id: <?php echo $account->acount_id; ?>,sched_util:1},function(data){
                    $("#info-box").css("background","none"); 
                    $("#info-box").html(data);    
                });<?php } else { ?>            
             $.get("viewAccount-main.php",{id: <?php echo $account->acount_id; ?>},function(data){
                $("#info-box").css("background","none"); 
                $("#info-box").html(data);    
            });
            <?php } ?>    
}); 



$("#info-box").css("background","url(img/loading.gif) no-repeat center 30px"); 
$("#transparent").hide();


$("ul.navi li").css('background','rgb(242,242,242)');

$("#main").css({
   border:"1px solid black",
   color: "rgb(69, 121, 55)",
   background:"white" 
});

$("#main").click(function(){    
    $.get("viewAccount-main.php",{id: <?php echo $account->acount_id; ?> },function(data){
        $("#info-box").css("background","none");
        $("#info-box").html(data);
   });
});




$("#admin").click(function(){
    $.get("viewAccount-admin.php",{id: <?php echo $account->acount_id; ?> },function(data){
       $("#info-box").css("background","none");
       $("#info-box").html(data); 
    });
});


$("#map").click(function(){
    $.get("viewAccount-map.php",{id: <?php echo $account->acount_id; ?> },function(data){
        $("#info-box").css("background","none");
        $("#info-box").html(data);
    });
});


$("ul.navi li").click(function(){
   $("#info-box").css("background","url(img/loading.gif) no-repeat center 30px"); 
   $("#info-box").html(""); 
   $("ul.navi li").css({
        border: "1px solid black",
        color: "black",
        background:"rgb(242,242,242)"
   });
   $(this).css({
        border: "1px solid #bbb",
        color:"rgb(69, 121, 55)",
        background:"white"
   }); 
});



<?php

$act[]="";
$ac = $db->get($dbprefix."_accounts","account_ID,name");
if(count($ac) !=0){   
    foreach($ac as $value){
        $name =str_replace("-","",htmlspecialchars($value['name']));
        $act[] = "\"".$name." - $value[account_ID]\" \r\n";
    }
}

echo "var accountlist = [";
 echo implode(",",array_filter($act) )."]; ";
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
            window.location.href = 'viewAccount.php?id='+number;
        } else {
            var k = ui.item.value;
            var buffer = k.split("-");     
            var number = buffer[1].replace(/[^0-9]/g, '');
            var o,y;
            y= buffer[0];
            o = y.toUpperCase();
            $("input#quicks").val(buffer[0]);   
            window.location.href = 'viewAccount.php?id='+number;
        }
    }
});

$("#sb-info").on("click",function(){
    alert('found');
});


$("#topfac").change(function(){
    $.post("topfac.php",{facnum: $(this).val(),account_no:<?php echo $account->acount_id; ?>},function(){
        alert("Facility Changed");
    });
});

// alert("HI"+$.ui.version); // is it there yet?




</script>
<?php 
    
}else{
    echo "Your Session has expired, please re-login<br/>";
}
include "source/footer.php"; ?>

</body>
</html>  
  