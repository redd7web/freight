<?php
    include "protected/global.php";
    include "source/css.php";
    include "source/scripts.php";
    ini_set("display_errors",1);
    ini_set('memory_limit','256M');
    //echo $_GET['id']."<br/>";
    $account = new Account($_GET['id']);

    //echo $_GET['sched_util']."<br/>";
    $person = new Person();
    //$dtable = new Dtable();
    //echo basename($_SERVER['REQUEST_URI']);
    //var_dump($account);
        
    $askthree = $db->query("SELECT * FROM freight_data_table WHERE account_no = $account->acount_id ORDER BY date_of_pickup DESC");
?>
<style type="text/css">
.box{
    border-radius:5px;
}

 .ui-tabs.ui-tabs-vertical {
    margin-top:5px;    
    padding: 0;
    width: 470px;
    margin-left:5px;
    height:180px;
    float:left;
    
    
}
.ui-tabs.ui-tabs-vertical .ui-widget-header {
    border: none;
}
.ui-tabs.ui-tabs-vertical .ui-tabs-nav {
    float: left;
    width: 50px;
    background: #CCC;
    border-radius: 4px 0 0 4px;
    border-right: 1px solid gray;
}
.ui-tabs.ui-tabs-vertical .ui-tabs-nav li {
    clear: left;
    width: 40px;
    margin: 0.2em 0;
    border: 1px solid gray;
    border-width: 1px 0 1px 1px;
    border-radius: 4px 0 0 4px;
    overflow: hidden;
    position: relative;
    right: -2px;
    z-index: 2;

    text-align:left;
}
.ui-tabs.ui-tabs-vertical .ui-tabs-nav li a {
    display: block;
    width: 100%;
    padding-left:4px;
    text-align:left;
   
}
.ui-tabs.ui-tabs-vertical .ui-tabs-nav li a:hover {
    cursor: pointer;
}
.ui-tabs.ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active {
    margin-bottom: 0.2em;
    padding-bottom: 0;
    border-right: 1px solid white;
}
.ui-tabs.ui-tabs-vertical .ui-tabs-nav li:last-child {
    margin-bottom: 10px;
}
.ui-tabs.ui-tabs-vertical .ui-tabs-panel {
    float: left;
    width: 300px;
    height:100%;
    background:transparent;
    border-left: 1px solid gray;
    border-radius: 0;
    position: relative;
    left: -1px;
}

table#secondinfo td{
    vertical-align:top;
    text-align:left;
    padding-left:7px;
}
</style>
<script type="text/javascript">
Shadowbox.init({
    showOverlay:true,
    modal:false, 
    loadingImage:"shadow/loading.gif",
    displayNav: true,
     slideshowDelay: 2,        
    overlayOpacity: '0.9',
    overlayColor:"#FFFFFF",
    gallery: "gall" 
});
$("#times").hide();
</script>
<?php
$total_cap=  20000;
$check_complete = $db->query("SELECT route_id FROM freight_list_of_grease WHERE status='completed' AND facility =$account->acount_id");

if(count($check_complete)>0){
    foreach($check_complete as $check){
        $these[] = $check['route_id'];
    }
    $hb = $db->query("SELECT SUM(inches_to_gallons) as current_level FROM freight_grease_data_table WHERE facility_origin = $account->acount_id AND route_id IN(".implode(",",$these).") ORDER BY date_of_pickup DESC LIMIT 0,1");
    if(count($hb)>0){
        $onsite = $hb[0]['current_level'];
    }else{
        $onsite = 0;
    }
}else{
    $onsite = 0;
}

?>
<div id="debug"></div>
<div id="top-info-box-left" style="width:130px;height: 350px;float:left;background:rgba(255,255,255,.5);text-align:center;" title="oil-service-pickup">
<br />
&nbsp;&nbsp;<span style="font-weight: bold;font-size:16px;">Freight Service</span>
<p id="siren">
<?php echo $account->siren; ?>
<br /><br />
<?php
$hb = $db->query("SELECT date_of_pickup FROM freight_data_table WHERE account_no = $account->acount_id ORDER BY date_of_pickup DESC LIMIT 0,1");

 echo"<span style='font-size:20px;font-weight:bold;font-familt:tahoma' id='sub_script'><sup id='subscript'>$onsite</sup>/<sub>20000</sub></span>";
 
 ?>

</p>
<br />
<?php 
if($account->is_oil == 1){
    echo "&nbsp;<img src='img/metal_barrel-512.png'/>";
}

if($account->is_trap == 1){
    echo "&nbsp;<img src='img/grease-trap.png'/>";
}

