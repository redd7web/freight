<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        
<?php
include "protected/global.php";
include "source/scripts.php"; 
include "source/css.php";
//ini_set("display_errors",1);
if(isset($_SESSION['freight_id'])){ 
    $person = new Person();
    $util_route = new Container_Route($_GET['route_id']);
    //echo "<pre>";
    //var_dump($util_route);
    //echo "</pre>";
    
    //echo "driver $util_route->driver_no";
}


?>
<title>Enter Mainline Data</title>
<style type="text/css">

input[type="text"] {
    border: 1px solid #bbb;
    border-radius: 5px;
    height: 25px;
    width: 140px;
    margin-left:5px;
}

.list_row{
    cursor:pointer;
}
</style>
<iframe id="mach" style="width: 1000px;height:100px;display:none;"></iframe>
<div id="loading-screen"></div>
<div id="wrapper" style="height: auto;">
    <div id="fullgray" style="width: 100%;height:30px;background: linear-gradient(#F0F0F0, #CFCFCF) repeat scroll 0 0 rgba(0, 0, 0, 0);border-bottom: 1px solid #808080;margin-bottom:10px;">
        <div id="info_hold" style="width: 700px;margin:auto;height:30px;background:transparent;padding">
            <table style="width:700px;"><tr><td>Route ID:</td><td id="rid"> <?php  echo $_GET['route_id'];?> </td><td>Created: <?php   if(isset($util_route->created_date )){ echo $util_route->created_date; }else { echo date("Y-m-d");}   ?></td><td>By:<?php 
            if(!isset($util_route->created_by)){
            
            echo uNumToName($person->user_id);  }{
                echo $util_route->created_by;                
            }?> </td><td>Facility: <span id="facholder"><?php echo $util_route->recieving_facility; ?></span> </td></tr></table>
            </div>
        
    </div>
    <div id="accountHolder" style="width: 380px;height:480px;overflow-x:hidden;overflow-y:auto;padding:10px 10px 10px 10px;margin-left:30px;border-radius:5px 5px 5px 5px;border:3px solid rgb(242,242,242);float:left;margin-bottom:5px;">
<table style="width: 99%;" id="sched_list">
    <?php
    if(count($util_route->scheduled_routes)>0){
        $count = 1;
        foreach($util_route->scheduled_routes as $util_scheds){
    
            $mark = '';
            $schedule = new Util_Stop($util_scheds);
            $get_gallons = $db->where("account_no",$schedule->account_number)->where("route_id",$schedule->route_id)->get($dbprefix."_utility_data_table","*"); //has this account been picked up for this route?
            if(count($get_gallons) >=1){
                $mark =1 ;
            }
            echo "<tr class='list_row' xlr='$schedule->schedule_id' title='$schedule->schedule_id'>
                <td id='a$schedule->schedule_id' style='width:50px;'>&nbsp;</td>
                <td>$count</td>
                <td style='text-align:left;border-bottom:1px solid rgb(242,242,242);padding:8px 8px 8px 8px;'>
                <span class='schedulex' rel='$schedule->schedule_id' xlr='$schedule->account_number'>".$schedule->account_name."</span><br/><span style='font-size:12px;color:gray;'>Account id: $schedule->account_number</span>
                <input type='text' value='$mark' class='picked_up' placeholder='Mark this if picked up' title='$schedule->schedule_id' id='$schedule->schedule_id' xlr='$schedule->account_number'/>            
                </td>
                
                <td style='text-align:right;border-bottom:1px solid rgb(242,242,242);padding:8px 8px 8px 8px;' id='pic$schedule->schedule_id'>"; 
                
            if($schedule->code_red== 1 && count($get_gallons) == 0 ){ 
                echo "<img src='img/graphics-flashing-light-245546.gif' style='width:30px;height:30px;' class=''/>"; 
            } 
            else if( ($schedule->code_red== 1 && count($get_gallons) >= 1) || ($schedule->code_red== 0 && count($get_gallons) >= 1) ){
                echo "<img src='img/check_green_2s.png'/>";
            }         
            else {
                echo "<img src='img/redlight.jpg' style='width:30px;height:30px;'/>";
            }   
        echo "</td></tr>";
        $count++;
        }  
    }
     
    ?>
    </table>
    </div>
    
        <div id="enterData" style="padding: 10px 10px 10px 10px;width:680px;min-height:480px;float:left;margin-left:40px;margin-bottom:5px;height:auto;">
    <table style="width: 100%;"><tr><td><span id="back" style="cursor: pointer;text-decoration:underline;">&laquo; Back</span></td><td><span id="next" style="cursor: pointer;text-decoration:underline;">Next &raquo;</span></td></tr></table>
    <table style="width: 100%;">
    <tr><td colspan="2" >
        <div id="head" style="width: 400px;height:200px;border-radius:5px 5px 5px 5px;border-color:3px;margin:auto;border:3px solid rgb(242,242,242);">
            <div id="headLeft" style="width: 180px;padding:10px 10px 10px 10px;height:180px;text-align:center;float:left;">
                <p id="account_Name_address">Loading Info....</p>
            </div>    
            <div id="headRight" style="width: 180px;padding:10px 10px 10px 10px;height:180px;text-align:center;float:left;">
            <p style="text-align: center;">
                <span style="font-weight: bold;font-size: 13px;font-family:tahoma;text-align:center;"> Mainline Jetting Service </span><br />
                <span id="containInfo" >
                Loading info...
                </span>
            </p>
            
            </div>
            
        </div>
    
    </td></tr>
    </table>
    
    
    <table style="width: 600px;"  id="barrel_section">
    <tr><td> Loading Barrel Info....</td></tr>
    </table>

    
    <table style="width: 100%;">
    <tr><td style="text-align: right;vertical-align:top;">Reason for Zero Gallons</td><td style="text-align: left;vertical-align:top;">
    
    
                  <?php zero_gallons_reasons(); ?>          </td></tr>
    
    <tr><td style="text-align: right;vertical-align:top;">Field Notes</td><td style="text-align: left;vertical-align:top;"><textarea id="field_notes" name="field_notes" style="width: 100%;height:80px;"></textarea></td></tr>
    
    <tr><td style="text-align: right;vertical-align:top;">Driver</td><td style="text-align: left;vertical-align:top;"><?php  echo getDrivers($util_route->driver_no);  ?></td></tr>
    <tr><td style="text-align: right;vertical-align:top;">Date of Pickup</td><td style="text-align: left;vertical-align:top;"><input type="text" id="dop" name="dop" value="<?php echo date("Y-m-d"); ?>"/></td></tr>'
    <tr><td style="text-align: right;vertical-align:top;"><input type="submit" value="Service Complete" id="pickup_complete"/></td><td style="text-align: center;vertical-align:top;"><span style="background: linear-gradient(#fefefe, #cfcfcf) repeat scroll 0 0 rgba(0, 0, 0, 0);
        border: 1px solid #aaa;
        border-radius: 8px;
        box-shadow: 0.2em 0.2em 0.2em #ddd;
        cursor: pointer;
        height: 30px;
        margin: 3px;
        padding: 3px 3px 7px;
        text-align: center;
        vertical-align: middle;"><img num=""  src="img/Chat-bubble-blue.png" class="mail2" style="cursor: pointer;width:50px;height:50px;"/></span></td></tr>
    
    </table>
    
    </div>
    
    <table style="width: 100%;float:left;">
