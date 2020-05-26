<div id="footer" style="color: black;background:#cccccc;height:auto;">


<?php 


if(isset($_SESSION['sludge_id'])){ 
   
    $countf =0;
     $followx = $db->query("SELECT DISTINCT ( issue_no ), message,message_date FROM sludge_issue_notes WHERE created_by =$person->user_id GROUP BY issue_no");                    
    if(count($followx)>0){
        foreach($followx as $co){
            
            $extra = $db->query("SELECT * FROM sludge_issues WHERE issue_no = $co[issue_no] AND issue_status IN('active','Active')");
            if(count($extra)>0){
                $countf++;   
            }
        }
    }
    $mailbyme  = $db->where("issue_status","active")->where('reported_by',$person->user_id)->orderby("date_created","DESC")->get($dbprefix."_issues");
    $mailforme = $db->where("issue_status","active")->where('assigned_to',$person->user_id)->orderby("date_created","DESC")->get($dbprefix."_issues");
    $privatebyme =  $db->where("status","active")->where('author',$person->user_id)->orderby("date","DESC")->get($dbprefix."_private");
    $privateforme = $db->where("status","active")->where('recipient',$person->user_id)->orderby("date","DESC")->get($dbprefix."_private");
    ?>
    
        <table style="width: 1000px;height:auto;margin:auto;"><tr><td style="text-align: center;">
        <div id="messagenemergncy" style="width: 1000px;height:auto;">
        <div id="messagebox" style="width: 1000px;height:auto;background:white;">
        
            <div id="tabs" style="height: auto;">
                <ul>
                <li style="margin-right: 10px;background:transparent;border:0px solid #bbb;text-transform:uppercase;color:white;">Issues</li>
                <li><a href="#following" style="color:#435BA5 ;">Following (<?php echo $countf;  ?>)</a></li>
                <li><a href="#indbox" style="color:#435BA5;">For me (<?php if(count($mailforme)>0  ){ echo count($mailforme); } else { echo 0;} ?>)</a></li>
                <li><a href="#sendboxx" style="color:#435BA5;">By me(<?php if(count($mailbyme)>0  ){ echo count($mailbyme); } else { echo 0;} ?>)</a></li>
                <li><a href="#privatein" style="color:#435BA5 ;">Private for me(<?php if(count($privateforme) >0) {  echo count($privateforme); } else { echo 0;} ?>)</a></li>
                
                <li><a href="#fragment-4" style="color:#435BA5 ;">Private by me(<?php if(count($privatebyme)>0) { echo count($privatebyme); } else { echo 0;} ?>)</a></li>
                 
                </ul>
                <div id="following" style="height: auto;">
                    <table style="width: 100%;">
                    <tr><td>Main Thread</td><td style="padding: 0px 0px 0px 0px;">Account </td><td>Message</td><td>Message Date</td></tr>
                    <?php 
                    $follow = $db->query("SELECT DISTINCT ( issue_no ), message,message_date FROM sludge_issue_notes WHERE created_by =$person->user_id GROUP BY issue_no");                    
                    if(count($follow)>0){
                        foreach($follow as $co){                            
                            $extrax = $db->query("SELECT * FROM sludge_issues WHERE issue_no = $co[issue_no] AND issue_status IN('active','Active')");
                            if(count($extrax)>0){
                                echo "<tr><td><a href='viewIssues.php?id=$co[issue_no]' rel='shadowbox;width=700;'> <img src='img/table_edit.png'/></a></td><td>".account_NumtoName($extrax[0]['account_no'])."</td><td>$co[message]</td><td>$co[message_date]</td></tr>";  
                            }
                        }
                    }
                    ?>
                    
                    </table>
                    <div style="clear: both;"></div>
                </div>
                <div id="indbox" style="height: auto;">
                    <table style="width: 100%;">
                    <tr><td style="padding: 0px 0px 0px 0px;">#</td><td>Author</td><td>Date</td><td>Info</td><td>View</td></tr>
                    <?php
                        if(count($mailforme) !=0){
                            foreach($mailforme as $msg2){
                                echo "<tr><td style=\"padding: 0px 0px 0px 0px;\">$msg2[issue_no]</td><td>". uNumToName($msg2['reported_by']) ."</td><td>$msg2[date_created]</td><td style='color:black'>".account_NumtoName($msg2['account_no'] ) ."<br/> $msg2[message]</td><td><a href='viewIssues.php?id=$msg2[issue_no]' rel='shadowbox;width=700;'> <img src='img/table_edit.png'/></a></td></tr>";
                            }
                        }else
                        {
                            echo "<tr><td colspan='4' style='vertical-align:middle;text-align:center;'>No Issues for me</td></tr>";
                        }
                        
                        
                    
                    ?>
                    </table>
                    <div style="clear: both;"></div>
                </div>
                <div id="sendboxx" style="height: auto;">
                    <table style="width: 100%;">
                    <tr><td style="padding: 0px 0px 0px 0px;">#</td><td>Recipient</td><td>Date</td><td>Info</td><td>View</td></tr>
                    <?php 
                    if(count($mailbyme) !=0){
                        foreach($mailbyme as $msg){                            
                            echo "<tr><td style=\"padding: 0px 0px 0px 0px;\">$msg[issue_no]</td><td>". uNumToName($msg['assigned_to']) ."</td><td>$msg[date_created]</td><td>".account_NumtoName($msg['account_no'] ) ."<br/> $msg[message]</td><td><a href='viewIssues.php?id=$msg[issue_no]' rel='shadowbox;width=700;'> <img src='img/table_edit.png'/></a></td></tr>";
                        }
                    }else
                        {
                            echo "<tr><td colspan='4' style='vertical-align:middle;text-align:center;'>No Issues</td></tr>";
                        }
                    
                    ?>
                    </table>
                    <div style="clear: both;"></div>
                </div>
                <div id="privatein" style="height: auto;">
                 <table style="width: 100%;">
                    <tr><td style="padding: 0px 0px 0px 0px;">#</td><td>Author</td><td>Message</td><td>Date</td></tr>
                    <?php
                        if(count($privateforme) !=0){
                            foreach($privateforme as $privyx){
                                echo "<tr>
                                <td style=\"padding: 0px 0px 0px 0px;\">$privyx[id]</td>
                                <td>";
                                echo uNumToName($privyx['author']);
                                echo "</td>
                                <td>$privyx[message]</td>                                
                                <td>$privyx[date]</td>
                                <td><a href='pmreply.php?id=$privyx[id]' rel='shadowbox;width=700;'><img src='img/7866_6129_64_mail_reply_icon.png' style='width:30px;height:30px;'/></td>
                                </tr>";
                            }                        
                        } else {
                            echo "<tr><td colspan='4' style='vertical-align:middle;text-align:center;'>No Private Messages</td></tr>";
                        }                            
                        ?>
                    </table>
                    <div style="clear: both;"></div>
                </div>
                
                <div id="fragment-4" style="height: auto;">
                    <table style="width: 100%;">
                    <tr><td style="padding: 0px 0px 0px 0px;">#</td><td>Recipient</td><td>Message</td><td>Date</td></tr>
                        <?php
                        if(count($privatebyme) !=0){
                             foreach($privatebyme as $privy){
                                echo "<tr>
                                <td style=\"padding: 0px 0px 0px 0px;\">$privy[id]</td>
                                <td>
                                ";
                                echo uNumToName($privy['recipient']);
                                echo "</td>
                                <td>$privy[message]</td>
                                
                                <td>$privy[date]</td>
                                <td><a href='pmreply.php?id=$privy[id]' rel='shadowbox;width=700;'><img src='img/7866_6129_64_mail_reply_icon.png' style='width:30px;height:30px;'/></td>
                                </tr>";
                            }
                        }else {
                            echo "<tr><td colspan='4' style='vertical-align:middle;text-align:center;'>No Private Messages</td></tr>";
                        }
                        ?>
                    </table>
                    <div style="clear: both;"></div>
                </div>
                <div style="clear: both;"></div>  
            </div>
            <div style="clear: both;"></div>
        </div>
       <div style="clear: both;"></div>
    </div></td></tr>
    <tr><td style="text-align: center;"><div id="emergency" style="width: 1000px;height:56px;background:#ccc;">
            <div class="emergbutton" id="ticket" style="margin-top: 3px;margin-bottom:3px;height:50px;width:50px;;float:left;;background:silver url(img/open.jpg) no-repeat center top;cursor:pointer;"></div>
            <div class="emergbutton" style="margin-top: 3px;margin-bottom:3px;height:50px;width:50px;;float:left;margin-left:10px;background:silver url(img/iq.jpg) no-repeat center top;cursor:pointer;">
            <a href="ticket_queue.php" target="_blank"><img src="img/spot.gif" style="width: 50px;height:50px;"/></a>
            </div>
            <div class="emergbutton" style="margin-top: 3px;margin-bottom:3px;height:50px;width:50px;;float:left;margin-left:10px;background:silver"></div>
            <div class="emergbutton" style="margin-top: 3px;margin-bottom:3px;height:50px;width:50px;;float:left;margin-left:10px;background:silver"></div>
            <div class="emergbutton" style="margin-top: 3px;margin-bottom:3px;height:50px;width:50px;;float:left;margin-left:10px;background:silver"></div>
            <div class="emergbutton" style="margin-top: 3px;margin-bottom:3px;height:50px;width:50px;;float:left;margin-left:10px;background:silver"></div>
        </div></td></tr>
    </table>
      
    <?php } ?>
    <div style="clear: both;"></div>
</div>
<script src="js/general.js"></script>