?>


</div>
<div id="top-info-box-middle" style="width: 280px;float:left;height:550px;background:rgba(255,255,255);" title="meter-gauge">
<iframe id="guage" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=<?php echo $account->acount_id; ?>" style="width: 280px;float:left;height:550px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe>
</div>

<div id="top-info-box-right" style="width: 490px;height:265px;background:none;float:left;" title="notes">
    
    
    
    
    
   
    <div id="onsite" rel="<?php echo $account->acount_id; ?>" class="box" style="width: 50px;height:50px;float:left;background:rgba(255,255,255);margin-top:19px;margin-right:8px;cursor:pointer;" title="Schedule Freight">
        
        <img src="img/grese.jpg" /></a>
    </div>
    
   
    <div class="box" style="width: 50px;height:50px;float:left;margin-right:11px;margin-top:19px;background-size: contain;background:url(img/chat.jpg) no-repeat center top;">
    <a href="message.php?account=<?php echo $account->acount_id; ?>" rel="shadowbox;width=500;height=500">
    <img src="img/blank.jpg" style="width: 50px;height:50px;"/>
    </a>
    </div>
    
    
    <div id="tracked" class="box" style="width: 50px;height:50px;float:left;margin-right:8px;background:url(img/track.jpg) no-repeat center top;margin-top:19px;background-size: contain;cursor:pointer">
    
    </div>
    
    
    <div id="exportcompletedroutes"  class="box" style="width: 50px;height:50px;float:left;margin-right:8px;background:rgba(0,0,0,.7);margin-top:19px;background-size: contain;background:url(img/report.jpg) no-repeat center top;cursor:pointer;" title="Export Completed Pickups"></div>
    
    
    
    
   <div id="GTsummary" class="box" style="width: 50px;height:50px;float:left;margin-right:8px;margin-top:19px;background:url(img/greasetrap_1_1.jpg) no-repeat center top;cursor:pointer;background-size: cover;" title="GT Summary"></div>

    <div class="box" style="width: 50px;height:50px;float:left;background:rgba(255,255,255);margin-top:19px;margin-right:8px;"></div>
   
   
    <div id="tabs" style="width: 460px;height:270px;float:left;">
    <ul style="height:10px;">
        <li>
            <a href="#a">Notes</a>
        </li>
        <li>
            <a href="#b">Issues</a>
        </li>
        <li>
            <a href="#c">Warnings</a>
        </li>
        <li>
            <a href="#d">Running Notes</a>
        </li>
        <li>
            <a href="#e">Audio</a>
        </li>
    </ul>
    <div id="holdx" style="margin-top: 15px;height:200px;width:100%;">
        <div id="e" style="height: 100%;">
            <div id="reording" style="width: 100%;height:95%;overflow-y: auto;overflow-x: hidden;">
            <style type="text/css">
            #tb td{
                padding: 0px 0px 0px 0px;
                margin: 0px 0px 0px 0px;
                vertical-align:top;
            }
            </style>
            <table style="width: 100%;height:100%;" id="tb">
                <tr><td colspan="4">
                <form method="POST" enctype="multipart/form-data" action="viewAccount.php?id=<?php echo $account->acount_id; ?>">
                <textarea name="file_note"></textarea><br /><input type="file" name="file_sound"/><br /><input type="submit" name="file_up" style="margin-right: 50px;"/></form></td></tr>
                
                <tr>
                    <td style="vertical-align: top;padding: 0px 0px 0px 0px;">Author</td>
                    <td style="vertical-align: top;padding: 0px 0px 0px 0px;">Note</td>
                    <td style="vertical-align: top;padding: 0px 0px 0px 0px;">Date</td>
                    <td style="vertical-align: top;padding: 0px 0px 0px 0px;">File</td>
                </tr>
                <?php 

                 $uy = $db->query("SELECT * FROM freight_sound_files WHERE account_no = $account->acount_id");
                 if(count($uy)>0){
                    foreach($uy as $ku){
                        echo "<tr><td>$ku[author]</td><td>$ku[note]</td><td>$ku[date]</td><td><a href='$ku[file]' target='_blank'>Sound File</a></td></tr>";
                    }
                 }
                ?>
                
            </table>
            </div>
        </div>
        <div id="a" style="height: 100%;">
             
            <div id="thread" style="width: 100%;height:95%;overflow-y: auto;overflow-x: hidden;">
            <style type="text/css">
            #tb td{
                padding: 0px 0px 0px 0px;
                margin: 0px 0px 0px 0px;
                vertical-align:top;
            }
            </style>
                <table style="width: 100%;height:100%;" id="tb">
                    <tr>
                        <td style="vertical-align: top;padding: 0px 0px 0px 0px;">Author</td>
                        <td style="vertical-align: top;padding: 0px 0px 0px 0px;">Note</td>
                        <td style="vertical-align: top;padding: 0px 0px 0px 0px;">Date</td>
                    </tr>
                    <?php
                    //echo "SELECT * FROM freight_notes WHERE account_no = $account->acount_id<br/>";
                    $notes = $db->query("SELECT * FROM freight_notes WHERE account_no = $account->acount_id");
                    if(count($notes)>0){
                        foreach($notes as $note){         
                            $k = explode("|","$note[notes]");
                            echo"<tr>
                                <td style='vertical-align:top;'>".uNumToName($note['author_id'])."</td>
                                <td style='vertical-align:top;'>$k[0]<br/>$k[1]</td>
                                <td style='vertical-align:top;'>$note[date]</td>
                            </tr>";
                        }
                    }
                    ?>
                    <tr><td colspan="3"><textarea placeholder="write notes here" style="width: 96%;height:90px;border:1px solid #bbb;" id="account_notes" xlr="<?php echo $account->acount_id; ?>"><?php echo $account->notes; ?></textarea> </td></tr>
                </table>
            </div>
        </div>
        <div id="b" style="height: 100%;">
            <div id="thread" style="width: 100%;height:99%;overflow:auto;">
                <table style="width: 100%;height:100%;">
                    <tr>
                        <td style="vertical-align: top;">status</td>
                        <td style="vertical-align: top;">By</td>
                        <td style="vertical-align: top;">To</td>
                        <td style="vertical-align: top;">Priority</td>
                        <td style="vertical-align: top;">Note</td>
                        <td style="vertical-align: top;">Date</td>
                    </tr>
                    <?php
                     $issues = $db->where('account_no',$account->acount_id)->get($dbprefix."_issues");
                     if(count($issues)>0){
                        foreach($issues as $issue){
                            echo "<tr>";
                            echo "<td  style='vertical-align:top;'>$issue[issue_status]</td>";
                            echo "<td style='vertical-align:top;'>".uNumToName($issue['reported_by']) ."</td>";
                            echo "<td style='vertical-align:top;'>".uNumToName($issue['assigned_to']) ."</td>";
                            echo "<td style='vertical-align:top;'>".priorityConverter($issue['priority_level'])."</td>";
                            echo "<td style='vertical-align:top;'>$issue[message]</td>";
                            echo "<td style='vertical-align:top;'>$issue[date_created]</td>";
                            echo "</tr>
                            <tr><td>&nbsp;</td><td colspan='5'>
                                <table style='width:100%'>";
                                    $issue_notes = $db->where('issue_no',$issue['issue_no'])->get($dbprefix."_issue_notes");
                                    if(count($issue_notes)>0){
                                        foreach($issue_notes as $ko){
                                            echo "<tr>";
                                                echo "<td style='vertical-align:top;'>".uNumToName($issue['reported_by'])."</td>";
                                                echo "<td style='vertical-align:top;'>"; 
                                                if(strtolower($issue['to'])){
                                                    
                                                } else {
                                                    echo uNumToName($ko['to']);
                                                }
                                                
                                                echo "</td>";
                                                echo "<td style='vertical-align:top;'>".priorityConverter($issue['priority_level'])."</td>";
                                                echo "<td style='vertical-align:top;'>$ko[message]</td>";
                                                echo "<td style='vertical-align:top;'>$ko[message_date]</td>";
                                            echo "</tr>";
                                        }
                                    }
                                echo "</table>
                            <td></tr>
                            ";
                        }
                     }
                    ?>
                </table>
            </div>
            
        </div>
        <div id="c" style="height: 100%;">
            <?php
            
            
            $data_table = $dbprefix."_data_table";
            $list_of_routes = $dbprefix."_list_of_routes";
            
            
               //SELECT zero_gallon_reasons,route_no,schedule_no,fieldreport FROM $data_table WHERE account_no = $account->acount_id
                
            $warning = $db->where('account_no',$account->acount_id)->get($data_table);
            ?>
            
            
            <div id="skdjsks" style="width: 100%;height:100%;overflow-x: hidden;overflow-y:auto;">
                <table style="width: 95%;height:95%;margin:auto;">
                <thead>
                <tr>
                <td colspan="2">Choose Reason: </td>
                <td colspan="3">
                <?php zero_gallons_reasons(); ?></td>
                <td id="warning"><input type="submit" style="background: url(img/plus.png) no-repeat center center;background-size:contain;width:25px;height:25px;" value="" id="add_warn" name="subzero" account="<?php echo $account->acount_id; ?>" />
                <script>
                $("#add_warn").click(function(){
                   $.post('insertWarning.php',{ reason_for_skip_id:$("#reason_for_skip_id").val(),account: $(this).attr('account') },function(data){
                        alert("Warning Added!");
                   }); 
                });                
                
                $(".delwarn").click(function(){
                    var entry = $(this).attr('enum').replace('i','');
                    var tr = $(this).closest('tr');
                    alert(entry);
                    $.post("delwarn.php",{entry_n:entry },function(data){
                       tr.remove();     
                    });
                });
                </script>
             </td>
                </tr>
                <tr>
                <td style="vertical-align: top;">Schedule ID</td>
                <td style="vertical-align: top;">Route No</td>
                <td style="vertical-align: top;">Field Report</td>
                <td style="vertical-align: top;">Warning</td>
                <td style="vertical-align: top;">Driver</td>
                <td>&nbsp;</td>
                </tr>
                </thead>
                <tbody>
                <?php
                    if(count($warning)>0){
                        foreach($warning as $warn){
                            $complete = $db->where('route_id',$warn['route_id'])->get($list_of_routes,"status");
                            
                            if(count($complete) == 0 || $complete[0]['status'] != "completed" ){
                                
                                    echo "<tr>";
                                    echo "<td style='vertical-align:top;'>$warn[schedule_id]</td>
                                          <td style='vertical-align:top;'>$warn[route_id]</td>
                                          <td style='vertical-align:top;'>$warn[fieldreport]</td>
                                          <td style='vertical-align:top;'>"; echo field_report_decode($warn['zero_gallon_reason']); echo "</td>
                                          <td style='vertical-align:top;'>".uNumToName($warn['driver'])."</td>";
                                    echo "<td style='vertical-align:top;text-align:left;'><img src='img/delete-icon.jpg' style='cursor:pointer;' class='delwarn' enum='i$warn[entry_number]'/></td>";      
                                    echo"</tr>";
                                
                            }
                        }
                    }
             
                ?>
                </tbody>
            </table>
            </div>           
        </div>
        <div id="d"  style="height: 100%;">
            <div style="width:100%;height:100%;overflow-y:auto;overflow-x:hidden;" id="fit" >
            
                <form action="viewAccount.php?id=<?php echo $account->acount_id; ?>" method="post">
                <table style="width: 100%;">
                    
                    <tr><td colspan="3"><textarea <?php if($person->isDataEntry() || $person->isDriver()){
                        echo "readonly='readonly'";
                    }  ?>  style="width: 99%;height:100%;" name="running_note" id="running_note" placeholder="Write Comment Here"></textarea></td></tr>
                    <tr><td colspan="3" style="text-align: right;"><input <?php if(!$person->isAdmin()  || $person->isFacilityManager() ){ echo "disabled='disabled'";  } ?>  type="submit" name="submitxy" value="Enter Note"/></td></tr>
                    
                    <tr><td>Author</td><td>Date</td><td>Note</td></tr>
                    <?php
                     $jb = $db->query("SELECT * FROM freight_account_notes WHERE account_no = $account->acount_id");
                    if(count($jb)>0){
                        foreach($jb as $bj){
                            echo "<tr><td>".uNumToName($bj['author'])."</td><td>$bj[date]</td><td>$bj[note]</td></tr>";
                        }
                    }
                    ?>
                </table>
                </form>
            </div>
            
        </div>
        <table style="width:480px;margin:auto;margin-top:20px;" id="table">
        <tr><td colspan="2" style="color: red;font-weight:bold;text-align:center;">Please ENABLE POPUPS</td></tr>
        <tr><td colspan="2"><p style="margin: auto;width:480px;"><button id="inbound" rel="<?php echo $account->acount_id; ?>">Inbound</button>&nbsp;<button id="outbound"  rel="<?php echo $account->acount_id; ?>">Outbound</button></p></td></tr>
            
           <tr><td colspan="2" id="outboundx"></td></tr>
        </table>    
    </div>
    
