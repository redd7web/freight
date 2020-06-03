<?php
include "protected/global.php";
$person = new Person();

if(isset($_POST['statsub'])){
    $statchange = array(
        "status"=>$_POST['stat']
    );
    $db->where("id",$_POST['pmid'])->update("freight_private",$statchange);
}

if ( isset($_POST['kpmreply']) ) { 
    $private_message =Array( 
        "pm_thread_no"=>$_GET['id'],
        "recipient"=>$_POST['recipe'],
        "author"=>$person->user_id,
        "message"=>$_POST['pmmsg'],
        "created"=>date("Y-m-d H:i:s")
    );
   $db->insert($dbprefix."_private_thread",$private_message);
}

$first = $db->where('id',$_GET['id'])->get("freight_private");
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
</style>
<form action="pmreply.php?id=<?php echo $_GET['id']; ?>" method="post">
<div id="adduser" style="margin-top:5px;background:rgb(204, 204, 204);">
    <table style="width: 100%;">
    <tr><td colspan="2">Set Thread Status</td><td colspan="2"><form action="pmreply.php" method="post"><select  name="stat" id="stat"><option  <?php 
        if( strtolower($first[0]['status']) == "active"){ echo "selected";}
    ?>  >Active</option><option <?php if( strtolower($first[0]['status']) == "closed"){ echo "selected";} ?>>Closed</option></select><input name="pmid" type="hidden" value="<?php echo $_GET['id']; ?>"/><input type="submit" name="statsub" value="Change Status"/></form></td></tr>
    
     <tr><td style="padding: 0px 0px 0px 0px;">#</td><td>Author</td><td>Message</td><td>Date</td></tr>
    
   <?php
        
        
        if(count($first)>0){
            foreach($first as $ks){
                echo "<tr><td>$ks[id]</td><td>".uNumToName($ks['author'])."</td><td>$ks[message]</td><td>$ks[date]</td></tr>";
            }
        }  
   
        $hjk = $db->where('pm_thread_no',$_GET['id'])->orderby("created","DESC")->get($dbprefix.'_private_thread');
        
        if(count($hjk) !=0){
            foreach($hjk as $thread){
                echo "<tr><td>$thread[id]</td><td>".uNumToName($thread['author'])."</td><td>$thread[message]</td><td>$thread[created]</td></tr>";
            }
        }
   ?>
   
   <tr><td colspan="3" style="text-align: right;">
   <input type="hidden" value="<?php echo $hjk[0]['recipient']; ?>" name="recipe"/>
   <textarea name="pmmsg" placeholder="Reply here" style="width: 100%;"></textarea></td><td colspan="2"><input type="submit" value="Reply" name="kpmreply"/></td></tr>
    </table>
</div>

</form>