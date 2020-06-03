<?php
    include "protected/global.php";
    include "source/css.php";
    include "source/scripts.php";
    ini_set("display_errors",0);
    //echo $_GET['id']."<br/>";
    $account = new Account($_GET['id']);
    //echo $_GET['sched_util']."<br/>";
    $person = new Person();
    //$dtable = new Dtable();
    //echo basename($_SERVER['REQUEST_URI']);
    //var_dump($account);
    $grease_info = $db->where("account_no",$account->acount_id)->get($dbprefix."_grease_traps");
    $rate = 0;
    $volume = 0;
    $ppg = 0;
    $freq = 0;
    $active = 0;
    $active ="";
    $serviec = "";
    $grease_name="";
    $time_detail = "";
    $descript = "";
    $adt_price = "";
    $adt_price_detail = "";
    $dos = "";
    if(count($grease_info)>0){
        $volume = $grease_info[0]['volume'];
        $rate =$grease_info[0]['base_rate'];
        $ppg = $grease_info[0]['price_per_gallon'];
        $freq = $grease_info[0]['frequency'];
        $active =$grease_info[0]['active'];
        $service= $grease_info[0]['service'];
        $grease_name= $grease_info[0]['grease_name'];
        $time_detail = $grease_info[0]['time_of_service'];
        $descript = $grease_info[0]['notes'];
        $adt_price= $grease_info[0]['addt_price'];
        $adt_price_detail=$grease_info[0]['addt_info'];
        $dos = $grease_info[0]['service_date'];
    }
    $askthree = $db->query("SELECT * FROM `freight_grease_data_table` WHERE account_no=$account->acount_id GROUP BY schedule_id ORDER BY date_of_pickup DESC");
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
$total_caps ="";


?>
<div id="debug"></div>
<div id="top-info-box-left" style="width:130px;height: 350px;float:left;background:rgba(255,255,255,.5);text-align:center;" title="oil-service-pickup">
<br />
&nbsp;&nbsp;<span style="font-weight: bold;font-size:16px;">Grease Trap Service</span>
<p id="siren">
<?php echo $account->siren; ?>
<br /><br />
<?php
$hb = $db->query("SELECT date_of_pickup FROM freight_grease_data_table WHERE account_no = $account->acount_id ORDER BY date_of_pickup DESC LIMIT 0,1");
$onsite = date_different($hb[0]['date_of_pickup'],date("Y-m-d"));
 echo"<span style='font-size:20px;font-weight:bold;font-familt:tahoma'><sup>$onsite</sup>/<sub>$account->grease_freq</sub></span>"; ?>

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
<div id="top-info-box-middle" style="width: 280px;float:left;height:350px;background:rgba(255,255,255);" title="meter-gauge">
<iframe id="guage" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=<?php echo $account->acount_id; ?>" style="width: 280px;float:left;height:300px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe>
</div>

<div id="top-info-box-right" style="width: 490px;height:265px;background:none;float:left;" title="notes">
    
     <div class="box" style="width: 50px;height:50px;float:left;background:rgba(255,255,255);margin-top:19px;margin-right:8px;">
        <img id="bar" src="img/service.jpg"  title="Schedule Grease Trap" style="cursor:pointer;" />
    
    </div>
            
    <div id="schedpickup" class="box" style="width: 50px;height:50px;float:left;margin-right:8px;background:rgba(0,0,0,.7);margin-top:19px;margin-left:8px;background-size: contain;background:url(img/nroute.jpg) no-repeat center top;cursor:pointer;" title="Schedule Confined Space">
        <form action="oil_routing.php" method="post" id="route_this_now">
            <input type="hidden" name="schecheduled_ids" value="" id="schecheduled_ids"/>
            <input type="hidden" name="accounts_checked" value="<?php echo $account->acount_id."|"; ?>"/>
        </form>
    </div>
    <div id="onsite" class="box" style="width: 50px;height:50px;float:left;background:rgba(255,255,255);margin-top:19px;margin-right:8px;cursor:pointer;">
        
        <img src="img/grese.jpg" title="Schedule Line Jetting Delivery"/></a>
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
    </ul>
    <div id="holdx" style="margin-top: 15px;height:200px;width:100%;">
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
                <td><input type="submit" style="background: url(img/plus.png) no-repeat center center;background-size:contain;width:25px;height:25px;" value="" id="add_warn" name="subzero" account="<?php echo $account->acount_id; ?>" />
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
                    <tr><td colspan="3" style="text-align: right;"><input <?php if(!$person->isAdmin()  || $person->isFacilityManager() ){ echo "disabled='disabled'";  } ?>  type="submit" name="submitx" value="Enter Note"/></td></tr>
                    
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
        
    </div>
    