</div>
<script>

$("#inbound").click(function(){
    Shadowbox.open({
        content:"pre_info.php?tank="+$(this).attr('rel')+"&mode=in",
        player:"iframe",
        width:"800px",
        height:"300px"    
        
    });
});


$("#outbound").click(function(){
    $("#outboundx").html('<button class="outbound"  id="17" style="height:100px;width:100px;">RC Waste Resources MV</button>&nbsp;<button class="outbound"  id="18" style="height:100px;width:100px;">RC Waste Resources LC</button>&nbsp;<button class="outbound"  id="16" style="height:100px;width:100px;">Victorville</button>&nbsp;');
});

$("#table").on("click",".outbound",function(){
    Shadowbox.open({
        content:"pre_info.php?tank="+$(this).attr('id')+"&mode=out",
        player:"iframe",
        width:"800px",
        height:"300px"    
        
    });
})
</script>

</div>

<div id="top-inf-box-short" style="height:15px;width:890px;float:left;background:rgb(255,255,255);padding:5px 5px 5px 5px;font-size:16px;word-spacing:1px;border:1px solid black;border-right:0px solid transparent;border-left:0px solid transparent;">

&nbsp;

</div>

<div id="next" style="height: 338px;width:100%;float:left;">
   
    <div id="rightnext" style="width: 100%;height:270px;float:left;">
        
        
        
        <div id="scheduled" style="width: 100%;height:20px;background:white;float:left;text-align:center;font-weight:bold;">
                <span  style="color:blue;">Scheduled Pickups</span>
            </div>
            <div id="pickupboxtwo" style="width: 100%;height:150px;background:transparent;float:left;overflow:auto;">
                 <table style="width: 100%;">
                 <tr>
                    <td style="padding:0px 0px 0px 0px; border:1px solid;"><div style="width: 200px;">Date</div></td>
                    <td style="padding:0px 0px 0px 0px;border:1px solid;"><div style="width: 60px;">Route</div></td></tr>
                 <?php
                    print_r($account->schedule);
                    echo "<tr>";
                    echo "<td>";
                    if(count($account->schedule)>0){
                        if($account->schedule['route_status'] == "enroute"){
                            $stat = "(enroute)";
                        }
                        else if($account->schedule['route_status']=="scheduled"){
                            $stat = "scheduled";
                        } else if($account->schedule['route_status']=="completed"){
                            $stat = "completed";
                        }
                        $r_st ="";
                        if($account->schedule['route_id'] !=null){    
                            $r_stat = $db->query("SELECT status FROM iwp_list_of_routes WHERE route_id =". $account->schedule['route_id']);
                            
                            
                            if($r_stat[0]['status']=="enroute"){
                                $r_st = " - Route Open";
                            }
                        }
                      
                        if(count($account->schedule)>0){
                            echo "<img src='img/delete-icon.jpg' style='cursor:pointer;' rel='".$account->schedule['schedule_id']."' class='del_stop2'/>&nbsp;&nbsp;".$account->schedule['scheduled_start_date']." $stat $r_st";    
                        } 
                        
                    } else {
                        echo "No Stops.";
                    }
                    echo "</td>";
                    echo "<td>";
                    if($account->schedule['route_id'] != NULL){
                        echo "<img src='img/info_icon_12.png' class='tooltip' style='cursor:pointer;' title='".$account->schedule['route_id']."' />";
                    } 
                    echo "</td>";
                    echo "</tr>"; 
                 ?>
                 </table>
            </div>
        
        <div id="completed_pickups" style="width: 100%;height:390px;background:rgb(255,255,255);float:left;">
            
            <div id="secondinfo" style="width: 100%;height:340px;background:rgba(255, 255, 255);border-left:0px solid black;border-top:1px solid black;border-bottom:1px solid black;">
            
            
            
            <div id="titlx" style="width:100%;background:white;height:30px;text-align:center;font-size:18px;font-weight:bold;border:0px solid #bbb;">
            <table style="width: 100%;height:100%;"><tr><td style="text-align: center;vertical-align: central;">Completed Services( All Locations )</td></tr></table>
            </div>
            <div id="tablepickup" style="width:100%;height:315px;overflow:auto;border-top:1px solid black;">
             <table style="width: 100%;">
                <tr style="background: rgb(233, 234, 228);">
                    <td>Date</td>
                    <td>Customer</td>
                    <td>Gross</td>
                    <td>Tare</td>
                    <td>Net</td>
                    <td>Weight Certificate</td>
                    <td>Transporter's IKG Manifest Number</td>
                    </tr>
                <?php
                if(count($askthree)>0){
                    $alter=0;
                    foreach($askthree as $gvo){                       
                        $alter++;                        
                        switch($alter%2){
                            case 0:
                                $bg = '-moz-linear-gradient(center top , #F7F7F9, #E5E5E7) repeat scroll 0 0 rgba(0, 0, 0, 0)';
                            break;
                            default:
                                $bg = 'transparent';
                            break;
                        }
                        echo "<tr style='background:$bg'>
                            <td>$gvo[date_created]</td>
                            <td>"; 
                                switch($gvo['element_6']){                    		
                                    case 5: echo "ABILITY"; break;
                                    case 4: echo "OTHER"; break;
                                    case 45: echo "ALPHA PETROLEUM"; break;
                                    case 52: echo "ALPHA PUMPING"; break;
                                    case 29: echo ">AMBERWICK"; break;
                                    case 7: echo "ATLAS PUMPING"; break;
                                    case 53: echo "Athens"; break;
                                    case 8: echo "BAKER COMMODITIES"; break;
                                    case 38: echo "BIOTANE"; break;
                                    case 31: echo "BLUE WATER"; break;
                                    case 9: echo "BOB WALTON"; break;
                                    case 54: echo "CARI RECYCLING"; break;
                                    case 33: echo "CRIMSON"; break;
                                    case 10: echo "CO-WEST"; break;
                                    case 11: echo "DIAMOND ENV. (Delivered)"; break;
    
                                    case 50: echo "Environmental &amp; Chem Consult"; break;
                                    case 13: echo "HAZ-MAT"; break;
                                    case 42: echo "INLAND PUMPING"; break;
                                    case 14: echo "IWP"; break;
                                    case 46: echo "IWP - G Division"; break;
                                    case 47: echo "IWP - K Division"; break;
                                    case 44: echo "JR GREASE"; break;
                                    case 43: echo "LAMB CANYON"; break;
                                    case 15: echo "L &amp; S PIPELINE"; break;
                                    case 16: echo "LIQUID ENV."; break;
                                    case 17: echo "MAJOR CLEAN UP"; break;
                                    case 40: echo "OC PUMPING"; break;
                                    case 18: echo "PIPE MAINT."; break;
                                    case 19: echo "Pipe Maintenance"; break;
                                    case 30: echo "RE COMMODITIES"; break;
                                    case 20: echo "ROTO ROOTER"; break;
                                    case 37: echo "SB INDUSTRIAL"; break;
                                    case 21: echo "STATER BROS - BIG BEAR"; break;
                                    case 22: echo "STATER BROS Lake Arrowhead"; break;
                                    case 23: echo "STRESS LESS ENV."; break;
                                    case 28: echo "T&amp;R"; break;
                                    case 32: echo "UNITED PUMPING"; break;
                                    case 41: echo "VENTURA FOODS"; break;
                                    case 27: echo "Victorville"; break;
                                    case 35: echo "WESTERN ENV."; break;
                                    case 24: echo "WESTERN PACIFIC"; break;
                                    case 25: echo "WHITE HOUSE"; break;
                                    case 26: echo "WRIGHT"; break;
                                    case 48: echo "Sustainable Restaurant Services"; break;
                                }
                            echo "</td>
                            <td>$gvo[element_38]</td>
                            <td>$gvo[element_39]</td>                            
                            <td>$gvo[element_40]</td>                        
                            <td>$gvo[element_40]</td>
                            <td>$gvo[element_57]</td>
                            
                            
                            </tr>";
                    }
                }
                ?>
            </table>   
            </div>
        </div>
        
    </div>