<tr><td style="text-align: center;border:2px solid black"><input type="submit" id="route_complete" value="Close Route"/> </td><td style="text-align: center;vertical-align:middle;">If all data is entered for this route, close it out.</td></tr>
</table>
<input type="text" id="update_this_account" name="update_this_account" />
<div style="clear: both;"></div>
    <div style="clear: both;"></div>  
</div>


<div id="debug"></div>
<script>
$("#mach").attr('src','machlogin.php');
function close_window() {
  if (confirm("Route Closed! Uncompleted pickups went back home")) {
    close();
  }
} 


$(".mail2").click(function(){
    var y = $(this).attr('num');  
    Shadowbox.open({
        content: 'message.php?account='+y,
        player:"iframe",
        width:500,
        height:500,
        loadingImage:"shadow/loading.gif", 
        title: "Messaging / Issues",
        options: {    
            overlayColor:"#ffffff",
            overlayOpacity: ".9"            
        }
    });
});

function Traverse(number,route){
     $("#field_notes").val("");
    $("#reason_for_skip_id").val(0);
    $("#sched_list tr td:first-child").removeClass("right_arrow");
    $("#barrel_section").html("");
    $("#account_Name_address,#containInfo").html("Loading info...");
     $("input#inchescollected,input#gpicalc,input#inchesleftover,input#inchtogallonleftover").val("");
        $(".outof").html('');
        $.get("retrieveUtilSchedule_info.php",{sched_id:number,mode:"address"},function(data){
              $("#account_Name_address").html(data);
                    $("#containInfo").html(data);
                    
                    
                    $.get("retrieveUtilSchedule_info.php",{sched_id:number,mode:"num"},function(data){
                            $("input#update_this_account").val(data)
                                 selector = "#a"+number+"";
                                $(selector).addClass("right_arrow");
                                $("#loading-screen").fadeOut("slow");
                                $(".mail2").addAttr('num',number);  
                    });
                    $.get("retrieveUtilSchedule_info.php",{sched_id:number,mode:"field"},function(data){
                            $("#field_notes").val(data);
                    });
                    $.get("retrieveUtilSchedule_info.php",{sched_id:number,mode:"driver"},function(data){
                            $("#drivers").val(data);
                    });
                    $.get("retrieveUtilSchedule_info.php",{sched_id:number,mode:"dop"},function(data){
                            $("#dop").val(data);
                    });
                    
                    $.get("retrieveUtilSchedule_info.php",{sched_id:number,mode:"zero"},function(data){
                            $("#reason_for_skip_id").val(data);
                    });
        });        
        
        
        $.get("addmainlinetextfield.php",{sched_id:number,route_id:route},function(data){ 
            $("#barrel_section").html(data);     
            $("#loading-screen").fadeOut("slow");                
        });
}