</div>

</div>

<div id="top-inf-box-short" style="height:15px;width:890px;float:left;background:rgb(255,255,255);padding:5px 5px 5px 5px;font-size:16px;word-spacing:1px;border:1px solid black;border-right:0px solid transparent;border-left:0px solid transparent;">

<span style="font-weight: bold;margin-left:10px;">Containment</span>

</div>

<div id="next" style="height: 338px;width:100%;float:left;">
    <div id="leftnext" style="width: 405px;height:330px;float:left;">
        <div id="leftnexttop" style="width: 405px;height:338px;">
             <div id="totelist" style="width: 405px;height:250px;float:left;">
             
             <table style="width:100%;margin-top:5px;" id="containerlistxx">
                <tr><td colspan="2" style="text-align: center;"> <span style="font-weight:bold;font-size:20px;">Add Grease Trap Size</span><br />Click the "+" to set trap size</td></tr>
                <tr>

                    <td style="width:50%;">&nbsp;<input type="submit" value=""  style="width: 25px;height:25px;background:transparent url(img/plus.png) no-repeat center center; background-size:contain;" id="addContainer"  title="Select then add container"/></td>   
                    <td style="text-align: left;vertical-align: top;">&nbsp;</td>
                               
                </tr>
                
                <tr><td colspan="3">
               <div id="containment" style="width: 390px;height:150px;overflow-y:scroll;background:transparent;overflow-x:hidden;border:1px dotted black;">
               <?php 
                     echo "Grease Trap Size: $account->grease_volume";
               ?>
               </div>
               </td></tr>
               <tr><td colspan="2"><input type="hidden" id="acc_no" value="<?php echo $account->acount_id; ?>"/></td></tr>
               </table>   
               <script>
               $("#addContainer").click(function(){                
                   
                  
                  Shadowbox.open({
                        content:"addtrapsize.php?account=<?php echo $account->acount_id; ?>",
                        player:"iframe",
                        width:"400px",
                        height:"400px",
                        title:"Add Grease Type Size"
                  });
                });
                
                $(".righthold").on("click",function(){
                    
                    //alert( $(this).attr('account')+" "+$(this).attr('entry')  );
                    
                    /**/
                    $.post('deleteTote.php',{
                            account:$(this).attr('account') ,
                            entry:$(this).attr('entry')},function(data){
                                alert('Container removed');
                                $("#guage").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=<?php echo $account->acount_id; ?>');
                                $.post("totelist.php",{id:<?php echo $account->acount_id; ?>},function(data){
                                    $("#totelist").html(data);
                                }); 
                        
                                $.post("showsiren.php",{id :<?php echo $account->acount_id;  ?>},function(data){
                                    $("#siren").html(data); 
                                });
                            });
                    });
               </script>             
            </div>
          
            <div id="scheduled" style="width: 405px;height:20px;background:white;float:left;text-align:center;font-weight:bold;">
                <span  style="color:blue;">Scheduled Pickups</span>
            </div>
            <div id="pickupboxtwo" style="width: 405px;height:70px;background:transparent;float:left;overflow:auto;">
                 <table style="width: 100%;">
                 <tr>
                    <td style="padding:0px 0px 0px 0px; border:1px solid;"><div style="width: 200px;">Date</div></td>
                    <td style="padding:0px 0px 0px 0px;border:1px solid;"><div style="width: 100px;">Status</div></td>
                    <td style="padding:0px 0px 0px 0px;border:1px solid;"><div style="width: 60px;">Route</div></td></tr>
                 <?php
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
                            $r_stat = $db->query("SELECT status FROM freight_list_of_routes WHERE route_id =". $account->schedule['route_id']);
                            
                            
                            if($r_stat[0]['status']=="enroute"){
                                $r_st = " - Route Open";
                            }
                        }
                        echo $account->schedule['scheduled_start_date']."  $r_st";
                    } else {
                        echo "No Stops.";
                    }
                    echo "</td>";
                    echo "<td>$stat</td>";
                    echo "<td>";
                    if($account->schedule['route_id'] != NULL){
                        echo "<img src='img/info_icon_12.png' class='tooltip' style='cursor:pointer;' title='".$account->schedule['route_id']."' />";
                    } 
                    echo "</td>";
                    echo "</tr>"; 
                 ?>
                 </table>
            </div>
            <div id="specs" style="height: 110px;margin-top:15px;width:405px;text-align:center;float:left;background:rgba(255,255,255);border-bottom:1px solid black;">
             
            </div>
        </div>
    </div>
    <div id="rightnext" style="width: 495px;height:270px;float:left;">
        
        <div id="completed_pickups" style="width: 495px;height:315px;background:rgb(255,255,255);float:left;">
            
            <div id="secondinfo" style="width: 490px;height:315px;background:rgba(255, 255, 255);border-left:0px solid black;border-top:1px solid black;border-bottom:1px solid black;">
            
            
            
            <div id="titlx" style="width: 495px;background:white;height:30px;text-align:center;font-size:18px;font-weight:bold;">
            <table style="width: 100%;height:100%;"><tr><td style="text-align: center;vertical-align: central;">Completed Services( All Locations )</td></tr></table>
            </div>
            <div id="tablepickup" style="width: 493px;height:315px;overflow:auto;border-top:1px solid black;">
             <table style="width: 100%;">
                <tr style="background: rgb(233, 234, 228);">
                    <td>Date</td>
                    <td>Volume</td>
                    <td>Jetting</td>
                    <td>CS</td>
                    <td>Route</td>
                    <td>Reciept</td>
                    <td>Info</td>
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
                            <td>$gvo[date_of_pickup]</td>
                            <td>$gvo[inches_to_gallons]</td>
                            <td>$gvo[jetting]</td>
                            <td>$gvo[cs]</td>                            
                            <td><a href='enterData.php?route_id=$gvo[route_id]&day=1' target='_blank'>$gvo[route_id]</a></td>
                            <td><img style='cursor:pointer;' class='edit_data' src=img/info_icon_12.png  rte='$gvo[route_id]' title='$gvo[entry_number]' schedule='$gvo[schedule_id]' account='$gvo[account_no]'/></td>
                            <td>Receipt Link</td>
                            <td>I</td>
                            </tr>";
                    }
                }
                ?>
            </table>   
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
            
            ?> value="Trap">Trap</option><option <?php 
             if( strtolower($account->service_type) == "jet"){
                echo "selected";
             }
            
            ?> value="Jet">Line Jetter</option></select></td>
            
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
            
            <script>
            
            
            </script>
            