</div>
</div>
  <div id="showspecs" style="width: 100%;height:100px;background:white;float:left;border-left:0px solid black;border-bottom:0px solid black;">
                <div id="barx" style="width: 100%;height:15px;background:gray;border-bottom:1px sold black;color:white;text-align:center;font-weight:bold;cursor:pointer;">  <!--Notes  Grease Trap Specificaitions +--!> 
                
                </div>
                
        <style>
        table#acnt_tab td{
            padding: 0px 0px 0px 0px;
        }
        </style>                
        <!--<table id="acnt_tab" style="width: 100%;">
        <tr class="table_row">  
            <td>Trap Active&nbsp;:</td>
            <td>
                <input value="0" checked="" type="radio" name="radios" class="acti" value="<?php if($account->trap_active == 0 ){
                    echo "checked";
                    
                } ?>" />&nbsp;No
            &nbsp;&nbsp;<input value="1" type="radio" name="radios" class="acti" <?php if($account->trap_active == 1 ){
                    echo "checked";
                    
                } ?>/>&nbsp;Yes</td>
            <td>Service Type  </td>
            <td><select name="service_type" id="service_type"><option <?php 
             if( strtolower($account->service_type) == "trap"){
                echo "selected";
             }
            
            ?> value="Trap">Trap"; break;<option <?php 
             if( strtolower($account->service_type) == "jet"){
                echo "selected";
             }
            
            ?> value="Jet">Line Jetter"; break;</select></td>
            
            <td>Label</td>
            <td class="table_data" style="font-size: 13px;">
                <input style="width: 170px;" name="trap_label" value="<?php
                         echo $account->grease_label;
                         ?>" id="tr_trap_label_update" size="20" placeholder="Name this" />
            </td>
            </tr>
            <tr>
            <td class="table_label" align="right" nowrap="" valign="top">Freq.</td>
            <td class="table_data" ><input name="frequency" value="<?php
                         echo $account->grease_freq;
                         ?>" id="tr_frequency_update" size="4" /></td>
        <td>Volume</td>    
        <td class="table_data" ><input placeholder="Volume" name="volume" value="<?php
                        echo $account->grease_volume;
                         ?>"  id="tr_volume_update" size="5"/></td>

        <td>PPG</td>    
        <td class="table_data" ><input value="<?php
                         echo $account->grease_ppg;
                         ?>" placeholder="PPG" name="price_per_gallon"  id="tr_price_per_gallon_update" size="10" />
       
         <input type="hidden" id="account_for_sched" name="account_for_sched" value="<?php echo $account->acount_id; ?>"/>
        </td>
        <td> <input type="submit" value=""  class="static_grease" style="background:url(img/save.png) no-repeat center center;width:20px;height:20px;background-size:contain;border:0px solid #bbb;cursor:pointer;" title="<?php if(count($grease_info)>0){ echo "Update Grease Trap info"; } else { echo "Save Grease Trap Info";} ?>"/></td>
        </tr>
