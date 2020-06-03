       
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
    <meta charset="UTF-8" />
	<meta name="author" content="Ede Dizon" />
<?php
include "protected/global.php";
if(isset($_SESSION['freight_id'])){
    ini_set("display_errors",0);
    
    include "source/scripts.php"; 
    include "source/css.php";
    $person = new Person();
    $grease_route = new Grease_IKG($_GET['route_id']);
    var_dump($grease_route->scheduled_routes);



?>
<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<title>Enter Utility Service Data</title>
<style type="text/css">

input[type="text"] {
    border: 1px solid #bbb;
    border-radius: 5px;
    height: 25px;
    width: 140px;
    margin-left:5px;
}
</style>
</head>
<body>
<div id="loading-screen" style="z-index: 9999;"></div>
<div id="wrapper" style="height: auto;">
    <div id="fullgray" style="width: 100%;height:30px;background: linear-gradient(#F0F0F0, #CFCFCF) repeat scroll 0 0 rgba(0, 0, 0, 0);border-bottom: 1px solid #808080;margin-bottom:10px;">
        <div id="info_hold" style="width: 900px;margin:auto;height:30px;background:transparent;padding">
            <table style="width:900px;"><tr><td>Route ID:</td><td id="rid"> <?php  echo $_GET['route_id'];?> </td><td>Created: <?php   if(isset($grease_route->created_date )){ echo $grease_route->created_date; }else { echo date("Y-m-d");}   ?></td><td>By:<?php 
            if(!isset($grease_route->created_by)){
            
            echo uNumToName($person->user_id);  }{
                echo $grease_route->created_by;                
            }?> </td><td>Facility: <span id="facholder"><?php echo $grease_route->recieving_facility; ?></span> </td></tr></table>
            </div>
        
    </div>
    <div id="accountHolder" style="width: 380px;height:480px;overflow-x:hidden;overflow-y:auto;padding:10px 10px 10px 10px;margin-left:30px;border-radius:5px 5px 5px 5px;border:3px solid rgb(242,242,242);float:left;margin-bottom:5px;">
<table style="width: 99%;" id="sched_list">
    <?php
    
   
    if(count($grease_route->scheduled_routes)>0){
        $count = 1;
        foreach($grease_route->scheduled_routes as $grease_scheds){
           if($grease_scheds != null){
                 $grease_stop = new Grease_Stop($grease_scheds);
                 $accnt = new Account($grease_stop->account_number);
                 $mark = '';
            //echo "SELECT * FROM freight_grease_data_table WHERE route_id=$grease_scheds[grease_route_no] AND account_no = $grease_scheds[account_no] AND schedule_id = $grease_scheds[grease_no]<br/>";
                $get_gallons = $db->query("SELECT * FROM freight_grease_data_table WHERE route_id=$grease_stop->grease_route_no AND account_no = $grease_stop->account_number AND schedule_id = $grease_stop->grease_no"); //has this account been picked up for this route?
                if(count($get_gallons) >=1){
                    $mark =1 ;
                }
                echo "<tr class='list_row' xlr='$grease_stop->grease_no' title='$grease_stop->grease_no' account='$grease_scheds[account_no]'>
                <td id='a$grease_stop->grease_no' style='width:50px;'>&nbsp;</td>
                <td>$count</td>
                <td style='text-align:left;border-bottom:1px solid rgb(242,242,242);padding:8px 8px 8px 8px;'>
                <span class='schedulex' rel='$grease_stop->grease_no' xlr='$grease_stop->account_number'>".account_NumToName($grease_stop->account_number)." "; 
                if($accnt->locked == 1 || $accnt->mlocked == 1){
                    echo "<span style='font-weight:bold;color:red;font-size:11px;'>( LOCKED )</span>";
                }
                echo "</span><br/><span style='font-size:12px;color:gray;'>Account id: $grease_stop->account_number</span>
                <input type='text' value='$mark' class='picked_up' placeholder='Mark this if picked up' title='$grease_stop->grease_no' id='p$grease_stop->grease_no' xlr='$grease_stop->account_number'/>            
                </td>
                
                <td style='text-align:right;border-bottom:1px solid rgb(242,242,242);padding:8px 8px 8px 8px;' id='pic$grease_stop->grease_no'>"; 
            
                if( $mark == 1  ){
                    echo "<img src='img/check_green_2s.png'/>";
                } else if( $grease_scheds['fire'] == 1  ){ 
                    echo "<img src='img/graphics-flashing-light-245546.gif' style='width:30px;height:30px;' class=''/>"; 
                } 
                else  {
                    echo "<img src='img/redlight.jpg'/>";
                }         
                echo "</td></tr>";
                $count++;
           }
        }  
    }
     
    ?>
    </table>
    </div>
    
        <div id="enterData" style="padding: 10px 10px 10px 10px;width:480px;min-height:480px;float:left;margin-left:10px;margin-bottom:5px;height:auto;">
    <table style="width: 100%;"><tr><td><span id="back" style="cursor: pointer;text-decoration:underline;">&laquo; Back</span></td><td><span id="next" style="cursor: pointer;text-decoration:underline;">Next &raquo;</span></td></tr></table>
    <table style="width: 100%;">
    <tr><td colspan="2" >
        <div id="head" style="width: 400px;height:200px;border-radius:5px 5px 5px 5px;border-color:3px;margin:auto;border:3px solid rgb(242,242,242);">
            <div id="headLeft" style="width: 180px;padding:10px 10px 10px 10px;height:180px;text-align:center;float:left;">
                <p id="account_Name_address">Loading Info....</p>
            </div>    
            <div id="headRight" style="width: 180px;padding:10px 10px 10px 10px;height:180px;text-align:center;float:left;">
            <p style="text-align: center;">
                <span style="font-weight: bold;font-size: 13px;font-family:tahoma;text-align:center;"> Service</span><br />
                <span id="containInfo" >
                Loading info...
                </span>
            </p>
            </div>
        </div>
    </td></tr>
    </table>
    
    
    
    <table style="width: 100%;" id="datasection">
    
    
    </table>
    
    </div>
    
    <table style="width: 100%;float:left;">
<tr><td><input type="submit" id="route_complete"  style="float:right;white-space:normal;text-align-right;" /> </td><td style="text-align: center;vertical-align:middle;">If all data is entered for this route, close it out.</td></tr>
</table>
<input type="text" id="update_this_account" name="update_this_account" readonly=""/>
    
    <div style="clear: both;"></div>  
</div>
<div id="debug" style="min-height: 300px;height:auto;width:100%;"></div>


<script>

$(".missing_info").click(function(){
    alert("Missing Gross Weight / Net Weight / Tare Weight.  Please Enter values and refresh this page.");
});



$("#row1hide").hide();
$("#row2hide").hide();
window.onload = function(){

}




function close_window() {
  if (confirm("Route Closed.  Uncompleted Stops went back to scheduled stops pool.")) {
    close();
  }
}

$(".mail2").click(function(){
    var y = $(this).attr('account');   
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

function Traverse(number,route_idx){
    
    $("textarea#acd").val("");
    $("input#aca").val("");
    $("textarea#in").val("");
    //alert(number);
    $("input#mileage").val("");
    $("input#arrival").val("");
    $("input#departure").val("");
    $("#emergency").attr("rel",number);
    $("#form_here").html("");
    $("#field_notes").val("");
    $("#reason_for_skip_id").val(0);
    $("#barrel_section").html("");
    $("input#grease_picked_up").val("");
    $("#sched_list tr td:first-child").removeClass("right_arrow");
    $("#account_Name_address,#containInfo").html("Loading info...");
    $("input#inchescollected,input#gpicalc,input#inchesleftover,input#inchtogallonleftover").val("");
    $(".outof").html('');
    $.get("retrieveGreaseSchedule_info.php",{sched_id:number,mode:"address"},function(data){
          $("#account_Name_address").html(data);
          $.get("retrieveGreaseSchedule_info.php",{sched_id:number,mode:"containment"},function(data){
                $("#containInfo").html(data);
                $.get("retrieveGreaseSchedule_info.php",{sched_id:number,mode:"amount"},function(data){
                    $(".outof").html(data);
                    $.get("retrieveGreaseSchedule_info.php",{sched_id:number,mode:"num"},function(data){
                        $(".mail2").attr('account',data);
                            selector = "#a"+number+"";
                            $(selector).addClass("right_arrow");
                            $("#loading-screen").fadeOut("slow");
                            $(".mail2").attr('num',number);                           
                    });
                });                    
          });          
    }); 
      
     
      
      
      $.ajax({
            method: "GET",
            url: "retrieveGreaseSchedule_info.php",
            data: { sched_id:number,mode:"emergency" }
      }).done(function( data ) {
            if(data == 1){
                //alert("emergency: "+data);
                $("#emergency").prop("checked",true);
            }else{
                $("#emergency").prop("checked",false);
            } 
      });
   
      $.get("addtextfield.php",{ sched_id:number,route_id:route_idx },function(data){
            
            $("#datasection").html(data);
      });
      
      $("#lj").attr('xlr',number);
        
      $.get("retrieveGreaseSchedule_info.php",{sched_id:number,mode:"volume"},function(data){ 
            $("#total_amount").html(data); 
      });
      
   
      $.get("retrieveGreaseSchedule_info.php",{sched_id:number,mode:"field"},function(data){ 
            $("#field_notes").val(data); 
      }); 
      
     
      $.get("retrieveGreaseSchedule_info.php",{sched_id:number,mode:"picked_up"},function(data){ 
            $("input#grease_picked_up").val(data); 
      });
               
      
      
      $("#cs").attr('schedule_id',number);
      
     
      
      $.get("retrieveGreaseSchedule_info.php",{sched_id:number,mode:"account_number"},function(data){ 
        $("input#update_this_account").val(data); 
      });
      
    
      $.get("retrieveGreaseSchedule_info.php",{sched_id:number,mode:"mileage"},function(data){ 
        $("input#mileage").val(data); 
      });
      
      $.get("retrieveGreaseSchedule_info.php",{sched_id:number,mode:"arrival"},function(data){ 
        $("input#arrival").val(data); 
      });
      
      $.get("retrieveGreaseSchedule_info.php",{sched_id:number,mode:"departure"},function(data){ 
        $("input#departure").val(data); 
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
       Traverse(schedules_to_traverse[current_schedule]);
    }
    else { 
        alert('At the end of the list, cannot go forward');
        $("#loading-screen").fadeOut("fast");
    }
    
});

$("#cs").click(function(){
    if($(this).is(":checked")){
        $("#row1hide").show();
        $("#row2hide").show();
    }else{
        $("#row1hide").hide();
        $("#row2hide").hide();
        $("select#cs_reason").val("");
        $("textarea#cs_notes").val("");
    }
})


$("#back").click(function(){    
    $("#loading-screen").show();
     //alert(current_schedule)
     if(current_schedule != 0){
        var selector = "#a"+schedules_to_traverse[current_schedule];
        $(selector).removeClass("right_arrow");
        current_schedule--;
        Traverse(schedules_to_traverse[current_schedule]);
     }
     else {
        alert('At the top of the list, cannot go back');
        $("#loading-screen").fadeOut("fast");
     }    
});

$("#datasection").on("click","#pickup_complete",function(){    
    $("#loading-screen").show();  
    $.post("save_grease_info.php",{ 
            route_id:<?php echo "$_GET[route_id]"; ?>,
            schedule_number: schedules_to_traverse[current_schedule],            
            account_no: $("input#update_this_account").val(),
            field_note:$("#field_notes").val(),
            picked_up: $("input#est_lbs").val(),//split /8.34
            mileage:$("input#mileage").val(),
            arrival:$("input#arrival").val(),
            departure:$("input#departure").val(),
            net_weight:$("input#net_weight").val(),
            percent_split:$("input#percent_split").val()
        },function(data){
            /*$.post('decrement_grease.php',{route:<?php echo $_GET['route_id']; ?>},function(data){})*/
            $("#debug").html(data);
            //confirm pickup was complete
            $("p#"+schedules_to_traverse[current_schedule]).val(1);
            $("#pic"+schedules_to_traverse[current_schedule]).html("<img src='img/check_green_2s.png'/>");
            //confirm pickup was complete            
            window.open('grease_recipet_single.php?route_no=<?php echo $_GET['route_id']; ?>'+"&schedule="+schedules_to_traverse[current_schedule])
            if( current_schedule != schedule_max-1 ){ 
                var selector = "#a"+schedules_to_traverse[current_schedule];
                $(selector).removeClass("right_arrow");
                current_schedule++;
                Traverse(schedules_to_traverse[current_schedule]);
            }
            else {
                $("#loading-screen").fadeOut("slow");
                alert("At end of list");
            }
   });
});



$("#route_complete").click(function(){
    $("#loading-screen").show();
   $.post("update_route_status_grease.php",{route_id:<?php echo $_GET['route_id']; ?>,status:"completed"  },function(data){
        $("#debug").html(data);
        alert(data);
        $("#loading-screen").hide();
        window.open('print_grease.php?route_no=<?php echo $_GET['route_id']; ?>');
        close_window();
    });
});

$("#driver_route_complete").click(function(){
    $.post("update_route_status_grease_driver.php",{route_id:<?php echo $_GET['route_id']; ?>},function(data){
        close_window();
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



$("#form_here").on('click','.ljform',function(){
   Shadowbox.open({
        content:"jet_form.php?account_no="+$(this).attr('account_no')+"&route_no="+$(this).attr('route_no')+"&schedule_id="+$(this).attr('grease_no')+"",
        player:"iframe",
        width:"900px",
        height:"500px",
        title:"Line Jetting Work Form"
        
   }); 
});


$("#form_here").on('click','.view_form',function(){
    
    Shadowbox.open({
            content:"../machforms/machform/view_entry.php?form_id=29942&entry_id="+$(this).attr('entry')+"",
            player:"iframe",
            width:"900px",
            height:"500px",
            title:"Line Jetting Work Form"
       });  
    
    
});


$("#arrival").datetimepicker({ dateFormat: 'yy-mm-dd',timeFormat:"HH:mm:ss",pick12HourFormat: false     });
$("#departure").datetimepicker({ dateFormat: 'yy-mm-dd',timeFormat:"HH:mm:ss",pick12HourFormat: false     });

</script>
<?php
} else {
    
    echo "Session has expired, please click here to re-login <a href='index.php'>re-login</a>";
}
?>
<div id="debug"></div>
</body>
</html>