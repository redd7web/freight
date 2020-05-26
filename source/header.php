<?php 
//ini_set("display_errors",0);
?>
<div id="header">
    <div id="alwaysmiddle">
        <div id="leftx" style="width:59.5%;height:90px;background:transparent;float:left;">
        <div id="quicksearch">
        <?php if(isset($_SESSION['sludge_id'])){ ?>
            <div class="mail">
            <a href="message.php<?php if(isset($_GET['id'])){ echo "?account=".$_GET['id'];} ?>" rel="shadowbox;width=500;height=500"><img src="img/msg.jpg" /></a>
            </div>
            <div id="home"></div>
            <div id="search"></div>
            <div id="spare1" style="width:25px;height:19px;float:left;background:url(https://inet.iwpusa.com/bakery/img/charts-512.png) no-repeat center top;margin-right:2px;background-size:contain;cursor:pointer;"></div>
            <div id="spare2" style="width:25px;height:19px;float:left;background:transparent;margin-right:2px;"></div>
            
            <div id="pw" style="width: 100px;height:19px;float:left;margin-right:2px;">
                <button style="width: 100px;height:20px;font-size:9px;font-family:tahoma;text-align:center;font" id="change">Change Password</button>
            </div>
            <script>
                $("#change").click(function(){
                   Shadowbox.open({
                        content: 'updatepw.php',
                        player:"iframe",
                        width:400,
                        height:100,
                        loadingImage:"shadow/loading.gif", 
                        title: "Update Password",
                        options: {    
                            overlayColor:"#ffffff",
                            overlayOpacity: ".9"
                            
                        }
                   }); 
                });
            </script>
            <div id="name" style="width: auto;min-width:100px;font-size:11px;font-family:tahoma;text-align:center;float:left;margin-right:2px;color:rgb(14, 41, 146);font-weight:bold;"> <?php $person = new Person(); echo $person->login_name;?>, is logged on
            </div>
            
            <div id="logout" style=""></div>
            <?php 
            if($person->isCoWest()){
            ?>
                <style>
                #blogo{
                float: left;height:90px;width:40.5%;background:url(../img/cwlogo_line.jpg) no-repeat right top;
            }
                </style>
            <?php
                        }
            ?>
            
          <?php }else { echo "<span style='width:auto;float:left;'>Please login below</span>"; } ?>  
             <div id="quick_navi" style="width: 103px;height:30px;float:left;margin-left:5px;">
                <select name="module_select" id="module_select"><option>Please select module--</option><option id="bakery">Bakery</option><option id="oil">Oil</option><option id="org">Organics</option></select>
            </div>
        </div> 
        <div id="spacerlogo" style="with:340px;height:11px;"></div> 
        <div id="menu_drown_down" style="height: 45px;width:340px;background:transparent;">
            <?php include "menu.php"; ?>        
        </div>    
     </div> 
     <div id="blogo">
        <div id="title_top_spacer"></div>
        <div id="title" style="width: 100%;text-align:center;color:rgb(14, 41, 146);font-weight:bold;font-size:22px;float:right;text-transform:uppercase;"><span style="float: right;margin-right:10px;font-size:16px;"><?php echo "$page"; ?></span></div>
    </div>
    
    </div>
     
    
</div>
<?php
 $extra="";
 if(isset($_GET['task'])){
         switch($_GET['task']){ 
            case "driverslog":
            case "overview": 
            case "oilrouting":            
            case "indices": 
            case "roi":
            case "vehicles":
            case "friendly":
            case "jobcost":
            case "freq":
            case "freport":
            case "freportarea":      
            case "crequest":
            case"trailer":
            case "forecast":
                $extra = "style='visibility:hidden;display:none;'";
            ?>
            <div id="scaffold" style="height:90px;width:100%;"></div>
            <?php
                break;
           
        }
    }        
?>