</table>!-->
            </div>
                        

    
   <input type="hidden" name="entry_count" id="entry_count" value="<?php echo count($hb); ?>" />
<script>

function load_level(){
    $.get("entries_sludge.php",{tank:<?php echo $account->acount_id; ?>},function(data){
         if( ( data *1)  > ( $("input#entry_count").val() *1 ) && data !="-1" ){
            $("#guage").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=<?php echo $account->acount_id; ?>'); 
            $("input#entry_count").val(data); 
            $.get("get_cap.php",{tank:<?php echo $account->acount_id; ?>},function(data){
                 $("#sub_script").html(data);
            });
         };
    });
}


$(document).ready(function(){
    setInterval('load_level()',5000);
    $(".edit_data").click(function(){
       Shadowbox.open({
            content:"edit_route_stop.php?entry="+$(this).attr('title')+"&route_id="+$(this).attr('rte')+"&schedule="+$(this).attr('schedule')+"&account="+$(this).attr('account'),
            player:"iframe",
            width: 500,
            height:365
       }) 
    });
    
    
    $("#dost").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
    
    
    $("#close").click(function(){
        $("#times").hide();
    });
    
    
    $("#bar").click(function(){
       $("#times").show(); 
    });
    
    $("#sched_trap").click(function(){
        Shadowbox.open({
           content:"sched_trap.php?account_no=<?php echo $account->acount_id; ?>",
           player:"iframe",
           width:500,
           height:365 
        });   
    });
    
    
    
    
    $(".deltrap").click(function(){
        $.post("deltrap.php",{trapnum: $(this).attr('rel')},function(data){
            alert("schedule removed");
            location.reload();
            //$("#trap"+$(this).attr('rel')).css('display','none');
        });
    });
    
    
    $(".trap_info").click(function(){
        Shadowbox.open({
            player:"iframe",
            content: "greasetrapinfo.php?trap_no="+$(this).attr('trap_no')+'&account_no='+$(this).attr('account_no'),
            width:"600px",
            height:"300px",
            options: { 
                modal:   true,
                onClose: function(){window.location.href='<?php echo "viewAccount.php?id=$account->acount_id"; ?>' }
            }
        });
    });
    
    $('#tabs').tabs({
         active: 0
    });
    
    
    
    $("#schedpickup").click(function(){    
        Shadowbox.open({
            player: "iframe",
            content: "schedulepickup.php?account_no=<?php echo $account->acount_id; ?>",
            width: 500,
            height:365,
            options: { 
                modal:   true,
                onClose: function(){window.location.href='<?php echo "viewAccount.php?id=$account->acount_id"; ?>' }
            }
       }); 
    });
    
    
    $("#tracked").click(function(){
        Shadowbox.open({
            player:"iframe",
            content:"tracked.php?account=<?php echo $account->acount_id; ?>",
            width:500,
            height:365,
            options: { 
                modal:   true,
                onClose: function(){window.location.href='<?php echo "viewAccount.php?id=$account->acount_id"; ?>' }
            } 
        });
    })
    
    
    $("#onsite").click(function(){
        Shadowbox.open({
             content:"schedulepickup.php?account_no="+$(this).attr('rel'),
            player: "iframe",
            width: 500,
            height:365,
            options: { 
                modal:   true,
                onClose: function(){window.location.href='<?php echo "viewAccount.php?id=$account->acount_id"; ?>' }
            }
        });
    });
    
    
    $("#exportcompletedroutes").click(function(){
        Shadowbox.open({
            player:"iframe",
            content: "export_completed_pickups.php?account_no=<?php echo $account->acount_id; ?>",
            width: 250,
            height:220,
            options: { 
                modal:   true,
                onClose: function(){window.location.href='<?php echo "viewAccount.php?id=$account->acount_id"; ?>' }
            }
       });
    });
    
    $("#GTsummary").click(function(){
        Shadowbox.open({
            player:"iframe",
            content: "export_completed_pickupsGT.php?account_no=<?php echo $account->acount_id; ?>",
            width: 200,
            height:230,
            options: { 
                modal:   true,
                onClose: function(){window.location.href='<?php echo "viewAccount.php?id=$account->acount_id"; ?>' }
            }
       });
    });
    
    
    
    $(".tooltip").click(function(){
        Shadowbox.open({
            player:"iframe",
            content: "tooltip.php?account_no=<?php echo $account->acount_id; ?>",
            width: 550,
            height:350,
            options: { 
                modal:   true,
                onClose: function(){window.location.href='<?php echo "viewAccount.php?id=$account->acount_id"; ?>' }
            }
       });
    });
    
    
    
    $("#bar").click(function(){
        Shadowbox.open({
            player:"iframe",
            content: "sched_trap.php?account_no=<?php echo $account->acount_id; ?>",
            width: 500,
            height:350,
            options: { 
                modal:   true,
                onClose: function(){window.location.href='<?php echo "viewAccount.php?id=$account->acount_id"; ?>' }
            }
       });
    })
    
    	
    $( ".ui-tabs-nav" ).tabs({ active: 3 });
    
    $("#account_notes").change(function(){
        
        $.post("updateAccountNotes.php",{notes: $(this).val(),account_no:$(this).attr('xlr')},function(data){
            alert("Notes Updated " + data);
        });
    });
    
    
    
    $(".static_grease").click(function(){               
        $.post("insert_static_grease.php",{
                active:$(".acti:checked").val(),
                service_type:$("#service_type").val(),
                label:$("input#tr_trap_label_update").val(),
                freq:$("input#tr_frequency_update").val(),
                volume:$("input#tr_volume_update").val(),
                rate:$("input#tr_price_flat_update").val(),
                ppg:$("input#tr_price_per_gallon_update").val(),
                account_no:$("input#account_for_sched").val()
            },function(data){
                alert("General Grease Trap Information Saved!");
        });
    });
    
    
    $("#del_scheduled_stop").click(function(){
        alert($(this).attr('rel'));
        $.post("deletes_schedule.php",{grease_no:$(this).attr('rel'),account_no:$(this).attr('xlr'),route_id:$(this).attr('rrr')},function(data){
            alert("STOP deleted "+data);
            window.location.reload();
        });  
    });

})
/*
$("#issues").blur(function(data){
   $.post('updateAccountIssues.php',{issues:$(this).val(),account_no:$(this).attr('xlr') },function(data){
    alert(data);
   }); 
});

$.get('getAccountIssues.php',{account_id: $("#issues").attr('xlr') },function(data){
   $("#issues").val(data); 
});



$.get("getAccountNotes.php",{account_id: $("#notes").attr('xlr') },function(data){
    $("#notes").val(data);
});

$("#notes").blur(function(data){
    $.post('updateAccountNotes.php',{notes: $(this).val(),account_no: $(this).attr('xlr') },function(data){
        alert('updated!');
        $("#notes").val(data); 
    });
});

$("#newroutethis").click(function(){
    var d = new Date();
    $.post('account_schedule_now.php',{date_sched_pickup_month:d.getFullYear()+"-"+d.getMonth()+"-"+d.getDay(),account_no:<?php echo $account->acount_id ?> , fire:1},function(data){
        alert(data);
        $("input#schecheduled_ids").val(data+"|");
        $("#route_this_now").submit(); 
    });
});
*/
</script>