var schedules_to_traverse = new Array();
var schedule_ids = new Array();

var current_schedule = 0;




$( ".schedulex" ).each(function( index ) {
    schedules_to_traverse.push( $(this).attr('rel')  );
    schedule_ids.push( $(this).attr('rel') );
});

var schedule_max = schedules_to_traverse.length;

Traverse(schedules_to_traverse[0],<?php echo $_GET['route_id']; ?>);




$("#next").click(function(){
    $("#loading-screen").show();    
    //alert(current_schedule)
    if( current_schedule != schedule_max-1 ){        
        var selector = "#a"+schedules_to_traverse[current_schedule];
        $(selector).removeClass("right_arrow");
        current_schedule++; 
       Traverse(schedules_to_traverse[current_schedule],<?php echo $_GET['route_id']; ?>);
    }
    else { 
        alert('At the end of the list, cannot go forward');
        $("#loading-screen").fadeOut("fast");
    }
    
});


$("#back").click(function(){    
    $("#loading-screen").show();
     //alert(current_schedule)
     if(current_schedule != 0){
        var selector = "#a"+schedules_to_traverse[current_schedule];
        $(selector).removeClass("right_arrow");
        current_schedule--;
        Traverse(schedules_to_traverse[current_schedule],<?php echo $_GET['route_id']; ?>);
     }
     else {
        alert('At the top of the list, cannot go back');
        $("#loading-screen").fadeOut("fast");
     }    
});

$("#pickup_complete").click(function(){
    $("#loading-screen").show();
    $.post("save_util_info.php",{ 
            route_id:<?php echo "$_GET[route_id]"; ?>,
            schedule_number: schedules_to_traverse[current_schedule],            
            account_no: $("input#update_this_account").val(),
            zero_gallon_reason: $("#reason_for_skip_id").val(),
            field_note:$("#field_notes").val(),
            driver: $("#drivers").val(),
            dop:$("input#dop").val()             
        },function(data){
            $.post('decrement_util.php',{schedule_number: schedules_to_traverse[current_schedule],route:<?php echo $_GET['route_id']; ?>},function(data){})
            $("#debug").html(data);
            //confirm pickup was complete
            $("#p"+schedules_to_traverse[current_schedule]).val(1);
            $("#pic"+schedules_to_traverse[current_schedule]).html("<img src='img/check_green_2s.png'/>");
            //confirm pickup was complete
            //alert("Data Saved!");
            if( current_schedule != schedule_max-1 ){ 
                var selector = "#a"+schedules_to_traverse[current_schedule];
                $(selector).removeClass("right_arrow");
                current_schedule++;
                Traverse(schedules_to_traverse[current_schedule],<?php echo $_GET['route_id']; ?>);
            }
            else {
                $("#loading-screen").fadeOut("slow");
                alert("At end of list");
            }
   });
    
});


    $("#route_complete").click(function(){        
       $.post("update_route_status_util.php",{route_id:<?php echo $_GET['route_id']; ?>  },function(data){
            //alert('Route Closed! Uncompleted pickups went back home');
            close_window()
            $("#debug").html(data);
       }); 
    });
    
   
   



$(".list_row").click(function(){
    current_schedule = jQuery.inArray( $(this).attr('xlr') , schedules_to_traverse);
    Traverse( schedules_to_traverse[current_schedule],<?php echo $_GET['route_id']; ?> );
});


$(".list_row").hover(function(){
   $(this).css('background','rgba(220,220,220,.2)');
},function(){
    $(this).css('background','#ffffff');
});

$("input#dop").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});


$("#barrel_section").on('click','.work_form',function(){
    Shadowbox.open({
        content:"../cssample.php?route_id="+$(this).attr('route_id')+"&schedule_id="+$(this).attr('schedule_id')+"&account_no="+$(this).attr('account')+"&ml=1",
        player:"iframe",
        width:"1000px",
        height:"500px",
        title:"Mainline Jetting Work Form"
    })
});

$("#barrel_section").on('click','.view_form',function(){
    alert($(this).attr('rel'));
   Shadowbox.open({
        content:"../machforms/machform/view_entry.php?form_id=28181&entry_id="+$(this).attr('rel')+"",
        player:"iframe",
        width:"1000px",
        height:"500px",
        title:"Mainline Jetting Work Form"
   }); 
});

</script>