<div id="transparent"<?php echo $extra; ?>>
    <style>
    table td{
        padding:5px 5px 5px 5px;
    }
    </style>
    <?php if( !isset($_SESSION['sludge_id'])){ ?>
    <form action="protected/biologin.php" method="post" id="form_one">
    <table style="margin: 0 auto;width:400px;height:100px;font-size:18px;background:#845430">
        <tr><td style="vertical-align: middle;text-align:left;border:0px solid #bbb;"><input name="gtuser" type="text" placeholder="Username" style=" "/></td></tr><tr><td style="vertical-align:middle;border:0px solid #bbb;">
        <input name="gtpw" type="password" placeholder="Password" style="float:left; "/><input type="submit" name="gtsub" value="Submit" style="float:left;margin-left:5px;"/></td></tr>
    </table>
    <input type="text" name="previous" id="previous" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" readonly=""/>
    </form>
    <?php }else {
        //load appropriate search fields / disclaimer
        if(isset($_GET['task'])){
            switch($_GET['task']){ 
                case "driverslog":
                    ?>
                    <form action="management.php?task=driverslog" method="post">
                    <table style="width:800px;margin:auto;">
                    <tr><td>Driver</td><td><?php ?></td><td>Route title</td><td><input type="text" placeholder="Route id" value="<?php echo $_POST['route_title']; ?>" name="route_title" id="route_title"/></td></tr>
                    </table>
                    </form>
                    <?php
                break;
                case "notes":
                    ?>
                    <form action="management.php?task=notes" method="post">
                   <table border="0" align="center" cellpadding="1" style="font-size:smaller; border:1px grey solid; margin-top:20px;"><tbody>
                   <tr>
                   <td>Author</td>
                   <td>
                   <select name="author">
                   <option></option>
                   <?php
                         $axix = $db->query("SELECT user_id,first,last FROM sludge_users");
                         if(count($axix)>0){
                            foreach($axix as $acox){
                                $compare ="";
                                if($_POST['author']==$acox['user_id']){
                                    $compare = "selected";
                                }
                                echo "<option $compare value='$acox[user_id]'>$acox[first] $acox[last]</option>";
                            }
                         }
                   ?>
                   </select>
                   </td>
                   <td>Account</td>
                   <td><select name="account">
                   <option></option>
                   <?php
                         $ax = $db->query("SELECT account_ID,name FROM sludge_accounts");
                         if(count($ax)>0){
                            foreach($ax as $aco){
                                $compare ="";
                                if($_POST['account']==$aco['account_ID']){
                                    $compare = "selected";
                                }
                                echo "<option $compare value='$aco[account_ID]'>$aco[name]</option>";
                            }
                         }
                   ?>
                   </select></td>
                   <td>Facility</td>
                   <td>
                   <?php 
                    if(isset($_POST['search_now'])){
                        echo getFacilityList("",$_POST['facility']);
                    } else {
                        echo getFacilityList("","");
                    }
                   ?>
                   </td>
                   </tr>
                   <tr>
                   
                   <td>Keyword(s)</td>
                   <td><input type="text" placeholder="keyword(s)" value="<?php if(isset($_POST['search_now'])){ echo $_POST['run_key'];  } ?>"  name="run_key"/></td>
                  <td>
                <input type="text" placeholder="start date" style="border-radius: 0px 0px 0px 0px;" id="from" name="from" value="<?php if(isset($_POST['from'])){ echo $_POST['from'];  } ?>"  />&nbsp;
                <input value="<?php if(isset($_POST['to'])){ echo $_POST['to'];  } ?>"  type="text" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to" />

</td><td width="80" align="left"><input type="submit" value="Search" name="search_now" /><br /><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td></tr></tbody></table>
                    </form>
                                        
                    <?php
                    break;
                case "csupport":
                    ?>
                        <form action="management.php?task=csupport" method="post">
                        <table style="margin: auto;width:80%;margin-top:5px;margin-bottom:5px;">
                    <tbody><tr><td nowrap="" align="right" style="padding-left:10px; padding-right:50px;" class="field_label"><div style="margin-right:30px;">Customer Support Period</div><div><input type="text" placeholder="start date" style="border-radius: 0px 0px 0px 0px;" id="from" name="from"  value="<?php if(isset($_POST['from'])){ echo $_POST['from'];  } ?>" />&nbsp;</div><div><input type="text" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to" value="<?php if(isset($_POST['from'])){ echo $_POST['to'];  } ?>" />&nbsp;</div></td><td width="220" align="center">Friendly&nbsp;
                    <?php
                        if(isset($_POST['friendly'])){ 
                            getFriendLists($_POST['friendly']); 
                        } else { 
                            getFriendLists(); 
                        };
                        
                    ?>
                   
</select><br /><br/>Facility <?php 
    if(isset($_POST['facility'])){ 
        getFacilityList("",$_POST['facility']);
    } else { 
        getFacilityList(); 
    }   ?></td><td width="220" align="center">State <input value="" size="3" name="state" value="<?php  
    if( isset($_POST['state']) ){ echo $_POST['state'];} ?>" /></td></tr>
<tr><td colspan="5"><input type="submit" style="float: right;" value="Search" name="search_now"/><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td></tr>
</tbody>
                    </table>
                    </form>
                    <?php
                    break;
                case "alloil":
                    ?>
                    <form action="management.php?task=alloil" method="POST">
                    <table style="margin: auto;width:80%;">
                    <tbody><tr>
                        <td nowrap="" align="right" style="padding-left:10px; padding-right:50px;" class="field_label">
                            <div style="margin-right:30px;">Oil Collection Period</div>
                            <div>
                                <input type="text" placeholder="start date" style="border-radius: 0px 0px 0px 0px;" id="from" name="from" value="<?php  
                                if(isset($_POST['from'])){  
                                    echo $_POST['to'];
                                } ?>" />
                            </div>
                            <div>
                                <input type="text" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to" value="<?php  if(isset($_POST['to'])){  
                                    echo $_POST['to'];
                                } ?>" />
                            </div>
                            </td>
                            
                            <td style="vertical-align: top;text-align: left;">
                                Friendly <?php if(isset($_POST['friendly'])){ getFriendLists($_POST['friendly']); } else { getFriendLists(); }
 ?><br /><br />
                                Facility <?php if(isset($_POST['facility'])){ getFacilityList("",$_POST['facility']);} else { getFacilityList(); } ?>
                            </td>
                            <td width="280" align="center">
                                Account Name <input value="<?php if(isset($_POST['account_id'])){ echo $_POST['account_id'];  } ?>"  size="15" name="account_name"/>Account ID <input value="" size="10" name="account_id"/>
                            </td>
                            <td width="220" align="center" style="vertical-align: top;text-align:left;">State <input value="<?php if(isset($_POST['state'])){ echo $_POST['state'];  } ?>" size="3" name="state" /></td></tr>
                            <tr>
                          
                            <td colspan="4" style="text-align: right;"><input type="submit" name="search_now" value="Filter Now"/></td></tr>
</tbody>
                    </table>
                    </form>
                    <?php
                    break;
                 case "cancel":
                    ?> 
                    <form method="post" action="management.php?task=cancel">
                    <table style="width: 50%;margin:auto;margin-top:20px;margin-bottom:20px;">
                    <tr><td style="vertical-align: top;">Facility</td><td  style="text-align: center;vertical-align:top;"><?php if(isset($_POST['facility'])){ getFacilityList("",$_POST['facility']);} else { getFacilityList(); }; ?></td></tr>
                    <tr><td style="vertical-align: top;">Date Range</td><td style="text-align:center;vertical-align:top;">
                        <input style="border-radius: 0px 0px 0px;" type="text" name="from"   value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>"  placeholder="Start Date" id="cancelstart"/><br /><br />
                        <input  value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" style="border-radius: 0px 0px 0px;" type="text" name="to"  placeholder="End Date"  id="cancelend"/>
                        </td></tr>
                        <tr><td>Friendly</td><td> <input type="radio" <?php if($_POST['friend'] == 1){ echo " checked "; } ?>   name="friend" value="1"/>&nbsp;Exclude Friendlies <br /><input <?php if($_POST['friend'] == 2){ echo " checked "; } ?>  type="radio" name="friend" value="2"/>&nbsp;Include Friendlies</td></tr>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                             
                             <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>"  style="margin-left:10px;">Default Data View</a>&nbsp;<input type="reset" value="Reset"/>&nbsp;<input type="submit" value="Search Now" name="search_now" style="margin-left:10px;"/>
                        </td>
                    </tr>
                    
                    </table>
                    </form>
                    <?php
                    break;
                    
                case "asset":
                ?>
                <form action="management.php?task=asset" method="post">
                    <table border="0" align="center" cellpadding="1" style="font-size:smaller; border:1px grey solid; margin-top:20px;"><tbody><tr>
                    <td style="text-align:center;">
                    Container 
                    </td>
                    <td>
                    <?php
                        if(isset($_POST['search_now'])){
                            containerList($_POST['container_size'],"");
                        } else {
                            containerList("","");    
                        }
                    ?>
                    </td>
                    <td>Facility</td>
                    <td>
                    <?php
                        if(isset($_POST['search_now'])){
                            getFacilityList("",$_POST['facility']);
                        }else {
                            getFacilityList("","");        
                        }
                        
                    ?></a>
                    </td>
                    <td>
                    Date delivered
                    </td>
                    <td>
                        <input type="text" placeholder="Date Delivered" name="delivered" value="<?php  if(isset($_POST['search_now'])){ echo $_POST['delivered']; }?>" id="delivered"/>
                    </td>
                    </tr>
                    <tr>
                    
                    <td style="text-align: center;">Account:</td>
                    <td>
                    <select name="accounts"><option></option>
                    <?php 
                    $kl = $db->query("SELECT name,account_ID FROM sludge_accounts WHERE status ='active'");
                    if(count($kl)>0){
                        
                        foreach($kl as $lk){
                            $compare = '';
                            $selected ='';
                            if(isset($_POST['search_now'])){
                                $compare = $_POST['accounts'];
                            }
                            if($compare == $lk['account_ID']){ echo  $selected =" selected "; }
                            
                            echo "<option $selected value='$lk[account_ID]''>$lk[name]</option>";
                        }
                    }
                    ?>
                    </select>
                    </td>
                    
                    <td colspan="2"><input type="submit" value="submit now" name="search_now"/>&nbsp;&nbsp;<a href="management.php?task=friendly">Default Data View</a></td>
                    </tr></tbody></table></form>
                <?php
                break; 
                case "users":                    
                    ?>
                    <table border="0" align="center" cellpadding="1"><tbody>
                        <tr>
                    
                            <td width="100" style="white-space:nowrap;font-weight:bold;">Name&nbsp;<input  value="<?php  if( isset($_POST['search_value']) ){ echo $_POST['search_value'];} ?>" size="15" name="search_value" id="search_value"/>
                            </td>
                            <td nowrap="" align="right" style="padding-left:10px;font-weight:bold;" class="field_label">
                            User Group
                            </td>
                            <td width="200" align="left">
                                <select id="group_id" name="group_id"><option value="">All</option>
                                <option <?php if($_POST['group_id'] == "1020" ) { echo "selected"; } ?>  value="1020">Data Entry</option>
                                
                                <option  <?php if($_POST['group_id'] == "1056" ) { echo "selected"; } ?>   value="1056">Customer Support (Basic)</option>
                                
                                <option  <?php if($_POST['group_id'] == "1057" ) { echo "selected"; } ?>   value="1057">Customer Support (Full)</option>
                                
                                <option  <?php if($_POST['group_id'] == "1040" ) { echo "selected"; } ?>   value="1040">Sales</option>
                                
                                <option  <?php if($_POST['group_id'] == "1025" ) { echo "selected"; } ?>   value="1025">Driver</option>
                                
                                <option  <?php if($_POST['group_id'] == "1050" ) { echo "selected"; } ?>   value="1050">Routing</option>
                                
                                <option  <?php if($_POST['group_id'] == "1060" ) { echo "selected"; } ?>   value="1060">Advanced Searching</option>
                                
                                <option  <?php if($_POST['group_id'] == "1061" ) { echo "selected"; } ?>   value="1061">Report Access</option>
                                
                                <option  <?php if($_POST['group_id'] == "1020" ) { echo "selected"; } ?>   value="1062">Routing (Advanced)</option>
                                
                                <option  <?php if($_POST['group_id'] == "1058" ) { echo "selected"; } ?>   value="1058">Customer Support (Advanced)</option>
                                
                                <option  <?php if($_POST['group_id'] == "1030" ) { echo "selected"; } ?>   value="1030">Sales Management</option>
                                
                                <option  <?php if($_POST['group_id'] == "1059" ) { echo "selected"; } ?>   value="1059">Staff Management</option>
                                
                                <option  <?php if($_POST['group_id'] == "130" ) { echo "selected"; } ?>   value="130">User Management</option>
                                
                                <option  <?php if($_POST['group_id'] == "1010" ) { echo "selected"; } ?>   value="1010">Data Management</option>
                                
                                <option  <?php if($_POST['group_id'] == "1063" ) { echo "selected"; } ?>   value="1063">User Management (Advanced)</option>
                                
                                <option  <?php if($_POST['group_id'] == "1052" ) { echo "selected"; } ?>   value="1052">Business Management</option>
                                </select></td>
                                
                                <td nowrap="" align="right" style="padding-left:10px;font-weight:bold;" class="field_label">Staff Role</td>
                                <td width="200" align="left">
                            <select id="role_id" name="role_id"><option value="">All</option>
                            <option  <?php if($_POST['role_id'] == "1" ) { echo "selected"; } ?>   value="1">Customer Support</option>
                            
                            <option  <?php if($_POST['role_id'] == "2" ) { echo "selected"; } ?>   value="2">Account Representative</option>
                            
                            <option  <?php if($_POST['role_id'] == "3" ) { echo "selected"; } ?>   value="3">Sales Representative</option>
                            
                            <option  <?php if($_POST['role_id'] == "19" ) { echo "selected"; } ?>   value="19">Sales Leads User</option>
                            
                            <option  <?php if($_POST['role_id'] == "4" ) { echo "selected"; } ?>   value="4">Service Driver</option>
                            
                            <option  <?php if($_POST['role_id'] == "5" ) { echo "selected"; } ?>   value="5">Oil Driver</option>
                            
                            <option  <?php if($_POST['role_id'] == "11" ) { echo "selected"; } ?>   value="11">Scheduler</option>
                            
                            <option  <?php if($_POST['role_id'] == "7" ) { echo "selected"; } ?>   value="7">Facility Manager</option>
                            
                            <option  <?php if($_POST['role_id'] == "8" ) { echo "selected"; } ?>   value="8">Corporate Manager</option>
                            
                            <option  <?php if($_POST['role_id'] == "9" ) { echo "selected"; } ?>   value="9">Shop Crew</option>
                            
                            <option  <?php if($_POST['role_id'] == "10" ) { echo "selected"; } ?>   value="10">Sales Zone Manager</option>
                            
                            <option  <?php if($_POST['role_id'] == "12" ) { echo "selected"; } ?>   value="12">Can be Assigned Issues</option>
                            
                            <option  <?php if($_POST['role_id'] == "13" ) { echo "selected"; } ?>   value="13">MMS for New fires</option>
                            
                            <option  <?php if($_POST['role_id'] == "18" ) { echo "selected"; } ?>   value="18">MMS for Theft Alert</option>
                            
                            <option  <?php if($_POST['role_id'] == "14" ) { echo "selected"; } ?>   value="14">MMS for Call Center Message</option>
                            
                            <option  <?php if($_POST['role_id'] == "15" ) { echo "selected"; } ?>   value="15">Receive Phone Messages</option>
                            
                            
                            
                            <option value="16">CIP Enterprise Contact</option>
                            </select></td><td style="white-space:nowrap;font-weight:bold;"><input type="checkbox" name="include_inactive"  value="<?php  if( isset($_POST['inclued_inactive']) ){ echo "checked";} ?>"  style="margin-right: 10px;" />Show&nbsp;Inactive</td><td width="80" align="left"><input type="submit" value="Search" name="search_now" /><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td></tr></tbody></table>
                    <?php
                    break;
                case "staff":
                    ?>
                    
                     <table border="0" align="center" cellpadding="1" style="font-size: 12px; border:1px grey solid; margin:auto;width:80%;margin-top:35px;font-weight:normal;"><tbody><tr><td style="white-space:nowrap;">Search for 
                     
                     <input value="" size="15" name="search_value"  value="<?php  if( isset($_POST['search_value']) ){ echo $_POST['search_value'];} ?>"/> and/or choose </td>
                    <td nowrap="" align="right" style="padding-left:10px;vertical-align:middle;" class="field_label">Role</td>
                    <td width="200" align="left">
                    <select id="role_id" name="role_id"><option value="">All</option>
                    <option  <?php if($_POST['role_id'] == "1" ) { echo "selected"; } ?>   value="1">Customer Support</option>
                    
                    <option  <?php if($_POST['role_id'] == "2" ) { echo "selected"; } ?>   value="2">Account Representative</option>
                    
                    <option  <?php if($_POST['role_id'] == "3" ) { echo "selected"; } ?>   value="3">Sales Representative</option>
                    
                    <option  <?php if($_POST['role_id'] == "19" ) { echo "selected"; } ?>   value="19">Sales Leads User</option>
                    
                    <option  <?php if($_POST['role_id'] == "4" ) { echo "selected"; } ?>   value="4">Service Driver</option>
                    
                    <option  <?php if($_POST['role_id'] == "5" ) { echo "selected"; } ?>   value="5">Oil Driver</option>
                    
                    <option  <?php if($_POST['role_id'] == "11" ) { echo "selected"; } ?>   value="11">Scheduler</option>
                    
                    <option  <?php if($_POST['role_id'] == "7" ) { echo "selected"; } ?>   value="7">Facility Manager</option>
                    
                    <option  <?php if($_POST['role_id'] == "8" ) { echo "selected"; } ?>   value="8">Corporate Manager</option>
                    
                    <option  <?php if($_POST['role_id'] == "9" ) { echo "selected"; } ?>   value="9">Shop Crew</option>
                    
                    <option  <?php if($_POST['role_id'] == "10" ) { echo "selected"; } ?>   value="10">Sales Zone Manager</option>
                    
                    <option  <?php if($_POST['role_id'] == "12" ) { echo "selected"; } ?>   value="12">Can be Assigned Issues</option>
                    
                    <option  <?php if($_POST['role_id'] == "13" ) { echo "selected"; } ?>   value="13">MMS for New fires</option>
                    
                    <option  <?php if($_POST['role_id'] == "18" ) { echo "selected"; } ?>   value="18">MMS for Theft Alert</option>
                    
                    <option  <?php if($_POST['role_id'] == "14" ) { echo "selected"; } ?>   value="14">MMS for Call Center Message</option>
                    
                    <option  <?php if($_POST['role_id'] == "15" ) { echo "selected"; } ?>   value="15">Receive Phone Messages</option>
                    
                    
                    <option  <?php if($_POST['role_id'] == "16" ) { echo "selected"; } ?>   value="16">CIP Enterprise Contact</option>
                    </select></td><td style="font-size:80%; white-space:nowrap;width:30%;">
                    
                    <input type="checkbox" name="include_inactive" style="margin-right:5px;"  value="<?php  if( isset($_POST['include_inactive']) ){ echo $_POST['include_inactive'];} ?>"/>&nbsp;&nbsp;&nbsp;Show Inactive</td><td width="80" align="left"><input type="submit" value="Search" name="search_now"/><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td></tr></tbody></table>
                    
                    <?php
                    break;
                case "accounts":    
                
                    $fr1 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =201");
                    $fr2 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =202");
                    $fr3 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =203");
                    $fr4 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =204");
                    $fr5 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =205");
                    $fr6 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =206");
                    $fr7 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =207");
                    $fr8 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =208");
                    $fr9 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =209");
                    $fr10 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =210");
                    $fr11 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =211");
                    
                    $tmp1 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =212");
                    $tmp2 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =213");
                    $tmp3 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =214");
                    $tmp4 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =215");
                    $tmp5 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =216");
                               
                   ?>
                   <style type="text/css">
                   input[type=text]{
                    width:100px;
                    border-radius:0px 0px 0px 0px;
                    border:1px solid #bbb;
                    height:25px
                   }
                   </style>
                   <div id="mainHolder" style="width: 1000px;height:auto;min-height:200px;margin:auto;">
                        <form method="post" action="customers.php?task=accounts">
                       <table style="float: left;margin-right:10px;width:300px;">
                            <tr><td>Account Status</td><td style="text-align: left;">
                            <select id="status" name="status" class="field" rel="status" >
                    <option value="ignore">--</option>
                   
                    <option  <?php if($_POST['status'] == "Archive" ) { echo "selected"; } ?>   value="Archive">Archived</option>
                    <option  <?php if($_POST['status'] == "New" ) { echo "selected"; } ?>   value="New">New</option>
                    <option  <?php if($_POST['status'] == "Active" ) { echo "selected"; } ?>   value="Active">Active</option>
                    <option  <?php if($_POST['status'] == "pending" ) { echo "selected"; } ?>   value="pending">Pending</option>
                </select></td></tr>
                            <tr><td>Payment</td><td>
                            
                                <select id="payment_type_id" name="payment_type_id"><option value="ignore">All</option>
                                   <option>--</option>
                                    <option <?php if($_POST['payment_type_id'] == "No Pay") { echo "selected";}  ?> value="No Pay"  id="np">No Pay</option>
                                    <option  <?php if($_POST['payment_type_id'] == "Jacobson") { echo "selected";}  ?>  value="Jacobson" id="index">Index (Jacobson)</option>
                                    <option <?php if($_POST['payment_type_id'] == "Per Gallon") { echo "selected";}  ?>  value="Per Gallon" id="pg">Per Gallon</option>
                                    <option <?php if($_POST['payment_type_id'] == "O.T.P. Per Gallon") { echo "selected";}  ?>  value="O.T.P. Per Gallon" id="otppg">One Time Payment Per Gallon</option>
                                    <option <?php if($_POST['payment_type_id'] == "O.T.P.") { echo "selected";}  ?> value="O.T.P." id="otp">One Time Payment</option>
                                    <option <?php if($_POST['payment_type_id'] == "Cash On Delivery") { echo "selected";}  ?>  value="Cash On Delivery">Cash On Delivery</option>
                                    </select></td>
        </tr>
                            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                       </table>
                       
                        <table style="float: left;margin-right:10px;width:300px;">
                            <tr><td>Flag</td><td><select id="flag_id" name="flag_id"><option value="">--</option>
                                    <option <?php if($_POST['flag_id'] == 1) { echo "selected";}  ?> value="1">Needs Contract</option>
                                    
                                    <option <?php if($_POST['flag_id'] == 2) { echo "selected";}  ?> value="2">Needs Profile</option>
                                    
                                    <option <?php if($_POST['flag_id'] == 3) { echo "selected";}  ?> value="3">Needs Cancellation Letter</option>
                                    
                                    <option <?php if($_POST['flag_id'] == 6) { echo "selected";}  ?> value="6">Bad Payment Address</option>
                                    
                                    <option <?php if($_POST['flag_id'] == 7) { echo "selected";}  ?> value="7">Bad Main Address</option>
                                    
                                    <option <?php if($_POST['flag_id'] == 10) { echo "selected";}  ?> value="10">Re-sale Prospect</option>
                                    
                                    
                                    <option <?php if($_POST['flag_id'] == 4) { echo "selected";}  ?> value="4">Out Of Business</option>
                                    
                                    <option <?php if($_POST['flag_id'] == 8) { echo "selected";}  ?> value="8">Restaurant Canceled</option>
                                    
                                    <option <?php if($_POST['flag_id'] == 9) { echo "selected";}  ?> value="9">Lost to General</option>
                                    
                                    <option <?php if($_POST['flag_id'] == 11) { echo "selected";}  ?> value="11">Lost to Baker</option>
                                    
                                    <option <?php if($_POST['flag_id'] == 12 ) { echo "selected";}  ?> value="12">Lost to Darling</option>
                                    </select></td></tr>
                            <tr><td>Original Sale</td><td><?php if(isset($_POST['orig'])){ echo getOrigRep($_POST['orig']); } else {  echo getOrigRep(); }    ?></td></tr>
                            <tr><td>Account Rep</td><td><?php if(isset($_POST['salesrep'])){ echo getSalesRep($_POST['salesrep']); } else {  echo getSalesRep(); }  ?></td></tr>
                       </table>
                       
                        <table style="float: left;width:380px;">
                            <tr><td>Friendly</td><td><?php if(isset($_POST['friendly'])){ getFriendLists($_POST['friendly']); } else { getFriendLists(); } ?></td></tr>
                            <tr><td>Previous Provider</td><td><?php if(isset($_POST['prev_compet'])){ previousP($_POST['prev_compet']);  } else { previousP();  }  ?></td></tr>
                            <tr><td>Disposition</td><td><select id="disposition" name="disposition">
                                <option <?php if($_POST['disposition'] == "all" ) { echo "selected";}  ?>  value="all">All</option>
                                
                                <option <?php if($_POST['disposition'] == "own" ) { echo "selected";}  ?> value="own">Accounts I Own</option>
                                
                                <option <?php if($_POST['disposition'] == "orig" ) { echo "selected";}  ?> value="orig">Accounts I Originated</option>
                                
                                <option <?php if($_POST['disposition'] == "own_not_orig" ) { echo "selected";}  ?> value="own_not_orig">Own but did not Originate</option>
                                </select></td></tr>
                       </table>
                       <div style="width: 1000px;clear:both"></div>
                       
                       <?php 
                       
                       
                       
                       if(!$person->isFriendly() && !$person->isCoWest()){
                        ?>
                        <table style="width:400px;background:white;height:100px;width:100%;float:left;">
                                <tr>
                                    <td colspan="3" style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;Facility</span></td>
                                </tr>
                            <tr>
                            <td style="vertical-align:top;width:50%;pading:0px 0px 0px 0px;text-align:left;">
                                <ul class="facs">          
                                    <li><input id="all" name="all" <?php if(isset($_POST['all'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;All</li>
                                    <li><input id="all_frack" name="all_frack" <?php if(isset($_POST['all_frack'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;All Frack Tanks</li>
                                   <li><input class="frac fac" value="201" id="Frack_Tank_1" name="fracktank1" <?php  if($fr1[0]['locked'] == 1 || $fr1[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; } if(isset($_POST['fracktank1'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 1</li>
                                   <li><input class="frac fac"  value="202"   id="Frack_Tank_2" name="fracktank2" <?php  if($fr2[0]['locked'] == 1 || $fr2[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank2'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 2</li>
                                   <li><input class="frac fac"  value="203"  id="Frack_Tank_3" name="fracktank3" <?php  if($fr3[0]['locked'] == 1 || $fr3[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank3'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 3</li>
                                   <li><input class="frac fac"  value="204"  id="Frack_Tank_4" name="fracktank4" <?php  if($fr4[0]['locked'] == 1 || $fr4[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank4'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 4</li>
                                   <li><input class="frac fac"  value="205"  id="Frack_Tank_5" name="fracktank5" <?php  if($fr5[0]['locked'] == 1 || $fr5[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank5'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 5</li>
                                   <li><input class="frac fac"  value="206"  id="Frack_Tank_6" name="fracktank6" <?php  if($fr6[0]['locked'] == 1 || $fr6[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank6'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 6</li>
                                   <li><input class="frac fac"  value="207"  id="Frack_Tank_7" name="fracktank7" <?php  if($fr7[0]['locked'] == 1 || $fr7[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank7'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 7</li>
                                   <li><input class="frac fac"  value="208"  id="Frack_Tank_8" name="fracktank8" <?php  if($fr8[0]['locked'] == 1 || $fr8[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank8'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 8</li>
                                   <li><input class="frac fac"  value="209"  id="Frack_Tank_9" name="fracktank9" <?php   if($fr9[0]['locked'] == 1 || $fr9[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; } if(isset($_POST['fracktank9'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 9</li>
                                   <li><input class="frac fac"  value="210"  id="Frack_Tank_10" name="fracktank10" <?php   if($fr10[0]['locked'] == 1 || $fr10[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank10'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 10</li>
                                   <li><input class="frac fac"  value="211"  id="Frack_Tank_11" name="fracktank11" <?php  if($fr11[0]['locked'] == 1 || $fr11[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank11'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 11</li>
                                   
                                   <li><input class="frac fac"   value="212"id="tmp1" name="tmp1" <?php  if($tmp1[0]['locked'] == 1 || $tmp1[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['tmp1'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Temp Frac1</li>
                                   <li><input class="frac fac"  value="213" id="tmp2" name="tmp2" <?php  if($tmp2[0]['locked'] == 1 || $tmp2[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['tmp2'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Temp Frac2</li>
                                   <li><input class="frac fac"  value="214" id="tmp3" name="tmp3" <?php  if($tmp3[0]['locked'] == 1 || $tmp3[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['tmp3'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Temp Frac3</li>
                                   
                                   <li><input class="frac fac"  value="215" id="tmp4" name="tmp4" <?php  if($tmp4[0]['locked'] == 1 || $tmp4[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['tmp4'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Temp Frac4</li>
                                   
                                   <li><input class="frac fac"  value="216" id="tmp5" name="tmp5" <?php  if($tmp5[0]['locked'] == 1 || $tmp5[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['tmp5'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Temp Frac5</li>
                                    
                                      
                                    
                                </ul>
                            </td>
                            <td style="width: 80%;pading:0px 0px 0px 0px;text-align:left;">
                                <ul class="facs">          
                                                                       
                                 
                                    <li><input value="15" name="fac15" type="checkbox" class="fac uc"  <?php if(isset($_POST['fac15'])){ echo "checked"; } else {  if($person->facility == 15){ echo "checked"; } }?>/>&nbsp;W Division Bernadino</li>    
                                    
                                    <li><input value="18" name="fac18" type="checkbox" class="fac"  <?php if(isset($_POST['fac18'])){ echo "checked"; } else {  if($person->facility == 18){ echo "checked"; } }?>/>&nbsp;RC Waste Resources LC</li> 
                                    <li><input name="allselma"  id="allselma" type="checkbox" class="selma"/>&nbsp;All Selma</li>
                                    <li><input value="16" name="fac16" type="checkbox" class="fac"  <?php if(isset($_POST['fac16'])){ echo "checked"; } else {  if($person->facility == 16){ echo "checked"; } }?>/>&nbsp;Victorville</li>  
                                     <li><input value="17" name="fac17" type="checkbox" class="fac"  <?php if(isset($_POST['fac17'])){ echo "checked"; } else {  if($person->facility == 17){ echo "checked"; } }?>/>&nbsp;RC Waste Resources MV</li>   
                                    
                                    
                                </ul>
                            </td>
                            </tr>
                           
                           
                            </table>                        
                        <?php                        
                                               
                       }
                       
                       ?>
                       <div style="width: 1000px;clear:both"></div>
                        <table style="width: 1000px;margin:auto;">
                           <tr>
                            <td  style="padding: 0px 0px 0px 0px;width:70px;text-align:center;">Id</td>
                            <td style="padding: 0px 0px 0px 0px;text-align:center;">Name</td>
                            <td  style="padding: 0px 0px 0px 0px;text-align:center;">Address</td>
                            <td  style="padding: 0px 0px 0px 0px;text-align:center;">City</td>
                            <td  style="padding: 0px 0px 0px 0px;text-align:center;">State</td>
                            <td  style="padding: 0px 0px 0px 0px;text-align:center;">Zip</td>
                            <td  style="padding: 0px 0px 0px 0px;text-align:center;">Area</td>
                            <td  style="padding: 0px 0px 0px 0px;text-align:center;">Phone</td>
                            </tr>
                           <tr>
                                <td style="padding: 5px 5px 5px 5px;text-align:center;"><input id="id" placeholder="id" name="id"  value="<?php  if( isset($_POST['id']) ){ echo $_POST['id'];} ?>" type="text"/></td>
                                <td style="padding: 5px 5px 5px 5px;text-align:center;"><input id="name" placeholder="name" name="name"  value="<?php  if( isset($_POST['name']) ){ echo $_POST['name'];} ?>"  type="text"/></td>
                                <td style="padding: 5px 5px 5px 5px;text-align:center;"><input id="address" placeholder="address" name="address"  value="<?php  if( isset($_POST['address']) ){ echo $_POST['address'];} ?>" type="text"/></td>
                                <td style="padding: 5px 5px 5px 5px;text-align:center;"><input id="city" placeholder="city" name="city"  value="<?php  if( isset($_POST['city']) ){ echo $_POST['city'];} ?>" type="text"/></td>
                                <td style="padding: 5px 5px 5px 5px;text-align:center;"><input id="state" placeholder="state" name="state"  value="<?php  if( isset($_POST['state']) ){ echo $_POST['state'];} ?>" type="text"/></td>
                                <td style="padding: 5px 5px 5px 5px;text-align:center;"><input id="zip" placeholder="zip" name="zip"  value="<?php  if( isset($_POST['zip']) ){ echo $_POST['zip'];} ?>" type="text"/></td>
                                <td style="padding: 5px 5px 5px 5px;text-align:center;"><input id="area" placeholder="area" name="area"  value="<?php  if( isset($_POST['area']) ){ echo $_POST['area'];} ?>"  type="text"/></td>
                                <td style="padding: 5px 5px 5px 5px;text-align:center;"><input id="phone" placeholder="phone" name="phone"  value="<?php  if( isset($_POST['phone']) ){ echo $_POST['phone'];} ?>"  type="text"/></td>
                                
                            </tr>
                       </table>    
                       <table style="width: 1000px;"><tr><td style="text-align: center;"><span style="font-size:smaller; margin-right:20px;" class="field_label">View:</span><span style="margin-right:20px;" class="field_label">
                       
                       <input type="checkbox" id="full_contact"  <?php  if( isset($_POST['full_contact'] ) ){ echo "checked";} ?> name="full_contact"/>&nbsp;More Contact Info</span>
<span style="margin-right:20px;" class="field_label">&nbsp;
                        <input type="checkbox"  <?php  if( isset($_POST['get_files']) ){ echo "checked";} ?> title="Display uploaded files" id="get_files" name="get_files"/>&nbsp;Uploaded Files</span>
<span style="margin-right:20px;" class="field_label"><input type="checkbox"  <?php  if( isset($_POST['oil_stats']) ){ echo "checked";} ?> id="oil_stats" name="oil_stats"/>&nbsp;Pickup Info</span>
<span style="margin-right:20px;" class="field_label"><input type="checkbox"  <?php  if( isset($_POST['get_ppg']) ){ echo "checked";} ?> id="get_ppg" name="get_ppg"/>&nbsp;Payment Info</span>
<span style="margin-right:20px;" class="field_label"><input type="checkbox"  <?php  if( isset($_POST['get_sales_rep']) ){ echo "checked";} ?> id="get_sales_rep" name="get_sales_rep"/>&nbsp;Sales &amp; Rep</span>
</div></td></tr>
<tr><td colspan="10" style="text-align: right;"><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a><input type="submit" style="margin-left: 5px;"  value="Search" name="search_now" id="search_now"/></td></tr>
</table>
                    <!--
                    <table style="width: 1000px;margin:auto;margin-bottom:15px;">
                        <tbody><tr><td nowrap="" align="center" class="field_label">Sort By</td>
                            <td><select id="sort_fields_0" name="sort_fields[0]">
                            <option  selected="" value="">None</option>
                            <option value="sb_account_id">ID</option>
                            <option value="account_status_id">Status</option>
                            <option value="account_class_id">Class</option>
                            <option value="company_name">Name</option>
                            <option value="city">City</option>
                            <option value="state">State</option>
                            <option value="date_created">Created</option>
                            <option value="date_contract_<br />expires">Expires</option>
                            <option value="lcount">Locations</option>
                            </select>
                            </td><td width="70"><select id="sort_directions_0" name="sort_directions[0]">
                            <option selected="" value=""> - </option>
                            <option value="asc">^</option>
                            <option value="desc">V</option>
                            </select>
                            </td>
                            <td nowrap="" align="center" class="field_label">Then By</td>
                            <td><select id="sort_fields_1" name="sort_fields[1]">
                            <option selected="" value="">None</option>
                            <option value="sb_account_id">ID</option>
                            <option value="account_status_id">Status</option>
                            <option value="account_class_id">Class</option>
                            <option value="company_name">Name</option>
                            <option value="city">City</option>
                            <option value="state">State</option>
                            <option value="date_created">Created</option>
                            <option value="date_contract_expires">Expires</option>
                            <option value="lcount">Locations</option>
                            </select>
                            </td><td width="70"><select id="sort_directions_1" name="sort_directions[1]">
                            <option selected="" value=""> - </option>
                            <option value="asc">^</option>
                            <option value="desc">V</option>
                            </select>
                            </td>
                            <td nowrap="" align="center" class="field_label">Then By</td>
                            <td><select id="sort_fields_2" name="sort_fields[2]">
                            <option selected="" value="">None</option>
                            <option value="sb_account_id">ID</option>
                            <option value="account_status_id">Status</option>
                            <option value="account_class_id">Class</option>
                            <option value="company_name">Name</option>
                            <option value="city">City</option>
                            <option value="state">State</option>
                            <option value="date_created">Created</option>
                            <option value="date_contract_expires">Expires</option>
                            <option value="lcount">Locations</option>
                            </select>
                            </td><td width="70"><select id="sort_directions_2" name="sort_directions[2]">
                            <option selected="" value=""> - </option>
                            <option value="asc">^</option>
                            <option value="desc">V</option>
                            </select>
                            </td>
                            </tr> ---!>
                            
                            
                                                
                            </form>
                   </div>
                   <?php
                    break; 
                case "cop":
                    ?>
                    <form action="scheduling.php?task=cop" method="post">
                    <input type="hidden" value="report_collected_fires" name="task" value="<?php if(isset($_POST['get_stats'])){ echo $_POST['get_stats'];} ?>" />
                    
                    <table style="font-size:smaller;margin:auto;margin-top:20px;table-layout:fixed;width:50%;margin-bottom:20px;">
                    
                    <tbody><tr>
                     <td >Route title</td><td><input type="text" value="<?php if(isset($_POST['rtitle'])){ echo $_POST['rtitle']; } else {echo "";} ?>" placeholder="route title" id="rtitle" name="rtitle"/></td></tr>
                     
                     <tr>
                   <td  style="width:33%;text-align:left;">Route id</td><td><input type="text" name="rid" placeholder="Route Id" value="<?php if(isset($_POST['rid'])){ echo $_POST['rid']; } else {echo "";} ?>"/></td></tr>
                   
                   <tr>
                    <td    style="width:33%;text-align:left;" class="field_label">Group By</td><td><select id="my_group" name="my_group">
                    <option value="-">--</option>
                    <option value="created_by">Created By</option>
                    <?php if(!$person->isFriendly() && !$person->isCoWest()){ ?>
                    <option value="recieving_facility">Facility</option>
                    <?php } ?>
                    <option value="driver">Driver</option>
                    <option value="created_by">Created By</option>
</select></td></tr>
               
                <tr>
<td  style="text-align:left;" class="field_label">

Wait Days Range </td><td> <input type="text" id="min" name="min" placeholder="min days" style="width: 90px;"  value="<?php if(isset($_POST['search_now'])){ echo $_POST['min'];  } ?>"/>&nbsp;to&nbsp;<input type="text" id="max" name="max" placeholder="max days"  style="width: 90px;"   value="<?php if(isset($_POST['search_now'])){ echo $_POST['max'];  } ?>"/>

</td></tr>

            <tr>
            <td  style="text-align:left;" class="field_label">
            <input type="radio" name="report_type"  <?php 
                    if(isset($_POST['search_now'])){  
                        if($_POST['report_type'] == 1) { 
                                echo "checked='checked'";
                        } 
                    } ?>  value="1"/> Date Reported<br />
            
            
            <input type="radio" name="report_type" <?php if(isset($_POST['search_now'])){  
                if($_POST['report_type'] == 2) { 
                    echo "checked='checked'";
                    }   
                } ?>  value="2"/>&nbsp;Date Collected</td><td>

            
            
            <input type="text" value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" id="from" name="from" placeholder="From: "/><br />
            <input type="text" value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" id="to" name="to" placeholder="To: "/></td>
            </tr>
            
            <tr>
            <td colspan="2"  style="text-align:right;"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>" style="margin-left: 10px;">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" value="Search" style="margin-left: 10px;" name="search_now"/>
</td></tr></tbody></table></form>
<script>
                    $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    </script>
                    <?php
                    break;  
               
                case "containers":
                ?>
                
                
                <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller"><tbody><tr><td nowrap="" align="right" class="field_label" style="width: 20%;">Container Class</td>
<td style="width: 30%;">

               <?php 
               
               if(isset($_POST['container_size'])){
                    containerList($_POST['container_size'],""); 
               } else {
                    containerList("","");
               }
               ?>

</td>
<td  style="width: 20%;"><input size="15" value="<?php if(isset($_POST['search_value'])){ echo $_POST['search_value'] ;} ?>" name="search_value" /></td>

<td width="80" align="left"><input type="submit" value="Search" name="search_now" /><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td><td><button>Export XLS</button></td></tr>
<tr><td colspan="5">

            <input type="text" value="<?php if(isset($_POST['from'])){ echo $_POST['from'];  } ?>" placeholder="start date" style="border-radius: 0px 0px 0px 0px;" id="from" name="from" />&nbsp;<input value="<?php if(isset($_POST['to'])){ echo $_POST['from'];  } ?>" type="text" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to" />
            
            
<script>
$("input#from").datepicker({dateFormat: "yy-mm-dd"});
$("input#to").datepicker({dateFormat: "yy-mm-dd"});
</script></td></tr></table>

             
                    <script>
                        $("input#start").datepicker();
                        $("input#end").datepicker();
                    </script>
                    

                <?php
                    break;     
                case "newloc":
                    ?>
                    <form action="management.php?task=newloc" method="post">
                    <table style="font-size:smaller;margin:auto;margin-bottom:20px;margin-top:20px;width:50%;"><tbody>
                        <tr><td class="field_label"><input type="checkbox"  <?php  if( isset($_POST['get_reps']) ){ echo "checked";} ?> name="get_reps" />Show Sales Reps
                    <br/>Sales Rep</td><td>
                    
                    <?php
                        if(isset($_POST['salesrep'])){
                            getSalesRep($_POST['salesrep']);
                        } else {
                            getSalesRep();
                        }
                        
                    ?>
                    </td>
                    </tr>
                    <td>Facility</td><td> <?php if(isset($_POST['facility'])){  getFacilityList("",$_POST['facility']);} else { getFacilityList("",""); }; ?></td>
                    </tr>
                    <tr>
                    <td>Start Date</td>
                    <td class="field_label"><input type="text" id="from" name="from" value="<?php echo $_POST['from']; ?>"  placeholder="click here to select date" /><br /><input type="text"  value="<?php echo $_POST['to']; ?>" id="to" name="to"  placeholder="click here to select date" /></td>
                    </tr><tr>
                    <td colspan="2" style="text-align: right;"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" value="Search" name="search_now" /></td></tr></tbody></table>
                    </form>
 <script>
                    $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    </script>
                               
                    <?php
                    break; 
                case "gpexpout":
                ?>
                <form method="post" action="management.php?task=gpexpout">
                <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller"><tbody>
                <tr><td>Tank Origination</td><td><?php
                 if(isset($_POST['search_now'])){
                    inboundFacility("inbound","$_POST[inbound]"); 
                 }else{
                    inboundFacility("inbound",""); 
                 }
                 ?></td></tr>
                <tr><td>Outbound Desination</td><td><?php
                    if(isset($_POST['search_now'])){
                        outboundFacility("outbound","$_POST[outbound]");
                    }else{
                        outboundFacility("outbound","");
                    }
                  ?></td></tr>
                <tr><td>IKG Manifest Number</td><td><input type="text" name="ikg" value="<?php echo $_POST['ikg']; ?>" placeholder="IKG Manifest Number"/></td></tr>
                <tr><td>Conductivity</td><td style="text-align: center;"><input type="text" name="conductivity" value="<?php echo $_POST['conductivity']; ?>" placeholder="Conductivity From"/><br />to<br /><input name="c_to" placeholder="Conductivity To" value="<?php echo $_POST['c_to']; ?>"  /></td></tr>
                <tr><td>Percent Solids</td><td  style="text-align: center;"><input type="text" name="percent_solid" value="<?php echo $_POST['percent_solid']; ?>" placeholder="Percent Solids From"/><br />to<br /><input type="text" name="p_to" placeholder="Percent Solids To"  value="<?php echo $_POST['p_to']; ?>" /></td></tr>
                <tr><td>Weight Certification</td><td><input type="text" name="weight_cert" value="<?php echo $_POST['weight_cert']; ?>" placeholder="Weight Certification"/></td></tr>
                <tr><td>Driver</td><td><?php
                    if(isset($_POST['search_now'])){
                         getDrivers($_POST['drivers']);
                    }else{
                         getDrivers("");
                    }
                 ?></td></tr>
                </tbody>
                <tfoot>
                    <tr><td colspan="2" style="text-align: right;"><a href="management.php?task=gpexpout">Default Data View</a>&nbsp;<input type="reset" value="Reset"/>&nbsp;<input type="submit" value="Submit Now" name="search_now"/></td></tr>
                </tfoot>
                </table>
                </form>
                <?php
                break;
                case "gpexp":
                    ?>
                    <form method="post" action="management.php?task=gpexp">
                    <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller"><tbody>
                    <tr><td>Tank #</td><td><input type="text" placeholder="Tank #" value="<?php echo $_POST['tank'] ?>" name="tank" /></td></tr>
                    <tr><td>IKG Manifest Number</td><td><input type="text" value="<?php echo $_POST['ikg'] ?>" name="ikg" placeholder="IKG MANIFEST ROUTE NUMBER" /></td></tr>
                    <tr><td>Weight Certificate</td><td><input name="weight_cert" placeholder="Weight Certification" type="text" value="<?php echo $_POST['weight_cert']; ?>"/></td></tr>
        <tr><td>Invoice Number</td><td><input type="text" placeholder="Invoice Number" name="invoice" value="<?php echo $_POST['invoice']; ?>"/></td></tr>
                    <tr><td>Customer</td><td><select id="element_6" name="element_6"> 
			<option value="" ></option>
                        <option value="4" <?php if($_POST['element_6'] == 4){ echo " selected='selected' "; } ?> >OTHER</option>
                        <option value="5"  <?php if($_POST['element_6'] == 5){ echo " selected='selected' "; } ?> >ABILITY</option>
                        <option value="45"  <?php if($_POST['element_6'] == 45){ echo " selected='selected' "; } ?> >ALPHA PETROLEUM</option>
                        <option value="52"  <?php if($_POST['element_6'] == 52){ echo " selected='selected' "; } ?> >ALPHA PUMPING</option>
                        <option value="29"  <?php if($_POST['element_6'] == 29){ echo " selected='selected' "; } ?> >AMBERWICK</option>
                        <option value="7"  <?php if($_POST['element_6'] == 7){ echo " selected='selected' "; } ?> >ATLAS PUMPING</option>
                        <option value="53"  <?php if($_POST['element_6'] == 53){ echo " selected='selected' "; } ?> >Athens</option>
                        <option value="8"  <?php if($_POST['element_6'] == 8){ echo " selected='selected' "; } ?> >BAKER COMMODITIES</option>
                        <option value="38"  <?php if($_POST['element_6'] == 38){ echo " selected='selected' "; } ?> >BIOTANE</option>
                        <option value="31"  <?php if($_POST['element_6'] == 31){ echo " selected='selected' "; } ?> >BLUE WATER</option>
                        <option value="9"  <?php if($_POST['element_6'] == 9){ echo " selected='selected' "; } ?> >BOB WALTON</option>
                        <option value="54"  <?php if($_POST['element_6'] == 54){ echo " selected='selected' "; } ?> >CARI RECYCLING</option>
                        <option value="33"  <?php if($_POST['element_6'] == 33){ echo " selected='selected' "; } ?> >CRIMSON</option>
                        <option value="10"  <?php if($_POST['element_6'] == 10){ echo " selected='selected' "; } ?> >CO-WEST</option>
                        <option value="11"  <?php if($_POST['element_6'] == 11){ echo " selected='selected' "; } ?> >DIAMOND ENV. (Delivered)</option>
                        <option value="12"  <?php if($_POST['element_6'] == 12){ echo " selected='selected' "; } ?> >Diamond Environmental (Pick Up)</option>
                        <option value="50"  <?php if($_POST['element_6'] == 50){ echo " selected='selected' "; } ?> >Environmental &amp; Chem Consult</option>
                        <option value="13"  <?php if($_POST['element_6'] == 13){ echo " selected='selected' "; } ?> >HAZ-MAT</option>
                        <option value="42"  <?php if($_POST['element_6'] == 42){ echo " selected='selected' "; } ?> >INLAND PUMPING</option>
                        <option value="14"  <?php if($_POST['element_6'] == 14){ echo " selected='selected' "; } ?> >IWP</option>
                        <option value="46"  <?php if($_POST['element_6'] == 46){ echo " selected='selected' "; } ?> >IWP - G Division</option>
                        <option value="47"  <?php if($_POST['element_6'] == 47){ echo " selected='selected' "; } ?> >IWP - K Division</option>
                        <option value="44"  <?php if($_POST['element_6'] == 44){ echo " selected='selected' "; } ?> >JR GREASE</option>
                        <option value="43"  <?php if($_POST['element_6'] == 43){ echo " selected='selected' "; } ?> >LAMB CANYON</option>
                        <option value="15"  <?php if($_POST['element_6'] == 15){ echo " selected='selected' "; } ?> >L &amp; S PIPELINE</option>
                        <option value="16"  <?php if($_POST['element_6'] == 16){ echo " selected='selected' "; } ?> >LIQUID ENV.</option>
                        <option value="17"  <?php if($_POST['element_6'] == 17){ echo " selected='selected' "; } ?> >MAJOR CLEAN UP</option>
                        <option value="40"  <?php if($_POST['element_6'] == 40){ echo " selected='selected' "; } ?> >OC PUMPING</option>
                        <option value="18"  <?php if($_POST['element_6'] == 18){ echo " selected='selected' "; } ?> >PIPE MAINT.</option>
                        <option value="19"  <?php if($_POST['element_6'] == 19){ echo " selected='selected' "; } ?> >Pipe Maintenance</option>
                        <option value="30"  <?php if($_POST['element_6'] == 30){ echo " selected='selected' "; } ?> >RE COMMODITIES</option>
                        <option value="20"  <?php if($_POST['element_6'] == 20){ echo " selected='selected' "; } ?> >ROTO ROOTER</option>
                        <option value="37"  <?php if($_POST['element_6'] == 37){ echo " selected='selected' "; } ?> >SB INDUSTRIAL</option>
                        <option value="21"  <?php if($_POST['element_6'] == 21){ echo " selected='selected' "; } ?> >STATER BROS - BIG BEAR</option>
                        <option value="22"  <?php if($_POST['element_6'] == 22){ echo " selected='selected' "; } ?> >STATER BROS Lake Arrowhead</option>
                        <option value="23"  <?php if($_POST['element_6'] == 23){ echo " selected='selected' "; } ?> >STRESS LESS ENV.</option>
                        <option value="28"  <?php if($_POST['element_6'] == 28){ echo " selected='selected' "; } ?> >T&amp;R</option>
                        <option value="32"  <?php if($_POST['element_6'] == 32){ echo " selected='selected' "; } ?> >UNITED PUMPING</option>
                        <option value="41"  <?php if($_POST['element_6'] == 41){ echo " selected='selected' "; } ?> >VENTURA FOODS</option>
                        <option value="27"  <?php if($_POST['element_6'] == 27){ echo " selected='selected' "; } ?> >Victorville</option>
                        <option value="35"  <?php if($_POST['element_6'] == 35){ echo " selected='selected' "; } ?> >WESTERN ENV.</option>
                        <option value="24"  <?php if($_POST['element_6'] == 24){ echo " selected='selected' "; } ?> >WESTERN PACIFIC</option>
                        <option value="25"  <?php if($_POST['element_6'] == 25){ echo " selected='selected' "; } ?> >WHITE HOUSE</option>
                        <option value="26"  <?php if($_POST['element_6'] == 26){ echo " selected='selected' "; } ?> >WRIGHT</option>
                        <option value="48"  <?php if($_POST['element_6'] == 48){ echo " selected='selected' "; } ?> >Sustainable Restaurant Services</option>
                        
                        		</select><br /><input type="text" placeholder="Other Choice" name="o_choice"/></td></tr>
                    <tr><td>Carrier/Transporter</td><td>
                    <select class="element select medium" id="element_8" name="element_8"> 
                        <option value=""></option>
                        <option value="1" <?php if($_POST['element_8'] == 1){ echo "selected ='selected'"; } ?> >Co-West</option>
                        <option value="31" <?php if($_POST['element_8'] == 31){ echo "selected ='selected'"; } ?> >Ability</option>
                        <option value="28" <?php if($_POST['element_8'] == 28){ echo "selected ='selected'"; } ?> >Alpha Petroleum</option>
                        <option value="35" <?php if($_POST['element_8'] == 35){ echo "selected ='selected'"; } ?> >Alpha Pumping</option>
                        <option value="6" <?php if($_POST['element_8'] == 6){ echo "selected ='selected'"; } ?> >Amberwick</option>
                        <option value="7" <?php if($_POST['element_8'] == 7){ echo "selected ='selected'"; } ?> >Atlas Pumping</option>
                        <option value="36" <?php if($_POST['element_8'] == 36){ echo "selected ='selected'"; } ?> >Athens</option>
                        <option value="8" <?php if($_POST['element_8'] == 8){ echo "selected ='selected'"; } ?> >Baker Commodities</option>
                        <option value="12" <?php if($_POST['element_8'] == 12){ echo "selected ='selected'"; } ?> >Best Western</option>
                        <option value="16" <?php if($_POST['element_8'] == 16){ echo "selected ='selected'"; } ?> >Biotane</option>
                        <option value="37" <?php if($_POST['element_8'] == 37){ echo "selected ='selected'"; } ?> >CARI RECYCLING</option>
                        <option value="20" <?php if($_POST['element_8'] == 20){ echo "selected ='selected'"; } ?> >Desert Soul</option>
                        <option value="24" <?php if($_POST['element_8'] == 24){ echo "selected ='selected'"; } ?> >Diamond</option>
                        <option value="5"  <?php if($_POST['element_8'] == 5){ echo "selected ='selected'"; } ?>  >Empire</option>
                        <option value="33"  <?php if($_POST['element_8'] == 33){ echo "selected ='selected'"; } ?>  >Environmental &amp; Chem Consult</option>
                        <option value="27"  <?php if($_POST['element_8'] == 27){ echo "selected ='selected'"; } ?>  >FEMA</option>
                        <option value="9"  <?php if($_POST['element_8'] == 9){ echo "selected ='selected'"; } ?>  >Haz-Mat</option>
                        <option value="19"  <?php if($_POST['element_8'] == 19){ echo "selected ='selected'"; } ?>  >Inland Pumping</option>
                        <option value="2"  <?php if($_POST['element_8'] == 2){ echo "selected ='selected'"; } ?>  >IWP</option>
                        <option value="26"  <?php if($_POST['element_8'] == 26){ echo "selected ='selected'"; } ?>  >JL Trucking</option>
                        <option value="21"  <?php if($_POST['element_8'] == 21){ echo "selected ='selected'"; } ?>  >JR Grease</option>
                        <option value="11"  <?php if($_POST['element_8'] == 11){ echo "selected ='selected'"; } ?>  >Liquid Env</option>
                        <option value="22"  <?php if($_POST['element_8'] == 22){ echo "selected ='selected'"; } ?>  >Major CleanUp</option>
                        <option value="18"  <?php if($_POST['element_8'] == 18){ echo "selected ='selected'"; } ?>  >OC Pumping</option>
                        <option value="25"  <?php if($_POST['element_8'] == 25){ echo "selected ='selected'"; } ?>  >Plowman</option>
                        <option value="3"  <?php if($_POST['element_8'] == 3){ echo "selected ='selected'"; } ?>  >Nu West</option>
                        <option value="10"  <?php if($_POST['element_8'] == 10){ echo "selected ='selected'"; } ?>  >RE Commodities</option>
                        <option value="17"  <?php if($_POST['element_8'] == 17){ echo "selected ='selected'"; } ?>  >SB Industrial</option>
                        <option value="29"  <?php if($_POST['element_8'] == 29){ echo "selected ='selected'"; } ?>  >Stressless</option>
                        <option value="15"  <?php if($_POST['element_8'] == 15){ echo "selected ='selected'"; } ?>  >T&amp;R</option>
                        <option value="13"  <?php if($_POST['element_8'] == 13){ echo "selected ='selected'"; } ?>  >White House</option>
                        <option value="14"  <?php if($_POST['element_8'] == 14){ echo "selected ='selected'"; } ?>  >Wright</option>
                        <option value="4"  <?php if($_POST['element_8'] == 4){ echo "selected ='selected'"; } ?>  >Other</option>
                        <option value="32"  <?php if($_POST['element_8'] == 32){ echo "selected ='selected'"; } ?>  >Sustainable Restaurant Services</option>
                        </select>
                                    
                            </td></tr>
                    <tr><td style="text-align:left;vertical-align: top;" class="field_label" colspan="2">
                    <table style="width: 100%;height:100%;border:0px solid #bbb;">
        <td><input <?php if($_POST['type']==1){ echo "checked";} ?> type="radio" name="type" value="1"/>&nbsp;Completed Date<br />
            <input <?php if($_POST['type']==2){ echo "checked";} ?>  type="radio" name="type" value="2"/>&nbsp;Service Date<br/></td>
        
        <td style="text-align:center;vertical-align:middle;" class="field_label">
            <input type="text" placeholder="start date"  style="border-radius: 0px 0px 0px 0px;" id="from" name="from"  value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" /><input type="text" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to"  value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" /></td>
        </tr>
                    
                    </table>
                    </td>
                    <tr><td colspan="5" style="text-align: right;"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" style="float: right;" value="Search" name="search_now"/></td></tr>
                    </tbody></table></form>
                    <?php
                    break;
                case "gpsin":
                ?>
                <form action="management.php?task=gpsin" method="post">
                <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller"><tbody>
                    <tr><td>Tank #</td><td>
                    <?php 
                        if(isset($_POST['search_now'])){
                            inboundFacility("tank",$_POST['tank']);
                        }else{
                            inboundFacility("tank","");
                        }
                     ?></td></tr>
                    <tr><td>IKG Manifest Number</td><td><input type="text" value="<?php echo $_POST['ikg'] ?>" name="ikg" placeholder="IKG MANIFEST ROUTE NUMBER" /></td></tr>
                    <tr><td>Weight Certificate</td><td><input name="weight_cert" placeholder="Weight Certification" type="text" value="<?php echo $_POST['weight_cert']; ?>"/></td></tr>
        <tr><td>Invoice Number</td><td><input type="text" placeholder="Invoice Number" name="invoice" value="<?php echo $_POST['invoice']; ?>"/></td></tr>
                    <tr><td>Customer</td><td><select id="element_6" name="element_6"> 
			<option value="" ></option>
                        <option value="4" <?php if($_POST['element_6'] == 4){ echo " selected='selected' "; } ?> >OTHER</option>
                        <option value="5"  <?php if($_POST['element_6'] == 5){ echo " selected='selected' "; } ?> >ABILITY</option>
                        <option value="45"  <?php if($_POST['element_6'] == 45){ echo " selected='selected' "; } ?> >ALPHA PETROLEUM</option>
                        <option value="52"  <?php if($_POST['element_6'] == 52){ echo " selected='selected' "; } ?> >ALPHA PUMPING</option>
                        <option value="29"  <?php if($_POST['element_6'] == 29){ echo " selected='selected' "; } ?> >AMBERWICK</option>
                        <option value="7"  <?php if($_POST['element_6'] == 7){ echo " selected='selected' "; } ?> >ATLAS PUMPING</option>
                        <option value="53"  <?php if($_POST['element_6'] == 53){ echo " selected='selected' "; } ?> >Athens</option>
                        <option value="8"  <?php if($_POST['element_6'] == 8){ echo " selected='selected' "; } ?> >BAKER COMMODITIES</option>
                        <option value="38"  <?php if($_POST['element_6'] == 38){ echo " selected='selected' "; } ?> >BIOTANE</option>
                        <option value="31"  <?php if($_POST['element_6'] == 31){ echo " selected='selected' "; } ?> >BLUE WATER</option>
                        <option value="9"  <?php if($_POST['element_6'] == 9){ echo " selected='selected' "; } ?> >BOB WALTON</option>
                        <option value="54"  <?php if($_POST['element_6'] == 54){ echo " selected='selected' "; } ?> >CARI RECYCLING</option>
                        <option value="33"  <?php if($_POST['element_6'] == 33){ echo " selected='selected' "; } ?> >CRIMSON</option>
                        <option value="10"  <?php if($_POST['element_6'] == 10){ echo " selected='selected' "; } ?> >CO-WEST</option>
                        <option value="11"  <?php if($_POST['element_6'] == 11){ echo " selected='selected' "; } ?> >DIAMOND ENV. (Delivered)</option>
                        <option value="12"  <?php if($_POST['element_6'] == 12){ echo " selected='selected' "; } ?> >Diamond Environmental (Pick Up)</option>
                        <option value="50"  <?php if($_POST['element_6'] == 50){ echo " selected='selected' "; } ?> >Environmental &amp; Chem Consult</option>
                        <option value="13"  <?php if($_POST['element_6'] == 13){ echo " selected='selected' "; } ?> >HAZ-MAT</option>
                        <option value="42"  <?php if($_POST['element_6'] == 42){ echo " selected='selected' "; } ?> >INLAND PUMPING</option>
                        <option value="14"  <?php if($_POST['element_6'] == 14){ echo " selected='selected' "; } ?> >IWP</option>
                        <option value="46"  <?php if($_POST['element_6'] == 46){ echo " selected='selected' "; } ?> >IWP - G Division</option>
                        <option value="47"  <?php if($_POST['element_6'] == 47){ echo " selected='selected' "; } ?> >IWP - K Division</option>
                        <option value="44"  <?php if($_POST['element_6'] == 44){ echo " selected='selected' "; } ?> >JR GREASE</option>
                        <option value="43"  <?php if($_POST['element_6'] == 43){ echo " selected='selected' "; } ?> >LAMB CANYON</option>
                        <option value="15"  <?php if($_POST['element_6'] == 15){ echo " selected='selected' "; } ?> >L &amp; S PIPELINE</option>
                        <option value="16"  <?php if($_POST['element_6'] == 16){ echo " selected='selected' "; } ?> >LIQUID ENV.</option>
                        <option value="17"  <?php if($_POST['element_6'] == 17){ echo " selected='selected' "; } ?> >MAJOR CLEAN UP</option>
                        <option value="40"  <?php if($_POST['element_6'] == 40){ echo " selected='selected' "; } ?> >OC PUMPING</option>
                        <option value="18"  <?php if($_POST['element_6'] == 18){ echo " selected='selected' "; } ?> >PIPE MAINT.</option>
                        <option value="19"  <?php if($_POST['element_6'] == 19){ echo " selected='selected' "; } ?> >Pipe Maintenance</option>
                        <option value="30"  <?php if($_POST['element_6'] == 30){ echo " selected='selected' "; } ?> >RE COMMODITIES</option>
                        <option value="20"  <?php if($_POST['element_6'] == 20){ echo " selected='selected' "; } ?> >ROTO ROOTER</option>
                        <option value="37"  <?php if($_POST['element_6'] == 37){ echo " selected='selected' "; } ?> >SB INDUSTRIAL</option>
                        <option value="21"  <?php if($_POST['element_6'] == 21){ echo " selected='selected' "; } ?> >STATER BROS - BIG BEAR</option>
                        <option value="22"  <?php if($_POST['element_6'] == 22){ echo " selected='selected' "; } ?> >STATER BROS Lake Arrowhead</option>
                        <option value="23"  <?php if($_POST['element_6'] == 23){ echo " selected='selected' "; } ?> >STRESS LESS ENV.</option>
                        <option value="28"  <?php if($_POST['element_6'] == 28){ echo " selected='selected' "; } ?> >T&amp;R</option>
                        <option value="32"  <?php if($_POST['element_6'] == 32){ echo " selected='selected' "; } ?> >UNITED PUMPING</option>
                        <option value="41"  <?php if($_POST['element_6'] == 41){ echo " selected='selected' "; } ?> >VENTURA FOODS</option>
                        <option value="27"  <?php if($_POST['element_6'] == 27){ echo " selected='selected' "; } ?> >Victorville</option>
                        <option value="35"  <?php if($_POST['element_6'] == 35){ echo " selected='selected' "; } ?> >WESTERN ENV.</option>
                        <option value="24"  <?php if($_POST['element_6'] == 24){ echo " selected='selected' "; } ?> >WESTERN PACIFIC</option>
                        <option value="25"  <?php if($_POST['element_6'] == 25){ echo " selected='selected' "; } ?> >WHITE HOUSE</option>
                        <option value="26"  <?php if($_POST['element_6'] == 26){ echo " selected='selected' "; } ?> >WRIGHT</option>
                        <option value="48"  <?php if($_POST['element_6'] == 48){ echo " selected='selected' "; } ?> >Sustainable Restaurant Services</option>
                        
                        		</select><br /><input type="text" placeholder="Other Choice" name="o_choice"/></td></tr>
                    <tr><td>Carrier/Transporter</td><td>
                    <select class="element select medium" id="element_8" name="element_8"> 
                        <option value=""></option>
                        <option value="1" <?php if($_POST['element_8'] == 1){ echo "selected ='selected'"; } ?> >Co-West</option>
                        <option value="31" <?php if($_POST['element_8'] == 31){ echo "selected ='selected'"; } ?> >Ability</option>
                        <option value="28" <?php if($_POST['element_8'] == 28){ echo "selected ='selected'"; } ?> >Alpha Petroleum</option>
                        <option value="35" <?php if($_POST['element_8'] == 35){ echo "selected ='selected'"; } ?> >Alpha Pumping</option>
                        <option value="6" <?php if($_POST['element_8'] == 6){ echo "selected ='selected'"; } ?> >Amberwick</option>
                        <option value="7" <?php if($_POST['element_8'] == 7){ echo "selected ='selected'"; } ?> >Atlas Pumping</option>
                        <option value="36" <?php if($_POST['element_8'] == 36){ echo "selected ='selected'"; } ?> >Athens</option>
                        <option value="8" <?php if($_POST['element_8'] == 8){ echo "selected ='selected'"; } ?> >Baker Commodities</option>
                        <option value="12" <?php if($_POST['element_8'] == 12){ echo "selected ='selected'"; } ?> >Best Western</option>
                        <option value="16" <?php if($_POST['element_8'] == 16){ echo "selected ='selected'"; } ?> >Biotane</option>
                        <option value="37" <?php if($_POST['element_8'] == 37){ echo "selected ='selected'"; } ?> >CARI RECYCLING</option>
                        <option value="20" <?php if($_POST['element_8'] == 20){ echo "selected ='selected'"; } ?> >Desert Soul</option>
                        <option value="24" <?php if($_POST['element_8'] == 24){ echo "selected ='selected'"; } ?> >Diamond</option>
                        <option value="5"  <?php if($_POST['element_8'] == 5){ echo "selected ='selected'"; } ?>  >Empire</option>
                        <option value="33"  <?php if($_POST['element_8'] == 33){ echo "selected ='selected'"; } ?>  >Environmental &amp; Chem Consult</option>
                        <option value="27"  <?php if($_POST['element_8'] == 27){ echo "selected ='selected'"; } ?>  >FEMA</option>
                        <option value="9"  <?php if($_POST['element_8'] == 9){ echo "selected ='selected'"; } ?>  >Haz-Mat</option>
                        <option value="19"  <?php if($_POST['element_8'] == 19){ echo "selected ='selected'"; } ?>  >Inland Pumping</option>
                        <option value="2"  <?php if($_POST['element_8'] == 2){ echo "selected ='selected'"; } ?>  >IWP</option>
                        <option value="26"  <?php if($_POST['element_8'] == 26){ echo "selected ='selected'"; } ?>  >JL Trucking</option>
                        <option value="21"  <?php if($_POST['element_8'] == 21){ echo "selected ='selected'"; } ?>  >JR Grease</option>
                        <option value="11"  <?php if($_POST['element_8'] == 11){ echo "selected ='selected'"; } ?>  >Liquid Env</option>
                        <option value="22"  <?php if($_POST['element_8'] == 22){ echo "selected ='selected'"; } ?>  >Major CleanUp</option>
                        <option value="18"  <?php if($_POST['element_8'] == 18){ echo "selected ='selected'"; } ?>  >OC Pumping</option>
                        <option value="25"  <?php if($_POST['element_8'] == 25){ echo "selected ='selected'"; } ?>  >Plowman</option>
                        <option value="3"  <?php if($_POST['element_8'] == 3){ echo "selected ='selected'"; } ?>  >Nu West</option>
                        <option value="10"  <?php if($_POST['element_8'] == 10){ echo "selected ='selected'"; } ?>  >RE Commodities</option>
                        <option value="17"  <?php if($_POST['element_8'] == 17){ echo "selected ='selected'"; } ?>  >SB Industrial</option>
                        <option value="29"  <?php if($_POST['element_8'] == 29){ echo "selected ='selected'"; } ?>  >Stressless</option>
                        <option value="15"  <?php if($_POST['element_8'] == 15){ echo "selected ='selected'"; } ?>  >T&amp;R</option>
                        <option value="13"  <?php if($_POST['element_8'] == 13){ echo "selected ='selected'"; } ?>  >White House</option>
                        <option value="14"  <?php if($_POST['element_8'] == 14){ echo "selected ='selected'"; } ?>  >Wright</option>
                        <option value="4"  <?php if($_POST['element_8'] == 4){ echo "selected ='selected'"; } ?>  >Other</option>
                        <option value="32"  <?php if($_POST['element_8'] == 32){ echo "selected ='selected'"; } ?>  >Sustainable Restaurant Services</option>
                        </select>
                                    
                            </td></tr>
                    <tr><td style="text-align:left;vertical-align: top;" class="field_label" colspan="2">
                    <table style="width: 100%;height:100%;border:0px solid #bbb;">
        <td><input <?php if($_POST['type']==1){ echo "checked";} ?> type="radio" name="type" value="1"/>&nbsp;Completed Date<br />
            <input <?php if($_POST['type']==2){ echo "checked";} ?>  type="radio" name="type" value="2"/>&nbsp;Service Date<br/></td>
        
        <td style="text-align:center;vertical-align:middle;" class="field_label">
            <input type="text" placeholder="start date"  style="border-radius: 0px 0px 0px 0px;" id="from" name="from"  value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" /><input type="text" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to"  value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" /></td>
        </tr>
                    
                    </table>
                    </td>
                    <tr><td colspan="5" style="text-align: right;"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" style="float: right;" value="Search" name="search_now"/></td></tr>
                    </tbody></table></form>
                
                <?php
                break;
                case "gps":
                ?>
                    <form method="post" action="management.php?task=gps">                   
                    <table style="width:50%;margin:auto;margin-top:20px;margin-bottom:20px;">
                        <tr><td  style="border: 0px solid #bbb;">Tank</td><td  style="border: 0px solid #bbb;">
                        <?php 
                             if(isset($_POST['search_now'])){
                                inboundFacility("inbound",$_POST['inbound']);
                             }else{
                                inboundFacility("inbound","");
                             }
                        ?>
                        </td></tr>
                    <tr><td style="border: 0px solid #bbb;">Destination</td><td  style="border: 0px solid #bbb;"> <select id="salesrep" name="salesrep"> <option value="">--</option> 
                    <?php 
                       
                    
                    ?> </select></td></tr>
                        <tr><td style="border: 0px solid #bbb;">Friendly</td><td  style="border: 0px solid #bbb;"> <input type="radio" <?php if($_POST['friend'] == 1){ echo " checked "; } ?>   name="friend" value="1"/>&nbsp;Exclude Friendlies <br /><input <?php if($_POST['friend'] == 2){ echo " checked "; } ?>  type="radio" name="friend" value="2"/>&nbsp;Include Friendlies</td></tr>
                        <tr><td style="border: 0px solid #bbb;">Facility</td><td  style="border: 0px solid #bbb;">
                            <?php 
                            if(isset($_POST['search_now'])){
                                getFacilityList("",$_POST['facility']);
                            } else {
                                getFacilityList("","");
                            } 
                            ?>
                        </td></tr>
                        <tr><td style="border: 0px solid #bbb;">Group By</td><td  style="border: 0px solid #bbb;">
                        <select id="my_group" name="my_group">
                        <option value="-">--</option>
                            <option   <?php if($_POST['my_group'] == "original_sales_person"){ echo "selected"; } ?>   value="original_sales_person">Original Sale By</option>

                            <option <?php if($_POST['my_group'] == "account_rep"){ echo "selected"; } ?> value="account_rep">Account Rep</option>
                            
                            <option <?php if($_POST['my_group'] == "account_ID"){ echo "selected"; } ?>  value="account_ID">Account</option>
                            
                            <option <?php if($_POST['my_group'] == "division"){ echo "selected"; } ?> value="division">Facility</option>
                            
                            
</select></td></tr>
                    <tr> <td style="vertical-align:top;text-align:left;">Scheduled Date</td>   <td nowrap="" align="right" style="text-align:center;vertical-align:middle;" class="field_label"><div><input type="text" placeholder="start date"  style="border-radius: 0px 0px 0px 0px;" id="from" name="from"  value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" /></div><div><input type="text" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to"  value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" /></div><div style="text-align:center;margin-top:30px;"></div></td></tr>
                 
                    <tr><td colspan="2" style="text-align: right;"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" style="float: right;" value="Search" name="search_now"/></td></tr>
                    </tbody></table></form>
                <?php
                break;   
                case "ops":
                    ?>
                    <form method="post" action="management.php?task=ops">
                    <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller"><tbody><tr><td style="text-align:left;vertical-align: top;" class="field_label">
                    <table style="width: 100%;height:100%;border:0px solid #bbb;">
                        <tr><td  style="border: 0px solid #bbb;">Account Rep</td><td  style="border: 0px solid #bbb;"><select id="account_rep" name="account_rep">
                        <option value="">--</option>
                    <?php 
                        $table = $dbprefix."_users";
                        $request_rep = $db->query("SELECT * FROM $table WHERE roles like '%Sales%Representative%'");
                        if(count($request_rep)>0){
                            foreach($request_rep as $v){
                                echo "<option "; if($_POST['account_rep'] == $v['user_id']){  echo "selected"; }  echo " value='$v[user_id]'>$v[first] $v[last]</option>";
                            }
                        }
                        else{                            
                            echo "<option>No Representaties created</option>";
                        }
                    
                    ?>
                    </select></td></tr>
                        <tr><td style="border: 0px solid #bbb;">Original Sale By</td><td  style="border: 0px solid #bbb;"> <select id="salesrep" name="salesrep"> <option value="">--</option> 
                    <?php 
                        $table = $dbprefix."_users";
                        $request_rep = $db->query("SELECT * FROM $table WHERE roles like '%Sales%Representative%'");
                        if(count($request_rep)>0){
                            foreach($request_rep as $v){
                                echo "<option "; if($_POST['salesrep'] == $v['user_id']){  echo "selected"; }  echo " value='$v[user_id]'>$v[first] $v[last]</option>";
                            }
                        }
                        else{                            
                            echo "<option>No Representaties created</option>";
                        }
                    
                    ?> </select></td></tr>
                        <tr><td style="border: 0px solid #bbb;">Friendly</td><td  style="border: 0px solid #bbb;"> <?php if(isset($_POST['search_now'])){ echo getFriendLists($_POST['friendly']);   } else { echo getFriendLists(); } ?></td></tr>
                        <tr><td style="border: 0px solid #bbb;">Group By</td><td  style="border: 0px solid #bbb;">
                        <select id="my_group" name="my_group">
                        <option value="-">--</option>
                            <option   <?php if($_POST['my_group'] == "original_sales_person"){ echo "selected"; } ?>   value="original_sales_person">Original Sale By</option>

                            <option <?php if($_POST['my_group'] == "account_rep"){ echo "selected"; } ?> value="account_rep">Account Rep</option>
                            
                            <option <?php if($_POST['my_group'] == "previous_provider"){ echo "selected"; } ?>  value="previous_provider">Previous Provider</option>
                            
                            <option <?php if($_POST['my_group'] == "division"){ echo "selected"; } ?> value="division">Facility</option>
                            
                            
</select></td></tr>
                    </table>
                    </td><td nowrap="" align="right" style="padding-left:30px; padding-right:30px;" class="field_label"><div><br /><br /><input type="text" placeholder="start date"  style="border-radius: 0px 0px 0px 0px;" id="from" name="from"  value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" /></div><div><input type="text" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to"  value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" /></div><div style="text-align:center;margin-top:30px;"></div></td></tr>
                    <tr><td colspan="5"><input type="submit" style="float: right;" value="Search" name="search_now"/><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td></tr>
                    </tbody></table></form>
                    <script>
                    $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    </script>
                <?php
                    break;    
                case "ocd":
                ?>
                    <form action="management.php?task=ocd" method="post">
                    <table border="0" align="center" cellpadding="1" style="font-size:smaller; border:1px grey solid; margin:20px;margin:auto;width:70%;margin-top:15px;">
                        <tbody>
                            <tr>
                            <td style="padding-left:10px;width:30%;" class="field_label">
                                Driver's Home Facility
                            </td>
                           
                            <td style="width:30%;">
                                &nbsp;&nbsp;<?php if(isset($_POST['facility'])){ getFacilityList("",$_POST['facility']);} else { getFacilityList(); } ?>
                            </td>
                            
                            <td style="width:30%;">&nbsp;&nbsp;<input type="checkbox" <?php if(isset($_POST['include_inactive'])){ echo "checked"; } ?>  name="include_inactive" />&nbsp;&nbsp;Show Inactive</td>
                            <td width="80" align="left"><input type="submit" value="Search" name="search_now" /> <input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td></tr></tbody></table>
                            </form>
                <?php
                    break;
                case "xlog":
                    ?>
                        <form action="management.php?task=xlog" method="post">
                        <table style="font-size:smaller;margin:auto;width:50%;margin-top:20px;margin-bottom:20px;"><tbody>
                        <tr><td>Transaction Type</td><td><select name="ttype"><option value="">All</option>
                        <option <?php if($_POST['ttype'] == 5){ echo "selected";} ?>  value="5">Update User</option>
                        
                        <option <?php if($_POST['ttype'] == 7){ echo "selected";} ?> value="7">Update Account</option>
                        
                        <option <?php if($_POST['ttype'] == 10){ echo "selected";} ?> value="10">User Log In</option>
                        
                        <option <?php if($_POST['ttype'] == 11){ echo "selected";} ?> value="11">Update Person</option>
                        
                        <option <?php if($_POST['ttype'] == 12){ echo "selected";} ?> value="12">Service Center Call</option>
                        
                        <option <?php if($_POST['ttype'] == 13){ echo "selected";} ?> value="13">Assign User to Issue</option>
                        
                        <option <?php if($_POST['ttype'] == 14){ echo "selected";} ?> value="14">Update Oil Pickup</option>
                        
                        <option  <?php if($_POST['ttype'] == 15){ echo "selected";} ?> value="15">Update Index Price</option>
                        
                        <option  <?php if($_POST['ttype'] == 16){ echo "selected";} ?> value="16">Check Zone Status</option>
                        </select></td></tr>
                        
                        <tr><td>Pertaining</td><td><select id="related_table" name="related_table"><option value="">All</option>
                            
                            <option <?php if($_POST['related_table'] == 2){ echo "selected"; } ?>  value="2">Account</option>
                            
                            <option <?php if($_POST['related_table'] == 3){ echo "selected"; } ?>  value="3">Issue</option>
                            
                            <option <?php if($_POST['related_table'] == 4){ echo "selected"; } ?>  value="4">User</option>
                            
                            <option  <?php if($_POST['related_table'] == 5){ echo "selected"; } ?>  value="5">Person</option>
                            
                            <option <?php if($_POST['related_table'] == 6){ echo "selected"; } ?>  value="6">Oil Pickup</option>
                            
                            <option <?php if($_POST['related_table'] == 7){ echo "selected"; } ?>  value="7">payment_index</option>
                        </select></td></tr>
                        
                        
                        <tr><td>User </td><td>
                    
                    
                        <select name="users" id="users">
                        <?php 
                        echo "<option value=''>All</option>";
                        $all = $db->orderby("last","ASC")->get($dbprefix."_users","first,last,user_id");
                        if(count($all)>0){
                            foreach($all as $ll){
                                echo "<option "; 
                                    if($_POST['users'] == $ll['user_id']){
                                        echo " selected ";
                                    }
                                echo " value='$ll[user_id]'>$ll[last], $ll[first]</option>";
                            }
                        }
                        
                        ?>
                        </select>

                        </td>
                        </tr>
                        <tr>
                        <td style="vertical-align: top;">Date Performed</td>
                        <td class="field_label" style="vertical-align: top;"><input  value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" type="text" id="from" name="from" placeholder="From:"/><br /><input  value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" type="text" id="to" name="to" placeholder="To:"/></td>
                        </tr><tr>
                        <tr><td>Account</td><td><input type="text" id="account_no" name="account_no"/></td></tr>
                        <td  colspan="2" style="text-align: right;"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" value="Search" name="search_now"/></td></tr></tbody></table></form>
                    <script>
                    $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    </script>
                    <?php
                    break;
                case "expire":
                    ?>
                    <form action="management.php?task=expire" method="post">
                        <table style="font-size:smaller;margin:auto;margin-top:20px;margin-bottom:20px;table-layout:fixed;width:50%;margin-top: 30px; margin-bottom:;"><tbody>
                        
                        <tr><td style="padding-right:5px;width:50%" class="field_label">Status</td><td><select id="account_status" name="account_status">
                        
                        <option value="">All</option>

                        <option  <?php  if($_POST['account_status'] == "New"){ echo " SELECTED = 'selected'";  } ?>  value="New">New</option>
                        
                        <option <?php  if($_POST['account_status'] == "Active"){ echo " SELECTED = 'selected'";  } ?> value="Active">Active Only</option>
                        
                        
                        <option  <?php  if($_POST['account_status'] == "Archive"){ echo " SELECTED = 'selected'";  } ?> value="Archive">Archived</option>
                        <option <?php  if($_POST['account_status'] == "pending"){ echo " SELECTED = 'selected'";  } ?> value="pending">Pending</option>
                        </select></td></tr>
                        
                        <tr>

<td  style="width:50%;" class="field_label">Account Rep</td><td> <?php 
            if(isset($_POST['salesrep'])){ 
                getSalesRep($_POST['salesrep']); 
            } else { 
                getSalesRep(""); 
            }  ?> 
            
</td></tr> <tr>

<td>Start Date</td>
<td  class="field_label">
            <input type="checkbox" id="get_no_exp" name="get_no_exp" <?php  if( isset($_POST['get_no_exp']) ){ echo "checked";} ?> />&nbsp;No Expiration Date<br />

                <input type="text" placeholder="start date" value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" style="border-radius: 0px 0px 0px 0px;" id="from" name="from" /><br /><input type="text" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to"  value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>"/></td>
                </tr>
                
                <tr>
               

                <td colspan="2"  style="text-align: right;"><input type="radio" <?php if($_POST['friend'] == 1){ echo " checked "; } ?>   name="friend" value="1"/>&nbsp;Exclude Friendlies <input <?php if($_POST['friend'] == 2){ echo " checked "; } ?>  type="radio" name="friend" value="2"/>&nbsp;Include Friendlies<br /><br /> <input type="checkbox" name="get_acct_reps" <?php  
                    if( isset($_POST['get_acct_reps']) ){ 
                        echo "checked";} 
                    ?> />&nbsp;Show Account Reps
                &nbsp;&nbsp;<input type="checkbox" name="get_sales_reps" <?php  if( isset($_POST['get_sales_reps']) ){ echo "checked";} ?> />&nbsp;Show Sales Reps
                &nbsp;&nbsp;<input type="checkbox" name="get_affs" <?php  if( isset($_POST['get_affs']) ){ echo "checked";} ?> />&nbsp;Show Friendly<br /><br />&nbsp;<a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>"  style="margin-left: 10px;">Default Data View</a>&nbsp;<input type="reset" style="margin-left: 10px;"/>  <input type="submit" value="Search" name="search_now" style="margin-left: 10px;"/></td></tr></tbody></table>
</form>
                    <script>
                    $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    </script>
                    <?php                
                    break;
                case "zero":
                    ?>
                    <form action="management.php?task=zero" method="post">
                    <table border="0" align="center" cellpadding="1" style="width: 50%;">

                        <tbody><tr>
                        <td valign="top">Reason For Zero</td><td nowrap="" align="left" class="field_label"><select id="reason_for_skip_id" name="reason_for_skip_id"><option value="">Any</option>
                        <option <?php if($_POST['reason_for_skip_id'] == 10){ echo "selected"; } ?>  value="10">No oil</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 12){ echo "selected"; } ?> value="12">Skipped: Driver Choice</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 14){ echo "selected"; } ?> value="14">Skipped: Truck Full</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 16){ echo "selected"; } ?> value="16">Skipped: Other</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 22){ echo "selected"; } ?> value="22">Locked: No Key</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 24){ echo "selected"; } ?> value="24">Locked: Our key did not work</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 26){ echo "selected"; } ?> value="26">Blocked</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 32){ echo "selected"; } ?> value="32">Missed time window</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 40){ echo "selected"; } ?> value="40">Oil Frozen</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 42){ echo "selected"; } ?> value="42">Garbage in container</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 44){ echo "selected"; } ?> value="44">Container damaged</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 52){ echo "selected"; } ?> value="52">Oil Theft: Suspected</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 54){ echo "selected"; } ?> value="54">Oil Theft: Confirmed</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 62){ echo "selected"; } ?> value="62">Location Closed: Temporary</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 64){ echo "selected"; } ?> value="64">Location Closed: Out of business</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 66){ echo "selected"; } ?> value="66">Lost Account - Confirmed</option>
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 68){ echo "selected"; } ?> value="68">Manager refused pickup</option><br />
                        
                        <option  <?php if($_POST['reason_for_skip_id'] == 72){ echo "selected"; } ?> value="72">Unneeeded: Added in Error</option>
                        </select></td></tr><tr><td nowrap="" align="right" class="field_label">Other Zeroes Interval</td><td nowrap="" align="left" class="field_label"><select id="my_interval" name="my_interval">
                        <option  <?php if($_POST['my_interval'] == 30){ echo "selected"; } ?> value="30">30 Days</option>
                        
                        <option  <?php if($_POST['my_interval'] == 93){ echo "selected"; } ?>  value="93">3 Months</option>
                        
                        <option   <?php if($_POST['my_interval'] == 185){ echo "selected"; } ?>  value="185">6 Months</option>
                        
                        <option   <?php if($_POST['my_interval'] == 365){ echo "selected"; } ?>  value="365">1 Year</option>
                        
                        <option   <?php if($_POST['my_interval'] == 730){ echo "selected"; } ?>  value="730">2 Years</option>
                        </select> </td></tr>
                        <tr><td colspan="3" style="text-align: center;"><input type="text" value="<?php if(isset($_POST['from'])){ echo "$_POST[from]"; } ?>" id="from" name="from" placeholder="Beginning Date" style="border-radius: 0px 0px 0px 0px;"/>&nbsp;&nbsp;<input type="text" id="to"  value="<?php if(isset($_POST['to'])){ echo "$_POST[to]"; } ?>" name="to" style="border-radius: 0px 0px 0px 0px;" placeholder="End Date"/></td></tr>
                        </tbody>
                        <tr><td width="120" nowrap="" align="right" class="field_label" colspan="2"><input type="reset"/><input type="submit" value="Search" name="search_now" />  <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>
                        </td></tr>
                        </table>
                        </form>
                        <script>
                        $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                        $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                        </script>
                    <?php
                    break;
                case "theft":
                    ?>
                    <form method="post" action="management.php?task=theft">
                    <table border="0" align="center" cellpadding="1" style="font-size:smaller; border:1px grey solid; margin:auto;margin-top:20px;width:90%;">
                    
                        <tbody>
                        <tr>
                            <td style="padding-left:10px;width:20%;" class="field_label">Friendly</td>
                        <td align="left" style="width: 16%;"><?php getFriendLists(); ?></td>
                        <td nowrap="" align="right" class="field_label" style="width: 23%;">Zone&nbsp;<select id="zone_id" name="zone_id"><option value="">All</option>
                        <option value="5010">5010 - Region 5 of UC</option>
                        
                        <option value="5030">5030 - Region 1 of UC NORM</option>
                        
                        <option value="5050">5050 - Region 2 of UC RAMON</option>
                        
                        <option value="5070">5070 - Region 3 of UC CHATO</option>
                        
                        <option value="5090">5090 - Region 4 of UC CHUCK</option>
                        
                        <option value="5105">5105 - CA San Diego Co. - Oceanside</option>
                        
                        <option value="5110">5110 - CA San Diego Central</option>
                        
                        <option value="5115">5115 - CA - San Diego Co. - Escondido</option>
                        
                        <option value="5117">5117 - CA San Diego Co. - Borrego</option>
                        
                        <option value="5120">5120 - CA San Diego Co. - Miramar</option>
                        
                        <option value="5125">5125 - CA San Diego Co. - El Cajon</option>
                        
                        <option value="5130">5130 - CA San Diego Co. -  Border East</option>
                        
                        <option value="5135">5135 - CA San Diego Co. -  Border West</option>
                        
                        <option value="5140">5140 - CA San Diego Co. - Interstate 8 East</option>
                        
                        <option value="5150">5150 - CA - National City - Chula Vista</option>
                        
                        <option value="5160">5160 - CA San Diego East</option>
                        
                        <option value="5165">5165 - CA - San Diego Co. La Mesa</option>
                        
                        <option value="5175">5175 - CA Point Loma</option>
                        
                        <option value="5180">5180 - CA San Diego Co. - Mission Valley</option>
                        
                        <option value="5190">5190 - CA Salton-Brawley</option>
                        
                        <option value="5195">5195 - CA Calexico-El Centro</option>
                        
                        <option value="5210">5210 - CA Orange County - South</option>
                        
                        <option value="5250">5250 - CA Orange County - North</option>
                        
                        <option value="5303">5303 - CA Riverside Co. Blythe</option>
                        
                        <option value="5310">5310 - CA Riverside Co. - Palm Springs</option>
                        
                        <option value="5320">5320 - CA Riverside Co,  Moreno Valley</option>
                        
                        <option value="5330">5330 - CA - Riverside Co. - Paris, Sun City</option>
                        
                        <option value="5340">5340 - CA - Riverside Co. - Temecula</option>
                        
                        <option value="5350">5350 - CA Riverside Central</option>
                        
                        <option value="5380">5380 - CA Riverside - West -Corona</option>
                        
                        <option value="5410">5410 - CA San Bernardino East</option>
                        
                        <option value="5420">5420 - CA San Bernardino West</option>
                        
                        <option value="5440">5440 - CA San Bernardino Mtns</option>
                        
                        <option value="5450">5450 - CA Mojave</option>
                        
                        <option value="5530">5530 - CA - LA Co. - San Gabriel Valley West</option>
                        
                        <option value="5610">5610 - CA - LA Co. - Long Beach</option>
                        
                        <option value="5620">5620 - CA Long Beach North</option>
                        
                        <option value="5660">5660 - CA Los Angeles - Harbor</option>
                        
                        <option value="5680">5680 - CA Los Angeles - South Bay</option>
                        
                        <option value="5685">5685 - CA Los Angeles - Palos Verdes</option>
                        
                        <option value="5740">5740 - CA Los Angeles - Downtown</option>
                        
                        <option value="5750">5750 - CA Los Angeles - West Central</option>
                        
                        <option value="5780">5780 - CA Los Angeles - Beaches</option>
                        
                        <option value="5820">5820 - CA - LA Co. - San Fernando Valley East</option>
                        
                        <option value="5840">5840 - CA - LA Co. - San Fernando Valley West</option>
                        
                        <option value="5870">5870 - CA - LA Co. - Tujunga, La Canada</option>
                        
                        <option value="5880">5880 - CA Victorville Barstow</option>
                        
                        <option value="5890">5890 - CA - LA Co. - Antelope Valley</option>
                        
                        <option value="5910">5910 - CA Ventura County</option>
                        
                        <option value="5950">5950 - CA Santa Barbara County</option>
                        
                        <option value="6401">6401 - AZ Unassigned</option>
                        
                        <option value="38010">38010 - CA North Coast</option>
                        
                        <option value="38015">38015 - CA Biotane-Encore Overlap</option>
                        
                        <option value="38050">38050 - CA North Central Siskiyou</option>
                        
                        <option value="38110">38110 - CA Sonoma</option>
                        
                        <option value="38150">38150 - CA Napa</option>
                        
                        <option value="38210">38210 - CA Marin</option>
                        
                        <option value="38250">38250 - CA - Solano</option>
                        
                        <option value="38310">38310 - CA - Contra Costa and Alameda</option>
                        
                        <option value="38410">38410 - CA San Francisco</option>
                        
                        <option value="38510">38510 - CA San Mateo</option>
                        
                        <option value="38550">38550 - CA San Jose</option>
                        
                        <option value="38610">38610 - CA Sacramento</option>
                        
                        <option value="38630">38630 - CA Sacramento Valley</option>
                        
                        <option value="38710">38710 - CA Stockton - Madera</option>
                        
                        <option value="38810">38810 - CA Santa Cruz</option>
                        
                        <option value="38850">38850 - CA Central Coast</option>
                        
                        <option value="38950">38950 - CA Fresno - Visalia</option>
                        
                        <option value="38970">38970 - CA Inyo Face</option>
                        
                        <option value="38990">38990 - CA Bakersfield - Kern Co.</option>
                        </select></td>
                        <td style="text-align: left;">
                        <input type="text" id="from" name="from"  value="<?php if(isset($_POST['from'])){ echo "$_POST[from]"; } ?>" placeholder="Beginning Date" style="border-radius:0px 0px 0px 0px;" />
                        
                        <input type="text" id="to"  value="<?php if(isset($_POST['to'])){ echo "$_POST[to]"; } ?>" name="to" placeholder="End Date" style="border-radius:0px 0px 0px 0px;" />
                        </td>
                        
                        <td width="80" align="left"><input type="reset"/><input type="submit" value="Search" name="search_now" /> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td>
                        </tr></tbody>
                        </table>
                        </form>
                    <?php
                    break;
                case "delivery":
                    ?>
                    <form action="management.php?task=delivery" method="post">
                    <table border="0" align="center" cellpadding="1" style="font-size:smaller; border:1px grey solid; margin-top:20px;width:90%">
                    <tbody>
                        <tr>
                            <td style="width: 10%;">Facility</td>
                            <td style="width: 15%;"><?php 
                                if (isset($_POST['search_now'])   )  { 
                                    getFacilityList("facility",$_POST['facility']);    
                                } else{ 
                                    getFacilityList();
                                } ; ?></td>
                            <td style="text-align:center;" class="field_label">
                            <input type="text" placeholder="start date"  value="<?php if(isset($_POST['from'])){ echo "$_POST[from]"; } ?>" style="border-radius: 0px 0px 0px 0px;" id="from" name="from" />&nbsp;
                            <input type="text" placeholder="end date"  value="<?php if(isset($_POST['to'])){ echo "$_POST[to]"; } ?>"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to" />

</td>
                            <td width="80" align="left"><input type="submit" value="Search" name="search_now"/><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td>
                        </tr>

</tbody></table></form>
<script>
$("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
$("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
</script>

                    <?php
                    break;
                    case "collected":
                    ?>
                    <form action="management.php?task=collected" method="post">
                    <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller;margin:auto;margin-top:20px;">
                        <tbody><tr>
                        <td style="padding: 5px 5px 5px 5px;text-align:left;width:25%">Facility &nbsp;&nbsp;<?php 
    if(isset($_POST['facility'])){ 
        getFacilityList("",$_POST['facility']);
    } else { 
        getFacilityList(); 
    }   ?></td>
                        <td style="padding: 5px 5px 5px 5px;text-align:left;width:25%;" class="field_label">Group By&nbsp;&nbsp;                                <select id="my_group" name="my_group">
                                <option value="-">--</option>
                                <option <?php if( $_POST['my_group'] == "account_rep"){ echo "selected"; } ?>   value="account_rep">Sales Rep</option>
                                
                                <option <?php if( $_POST['my_group'] == "friendly"){ echo "selected"; } ?>   value="friendly">Friendly</option>
                                
                                <option <?php if( $_POST['my_group'] == "previous_provider"){ echo "selected"; } ?>   value="previous_provider">Previous Provider</option>
                                
                                <option <?php if( $_POST['my_group'] == "division"){ echo "selected"; } ?>   value="division">Facility</option>
                            </select></td>
                            
                            <td style="padding-right:50px;text-align:center;" class="field_label">
                            <input type="checkbox" name="get_reps" <?php if(isset($_POST['get_reps'])){ echo "checked";  } ?>  />Show Reps &nbsp;&nbsp;
                            Rep&nbsp;<?php  if(isset($_POST['salesrep'])){ getSalesRep($_POST['salesrep']); } else {getSalesRep();}  ?></td>

<td nowrap="" align="right" class="field_label"><div>
                            <input type="text" value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" placeholder="start date" style="border-radius: 0px 0px 0px 0px;" id="from" name="from" />&nbsp;</div><div>
                            <input type="text" value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to" /></div></td><td width="80" align="right"><input type="submit" value="Search" name="search_now" /> <input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td></tr></tbody></table>
</form>
<br />
                            <script>
                            $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                            $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                            </script>
                    <?php
                    break;
                    case "picknpay":
                        ?>
                        <form action="management.php?task=picknpay" method="post">
                        <table width="95%" border="0" align="center" cellpadding="1" style="font-size:smaller;margin:auto;position:relative;top:3px;">
                            <tbody>
                                <tr>
                                    <td nowrap="" align="left" style="padding-left:10px; padding-right:20px;width:30%;" class="field_label">
                                    <input type="checkbox" name="archive" <?php if(isset($_POST['archive'])){ echo "checked='checked'";  } ?> />&nbsp; Show Archived Accounts<br />
                                    <input type="radio" <?php if(isset($_POST['checks_mode'])){ echo "checked"; } ?>  value="n" name="checks_mode" /> All Accounts (Full info about collections)<br />
                                    <input type="radio"  value="y" name="checks_mode" <?php if(isset($_POST['checks_mode'])){ echo "checked"; } ?> /> Printing Checks (only what is needed for printing checks)<div style="margin-left:30px;color:#dddddd" id="pt_label">Payment Threshold
                                    
                                    <select id="payment_threshold" name="payment_threshold">
                                    <option <?php 
                                    if( isset($_POST['payment_threshold']) ){
                                        if($_POST['payment_threshold'] == 0.00){
                                            echo "selected";
                                        }
                                    } ?> value="0.00"> $0.00</option>
                                    <option <?php 
                                    if( isset($_POST['payment_threshold']) ){
                                        if($_POST['payment_threshold'] == 5.00){
                                            echo "selected";
                                        }
                                    } ?> value="5.00">$5.00</option>
                                    <option <?php 
                                    if( isset($_POST['payment_threshold']) ){
                                        if($_POST['payment_threshold'] == 10.00){
                                            echo "selected";
                                        }
                                    } ?> value="10.00">$10.00</option>
                                    <option <?php 
                                    if( isset($_POST['payment_threshold']) ){
                                        if($_POST['payment_threshold'] == 20.00){
                                            echo "selected";
                                        }
                                    } ?> value="20.00">$20.00</option>
                                    </select> <span style="font-size:smaller;">(Get accounts owed more than...)</span></div></td>
                                    <td style="width: 25%;">Facility <?php if(isset($_POST['facility'])){ echo getFacilityList("",$_POST['facility']);  } else {echo getFacilityList();} ?>
                                    <br />Group By <select name="my_group" id="my_group">
                                                        <option value="-">--</option>
                                                           
                                                            
                                                            <option <?php if(isset($_POST['search_now'])){  if($_POST['my_group'] == "account"){ echo "selected='selected' "; } } ?>  value="account">Account</option>
                                                            
                                                            
                                </select></td>
                                    <td nowrap="" align="right" style="padding-left:10px; padding-right:50px;width:25%;" class="field_label"><div>
                                    <input value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" type="text" placeholder="start date" style="border-radius: 0px 0px 0px 0px;" id="from" name="from" /></div><div>
                                    <input value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" type="text" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to" /></div></td>
                                    </tr>
                                    <tr>
                                    <td colspan="3" align="left" style="text-align:right;"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>&nbsp;<input type="reset"/> &nbsp;<input type="submit" value="Search" name="search_now" /> </td></tr></tbody></table></form>
                        <script>
                            $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                            $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                            </script>
                                    </tr>
                                    </table>
                        <?php
                    break;
                    case"oilperloc":
                        ?>
                        <form action="management.php?task=oilperloc" method="post">
                        <table border="0" align="center" cellpadding="1" style="font-size:smaller;margin-top:20px;margin:auto;">
                            <tbody>
                                <tr>
                                    <td nowrap="" align="right" style="padding-left:10px; padding-right:50px;" class="field_label">
                                        <div style="margin-right:30px;">Oil Collection Period</div>
                                        <div>
                                            <input type="text" placeholder="start date" style="border-radius: 0px 0px 0px 0px;" id="from" name="from" value="<?php if(isset($_POST['from'])){ echo "$_POST[from]"; } ?>" />
                                        </div>
                                        <div><input type="text" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to"  value="<?php if(isset($_POST['to'])){ echo "$_POST[to]"; } ?>" /></div>
                                    </td>
                                    
                                    <td width="220" align="center">Friendly&nbsp;<?php echo getFriendLists(); ?></td>
                                    <td width="220" align="center">Account Name <input value="" size="15"  <?php if(isset($_POST['account_name'])){ echo "$_POST[account_name]"; } ?> name="account_name"/></td>
                                    <td width="220" align="center">Account ID <input value="<?php if(isset($_POST['account_id'])){ echo "$_POST[account_id]"; } ?>" size="10" name="account_id" /></td>
                                    <td width="220" align="center">State <input value="<?php if(isset($_POST['state'])){ echo "$_POST[state]"; } ?>" size="3" name="state"/></td></tr>
                                    <tr>
                                    <tr><td colspan="10" style="text-align: right;"><input type="submit" value="Search" name="search_now"/><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td></tr>
                            </tbody>
                        </table>
                        </form>
                        <script>
                            $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                            $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                            </script>
                        <?php
                    break;
                
                case "affil":
                    ?>
                    <form action="management.php?task=affil" id="exp_form" name="exp_form" method="post">
                    <input type="hidden" value="export_sludge_oil_breakout_per_route" name="task"  value="<?php if(isset($_POST['task'])){ echo "$_POST[task]"; } ?>" />
                    <input type="hidden" value="yes" name="update_search" value="<?php if(isset($_POST['update_search'])){ echo "$_POST[update_search]"; } ?>" />
                    <input type="hidden" value="" name="export_set" value="<?php if(isset($_POST['export_set'])){ echo "$_POST[export_set]"; } ?>"  /> 
                    <input type="hidden" value="sludge_obr" name="event"  value="<?php if(isset($_POST['event'])){ echo "$_POST[event]"; } ?>"/>
                    
                    <table border="0" align="center" cellpadding="1" style="font-size:smaller;"><tbody><tr>
                    <td> 
                     Biotane <input  <?php if(isset($_POST['biocheck'])){ echo "checked"; } ?> name="biocheck" type="checkbox"/>
                    </td>
                    
                    <td width="200" align="center">Route Title 
                    <input value="" size="15" name="title" /></td><td width="200" align="center">Route ID 
                    <input value="" size="10"  type="text" value="<?php if(isset($_POST['route_id'])){ echo "$_POST[route_id]"; } ?>" name="route_id"/></td><td width="200" align="center">Facility&nbsp;<?php if(isset($_POST['search_now'])){  getFacilityList("facility",$_POST['facility']); }else{   getFacilityList();  }  ?></td><td nowrap="" align="right" style="padding-left:30px; padding-right:30px;" class="field_label"><div>
                    <input  value="<?php if(isset($_POST['from'])){ echo "$_POST[from]"; } ?>" type="text" placeholder="start date" style="border-radius: 0px 0px 0px 0px;" id="from" name="from" /></div><div>
                    <input type="text" placeholder="end date"  style="border-radius: 0px 0px 0px 0px;" id="to" name="to"  value="<?php if(isset($_POST['to'])){ echo "$_POST[to]"; } ?>" /></div></td></tr>

<tr><td colspan="3" style="vertical-align: central;text-align:center;"><input type="submit" value="Search" name="search_now" /><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td><td colspan="2" style="vertical-align: central;text-align:center;"><button >Export Result</button></td></tr>
</tbody></table>
</form>
                    <script>
                    $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    </script>

                    <?php
                    break;
                case "issues":
                    ?>
                    <form action="customers.php?task=issues" method="post">
                    <table  style="font-size:smaller;margin:auto;margin-bottom:20px;margin-top:20px;width:50%;"><tbody><tr>
<td nowrap="" align="left" style="padding-right:5px;" class="field_label">Status</td><td>
                                    <select id="message_status" name="message_status">
                                    <option value="-">--</option>
                                    <option value="1">New and Active</option>
                                    
                                    <option value="2">New</option>
                                    
                                    <option value="3">Active</option>
                                    
                                    <option value="4">Resolved</option>
                                    
                                    <option value="5">Closed</option>
                                    </select></td></tr>
                                    
                                    <tr><td  class="field_label">Category</td><td>

                <select id="my_message_category_id" name="my_message_category_id"><option value="-">All</option>
                <option value="50">Account Needs Attention</option>
                
                <option value="60">Location Needs Attention</option>
                
                <option value="70">Customer Request</option>
                
                <option value="72">In House Request</option>
                
                
                
                <option value="90">Competitor On Site</option>
                
                <option value="100">Location Closing</option>
                
                <option value="110">Account Closing</option>
                
                <option value="120">Call Center Message</option>
                </select>
                
                </td>
                </tr>
                
                <tr>
                <td style="padding-right:5px;" class="field_label"><span title="Users with issues assigned to them. Note that this list will change with the status setting">User Assigned</span></td><td>

            <select id="assigned_to_user_id" name="assigned_to_user_id"><option value="-">--</option>
                <option value="19">Jim Austin</option>
                
                <option value="22">Chris Beltran</option>
                
                
                
                <option value="35">Ashley Trawick</option>
                
                <option value="48">Harvey Estrada</option>
                
                <option value="15">David Isen</option>
                
                <option value="34">William Keifer</option>
                
                <option value="16">Adam Parsons</option>
                
                <option value="21">Ryan Parsons</option>
                
                <option value="20">Antonio Sanchez</option>
            </select>

</td></tr><tr>

            <td colspan="2" style="text-align: right;"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" value="Search" name="search_now" /></td></tr></tbody></table>
                </form>                    
                    <?php
                    break;
                    case "tracker":
                        ?>
                        <form action="customers.php?task=tracker" method="post">
                        <table style="font-size:smaller;margin:auto;margin-top:20px;margin-bottom:20px;width:50%;"><tbody><tr>
                    <td>Location Status</td><td><select id="location_status" name="location_status">
                <option  <?php if( $_POST['location_status'] == 3 ) { echo "selected";} ?> value="3">Both</option>
                <option <?php if( $_POST['location_status'] == "new" ) { echo "selected";} ?> value="new">New</option>
                
                <option <?php if( $_POST['location_status'] == "active" ) { echo "selected";} ?> value="active">Active</option>


</select></td></tr>

                    <tr>
                    <td class="field_label">Original Sale By </td><td>
                    <?php
                    
                    
                    if(isset($_POST['salesrep'])){
                        getSalesRep($_POST['salesrep']);    
                    } else {
                        getSalesRep();
                    }
                    
                    ?>
                    
                    </td>
                    
                    </tr>
                    <tr>
                    <td>Flags</td><td> <select id="flag_id" name="flag_id">
                    <option value="-">--</option>
                    <option <?php if($_POST['flag_id'] == 1) { echo "selected";}  ?> value="1">Needs Contract</option>
                    <option <?php if($_POST['flag_id'] == 6) { echo "selected";}  ?> value="6">Bad Payment Address</option>
                    
                    <option <?php if($_POST['flag_id'] == 7) { echo "selected";}  ?> value="7">Bad Main Address</option>
                    <option  <?php if($_POST['flag_id'] == 8) { echo "selected";}  ?> value="8">No Barrel present</option>
                    </select> </td>
                    </tr>
                    <tr>
<td  colspan="2" style="text-align: right;"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" value="Search" name="search_now"/></td></tr></tbody></table></form>
                        <?php
                    break;
                case "services":
                ?>
                <form action="customers.php?task=services" method="post">
                <table style="width: 50%;margin:auto;margin-top:20px;margin-bottom:20px;">
                <tr>
                <td  class="field_label">Status</td>
                <td width="200"><select id="location_status" name="location_status">
                <option value="ending">Ending Service</option>
                
                <option value="archive">Archived</option>
                
                <option value="both">Both</option>
                </select></td></tr>
                
                <tr>
                <td style="text-align:right;" colspan="2"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" value="Search" name="search_now" /></td></tr></table></form>
                <?php
                    break;
                case "fgrid":
                    ?>
                    <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller"><tbody><tr><td align="left">
<div align="center" style="margin:10px;">&nbsp;</span><span style="padding-left:30px;">&nbsp;</span></div></td><td width="100">
                    <input value="" size="15" name="search_value"  value="<?php if(isset($_POST['search_value'])){ echo "$_POST[search_value]"; } ?>" />
                    
                    </td><td width="80" align="left"><input type="submit" value="Search" name="search" /> <input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td></tr></tbody></table>
                    
                    <?php
                    break;
                case "oilongoing":
                    ?>
                    <form action="scheduling.php?task=oilongoing" method="post">
                    <input type="hidden" value="report_collected_fires" name="task" value="<?php if(isset($_POST['get_stats'])){ echo $_POST['get_stats'];} ?>" />
                    
                    <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller;margin-top:10px;"><tbody><tr><td nowrap="" align="right" style="padding-right:50px;" class="field_label">Group By&nbsp;<select id="my_group" name="my_group">
                    <option value="-">--</option>
                    <option value="account_rep">Account Rep</option>
                    <option value="division">Facility</option>
                    <option value="driver">Driver</option>
                    <option value="created_by">Created By</option>


</select></td>

<td nowrap="" align="right" style="padding-right:50px;text-align:center;" class="field_label">

Wait Days Range  <input type="text" id="min" name="min" placeholder="min days" style="width: 90px;"  value="<?php if(isset($_POST['search_now'])){ echo $_POST['min'];  } ?>"/>&nbsp;to&nbsp;<input type="text" id="max" name="max" placeholder="max days"  style="width: 90px;"   value="<?php if(isset($_POST['search_now'])){ echo $_POST['max'];  } ?>"/>

</td>
<td>
Facility:&nbsp;
<?php 
                    if(isset($_POST['search_now'])){
                        echo getFacilityList("",$_POST['facility']);
                    } else {
                        echo getFacilityList("","");
                    }
                   ?></td>
<td nowrap="" align="right" class="field_label"><div>
<input type="radio" name="report_type"  <?php 
        if(isset($_POST['search_now'])){  
            if(isset($_POST['report_type']) == 1) { 
                    echo "checked='checked'";
            } 
        
        }else { 
                echo 'checked="checked"';  
            }  ?>  value="1"/> Date Reported&nbsp;<br />
Start Date&nbsp;
            
            
            <input type="text" value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" id="from" name="from" placeholder="From: "/></div><div>End Date&nbsp;&nbsp;&nbsp;
            <input type="text" value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" id="to" name="to" placeholder="To: "/></div></td><td width="80" align="right"><input type="submit" value="Search" name="search_now"/><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>
</td></tr></tbody></table></form>
                    <?php
                    break;
                case "oilcomplete":
                    ?>
                    <form action="scheduling.php?task=oilcomplete" method="post">
                    <input type="hidden" value="report_collected_fires" name="task" value="<?php if(isset($_POST['get_stats'])){ echo $_POST['get_stats'];} ?>" />
                    
                    <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller;margin-top:10px;"><tbody><tr><td nowrap="" align="right" style="padding-right:50px;" class="field_label">Group By&nbsp;<select id="my_group" name="my_group">
                    <option value="-">--</option>
                    <option value="account_rep">Account Rep</option>
                    <option value="division">Facility</option>
                    <option value="driver">Driver</option>
                    <option value="created_by">Created By</option>


</select></td>

<td nowrap="" align="right" style="padding-right:50px;text-align:center;" class="field_label">

Wait Days Range  <input type="text" id="min" name="min" placeholder="min days" style="width: 90px;"  value="<?php if(isset($_POST['search_now'])){ echo $_POST['min'];  } ?>"/>&nbsp;to&nbsp;<input type="text" id="max" name="max" placeholder="max days"  style="width: 90px;"   value="<?php if(isset($_POST['search_now'])){ echo $_POST['max'];  } ?>"/>

</td>

<td nowrap="" align="right" class="field_label"><div>
<input type="radio" name="report_type"  <?php 
        if(isset($_POST['search_now'])){  
            if(isset($_POST['report_type']) == 1) { 
                    echo "checked='checked'";
            } 
        
        }else { 
                echo 'checked="checked"';  
            }  ?>  value="1"/> Date Reported&nbsp;<input type="radio" name="report_type" <?php if(isset($_POST['search_now'])){  if(isset($_POST['report_type']) == 1) { echo "checked='checked'";}   } ?>  value="2"/> Date Collected&nbsp;<br />
Start Date&nbsp;
            
            
            <input type="text" value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" id="from" name="from" placeholder="From: "/></div><div>End Date&nbsp;&nbsp;&nbsp;
            <input type="text" value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" id="to" name="to" placeholder="To: "/></div></td>
            <td>
Facility:&nbsp;
<?php 
                    if(isset($_POST['search_now'])){
                        echo getFacilityList("",$_POST['facility']);
                    } else {
                        echo getFacilityList("","");
                    }
                   ?></td>
            <td width="80" align="right"><input type="submit" value="Search" name="search_now"/><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>
</td></tr></tbody></table></form>

<br />
<script>
                    $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    </script>
                    <?php
                    break;
                case "rop":
                    ?>
                    <style>
                    div#transparent table td{
    border-color:#ccc;
}                   input[type=text]{
                        border :1px solid #ccc;
                    }
                   table#secondsearchparams td{
font-size: 12px; border:1px green solid; margin:auto;width:80%;margin-top:35px;font-weight:normal;font-weight:bold;
text-align:left;
                  </style>    
                   <form action="scheduling.php?task=rop" method="post">
                                   <table  style="font-size:smaller;table-layout: fixed;width:50%;margin:auto;margin-top:20px;margin-bottom:20px;"><tbody>
                                   <tr>
                                   <td >Route Id</td><td><input type="text" name="rid" placeholder="Route Id" value="<?php if(isset($_POST['rid'])){ echo $_POST['rid']; } else {echo "";} ?>"/></td></tr>
                                   
                                    <tr>
                                    <td style="width: 50%;text-align:left;">Route title</td><td><input type="text" value="<?php if(isset($_POST['rtitle'])){ echo $_POST['rtitle']; } else {echo "";} ?>" placeholder="route title" id="rtitle" name="rtitle"/></td></tr>
                                   </tr>
                                   
                                   <tr>
                                   <td style="width: 50%;text-align:left;"  class="field_label" style="width: 20%;">Status</td><td>
            
                                    <select id="status_id" name="status_id">
                                    <option value="scheduled">Scheduled</option>
                                    
                                    <option selected="" value="enroute">En-route</option>
                                    
                                    </select></td></tr>
            
           
           <tr>
           <td  style="width: 50%;text-align:left;"   class="field_label">Driver</td><td>
            <?php 
            getDrivers();
            ?>
            </td>
            </tr>
            
             <tr>
             <td  style="width: 50%;text-align:left;"  class="field_label">Facility</td><td>
            <?php 
            if(!$person->isCoWest() && !$person->isFriendly()){ 
                echo getFacilityList("",""); 
            }else{
                echo "&nbsp;";
            }
           ?>
           </td></tr>
          
           <tr>
            <td  colspan="2" style="text-align: right;"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>"  style="margin-left:10px;">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" value="Search" name="search_now" style="margin-left:10px;"/></td></tr></tbody>

</table>
                        
                        </form>
                        <div style="clear: both;"></div> 
                        
                         <script>
                        $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                        $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                        </script>
                        <div style="clear: both;"></div>   
                    <?php
                    break;
                    case "shifts":
                        ?>
                        <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller"><tbody><tr><td nowrap="" align="right" class="field_label">Status</td>
<td width="100"><select id="trip_status_id" name="trip_status_id"><option value="all">All Shifts</option><option selected="" value="current">Current Shifts</option><option value="coming">New and Scheduled</option>
<option value="10">New</option>

<option value="20">Scheduled</option>

<option value="30">En Route</option>

<option value="40">Returned</option>

<option value="50">Completed</option>
</select></td>
<td nowrap="" align="right" style="padding-left:30px; padding-right:30px;" rowspan="2" class="field_label"><div>Start Date&nbsp;<input value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>  ?>" type="text" name="from" id="from"/></div><div>End Date&nbsp;&nbsp;&nbsp;<input value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>  ?>" type="text" name="to" id="to"/></div></td></tr><tr>
</tr><tr><td nowrap="" align="right" style="padding-left:10px;" class="field_label">Driver</td>
<td width="150" align="left">
<?php getDrivers(); ?></td><td width="80" align="center"><input type="submit" value="Search" name="search_now"/><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td></tr></tbody></table>
                    <script>
                    $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                        $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    </script>
                        <?php
                        break;
                 case "pickexp":
                        ?>
                        <form name="exp_form" action="home.php" method="post"><input type="hidden" value="export_pickups" name="task"><div style="margin:auto;width:80%;margin-top:20px;"><div style="float:left; width:180px; margin-left:20px; font-size:smaller;margin-left:20px;border:1px solid #efefef; padding:5px; padding-left:10px; border-radius: 10px; box-shadow: 2px 2px 2px #bbbbbb;"><table width="100%" border="0" align="center" cellpadding="1" id="export_box">

<tbody><tr><td width="180" align="center" class="field_label">As <select name="export_type"><option value="csv">CSV</option><option value="tab">Tab</option></select></td>
</tr><tr><td width="200" align="center" class="field_label">Encoding <select name="export_encoding"><option value="win">Windows</option><option value="unix">UNIX / Mac</option></select></td>
</tr><tr><td align="center">
                            <input type="hidden" value="" id="export_set" name="export_set" value="<?php  if( isset($_POST['export_set']) ){ echo $_POST['export_set'];} ?>  ?>" />
                            <input type="hidden" value="current_pickups" id="event" name="event" value="<?php  if( isset($_POST['event']) ){ echo $_POST['event'];} ?>  ?>" />
                            <input type="button"  value="Export" name="export" />

</td></tr></tbody></table>
</div>
<div style="float:right; width:500px;font-size:smaller;margin-left:20px;border:1px solid #efefef; padding:5px; padding-left:10px; border-radius: 10px; box-shadow: 2px 2px 2px #bbbbbb;"><table border="0" align="center" cellpadding="1" style="font-size:smaller"><tbody><tr><td width="120" align="left">
<div style="margin-top:10px;"><span class="field_label">Facility</span><?php echo getFacilityList(); ?></div><div style="margin-left:20px;margin-top:10px; width:120px;" class="field_label"><input type="checkbox" id="only_routed" name="only_routed"> Only Routed</div>
<div style="margin-left:20px;margin-top:2px; width:120px;" class="field_label">
                    <input type="checkbox" <?php  if( isset($_POST['only_fires']) ){ echo "checked";} ?>  id="only_fires" name="only_fires" /> Only Fires</div>
</td><td nowrap="" align="right" style="padding-left:4px; padding-right:30px;" class="field_label"><div style="text-align:left; margin-left:26px;margin-top:4px; " class="field_label">

<input type="checkbox"  id="use_date_range" name="use_date_range" <?php  if( isset($_POST['use_date_range']) ){ echo "checked";} ?>  /> Use Date Range <span class="mini">(Pickup's Scheduled Date)</span></div>
<div style="color:#dddddd;" id="sd_box">From&nbsp;<input value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>  ?>" type="text" id="from" name /></div><div style="margin-left:10px;color:#dddddd;" id="ed_box">To&nbsp;<input type="text" id="to" name="to" value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>  ?>" style=""/></div></td><td width="150" align="center"><input type="button"  value="Search" name="search_now"/><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>

</td></tr></tbody></table></div></div></form>
    <script>
    $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                    $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
    </script>

                        <?php
                        break;
                
                case "schoipu":                    
                    ?>
                    <style>
                        div#transparent table td{
                            border-color:#ccc;
                        }                   input[type=text]{
                            border :1px solid #ccc;
                            border-radius: 0px 0px 0px 0px;
                        }
                        table#secondsearchparams td{
                                font-size: 12px; border:1px green solid; margin:auto;width:80%;margin-top:35px;font-weight:normal;font-weight:bold;
                                text-align:left;
                                    
                        }
                        input{ 
                            border-radius:0px 0px 0px 0px;
                            width:150px;
                        }
                        
                        ul.facs {
                            float: left;
                            font-size: 12px;    
                            width: 330px;
                            margin-left:-10px;
                        }
                        
                        li.fac {
                            width:330px;
                        }
                    </style>
                      
                       <div id="duplicatesix" style="width: 1000px;margin:auto;height:auto;">
                           
                            <div id="searchTableLeft" style="width: 600px;height:auto;float:left;">
                            <table style="width:600px;border:1px solid #bbb;float:left;">
                              <tr>
                                <td style="vertical-align: middle;text-align:center;border:0px solid #bbb;width:20px;padding:0px 0px 0px 0px;">
                                <form method="post" action="oil_routing.php" name="schedtoikg" id="schedtoikg" target="_blank">                        
                                <input type="radio" class="new" />
                                
                                <input type="hidden" class="schecheduled_ids"  name="schecheduled_ids" placeholder="schedule numbers"  readonly=""/>
                                <input type="hidden" class="accounts_checked" name="accounts_checked" placeholder="account numbers"  readonly=""/>
                                <input type="hidden"  name="from_schoipu" value="1" readonly=""/>
                                </form>
                                
                                
                                </td>
                                <td style="width:580px;">
                                Create New Route
                                </td>
                              </tr>
                             <tr>
                                <td style="vertical-align: middle;text-align:center;border:1px solid #bbb;width:20px;padding:0px 0px 0px 0px;">                 
                                    <form method="post" id="add_to_form" action="oil_routing.php" method="post" target="_blank" name="add_to_form">
                                    <input type="hidden" name="from_routed_oil_pickups" id="from_routed_oil_pickups" title="from_routed_oil_pickups" value="1"/>
                                    
                                    <input type="hidden" class="schecheduled_ids"  name="schecheduled_ids" placeholder="schedule numbers" readonly=""/>
                                    <input type="hidden" class="accounts_checked" name="accounts_checked" placeholder="account numbers" readonly=""/>
                                    <input type="hidden" name="extra_mode" value="1"/>
                                    <input type="radio" name="route"  class="existing"  id="route" value="exist"/>
                                </td>
                                <td style="width:580px;text-align:center;"> 
                                    <span style="float: left;">Add to Existing Route</span><br />
                                    <select name="manifest" id="manifest" >
                                    
                                    <?php  
                                      $route_list_table = $dbprefix."_list_of_routes";
                                        $scrts = $db->query("SELECT route_id,ikg_manifest_route_number,driver FROM $route_list_table WHERE status ='new' || status='enroute' || status='scheduled'");
                                        
                                        if(count($scrts)>0){
                                            foreach($scrts as $add_existing){
                                                echo "<option value='$add_existing[route_id]'>$add_existing[route_id] $add_existing[ikg_manifest_route_number] (".uNumtoName($add_existing['driver']).")</option>";
                                            }
                                        }
                                    ?></select>&nbsp;<input style="color: black;" type="submit" value="submit" name="schedule_us" id="schedule_us" />
                                    
                                    
                                    </form>
                                    <p  ><input type="checkbox"/>&nbsp;Show Routes from all Facilities</p>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:0px 0p 0px 0px;text-align:center;">
                            
                            
                            <table style="width: 100%;border:0px solid #bbb;padding:0px 0px 0px 0px;height:auto;">
                                <tr style="background: red;">
                                    <td style="text-align: left;width:33%;padding:0px 0px 0px 0px;border:0px solid #Bbb;">  
                                    <form action="scheduling.php?task=schoipu" method="post">
                                            <input style="color: black;" type="submit" value="Show Code Reds"  name="show_creds"/>
                                    </form>
                                    </td>
                                    <td  style="text-align: center;width:33%;padding:0px 0px 0px 0px;border:0px solid #Bbb;">
                                        <form action="mapcodered.php?mode=grease" method="post" target="_blank">
                                        <input style="color: black;" type="submit" value="Map Code Reds" id="mapcodered" />
                                        </form>
                                        
                                    </td>
                                    
                                    <td  style="text-align: right;padding:0px 0px 0px 0px;border:0px solid #Bbb;">
                                    <form action="preview_route.php?mode=cs" method="post" target="_blank">
                                    <input type="hidden" class="schecheduled_ids"  name="schecheduled_ids" placeholder="schedule numbers"/>
                                    <input type="hidden" class="accounts_checked" name="accounts_checked" placeholder="account numbers" />
                                    <input type="submit" value="preview route" id="preview"/>
                                    </form>
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%;">
                                <tr>
                                    <td style="text-align: center;" colspan="3">
                                        <form action="scheduling.php?task=schoipu" method="post">
                                        <input type="text" id="from" name="from" placeholder="Start Date" name="from"/>&nbsp;&nbsp;
                                        <input type="text" id="to" name="to" placeholder="End Date" style="border-radius: 0px 0px 0px 0px;"/>
                                </td>
                            </tr>
                            </table>
                            </td>
                            
                            </tr>
                            <tr>
                            <td colspan="5" style="padding:0px 0px 0px 0px;">
                            </td></tr>
                                </table>
                                
                            </div>
                            
                                <div id="spacer" style="width: 30px;height:80px;float:left;">&nbsp;</div>
                                
                                <div id="searchTableRight" style="width: 370px;height:auto;float:left;">
                                    <?php 
                                        if(!$person->isFriendly() ){
                                            ?>
                                            <table style="width:370px;background:white;height:100px;width:100%;">
                                     
                                        <tr>
                                            <td style="text-align: center;" colspan="2">Facility</span></td>
                                        </tr>
                                        <tr>
                            <td style="vertical-align:top;width:50%;pading:0px 0px 0px 0px;text-align:left;padding:0px 0px 0px 0px;" colspan="2">
                                <ul class="facs">          
                                    <li><input id="all" name="all" <?php if(isset($_POST['all'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;All</li>
                                   <li><input name="allfac" type="checkbox" id="alluc"/>&nbsp;ALL UC</li>
                                    
                                    <li><input value="24" name="fac3" type="checkbox" class="fac uc" <?php if(isset($_POST['fac3'])){ echo "checked"; } else { if($person->facility == 24){ echo "checked"; } }?>  />&nbsp;LA Division(UC)</li> 
                                    <li><input value="32" name="fac4" type="checkbox" class="fac uc" <?php if(isset($_POST['fac4'])){ echo "checked"; } else {  if($person->facility == 32){ echo "checked"; } }?>/>&nbsp;LA Division(UC-Chato)</li>
                                    <li><input value="33"  <?php if(isset($_POST['fac5'])){ echo "checked"; } else {    if($person->facility == 33){ echo "checked"; } }?>  name="fac5" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Chuck)</li>
                                    <li><input value="25" <?php if(isset($_POST['fac6'])){ echo "checked"; } else {  if($person->facility == 25){ echo "checked"; } }?>  name="fac6" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Ramon)</li>
                                    <li><input value="30" <?php if(isset($_POST['fac7'])){ echo "checked"; } { if($person->facility == 30){ echo "checked"; } }?>  name="fac7" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Tony)</li>
                                    
                                    
                                    <li><input value="15" name="fac15" type="checkbox" class="fac uc"  <?php if(isset($_POST['fac15'])){ echo "checked"; } else {  if($person->facility == 15){ echo "checked"; } }?>/>&nbsp;W Division Bernadino</li>    
                                     <li><input value="14"  <?php if(isset($_POST['fac14'])){ echo "checked"; } else {  if($person->facility == 14){ echo "checked"; } }?> name="fac14" type="checkbox" class="fac"/>&nbsp;L Division (Coachella)</li>
                                    <li><input value="18" name="fac18" type="checkbox" class="fac"  <?php if(isset($_POST['fac18'])){ echo "checked"; } else {  if($person->facility == 18){ echo "checked"; } }?>/>&nbsp;RC Waste Resources LC</li> 
                                    
                                    
                                    
                                </ul>
                            </td>
                            
                            </tr>
                           <tr>
                            <td style="width: 80%;pading:0px 0px 0px 0px;text-align:left;padding:0px 0px 0px 0px;" colspan="2">
                              <?php 
                                if(!$person->isCoWest()){
                              ?>
                                <ul class="facs" >          
                                    
                                    <li><input name="allselma"  id="allselma" type="checkbox" class="selma"/>&nbsp;All Selma</li>
                                    <li><input value="10" name="fac10" type="checkbox" class="fac selma"  <?php if(isset($_POST['fac10'])){ echo "checked"; } else {  if($person->facility == 10){ echo "checked"; } }?>/>&nbsp;V-BAK</li>
                                    <li><input value="11" name="fac11" type="checkbox" class="fac selma"  <?php if(isset($_POST['fac11'])){ echo "checked"; } else {  if($person->facility == 11){ echo "checked"; } }?>/>&nbsp;V-Fresno</li>
                                    <li><input value="14"  <?php if(isset($_POST['fac14'])){ echo "checked"; } else {  if($person->facility == 14){ echo "checked"; } }?> name="fac14" type="checkbox" class="fac"/>&nbsp;L Division (Coachella)</li>                                   
                                    <li><input value="8" name="fac1" type="checkbox" class="fac"  <?php if(isset($_POST['fac1'])){ echo "checked"; } else {  if($person->facility == 8){ echo "checked"; }} ?> />&nbsp;Arizona Division(4)</li>
                                    <li><input value="23" name="fac2" type="checkbox" class="fac" <?php if(isset($_POST['fac2'])){ echo "checked"; } else { if($person->facility == 23){ echo "checked"; }} ?>  />&nbsp;Coachella Division (UD)</li>
                                  
                                    <li><input value="22" <?php if(isset($_POST['fac8'])){ echo "checked"; } else { if($person->facility == 22){ echo "checked"; } }?>  name="fac8" type="checkbox" class="fac"/>&nbsp;San Diego Division (US)</li>
                                    <li><input value="5" <?php if(isset($_POST['fac9'])){ echo "checked"; } else {  if($person->facility == 5){ echo "checked"; } }?>  name="fac9" type="checkbox"  class="fac selma"/>&nbsp;Selma (V)</li>
                                    <li><input value="12"  <?php if(isset($_POST['fac12'])){ echo "checked"; } else {  if($person->facility == 12){ echo "checked"; } }?> name="fac12" type="checkbox" class="fac selma"/>&nbsp;V-North</li>
                                    <li><input value="13"  <?php if(isset($_POST['fac13'])){ echo "checked"; } else {  if($person->facility == 13){ echo "checked"; } }?> name="fac13" type="checkbox" class="fac selma"/>&nbsp;V-Visalia</li>
                                    <li><input value="16" name="fac16" type="checkbox" class="fac"  <?php if(isset($_POST['fac16'])){ echo "checked"; } else {  if($person->facility == 16){ echo "checked"; } }?>/>&nbsp;Victorville</li>  
                                     <li><input value="17" name="fac17" type="checkbox" class="fac"  <?php if(isset($_POST['fac17'])){ echo "checked"; } else {  if($person->facility == 17){ echo "checked"; } }?>/>&nbsp;RC Waste Resources MV</li> 
                                </ul>
                                <?php } else{
                                    echo "&nbsp;";
                                } ?>
                            </td>
                           </tr>
                        </table>
                                <?php
                            }
                        ?>     
                    </div>
                                <div style="clear: both;"></div>
                            </div>                            
                       
                       
                       <div id="fullgraysearch" style="width: 100%;background:#bbbbbb;height:auto;float:left;">
                           <table style="width: 1000px;margin:auto;background:white;">
                            <tr>
                                <td style="width:200px;text-align: center;">Id</td>
                                <td style="width:200px;text-align: center;">Name</td>
                                <td style="width:200px;text-align: center;">City</td>
                                <td style="width:200px;text-align: center;">State</td>
                                <td style="width:200px;text-align: center;">Zip</td>
                                
                            </tr>
                          <tr>
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['id']) ){ echo $_POST['id'];} ?>" type="text" name="id" id="id" style="width: 190px;"/></td>
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['name']) ){ echo $_POST['name'];} ?>" type="text" name="name"  id="name"  style="width: 190px;" />
                                </td>                                            
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['city']) ){ echo $_POST['city'];} ?>" type="text" name="city" id="city"  style="width: 190px;" />
                                </td>
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['state']) ){ echo $_POST['state'];} ?>" type="text" name="state" id="state"  style="width: 190px;" />
                                </td>                                                            
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['zip']) ){ echo $_POST['zip'];} ?>" type="text" name="zip" id="zip"   style="width: 190px;"/>
                                </td>
                            </tr>  
                            <tr>
                                <td colspan="10"  style="text-align:right;"> <input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>"  style="margin-left: 10px;"/>>Default Data View</a> <input style="color: black;" type="submit" value="Search" name="search_now" style="margin-left: 10px;"/></td>
                            </tr>
                        </table>
                        </div>
                        
                       
                        
                        <div style="clear: both;"></div> 
                        
                         <script>
                        $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                        $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                        </script>
                        <div style="clear: both;"></div>
                        </form>         
                    <?php
                   
                    
                    break;
                case "cuc":
                    ?>
                    <form action="scheduling.php?task=cuc" method="post">
                    <input type="hidden" value="report_collected_fires" name="task" value="<?php if(isset($_POST['get_stats'])){ echo $_POST['get_stats'];} ?>" />
                    
                    <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller;margin-top:10px;"><tbody><tr>
                     <td style="width: 250px;">Route title</td>
                     <td><input type="text" value="<?php if(isset($_POST['rtitle'])){ echo $_POST['rtitle']; } else {echo "";} ?>" placeholder="route title" id="rtitle" name="rtitle"/></td>
                   <td>Route id</td>
                   <td><input type="text" name="rid" placeholder="Route Id" value="<?php if(isset($_POST['rid'])){ echo $_POST['rid']; } else {echo "";} ?>"/></td>
                    <td nowrap="" align="right" style="padding-right:50px;" class="field_label">Group By&nbsp;<select id="my_group" name="my_group">
                    <option value="-">--</option>
                    <option value="created_by">Created By</option>
                    <?php if(!$person->isFriendly() && !$person->isCoWest()) {?>
                    <option value="recieving_facility">Facility</option>
                    <?php } ?>
                    <option value="driver">Driver</option>
                    <option value="created_by">Created By</option>


</select></td>
                </tr>
                <tr>
<td nowrap="" align="right" style="padding-right:50px;text-align:center;" class="field_label">

Wait Days Range  <input type="text" id="min" name="min" placeholder="min days" style="width: 90px;"  value="<?php if(isset($_POST['search_now'])){ echo $_POST['min'];  } ?>"/>&nbsp;to&nbsp;<input type="text" id="max" name="max" placeholder="max days"  style="width: 90px;"   value="<?php if(isset($_POST['search_now'])){ echo $_POST['max'];  } ?>"/>

</td>

<td nowrap="" align="right" class="field_label"><div>
<input type="radio" name="report_type"  <?php 
        if(isset($_POST['search_now'])){  
            if($_POST['report_type'] == 1) { 
                    echo "checked='checked'";
            } 
        } ?>  value="1"/> Date Reported&nbsp;
            
            
            <input type="radio" name="report_type" <?php if(isset($_POST['search_now'])){  
                if($_POST['report_type'] == 2) { 
                    echo "checked='checked'";
                    }   
                } ?>  value="2"/> <br />
Start Date&nbsp;
            
            
            <input type="text" value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" id="from" name="from" placeholder="From: "/></div><div>End Date&nbsp;&nbsp;&nbsp;
            <input type="text" value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" id="to" name="to" placeholder="To: "/></div></td><td width="80" align="right" colspan="3"><input type="submit" value="Search" name="search_now"/><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>
</td></tr></tbody></table></form>
                    <?php
                    break;
                case "suc":
                    ?>                    
                    <style>
                    div#transparent table td{
    border-color:#ccc;
}                   input[type=text]{
    border :1px solid #ccc;}
                    </style>
                       <div id="duplicatesix" style="width: 1000px;margin:auto;height:auto;">
                            <div id="searchTableLeft" style="width: 600px;height:auto;float:left;">
                            <table style="width:600px;border:1px solid #bbb;float:left;">
                              <tr>
                                <td style="vertical-align: middle;text-align:center;border:0px solid #bbb;width:20px;padding:0px 0px 0px 0px;">
                                <form action="ikg_routing.php" method="post" target="_blank" id="newutil">
                                    <input type="radio"  name="route" value="new" class="new"/>
                                    <input type="hidden" class="schecheduled_ids"  name="schecheduled_ids" placeholder="schedule numbers" readonly=""/>
                                    <input type="hidden" class="accounts_checked"  name="accounts_checked" placeholder="account numbers"  readonly=""/>
                                    <input type="hidden" value="1" name="from_schedule_list"/>
                                </form>
                                </td>
                                <td style="width:580px;">
                                Create New Route
                                </td>
                              </tr>
                             <tr>
                                <td style="vertical-align: middle;text-align:center;border:0px solid #bbb;width:20px;padding:0px 0px 0px 0px;">
                                <form action="ikg_routing.php" method="post" target="_blank" id="add_to_form">
                                        <input type="hidden" value="1" title="from_utility" id="from_routed_util_list" name="from_routed_util_list"  readonly="" title="From routed Utility value"/>
                                        <input type="hidden" name="add_to_existing" id="add_to_existing" value="1" readonly="" title="Add to existing value"/>
                                        <input type="radio" name="route" value="exist" class="existing"/>
                                        <input type="hidden" class="schecheduled_ids"  name="schecheduled_ids" placeholder="schedule numbers"/>
                                        <input type="hidden" class="accounts_checked" name="accounts_checked" placeholder="account numbers" />
                                       
                                </td>
                                <td style="width:580px;text-align:center;"> 
                                    <span style="float: left;">Add to Existing Route</span><br />
                                    <select name="util_number"><?php 
                                        $query = $db->where("status","enroute")->get($dbprefix."_list_of_utility");
                                        if(count($query)>0){
                                            foreach($query as $util_routes){
                                                echo "<option value='$util_routes[route_id]'>$util_routes[ikg_manifest_route_number]</option>";
                                            }
                                        }
                                        
                                    ?></select></form><p  ><input type="checkbox"/>&nbsp;Show Routes from all Facilities<br /><input style="color: black;" type="submit" value="submit" id="schedule_us" /></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:0px 0p 0px 0px;text-align:center;">
                            <table style="width: 100%;background:transparent;border:0px solid #bbb;padding:0px 0px 0px 0px;height:auto;background:red;">
                            <tr>
                            <td style="text-align: left;width:33%;padding:0px 0px 0px 0px;border:0px solid #Bbb;"><form action="scheduling.php?task=suc" method="post"><input style="color: black;" type="submit" value="Show Code Reds" name="scrs"/></form></td>
                            <td  style="text-align: center;width:33%;padding:0px 0px 0px 0px;border:0px solid #Bbb;">
                            
                            <form action="mapcodered_util.php?mode=util" method="post" target="_blank"><input style="color: black;" type="submit" value="Map Code Reds"  /></form></td>
                            <td  style="text-align: right;width:33%;padding:0px 0px 0px 0px;border:0px solid #Bbb;">
                             <form action="preview_route.php?mode=utility" method="post" target="_blank">
                            <input type="hidden" class="schecheduled_ids"  name="schecheduled_ids" placeholder="schedule numbers"/>
                            <input type="hidden" class="accounts_checked" name="accounts_checked" placeholder="account numbers" />
                            <input type="submit" value="preview route" id="preview"/>
                            </form>
                            </td>
                            </tr>
                            </table>
                            
                            </td></tr>
                               <tr>
                                <td style="" colspan="2"><div id="dsds" style="width: 400p;x">Use Date Range</div></td>                                
                            </tr>
                           
                           <tr>
                           <form action="scheduling.php?task=suc" method="post">
                            <td colspan="2" style="text-align:center;">
                            <input value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>  " type="text" id="from" name="from" placeholder="Start Date" name="from"/>&nbsp;&nbsp;
                            <input value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?> " type="text" id="to" name="to" placeholder="End Date" style="border-radius: 0px 0px 0px 0px;"/>
                            </td>
                            
			               </tr>
                                </table>
                                
                            </div>
                            
                                <div id="spacer" style="width: 30px;height:80px;float:left;">&nbsp;</div>
                                <div id="searchTableRight" style="width: 370px;height:auto;float:left;">
                                    <?php 
                                        if(!$person->isCoWest() && !$person->isFriendly()){
                                    ?>
                                    <table style="width:370px;background:white;height:100px;width:100%;">
                                     <tr><td style="padding: 5px 5px 5px 5px;vertical-align:middle;text-align:left;">Time of Day</td><td style="border: 0px solid #bbb;"> <input type="text" name="timesearch" id="todsearch"/> </select></td></tr>
                                         
                                        <tr>
                                            <td style="text-align: center;" colspan="2">Facility</td>
                                        </tr>
                                        <tr>
                            <td style="vertical-align:top;width:50%;pading:0px 0px 0px 0px;text-align:left;padding:0px 0px 0px 0px;"  colspan="2">
                                <ul class="facs">          
                                    <li><input id="all" name="all" <?php if(isset($_POST['all'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;All</li>
                                   
                                    <li><input value="8" name="fac1" type="checkbox" class="fac"  <?php if(isset($_POST['fac1'])){ echo "checked"; } else {  if($person->facility == 8){ echo "checked"; }} ?> />&nbsp;Arizona Division(4)</li>
                                    <li><input value="23" name="fac2" type="checkbox" class="fac" <?php if(isset($_POST['fac2'])){ echo "checked"; } else { if($person->facility == 23){ echo "checked"; }} ?>  />&nbsp;Coachella Division (UD)</li>
                                    <li><input value="24" name="fac3" type="checkbox" class="fac uc" <?php if(isset($_POST['fac3'])){ echo "checked"; } else { if($person->facility == 24){ echo "checked"; } }?>  />&nbsp;LA Division(UC)</li> 
                                    <li><input value="32" name="fac4" type="checkbox" class="fac uc" <?php if(isset($_POST['fac4'])){ echo "checked"; } else {  if($person->facility == 32){ echo "checked"; } }?>/>&nbsp;LA Division(UC-Chato)</li>
                                    
                                    <li><input value="10" name="fac10" type="checkbox" class="fac selma"  <?php if(isset($_POST['fac10'])){ echo "checked"; } else {  if($person->facility == 10){ echo "checked"; } }?>/>&nbsp;V-BAK</li>
                                    <li><input value="11" name="fac11" type="checkbox" class="fac selma"  <?php if(isset($_POST['fac11'])){ echo "checked"; } else {  if($person->facility == 11){ echo "checked"; } }?>/>&nbsp;V-Fresno</li>
                                     
                                     
                                     <li><input value="14"  <?php if(isset($_POST['fac14'])){ echo "checked"; } else {  if($person->facility == 14){ echo "checked"; } }?> name="fac14" type="checkbox" class="fac"/>&nbsp;L Division (Coachella)</li>
                                    <li><input value="18" name="fac18" type="checkbox" class="fac"  <?php if(isset($_POST['fac18'])){ echo "checked"; } else {  if($person->facility == 18){ echo "checked"; } }?>/>&nbsp;RC Waste Resources LC</li> 
                                    
                                    
                                     
                                </ul>
                            </td>
                            
                            </tr>
                           <tr>
                            <td style="width: 80%;pading:0px 0px 0px 0px;text-align:left;padding:0px 0px 0px 0px;"  colspan="2">
                                <ul class="facs" >          
                                    <li><input name="allfac" type="checkbox" id="alluc"/>&nbsp;ALL UC</li>
                                    <li><input name="allselma"  id="allselma" type="checkbox" class="selma"/>&nbsp;All Selma</li>
                                    <li><input value="33"  <?php if(isset($_POST['fac5'])){ echo "checked"; } else {    if($person->facility == 33){ echo "checked"; } }?>  name="fac5" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Chuck)</li>
                                    <li><input value="25" <?php if(isset($_POST['fac6'])){ echo "checked"; } else {  if($person->facility == 25){ echo "checked"; } }?>  name="fac6" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Ramon)</li>
                                    <li><input value="30" <?php if(isset($_POST['fac7'])){ echo "checked"; } { if($person->facility == 30){ echo "checked"; } }?>  name="fac7" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Tony)</li>
                                    <li><input value="22" <?php if(isset($_POST['fac8'])){ echo "checked"; } else { if($person->facility == 22){ echo "checked"; } }?>  name="fac8" type="checkbox" class="fac"/>&nbsp;San Diego Division (US)</li>
                                    <li><input value="5" <?php if(isset($_POST['fac9'])){ echo "checked"; } else {  if($person->facility == 5){ echo "checked"; } }?>  name="fac9" type="checkbox"  class="fac selma"/>&nbsp;Selma (V)</li>
                                    <li><input value="12"  <?php if(isset($_POST['fac12'])){ echo "checked"; } else {  if($person->facility == 12){ echo "checked"; } }?> name="fac12" type="checkbox" class="fac selma"/>&nbsp;V-North</li>
                                    <li><input value="13"  <?php if(isset($_POST['fac13'])){ echo "checked"; } else {  if($person->facility == 13){ echo "checked"; } }?> name="fac13" type="checkbox" class="fac selma"/>&nbsp;V-Visalia</li>
                                    <li><input value="15" name="fac15" type="checkbox" class="fac uc"  <?php if(isset($_POST['fac15'])){ echo "checked"; } else {  if($person->facility == 15){ echo "checked"; } }?>/>&nbsp;W Division Bernadino</li>
                                    <li><input value="16" name="fac16" type="checkbox" class="fac"  <?php if(isset($_POST['fac16'])){ echo "checked"; } else {  if($person->facility == 16){ echo "checked"; } }?>/>&nbsp;Victorville</li>  
                                     <li><input value="17" name="fac17" type="checkbox" class="fac"  <?php if(isset($_POST['fac17'])){ echo "checked"; } else {  if($person->facility == 17){ echo "checked"; } }?>/>&nbsp;RC Waste Resources MV</li> 
                                </ul>
                            </td>
                           
                           </tr>
                        </table>
                                    <?php }  ?>     
                        </div>
                                <div style="clear: both;"></div>
                            </div>                            
                      <style type="text/css">
                      
                       table#secondsearchparams td{
    font-size: 12px; border:1px green solid; margin:auto;width:80%;margin-top:35px;font-weight:normal;font-weight:bold;
    text-align:left;
}
                      </style>    
                       
                       <div id="fullgraysearch" style="width: 100%;background:#bbbbbb;height:auto;float:left;">
                          <table style="width: 1000px;margin:auto;background:white;">
                            <tr>
                                <td style="">Id</td>
                                <td style="">Name</td>
                                <td style="">City</td>
                                <td style="">State</td>
                                <td style="">Zip</td>
                                
                            </tr>
                            <tr>
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['id']) ){ echo $_POST['id'];} ?>" type="text" name="id" id="id" style="width: 190px;"/></td>
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['name']) ){ echo $_POST['name'];} ?>" type="text" name="name"  id="name"  style="width: 190px;" />
                                </td>                                            
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['city']) ){ echo $_POST['city'];} ?>" type="text" name="city" id="city"  style="width: 190px;" />
                                </td>
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['state']) ){ echo $_POST['state'];} ?>" type="text" name="state" id="state"  style="width: 190px;" />
                                </td>                                                            
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['zip']) ){ echo $_POST['zip'];} ?>" type="text" name="zip" id="zip"   style="width: 190px;"/></td>
                            </tr>
                            <tr><td colspan="5" style="text-align: right;"><input type="submit" name="search_now" value="Search"/></td></tr>
                        </table>
                           <div style="clear: both;"></div>
                        </div>
                        </form>
                        
                        
                        <div style="clear: both;"></div> 
                        
                         <script>
                        $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                        $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                        </script>
                        <div style="clear: both;"></div>   
                    
                    <?php
                    break;
                    case "ruc":
                    ?>
                    
                    <form action="scheduling.php?task=ruc" method="post">
                <table  style="font-size:smaller;margin-top:20px;margin-bottom:20px;">                        
                
                <tbody>
                    <tr>
                     <td>Route Id</td>
                                   <td><input type="text" name="rid" placeholder="Route Id" value="<?php if(isset($_POST['rid'])){ echo $_POST['rid']; } else {echo "";} ?>"/></td></tr>
                                   
                    <tr>   
                    <td>Route title</td><td><input type="text" value="<?php if(isset($_POST['rtitle'])){ echo $_POST['rtitle']; } else {echo "";} ?>" placeholder="route title" id="rtitle" name="rtitle"/></td></tr>
                    
                    
                    <td nowrap="" align="right" class="field_label">Status</td>
                <td width="100"><select id="status_id" name="status_id">
                <option value="">All Routes</option>
                <option value="Scheduled">Scheduled</option>
                
                <option value="Enroute">En-route</option>
                
                <option value="Completed">Completed</option>
                </select></td>
                
                <td nowrap="" align="right" style="padding-left:10px;" class="field_label">Driver</td>
                <td width="150" align="left">
                <?php echo getDrivers(); ?></td><td width="80" align="left"><input type="submit" value="Search" name="search_now" /> <input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a></td></tr></tbody>
                
                    </table>
                    </form>
                    <?php
                    break;
                case "mini":
                break;
                case "sgt":
                    $fr1 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =201");
                    $fr2 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =202");
                    $fr3 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =203");
                    $fr4 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =204");
                    $fr5 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =205");
                    $fr6 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =206");
                    $fr7 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =207");
                    $fr8 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =208");
                    $fr9 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =209");
                    $fr10 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =210");
                    $fr11 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =211");
                    
                    $tmp1 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =212");
                    $tmp2 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =213");
                    $tmp3 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =214");
                    $tmp4 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =215");
                    $tmp5 = $db->query("SELECT locked,master_lock FROM sludge_accounts WHERE account_ID =216");
                    
                    ?>
                    <style>
                    div#transparent table td{
    border-color:#ccc;
}                   input[type=text]{
    border :1px solid #ccc;
}
                    /* highlight results */
.ui-autocomplete span.hl_results {
    background-color: #ffff66;
}
 
                    /* loading - the AJAX indicator */
                    .ui-autocomplete-loading {
                        background: white url('../img/ui-anim_basic_16x16.gif') right center no-repeat;
                    }
                     
                    /* scroll results */
                    .ui-autocomplete {
                        max-height: 250px;
                        overflow-y: auto;
                        /* prevent horizontal scrollbar */
                        overflow-x: hidden;
                        /* add padding for vertical scrollbar */
                        padding-right: 5px;
                    }
                     
                    .ui-autocomplete li {
                        font-size: 16px;
                    }
                     
                    /* IE 6 doesn't support max-height
                    * we use height instead, but this forces the menu to always be this tall
                    */
                    * html .ui-autocomplete {
                        height: 250px;
                    }
                    </style>
                        
                       <div id="duplicatesix" style="width: 1000px;margin:auto;height:auto;">
                            <div id="searchTableLeft" style="width: 600px;height:auto;float:left;">
                            <table style="width:600px;border:1px solid #bbb;float:left;">
                              <tr>
                                <td style="vertical-align: middle;text-align:center;border:0px solid #bbb;width:20px;padding:0px 0px 0px 0px;">
                                <form action="grease_ikg.php" method="post" target="_blank" id="schedgreasetoikg">
                                    <input type="hidden" value="1" name="from_schedule_list"/>
                                    <input type="hidden" class="schecheduled_ids"  name="schecheduled_ids" placeholder="schedule numbers" id="schedule_numbers" value="<?php 
                                        if(!empty($_SESSION['temp_stops'])){
                                            foreach($_SESSION['temp_stops'] as $tstops){
                                                $temp = new Grease_Stop($tstops);
                                                echo $temp->grease_no."|";
                                            }
                                        }
                                    ?>"/>
                                    <input type="hidden" class="accounts_checked" name="accounts_checked" placeholder="account numbers" value="<?php 
                                        if(!empty($_SESSION['temp_stops'])){
                                            foreach($_SESSION['temp_stops'] as $tstops){
                                                $temp = new Grease_Stop($tstops);
                                                echo $temp->account_number."|";
                                            }
                                        }
                                    ?>" />
                                    <input type="hidden" name="from_schedule_list" value="1" readonly=""/>
                                    <input type="radio" name="route" class="new" value="new"/>
                                   
                                </form>
                                </td>
                                <td style="width:580px;">
                                Create New Route
                                </td>
                              </tr>
                              <tr>
                            <td style="vertical-align: middle;text-align:center;border:0px solid #bbb;width:20px;padding:0px 0px 0px 0px;"> <input type="radio" id="completed" name="completed" class="completed"/></td>
                            <td><span style="float: left;">Completed Route</span><br />
                            
                            
                            <form action="grease_ikg.php" method="POST" id="add_to_completed" target="_blank">
                            <input placeholder="Route Title or Id" id="route_title_id" name="route_title_id" type="text"/><input type="hidden" name="util_routes" id="r_id"/>
                            <input type="hidden" class="schecheduled_ids"  name="schecheduled_ids" placeholder="schedule numbers" id="schedule_numbers" value="<?php 
                                        if(!empty($_SESSION['temp_stops'])){
                                            foreach($_SESSION['temp_stops'] as $tstops){
                                                $temp = new Grease_Stop($tstops);
                                                echo $temp->grease_no."|";
                                            }
                                        }
                                    ?>"/>
                                    <input type="hidden" class="accounts_checked" name="accounts_checked" placeholder="account numbers" value="<?php 
                                        if(!empty($_SESSION['temp_stops'])){
                                            foreach($_SESSION['temp_stops'] as $tstops){
                                                $temp = new Grease_Stop($tstops);
                                                echo $temp->account_number."|";
                                            }
                                        }
                                    ?>" />
                            <input type="hidden" name="from_routed_grease_list" value="1" />
                            <input type="hidden" name="add_to_route" value="1"/>
                            </form>
                            </td>
                            </tr>
                             <tr>
                                <td style="vertical-align: middle;text-align:center;border:0px solid #bbb;width:20px;padding:0px 0px 0px 0px;">
                                <form action="grease_ikg.php" method="post" target="_blank" id="add_to_form">
                                <input type="hidden" class="schecheduled_ids"  name="schecheduled_ids" placeholder="schedule numbers" id="schedule_numbers" value="<?php 
                                        if(!empty($_SESSION['temp_stops'])){
                                            foreach($_SESSION['temp_stops'] as $tstops){
                                                $temp = new Grease_Stop($tstops);
                                                echo $temp->grease_no."|";
                                            }
                                        }
                                    ?>"/>
                                    <input type="hidden" class="accounts_checked" name="accounts_checked" placeholder="account numbers" value="<?php 
                                        if(!empty($_SESSION['temp_stops'])){
                                            foreach($_SESSION['temp_stops'] as $tstops){
                                                $temp = new Grease_Stop($tstops);
                                                echo $temp->account_number."|";
                                            }
                                        }
                                    ?>" />
                                    <input type="radio" name="route" class="existing" value="exist"/>
                                </td>
                                <td style="width:580px;text-align:center;"> 
                                    <span style="float: left;">Add to Existing Route</span><br />
                                    
                                    <select name="util_routes"><?php  
                                     $rgs = $db->where("status","enroute")->get($dbprefix."_list_of_grease","route_id,ikg_manifest_route_number");

                                        if(count($rgs)>0){
                                            foreach($rgs as $routes){
                                                echo "<option value='$routes[route_id]'>$routes[ikg_manifest_route_number]</option>";
                                            }
                                        }

                                    ?></select>
                                    <input type="hidden" name="from_routed_grease_list" value="1" />
                                    <input type="hidden" name="add_to_route" value="1"/>
                                    </form><input style="color: black;" type="submit" value="submit" id="schedule_us" /><br />
                                    <p><input type="checkbox"/>&nbsp;Show Routes from all Facilities</p>
                                    
                                </td>
                            </tr>
                            
                            <tr>                            
                                <td colspan="2" style="padding:0px 0p 0px 0px;text-align:center;">
                                    <table style="width: 100%;border:0px solid #bbb;padding:0px 0px 0px 0px;height:auto;background:red;"><tr>
                                    <td style="text-align: left;width:33%;padding:0px 0px 0px 0px;border:0px solid #Bbb;"><form action="scheduling.php?task=sgt" method="post"><input style="color: black;" type="submit" value="Show Code Reds" name="scrs" /></form></td>
                                    
                                    <td  style="text-align: center;width:33%;padding:0px 0px 0px 0px;border:0px solid #Bbb;"><form action="mapcodered_grease.php?mode=gt" method="" target="_blank">
                                    <input type="text" readonly="" name="facs" id="facsx"/>
                                    <input style="color: black;" type="submit" value="Map Code Reds" />
                                    </form>&nbsp;</td>
                                    
                                    
                                    <td  style="text-align: right;width:33%;padding:0px 0px 0px 0px;border:0px solid #Bbb;">
                                    <form action="preview_route.php?mode=grease" method="post" target="_blank">
                                    <input type="hidden" class="schecheduled_ids"  name="schecheduled_ids" placeholder="schedule numbers"/>
                                    <input type="hidden" class="accounts_checked" name="accounts_checked" placeholder="account numbers" />
                                    <input type="submit" value="preview route" id="preview"/>
                                    </form>
                                </td></tr>
                                    
                                    
                                   
                                    </table>
                                    <table style="width: 100%;">
                                    
                                    <form action="scheduling.php?task=sgt" method="post">
                                    
                                     <tr><td style="padding: 5px 5px 5px 5px;vertical-align:middle;text-align:left;">Time of Day</td><td style="border: 0px solid #bbb;"> <input type="text" name="timesearch" id="todsearch"/> </select></td></tr>
                                         <tr>
                                         <td><input value="<?php echo $_POST['from']; ?>" type="text" id="from" name="from" placeholder="From:"/></td><td><input type="text" id="to" name="to" placeholder="To:"  value="<?php echo $_POST['to']; ?>"/></td>
                                         </tr>
                                         <tr><td>Trap Size: </td><td><input placeholder="From:" type="text" id="size_from" name="size_from"  value="<?php echo $_POST['size_from']; ?>"/><br /><input placeholder="To:" type="text" id="size_to" name="size_to" value="<?php echo $_POST['size_to']; ?>"/></td></tr>
                                    <tr style="background: white;border:1px solid white;"><td>Search By Debt</td><td><input type="text" id="from_debt" name="from_debt" placeholder="Minimum Amount $"  value="<?php echo $_POST['from_debt']; ?>"/><br /><input value="<?php echo $_POST['to_debt']; ?>" type="text" id="to_debt" name="to_debt" placeholder="Maximum Amount $" /></td></tr>
                                    <tr><td>Friendly</td><td><?php if(isset($_POST['search_now'])){
                                          getFriendLists($_POST['friendly']);
                                    }else{
                                        getFriendLists("");
                                    }  ?></td></tr>
                                    </table>
                                </td>
                            </tr>
                            
                            
                            
                                </table>
                                
                            </div>
                            
                                <div id="spacer" style="width: 30px;height:80px;float:left;">&nbsp;</div>
                                <div id="searchTableRight" style="width: 370px;height:auto;float:left;">
                                    <?php 
                                        if(!$person->isFriendly() ){
                                            ?>
                                            <table style="width:370px;background:white;height:100px;width:100%;">
                                     
                                        <tr>
                                            <td style="text-align: center;" colspan="2">Facility</span></td>
                                        </tr>
                                        <tr>
                            <td style="vertical-align:top;width:50%;pading:0px 0px 0px 0px;text-align:left;padding:0px 0px 0px 0px;" colspan="2">
                                <ul class="facs">          
                                    <li><input id="all" name="all" <?php if(isset($_POST['all'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;All</li>
                                    <li><input id="all_frack" name="all_frack" <?php if(isset($_POST['all_frack'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;All Frack Tanks</li>
                                   <li><input class="frac fac" value="201" id="Frack_Tank_1" name="fracktank1" <?php  if($fr1[0]['locked'] == 1 || $fr1[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; } if(isset($_POST['fracktank1'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 1</li>
                                   <li><input class="frac fac"  value="202"   id="Frack_Tank_2" name="fracktank2" <?php  if($fr2[0]['locked'] == 1 || $fr2[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank2'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 2</li>
                                   <li><input class="frac fac"  value="203"  id="Frack_Tank_3" name="fracktank3" <?php  if($fr3[0]['locked'] == 1 || $fr3[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank3'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 3</li>
                                   <li><input class="frac fac"  value="204"  id="Frack_Tank_4" name="fracktank4" <?php  if($fr4[0]['locked'] == 1 || $fr4[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank4'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 4</li>
                                   <li><input class="frac fac"  value="205"  id="Frack_Tank_5" name="fracktank5" <?php  if($fr5[0]['locked'] == 1 || $fr5[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank5'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 5</li>
                                   <li><input class="frac fac"  value="206"  id="Frack_Tank_6" name="fracktank6" <?php  if($fr6[0]['locked'] == 1 || $fr6[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank6'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 6</li>
                                   <li><input class="frac fac"  value="207"  id="Frack_Tank_7" name="fracktank7" <?php  if($fr7[0]['locked'] == 1 || $fr7[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank7'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 7</li>
                                   <li><input class="frac fac"  value="208"  id="Frack_Tank_8" name="fracktank8" <?php  if($fr8[0]['locked'] == 1 || $fr8[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank8'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 8</li>
                                   <li><input class="frac fac"  value="209"  id="Frack_Tank_9" name="fracktank9" <?php   if($fr9[0]['locked'] == 1 || $fr9[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; } if(isset($_POST['fracktank9'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 9</li>
                                   <li><input class="frac fac"  value="210"  id="Frack_Tank_10" name="fracktank10" <?php   if($fr10[0]['locked'] == 1 || $fr10[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank10'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 10</li>
                                   <li><input class="frac fac"  value="211"  id="Frack_Tank_11" name="fracktank11" <?php  if($fr11[0]['locked'] == 1 || $fr11[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['fracktank11'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Frack Tank 11</li>
                                   
                                   <li><input class="frac fac"   value="212"id="tmp1" name="tmp1" <?php  if($tmp1[0]['locked'] == 1 || $tmp1[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['tmp1'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Temp Frac1</li>
                                   <li><input class="frac fac"  value="213" id="tmp2" name="tmp2" <?php  if($tmp2[0]['locked'] == 1 || $tmp2[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['tmp2'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Temp Frac2</li>
                                   <li><input class="frac fac"  value="214" id="tmp3" name="tmp3" <?php  if($tmp3[0]['locked'] == 1 || $tmp3[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['tmp3'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Temp Frac3</li>
                                   
                                   <li><input class="frac fac"  value="215" id="tmp4" name="tmp4" <?php  if($tmp4[0]['locked'] == 1 || $tmp4[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['tmp4'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Temp Frac4</li>
                                   
                                   <li><input class="frac fac"  value="216" id="tmp5" name="tmp5" <?php  if($tmp5[0]['locked'] == 1 || $tmp5[0]['master_lock']==1){ echo "disabled title='Frac Tank locked.'"; }  if(isset($_POST['tmp5'])){ echo "checked"; }  ?> type="checkbox" style="float: left;"/>&nbsp;Temp Frac5</li>
                                    
                                      
                                    
                                </ul>
                            </td>
                            
                            </tr>
                           <tr>
                            <td style="width: 80%;pading:0px 0px 0px 0px;text-align:left;padding:0px 0px 0px 0px;" colspan="2">
                              <?php 
                                if(!$person->isCoWest()){
                              ?>
                                <ul class="facs" >                                     
                                    <li><input value="15" name="fac15" type="checkbox" class="fac uc"  <?php if(isset($_POST['fac15'])){ echo "checked"; } else {  if($person->facility == 15){ echo "checked"; } }?>/>&nbsp;W Division Bernadino</li>    
                                    
                                    <li><input value="18" name="fac18" type="checkbox" class="fac"  <?php if(isset($_POST['fac18'])){ echo "checked"; } else {  if($person->facility == 18){ echo "checked"; } }?>/>&nbsp;RC Waste Resources LC</li> 
                                    <li><input name="allselma"  id="allselma" type="checkbox" class="selma"/>&nbsp;All Selma</li>
                                    <li><input value="16" name="fac16" type="checkbox" class="fac"  <?php if(isset($_POST['fac16'])){ echo "checked"; } else {  if($person->facility == 16){ echo "checked"; } }?>/>&nbsp;Victorville</li>  
                                     <li><input value="17" name="fac17" type="checkbox" class="fac"  <?php if(isset($_POST['fac17'])){ echo "checked"; } else {  if($person->facility == 17){ echo "checked"; } }?>/>&nbsp;RC Waste Resources MV</li>  
                                </ul>
                                <?php } else{
                                    echo "&nbsp;";
                                } ?>
                            </td>
                           </tr>
                        </table>
                                <?php
                            }
                        ?>     
                    </div>
                           
                          
                                   
                                <div style="clear: both;"></div>
                            </div>                            
                      <style type="text/css">
                      
                       table#secondsearchparams td{
    font-size: 12px; border:1px green solid; margin:auto;width:80%;margin-top:35px;font-weight:normal;font-weight:bold;
    text-align:left;
}
                      </style>    
                       
                       <div id="fullgraysearch" style="width: 100%;background:#bbbbbb;height:auto;float:left;">
                           <table style="width: 1000px;margin:auto;background:white;">
                            <tr>
                                <td style="width:200px;text-align: center;">Id</td>
                                <td style="width:200px;text-align: center;">Name</td>
                                <td style="width:200px;text-align: center;">City</td>
                                <td style="width:200px;text-align: center;">State</td>
                                <td style="width:200px;text-align: center;">Zip</td>
                                
                            </tr>
                          <tr>
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['id']) ){ echo $_POST['id'];} ?>" type="text" name="id" id="id" style="width: 190px;"/></td>
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['name']) ){ echo $_POST['name'];} ?>" type="text" name="name"  id="name"  style="width: 190px;" />
                                </td>                                            
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['city']) ){ echo $_POST['city'];} ?>" type="text" name="city" id="city"  style="width: 190px;" />
                                </td>
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['state']) ){ echo $_POST['state'];} ?>" type="text" name="state" id="state"  style="width: 190px;" />
                                </td>                                                            
                                <td style="padding: 0px 0px 0px 0px;width:200px;text-align: center;">
                                    <input value="<?php  if( isset($_POST['zip']) ){ echo $_POST['zip'];} ?>" type="text" name="zip" id="zip"   style="width: 190px;"/>
                                </td>
                            </tr>  
                            <tr>
                                
                               
                               
                                <td  style="text-align:right;" colspan="10"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>" style="margin-left: 10px;">Default Data View</a>&nbsp;<input type="reset" id="remove_cached"/>&nbsp;<input style="color: black;" type="submit" value="Search" name="search_now" style="margin-left: 10px;"/><br />( Click "Reset" to remove temp stops )</td>
                            </tr>
                        </table>
                        </div>
                        </form>
                         <script>                       
                        $("input#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                        $("input#to").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
                        </script>
                        <div style="clear: both;"></div>   
                    <?php
                    break;
                case "rgt":
                    ?>
                    <form action="scheduling.php?task=rgt" method="post">
                    <table style="font-size:smaller;margin:auto;margin-top:20px;table-layout:fixed;width:50%;margin-bottom: 20px;"><tbody>
                    
                    <tr>
                    <td  style="text-align:left;"> Route Id</td><td><input type="text" name="rid" placeholder="Route Id" value="<?php if(isset($_POST['rid'])){ echo $_POST['rid']; } else {echo "";} ?>"/>
                    </td></tr>
                    
                    <tr>                
                    <td style="text-align:left;">Route title</td><td><input type="text" value="<?php if(isset($_POST['rtitle'])){ echo $_POST['rtitle']; } else {echo "";} ?>" placeholder="route title" id="rtitle" name="rtitle"/>
                    </td>
                    </tr>
                    
                    <tr>
                    <td  style="width: 50%;text-align:left;">Status</td><td>
                            <select id="status_id" name="status_id"><option value="all">All Routes</option>
                            <option <?php if($_POST['status_id'] == "scheduled") { echo "selected";} ?>  value="scheduled">Scheduled</option>
                            
                            <option <?php if($_POST['status_id'] == "enroute") { echo "selected";} ?> value="enroute">En-route</option>
                            
                            <option <?php if($_POST['status_id'] == "completed") { echo "selected";} ?> value="completed">Completed</option>
                            </select></td>
                    
                    <tr>
                    <td  style="width: 50%;text-align:left;">Driver</td><td>
                    <?php echo getDrivers(); ?></td>
                    </tr>
                    
                    
                    <tr>
                    <td style="text-align:left;" class="field_label">Facility</td><td>
                   <?php 
                    if(!$person->isCoWest() && !$person->isFriendly()){ 
                       
                        if(isset($_POST['facility'])){
                            
                            echo getFacilityList("",$_POST['facility']);
                        }else{
                            echo getFacilityList("","");    
                        }
                         
                   }else{
                        echo "&nbsp;";
                   }
                   ?>
        
                   </td></tr>
        
<td  style="text-align:right;" colspan="2"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>"  style="margin-left: 10px;">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" value="Search" name="search_now" style="margin-left: 10px;"/><br />

</td>


</tr>


</tbody></table>
                    </form>
                    <?php
                    break;  
                case "cgt":
                    ?>
                    <form action="scheduling.php?task=cgt" method="post">
                    <input type="hidden" value="report_collected_fires" name="task" value="<?php if(isset($_POST['get_stats'])){ echo $_POST['get_stats'];} ?>" />
                    
                    <table style="font-size:smaller;margin:auto;margin-bottom:20px;margin-top:20px;table-layout:fixed;width:50%"><tbody>
                    <tr>
                     <td style="width: 33%;text-align:center;">Route title</td><td><input type="text" value="<?php if(isset($_POST['rtitle'])){ echo $_POST['rtitle']; } else {echo "";} ?>" placeholder="route title" id="rtitle" name="rtitle"/></td></tr>
                     
                     <tr>
                   <td style="width: 33%;text-align:center;">Route id</td><td><input type="text" name="rid" placeholder="Route Id" value="<?php if(isset($_POST['rid'])){ echo $_POST['rid']; } else {echo "";} ?>"/></td></tr>
                   
                   <tr>
                    <td  style="width: 33%;text-align:center;" class="field_label">Group By</td><td><select id="my_group" name="my_group">
                    <option value="-">--</option>
                    <option value="created_by">Created By</option>
                    <?php if(!$person->isFriendly() && !$person->isCoWest()){ ?>
                    <option value="recieving_facility">Facility</option>
                    <?php } ?>
                    <option value="driver">Driver</option>
                    <option value="created_by">Created By</option>


</select></td>
                </tr>
                
                
                <tr>
                    <td  class="field_label"   style="text-align:center;">

Wait Days Range </td>
<td> <input type="text" id="min" name="min" placeholder="min days" style="width: 90px;"  value="<?php if(isset($_POST['search_now'])){ echo $_POST['min'];  } ?>"/><br /><input type="text" id="max" name="max" placeholder="max days"  style="width: 90px;"   value="<?php if(isset($_POST['search_now'])){ echo $_POST['max'];  } ?>"/>
            
            </td></tr>

<tr><td>

<input type="radio" name="report_type"  <?php 
        if(isset($_POST['search_now'])){  
            if($_POST['report_type'] == 1) { 
                    echo "checked='checked'";
            } 
        } ?>  value="1"/>&nbsp;Date Reported<br />
            
            
            <input type="radio" name="report_type" <?php if(isset($_POST['search_now'])){  
                if($_POST['report_type'] == 2) { 
                    echo "checked='checked'";
                    }   
                } ?>  value="2"/>&nbsp;Date Completed

</td>
<td  style="text-align:left;"class="field_label">
 
            
            
            <input type="text" value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" id="from" name="from" placeholder="From: "/>     <input type="text" value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" id="to" name="to" placeholder="To: "/></td>
            </tr>
            
            
            <td  style="text-align:right;" colspan="2"><input type="checkbox" name="year" id="year"/>&nbsp;Include Results older than a year<br /> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>"  style="margin-left: 10px;">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" value="Search" name="search_now" style="margin-left: 10px;"/>
</td></tr>

</tbody></table></form>
                    <?php
                    break;  
                case "utilalarmcomplete":
                    ?>
                    <form action="scheduling.php?task=utilalarmcomplete" method="post">
                    <input type="hidden" value="report_collected_fires" name="task" value="<?php if(isset($_POST['get_stats'])){ echo $_POST['get_stats'];} ?>" />
                    
                    <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller;margin-top:10px;table-layout: fixed;"><tbody><tr><td  style="width:150px;" class="field_label">Group By<br /><select id="my_group" name="my_group">
                    <option value="-">--</option>
                    <option value="account_rep">Account Rep</option>
                    
                    <option value="division">Facility</option>
                    
                    <option value="driver">Driver</option>
                    <option value="created_by">Created By</option>
                    

</select></td>

<td nowrap="" align="right" style="padding-right:50px;text-align:center;" class="field_label">

Wait Days Range  <input type="text" id="min" name="min" placeholder="min days" style="width: 90px;"  value="<?php if(isset($_POST['search_now'])){ echo $_POST['min'];  } ?>"/>&nbsp;to&nbsp;<input type="text" id="max" name="max" placeholder="max days"  style="width: 90px;"   value="<?php if(isset($_POST['search_now'])){ echo $_POST['max'];  } ?>"/>

</td>

<td nowrap="" align="right" class="field_label"><div>
<input type="radio" name="report_type"  <?php 
        if(isset($_POST['search_now'])){  
            if(isset($_POST['report_type']) == 1) { 
                    echo "checked='checked'";
            } 
        }else {
                if($_POST['report_type'] !=2){
                    echo 'checked="checked"';  
                }
        }  ?>  value="1"/>&nbsp;Date Reported<input type="radio" name="report_type" <?php if(isset($_POST['search_now'])){  if(isset($_POST['report_type']) == 2) { echo "checked='checked'";}   } ?>  value="2"/>&nbsp;Date Serviced<br />
Start Date&nbsp;
            
            
            <input type="text" value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" id="from" name="from" placeholder="From: "/></div><div>End Date&nbsp;&nbsp;&nbsp;
            <input type="text" value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" id="to" name="to" placeholder="To: "/></div></td>
            </tr><tr>
            <td>
            Service Type: 
            <?php
                if(isset($_POST['search_now'])){
                   echo  service_list("",$_POST['service_list']);
                } else {
                    echo service_list("","");
                }
            ?></td>
            <td>Facility : <br /><?php if(isset($_POST['search_now'])){
                getFacilityList("facility",$_POST['facility']);
            } else {
                getFacilityList();
            } ?></td>
            <td width="80" align="right"><input type="submit" value="Search" name="search_now"/><input type="reset"/> <a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>
</td></tr></tbody></table></form>
                    <?php
                    break;
                case "utilongoign":
                    ?>
                    <form action="scheduling.php?task=utilongoign" method="post">
                    <input type="hidden" value="report_collected_fires" name="task" value="<?php if(isset($_POST['get_stats'])){ echo $_POST['get_stats'];} ?>" />
                    
                    <table width="80%" border="0" align="center" cellpadding="1" style="font-size:smaller;margin-top:10px;table-layout: fixed;"><tbody><tr>
                    
                    <td style="width:150px;" >Group By<br /><select id="my_group" name="my_group">
                    <option value="-">--</option>
                    <option value="account_rep">Account Rep</option>
                    <option value="division">Facility</option>
                    <option value="driver">Driver</option>
                    <option value="created_by">Created By</option>
                    

</select></td>

<td class="field_label">

Wait Days Range  <input type="text" id="min" name="min" placeholder="min days" style="width: 90px;"  value="<?php if(isset($_POST['search_now'])){ echo $_POST['min'];  } ?>"/>&nbsp;to&nbsp;<input type="text" id="max" name="max" placeholder="max days"  style="width: 90px;"   value="<?php if(isset($_POST['search_now'])){ echo $_POST['max'];  } ?>"/>

</td>

<td colspan="2" align="right" class="field_label"><div>
<input type="radio" name="report_type"  <?php 
        if(isset($_POST['search_now'])){  
            if(isset($_POST['report_type']) == 1) { 
                    echo "checked='checked'";
            } 
        }else {
                if($_POST['report_type'] !=2){
                    echo 'checked="checked"';  
                }
        }  ?>  value="1"/> Date Reported&nbsp;<input type="radio" name="report_type" <?php if(isset($_POST['search_now'])){  if(isset($_POST['report_type']) == 2) { echo "checked='checked'";}   } ?>  value="2"/> Date Serviced&nbsp;<br />
Start Date&nbsp;
            
            
            <input type="text" value="<?php  if( isset($_POST['from']) ){ echo $_POST['from'];} ?>" id="from" name="from" placeholder="From: "/></div><div>End Date&nbsp;&nbsp;&nbsp;
            <input type="text" value="<?php  if( isset($_POST['to']) ){ echo $_POST['to'];} ?>" id="to" name="to" placeholder="To: "/></div></td>
            </tr><tr>
            <td>
            Service Type: <br />
            <?php
                if(isset($_POST['search_now'])){
                   echo  service_list("",$_POST['service_list']);
                } else {
                    echo service_list("","");
                }
            ?></td>
            
            <td>
            Route Status: <br />
            <select  name="r_status" id="r_status"><option></option>
                <option <?php 
                if(isset($_POST['search_now'])){ 
                        if($_POST['r_status'] =="enroute"){ 
                                echo " selected "; 
                        }  
                    } ?>  value="enroute">enroute</option>
                <option <?php 
                if(isset($_POST['search_now'])){ 
                        if($_POST['r_status'] =="scheduled"){ 
                                echo " selected "; 
                        }  
                    } ?>  value="scheduled">scheduled</option>
            </select></td>
            <td>Facility : <?php if(isset($_POST['search_now'])){
                getFacilityList("facility",$_POST['facility']);
            } else {
                getFacilityList();
            } ?></td>
            <td width="80" align="right"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>">Default Data View</a>&nbsp;<input type="reset"/>&nbsp;<input type="submit" value="Search" name="search_now"/>
</td></tr></tbody></table></form>
                    <?php
                    break;
                default:                   
                    echo "<p style='width:300px;margin:auto;margin-top:30px;color:black;'>This Database is for private use only and is copyright of IWP etc.. etc.. etc..</p>";
                    break;
                case "creport":
                ?>
                    <form action="management.php?task=creport" method="post">
                    <table style="width: 50%;margin:auto;table-layout:fixed;margin-top:20px;margin-bottom:20px;">
                    
                    <tr>
                        <td style="width:50%;text-align:center;">Date of last service</td>
                        <td><input type="text" placeholder="From" value="<?php echo $_POST['from']; ?>" id="from" name="from"/>&nbsp;<input type="text" placeholder="To"  value="<?php echo $_POST['to']; ?>" id="to" name="to"/>
                        </td>
                    </tr>
                    
                    
                    <tr>
                        <td  style="width:50%;text-align:center;">Upcoming Service Date </td>
                        <td>
                            <input type="text" placeholder="From" value="<?php echo $_POST['fromupc']; ?>" id="fromupc" name="fromupc"/>&nbsp;<input type="text" placeholder="To"  value="<?php echo $_POST['toupc']; ?>" id="toupc" name="toupc"/>
                        </td>
                    </tr>
                    
                    <tr><td  style="width:50%;text-align:center;">Corporate Accounts </td><td><input <?php 
                        if(isset($_POST['corp'])){ 
                            echo " checked ";
                        }
                    ?> type="checkbox" name="corp"/></td>
                    </tr>
                    
                    <tr>
                    
                    <td   style="width:50%;text-align:center;" >Credit Card On File</td><td><input type="checkbox" <?php 
                        if(isset($_POST['cc'])){ 
                            echo " checked ";
                        }
                    ?>  name="cc"/></td></tr>
                    
                    
                    
                        <tr><td  style="width:50%;text-align:center;">Facility </td><td><?php if(isset($_POST['search_now'])){
                            getFacilityList("facility",$_POST['facility']);
                        }else{
                            getFacilityList("facility","");
                        } ?></td>
                        </tr>
                        
                        <tr>
                            <td  style="width:50%;text-align:center;">Credit Line </td><td><input placeholder="Minimum Credit Line"  value="<?php echo $_POST['min']; ?>"  type="text" name="min"/>&nbsp;&nbsp;<input placeholder="Maximum Credit Line"  value="<?php echo $_POST['max']; ?>"  type="text" name="max"/></td>
                        </tr>
                        
                        
                        <tr><td  style="width:50%;text-align:center;">Locked</td><td><input type="checkbox" name="locked"  /></td></tr>
                        <tr>
                         <td style="border: 0px solid #bbb;text-align:right;" colspan="2"><input type="radio" <?php if($_POST['friend'] == 1){ echo " checked "; } ?>   name="friend" value="1"/>&nbsp;Exclude Friendlies <br /><input <?php if($_POST['friend'] == 2){ echo " checked "; } ?>  type="radio" name="friend" value="2"/>&nbsp;Include Friendlies</td>
                         </tr><tr>
                        <td colspan="2"  style="width:50%;text-align:right;"><a href="<?php echo "$_SERVER[REQUEST_URI]"; ?>" style="margin-left: 10px;">Default Data View</a>&nbsp;<input type="reset" value="Reset"/>&nbsp;<input type="submit" name="search_now" value="Search Now" style="margin-left: 10px;"/></td>
                        </tr>
                   
                   
                    </table>
                    </form>
                <?php
                 break;
            }
        } else  {
            echo "<p style='width:300px;margin:auto;margin-top:30px;color:black;'>This Database is for private use only and is copyright of IWP etc.. etc.. etc..</p>";
        }
    } ?>
    
    <div style="clear: both;"></div>
</div>
<?php