<div id="last" style="height: 334px;width:100%;float:left;background:#f2f2f2;margin-top:5px;">
    <div id="lastleft" style="width:409px ;height:334px;float:left;">
        <div id="schedgreasetrap" style="widith:409px;height:32px;text-align:center;font-weight:bold;font-size:13px;color:blue;">
            Scheduled Grease Traps Service
        </div>
        <div id="newgts" style="width: 409px;height:123px;overflow-y:auto;overflow-x:hidden;background:transparent;border-top:1px solid black">
        <table style="width: 100%;">
                <tr style="background: rgb(233, 234, 228);"><td style="width:20%;">date</td><td>Routed I</td><td>info</td></tr>
                <?php
                $g = $db->where("account_no",$account->acount_id)->where("route_status","scheduled")->get($dbprefix."_grease_traps","*");
                if(count($g)>0){
                    $alter =1;
                    foreach($g as $gtraps){
                                               
                        if($alter !=1 ||  $alter%2 == 0){
                            $bg = '-moz-linear-gradient(center top , #F7F7F9, #E5E5E7) repeat scroll 0 0 rgba(0, 0, 0, 0)';
                        }
                        $kj = explode(" ",$gtraps['service_date']);
                        echo "<tr style='background:$bg' id='trap$gtraps[grease_no]'><td>$kj[0]</td><td>$gtraps[grease_route_no]</td><td>
                        <img src='img/table_edit.png' class='trap_info' account_no='$account->acount_id' trap_no='p$gtraps[grease_no]' style='cursor:pointer;' /> | 
                        <img src='img/red_cancel.png' rel='$gtraps[grease_no]' class='deltrap' style='cursor:pointer;'/></td>
                        </tr>";
                        $alter++;
                    }
                }
                ?>
            </table>
        </div>
        <div id="schedutiltrap" style="widith:409px;height:32px;text-align:center;font-weight:bold;font-size:13px;color:red;border-top: 1px solid black;">
            Scheduled Utility Calls
        </div>
        <div id="uts" style="width: 409px;height:123px;overflow:auto;border:1px solid black;border-right:0px solid black;border-left:0px;">
            <table style="width: 100%;">
                <tr style="background: rgb(233, 234, 228);"><td>date</td><td>#</td><td>#</td><td>#</td><td>#</td><td>Routed I</td></tr>
                <?php 
                $getc = $db->query("SELECT * FROM freight_utility WHERE account_no = $account->acount_id AND route_status IN('enroute','scheduled')");
                //where("route_status","enroute")->where('account_no',$account->acount_id)->get($dbprefix."_utility","utility_sched_id,date_of_service");                
                if(count($getc)>0){                    
                    $alter = 1;
                    foreach($getc as $utils){
                        if($alter !=1 ||  $alter%2 == 0){
                            $bg = '-moz-linear-gradient(center top , #F7F7F9, #E5E5E7) repeat scroll 0 0 rgba(0, 0, 0, 0)';
                        }     
                        echo "<tr style='background:$bg'><td>$utils[date_of_service]</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>$utils[utility_sched_id]</td></tr>";
                    }
                }
                ?>
            </table>
        </div>
        
        
    </div>
    <div id="lastmid" style="width:10px;height:300px;float:left;text-align:center;background:rgb(233, 234, 228);margin-top:33px;">
        
       
    
    </div>
    <div id="lastright" style="width: 481px;height:334px;float:left;">
         
        <div id="completedgreasetraps" style="widith:303px;height:32px;text-align:center;font-weight:bold;font-size:13px;color:blue;">
            Completed Grease Traps Service
        </div>
        <div id="cgts" style="width: 481px;height:133px;overflow:auto;border:1px solid black;border-left:0px solid black;">
        
            <table style="width:100%;"><tr style="background: rgb(233, 234, 228);"><td>Date</td><td>Grease Trap Size</td><td>Route</td></tr>
            <?php
                $g = $db->where("account_no",$account->acount_id)->where("route_status","completed")->get($dbprefix."_grease_traps","*");
                if(count($g)>0){
                    $alter =1;
                    foreach($g as $gtraps){
                                               
                        if($alter !=1 ||  $alter%2 == 0){
                            $bg = '-moz-linear-gradient(center top , #F7F7F9, #E5E5E7) repeat scroll 0 0 rgba(0, 0, 0, 0)';
                        }
                        $op = $db->where("account_no",$account->acount_id)->where("route_id",$gtraps['grease_route_no'])->get("freight_grease_data_table");
                       
                        echo "<tr style='background:$bg' id='trap$gtraps[grease_no]'><td>".$op[0]['date_of_pickup']."</td><td>".$op[0]['inches_to_gallons']."</td><td>$gtraps[grease_route_no]</td>
                        
                        </tr>";
                        $alter++;
                    }
                }
                ?>
            </table>
        </div>
         
         
        <div id="completutil" style="widith:481px;height:32px;text-align:center;font-weight:bold;font-size:13px;color:red;">
            Completed Scheduled Utility Service Calls
        </div>
        <div id="cgts" style="width: 481px;height:123px;overflow:auto;border:1px solid black;border-right:0px solid black;border-left:0px;">        
            <table style="width:100%;"><tr style="background: rgb(233, 234, 228);"><td>Date</td><td>Service</td><td>container</td>
            <td>Route Id</td>
            </tr>
            <?php 
                $getc = $db->where("route_status","completed")->where('account_no',$account->acount_id)->get($dbprefix."_utility");                
                if(count($getc)>0){
                    
                    $alter = 1;
                    foreach($getc as $utils){
                        
                        if($alter !=1 ||  $alter%2 == 0){
                            $bg = '-moz-linear-gradient(center top , #F7F7F9, #E5E5E7) repeat scroll 0 0 rgba(0, 0, 0, 0)';
                        }
                        $hu = $db->query("SELECT date_of_pickup FROM freight_utility_data_table WHERE account_no=$account->acount_id AND schedule_id=$utils[utility_sched_id] AND route_id=$utils[rout_no]");
                        
                        if(count($hu)>0){
                            $completed_ = $hu[0]['date_of_pickup'];
                        } else {
                            $completed_ = 0;
                        }
                        $alter++;
                        echo "<tr style='background:$bg'>
                            <td>$completed_</td>
                            <td>";  service_call_decode($utils['type_of_service']); echo "</td>
                            
                            <td>";
                        
                        if($utils['type_of_service'] !=100){
                            echo containerNumToName($utils['container_label']);
                        } else  if($utils['type_of_service'] == 100){
                            echo containerNumToName($utils['container_label'])." for ".containerNumToName($utils['container_being_swapped_label']);
                        }
                        
                        echo "</td><td>$utils[rout_no]</td></tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>
  
</div>
    
    <div style="clear: both;"></div>
<script>
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
     active: 2
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
         content:<?php
        if(isset($_GET['sched_util']) || strtolower($account->status) == "new"){
            echo '"onsite.php?account='.$account->acount_id.'&sched_util=1"';
        } else {
            echo '"onsite.php?account='.$account->acount_id.'"';
        }
        ?>,
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
        width: 200,
        height:230,
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

$("#account_notes").blur(function(){
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
