<?php 
include "protected/global.php";
$person = new Person();

if( isset( $_POST['rep'] ) ) {
    
    
    if(strtolower($_POST['istatus']) == "closed"){
        $issue_pack = array(
            "issue_status"=>$_POST['istatus'],
            "completed_date"=>date("Y-m-d")
        );
        
        $db->where('issue_no',$_GET['id'])->update("freight_issues",$issue_pack);
    }
    
    

    $buff = Array(
        "issue_no"=>$_GET['id'],
        "message"=>$_POST['repxs'],
        "message_date"=>date("Y-m-d H:i:s"),
        "created_by"=>$person->user_id
    );
    $db->insert($dbprefix.'_issue_notes',$buff);
}

$gfd = $db->where("issue_no",$_GET['id'])->get($dbprefix."_issues");
?>
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/style.css"/>
<style type="text/css">
#adduser { 
    box-shadow:         1px 1px 3px 3px #70a170;
    width: 550px;min-height:400px;height:auto;margin:10px auto;border-radius:10px;border:1px solid black;padding-top:10px;
    padding-bottom:10px;
    
}
body{
    font-family:arial;
}

</style>
<form action="viewIssues.php?id=<?php echo $_GET['id']; ?>" method="post">
<div id="xxxx" style="margin-top:5px;background:transparent;border:0px solid #bbb;outline:0px solid #bbb;width:550px;height:auto;">
    <table style="width: 100%;">
    <tr><td style="text-align: center;" colspan="8">
        Message or issue regarding <?php echo account_NumtoName($gfd[0]['account_no']);  ?>
    </td></tr>
    
    <tr><td>Created by</td><td><?php echo $gfd[0]['date_created']; ?></td><td style="width: %10;">&nbsp;</td><td>Message Status</td><td>
     <select id="istatus" name="istatus" >
        <option value="active" <?php if(  strtolower($gfd[0]['issue_status']) == "active"){ echo "selected"; } ?>>Active</option> 
        <option <?php if( strtolower($gfd[0]['issue_status']) == "closed"){ echo "selected"; } ?>>Closed</option></select> 
    </td></tr>
    
    <tr><td>Last Activity</td><td></td><td>&nbsp;</td><td>Priority</td><td><select><option value="20" <?php if($gfd[0]['priority_level'] == 20){ echo "selected"; } ?>>Normal</option><option value="10" <?php if($gfd[0]['priority_level'] == 10){ echo "selected"; } ?>>Urgent</option></select></td></tr>
    
    <tr><td>Est Completion</td><td><input type="text" id="est_compl" style="width: 100px;"/></td> <td>&nbsp;</td>  <td>Category</td><td><select id="message_category_id" name="message_category_id">
<option value="1" <?php if($gfd[0]['issue'] == 1){ echo "selected"; } ?>>Needs Cancelation letter</option>
<option value="2" <?php if($gfd[0]['issue'] == 2){ echo "selected"; } ?>>Damaged Tote</option>
<option value="3" <?php if($gfd[0]['issue'] == 3){ echo "selected"; } ?>>Need GCP Poster</option>

<option value="4" <?php if($gfd[0]['issue'] == 4){ echo "selected"; } ?>>Needs To Be Swapped/Dirty Tote</option>
<option value="5" <?php if($gfd[0]['issue'] == 5){ echo "selected"; } ?>>Oil Theft</option>

<option value="6" <?php if($gfd[0]['issue'] == 6){ echo "selected"; } ?>>Competitor Onsite</option>
<option value="7" <?php if($gfd[0]['issue'] == 7){ echo "selected"; } ?>>Broken Lock</option>
<option value="8" <?php if($gfd[0]['issue'] == 8){ echo "selected"; } ?>>Out Of Business</option>
<option value="9" <?php if($gfd[0]['issue'] == 9){ echo "selected"; } ?>>Container Missing</option>


<option value="60" <?php if($gfd[0]['issue'] == 60){ echo "selected"; } ?>>Location Needs Attention</option>



<option value="90" <?php if($gfd[0]['issue'] == 90){ echo "selected"; } ?>>Competitor On Site</option>

<option value="100" <?php if($gfd[0]['issue'] == 100){ echo "selected"; } ?>>Location Closing</option>

</select></td></tr>

<tr><td>Completed</td><td><?php echo $gfd[0]['completed_date']; ?></td><td>&nbsp;</td>

<td>Assigned to</td><td><select id="assign_to"  rel="<?php echo $gfd[0]['issue_no'];  ?>">
<?php
    $yup = $db->get($dbprefix."_users");
    if(count($yup) >0){
        foreach($yup as $vak){
            if($vak['user_id']  == $gfd[0]['assigned_to'] ){
                    $ik = 'selected';
                }else { 
                    $ik = "";
                }
            echo "<option $ik value='$vak[user_id]'>$vak[first] $vak[last]</option>";
        }
    }
?>
</select>
<script>
$("#assign_to").change(function(){
    //alert($("#assign_to").attr('rel') + " "+$("#assign_to").val());
   $.get("update_assign_to.php",{issue_no:$("#assign_to").attr('rel'),person:$("#assign_to").val()},function(data){
        alert(data);
   }); 
});

</script>

</td></tr>
<tr><td><span style="cursor: pointer;text-decoration:underline;">Follow this issue</span></td><td></td><td>&nbsp;</td><td>Requested by</td><td><select name="requestby"><option value="70" <?php if($gfd[0]['issue'] == 70){ echo "selected"; } ?>>Customer Request</option>

<option value="72" <?php if($gfd[0]['issue'] == 72){ echo "selected"; } ?>>In House Request</option>

<option value="140" <?php if($gfd[0]['issue'] == 140){ echo "selected"; } ?>>Sales Team</option></select></td></tr>
    <tr><td colspan="5" style="height: 10px;">&nbsp;</td></tr>
    <?php
    
        echo "<tr style='background:white;'><td colspan='2' style='vertical-align:top;'>".$gfd[0]['date_created']."<br/>".uNumToName($gfd[0]['reported_by'])."</td><td colspan='3'  style='vertical-align:top;'>".$gfd[0]['message']."</td></tr>";
        $replies = $db->where('issue_no',$gfd[0]['issue_no'])->orderby('message_date','desc')->get($dbprefix.'_issue_notes');
        if ( count($replies) !=0 ){
            foreach($replies as $poi){
                echo "<tr style='background:white;'><td colspan='2' style='vertical-align:top;'>$poi[message_date]<br/>".uNumToName($poi['created_by'])."</td><td colspan='3'  style='vertical-align:top;'>$poi[message]</td></tr>";
                
                
            }
        }
    ?>
    
    <tr><td colspan="4">
    <textarea style="width: 100%;height:60px;" placeholder="reply here" name="repxs" id="repxs"></textarea>
    </td><td><input type="submit" value="Reply" name="rep" id="rep"/></td></tr>
    </table>
</div>
</form>
<script>
    
    $("input#est_compl").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
</script>