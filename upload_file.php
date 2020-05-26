<?php
include "protected/global.php";
$account_table = "sludge_accounts";
//ini_set("display_errors",1);

function is_dir_empty($dir) {
  if (!is_readable($dir)) return NULL; 
  $handle = opendir($dir);
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      return FALSE;
    }
  }
  return TRUE;
}



?>

<style>
body{
    padding:10px 10px 10px 10px;
    margin:0px 0px 0px 0px;
}
</style>

<table style="width: 100%;">
<tr><td style="text-align: center;">

<form enctype="multipart/form-data" method="post" action="upload_file.php?mode=<?php echo $_GET['mode']; ?>&account=<?php echo $_GET['account']; ?>">
<br />
 <input type="file" <?php 
 
    switch($_GET['mode']){
          case 1:case 3: case 4:
          echo 'name="file[]" multiple';
          break;
          default:
          echo 'name=file';
          break;
    }
 ?> /><br />
<input type="text" name="account" value="<?php echo $_GET['account']; ?>" readonly=""/><br />
<input type="text" name="mode" value="<?php echo $_GET['mode']; ?>" readonly=""/><br />
<input type="submit" value="Submit"  name="upload"/><br />
</form>

</td></tr>
<tr><td>
<?php 

if(isset($_GET['path'])){
    switch($_GET['mode']){
        case 1:
            if($db->query("UPDATE sludge_accounts SET contract =NULL WHERE account_ID=$_GET[account]")){
                echo "File deleted";
            }
            break;
        case 2:
            if($db->query("UPDATE sludge_accounts SET good_cleaning_practice_poster =NULL WHERE account_ID=$_GET[account]")){
                echo "File deleted";
            }
            break;
        case 3:
            break;
        case 5:
            if($db->query("UPDATE sludge_accounts SET cancel_letter = NULL WHERE account_ID = $_GET[account]")){
                echo "File deleted";
            }
            break;
    }
    unlink($_GET['path']);
}


if(isset($_POST['rename_now'])){
    
    $p = str_replace(" ","",$_POST['newname']);
    $p = substr($p, 0, 15);
    $temp = explode(".",$_POST['oldname']);
    echo "new file name ".$p.".".strtolower(end($temp))."<br/>";
    rename($_POST['oldname'],$_POST['directory'].$p.".".strtolower(end($temp))  );
}

switch($_GET['mode']){
    case 1:
        if(file_exists("$_GET[account]/contract/")){
            if ($handle = opendir("$_GET[account]/contract/")) {
                while (false !== ($entry = readdir($handle))) {            
                    if ($entry != "." && $entry != "..") {        
                        echo "<a href='$_GET[account]/contract/$entry' target='_blank'>$entry</a>&nbsp;&nbsp;<a href='upload_file.php?account=$_GET[account]&mode=$_GET[mode]&path=$_GET[account]/contract/$entry'>
                    <img src='img/delete-icon.jpg' title='Delete this notice'/>
                    </a>
                    <form action='upload_file.php?account=$_GET[account]&mode=$_GET[mode]' method='post'>
                        <input type='text' name='newname' placeholder='File New Name'/>&nbsp;
                        <input type='hidden' name='directory' value='$_GET[account]/contract/' />
                        <input type='hidden' value='$_GET[account]/contract/$entry' name='oldname' />
                        <input type='submit' name='rename_now' value='Rename File'/>
                        </form><br/>
                    "; 
                    }
                }        
                closedir($handle);
            }    
        }
    
        
        break;
    case 2:
        if(file_exists("$_GET[account]/poster/")){
              if ($handle = opendir("$_GET[account]/poster/")) {
                while (false !== ($entry = readdir($handle))) {            
                    if ($entry != "." && $entry != "..") {        
                        echo "<a href='$_GET[account]/poster/$entry' target='_blank'>Good Cleaing Practice Poster</a>&nbsp;&nbsp;<a href='upload_file.php?account=$_GET[account]&mode=$_GET[mode]&path=$_GET[account]/poster/$entry'>
                <img src='img/delete-icon.jpg' title='Delete this notice'/>
                </a>
                <form action='upload_file.php?account=$_GET[account]&mode=$_GET[mode]' method='post'>
                        <input type='text' name='newname' placeholder='File New Name'/>&nbsp;
                        <input type='hidden' name='directory' value='$_GET[account]/poster/' />
                        <input type='hidden' value='$_GET[account]/poster/$entry' name='oldname' />
                        <input type='submit' name='rename_now' value='Rename File'/>
                        </form>
                ";  
                    }
                }        
                closedir($handle);
            }
                 
        }
        
        
    case 3:
        if(is_dir("$_GET[account]/notices/")  && !is_dir_empty("$_GET[account]/notices/") ){
            $dir = "$_GET[account]/notices/";
            $dh  = opendir($dir);
            while (false !== ($filename = readdir($dh))) {
                
                if($filename !="."&& $filename !=".."){
                    $files[] = $filename;
                }
            }
            $count =1;
            foreach($files as $file){
                echo "
                <a href='$_GET[account]/notices/$file' target='_blank'>View Notice $count</a> &nbsp;&nbsp;
                
                <a href='upload_file.php?account=$_GET[account]&mode=$_GET[mode]&path=$_GET[account]/notices/$file'>
                <img src='img/delete-icon.jpg' title='Delete this notice'/>
                </a><form action='upload_file.php?account=$_GET[account]&mode=$_GET[mode]' method='post'>
                        <input type='text' name='newname' placeholder='File New Name'/>&nbsp;
                        <input type='hidden' name='directory' value='$_GET[account]/notices/' />
                        <input type='hidden' value='$_GET[account]/notices/$file' name='oldname' />
                        <input type='submit' name='rename_now' value='Rename File'/>
                        </form>
                <br/>";
                $count++;
            }
        }
    case 4:
        if(is_dir("$_GET[account]/photos") && !is_dir_empty("$_GET[account]/photos/")){
            $dir = "$_GET[account]/photos";
            $dh  = opendir($dir);
            while (false !== ($filename = readdir($dh))) {
                
                if($filename !="."&& $filename !=".."){
                    $files[] = $filename;
                }
            }
            $count =1;
            foreach($files as $file){
                echo "
                <a href='$_GET[account]/photos/$file' target='_blank'>Photo $count</a> &nbsp;&nbsp;
                
                <a href='upload_file.php?account=$_GET[account]&mode=$_GET[mode]&path=$_GET[account]/photos/$file'>
                <img src='img/delete-icon.jpg' title='Delete this notice'/>
                </a><form action='upload_file.php?account=$_GET[account]&mode=$_GET[mode]' method='post'>
                        <input type='text' name='newname' placeholder='File New Name'/>&nbsp;
                        <input type='hidden' name='directory' value='$_GET[account]/photos/' />
                        <input type='hidden' value='$_GET[account]/photos/$file' name='oldname' />
                        <input type='submit' name='rename_now' value='Rename File'/>
                        </form><br/>
                <br/>";
                $count++;
            }
        }
    case 5:
        if(file_exists("$_GET[account]/cancel/")){
                if ($handle = opendir("$_GET[account]/cancel/")) {
                while (false !== ($entry = readdir($handle))) {            
                    if ($entry != "." && $entry != "..") {        
                          echo "<a href='$_GET[account]/cancel/$entry' target='_blank'>Cancellation Notice</a>&nbsp;&nbsp;<a href='upload_file.php?account=$_GET[account]&mode=$_GET[mode]&path=$_GET[account]/cancel/$entry'><img src='img/delete-icon.jpg' title='Delete this notice'/></a>
                        <form action='upload_file.php?account=$_GET[account]&mode=$_GET[mode]' method='post'>
                        <input type='text' name='newname' placeholder='File New Name'/>&nbsp;
                        <input type='hidden' name='directory' value='$_GET[account]/cancel/' />
                        <input type='hidden' value='$_GET[account]/cancel/$entry' name='oldname' />
                        <input type='submit' name='rename_now' value='Rename File'/>
                        </form>";
                    }
                }        
                closedir($handle);
            }
                 
            
        }
        break;
}
?>
</td></tr>


</table>



<?php


if(isset($_POST['upload'])){
    switch($_POST['mode']){
        
        case 1:
            if (!is_dir("$_POST[account]/contract/")) {
                mkdir("$_POST[account]/contract/", 0777, true);
            }
            foreach ($_FILES['file']['name'] as $f => $name) {     
        	   $temp = explode(".",$_FILES["file"]["name"][$f]);
                $mod = uniqid();
                $newfilename = "contract-$_POST[account]$mod.".end($temp); 
                echo $newfilename."<br/>";
        	    if(move_uploaded_file($_FILES["file"]["tmp_name"][$f], "$_POST[account]/contract/".$newfilename)){
        	       $buffer = array(
                        "contract"=>"$_POST[account]/contract/$newfilename"
                    );   
                    $db->where('account_ID',$_POST['account'])->update($dbprefix."_accounts",$buffer);
                    echo "Contract Uploaded<br/>";
        	    }
                
        	}
           
            break;
        case 2:
            $temp = explode(".",$_FILES["file"]["name"]);
            $newfilename = "poster-$_POST[account].".end($temp);
            $newfilename = str_replace(" ","_",$newfilename);
            if (!is_dir("$_POST[account]/poster/")) {
                mkdir("$_POST[account]/poster/", 0777, true);
            }
            if(move_uploaded_file($_FILES["file"]["tmp_name"], "$_POST[account]/poster/" . $newfilename)){
                $buffer = array(
                    "good_cleaning_practice_poster"=>"$_POST[account]/poster/$newfilename"
                );
                $db->where('account_ID',$_POST['account'])->update($dbprefix."_accounts",$buffer);
                echo "Good Cleaning Practice Poster Uploaded<br/>";    
            }
            
            //var_dump($buffer);
            
            
            break;
        case 3:
           if (!is_dir("$_POST[account]/notices/")) {
                mkdir("$_POST[account]/notices/", 0777, true);
            }
            
            for($i=0; $i<2; $i++){ 
                $temp = explode(".",$_FILES['file']['name'][$i]);
                $newfilename = microtime()."-removal-$_POST[account].".end($temp);
                $newfilename = str_replace(" ","_",$newfilename);
                echo $_FILES['file']['name'][$i].'<br/>';
                if(move_uploaded_file($_FILES["file"]["tmp_name"][$i], "$_POST[account]/notices/$newfilename")){
                    if($i == 0){
                        $name = "first_removal";
                    } else if($i == 1){
                        $name = "second_removal";
                    }
                    $buffer = array(
                        "$name"=>"$_POST[account]/notices/$newfilename"
                    );
                    echo "Removal Notice Uploaded<br/>";
                    $db->where('account_ID',$_POST['account'])->update($dbprefix."_accounts",$buffer);    
                }
            }
            
            break;
        case 4:
            if (!is_dir("$_POST[account]/photos/")) {
                mkdir("$_POST[account]/photos/", 0777, true);
            }
           
            for($i=0; $i<count($_FILES['file']['name']); $i++){
                $temp = explode(".",$_FILES['file']['name'][$i]);
                $newfilename = microtime()."-photo-$_POST[account].".end($temp);
                $newfilename = str_replace(" ","_",$newfilename);
                echo $_FILES['file']['name'][$i].'<br/>';
                if(move_uploaded_file($_FILES["file"]["tmp_name"][$i], "$_POST[account]/photos/".$newfilename)){
                 
                    echo "Photo Uploaded<br/>";   
                }
                 
            }
           
            break;
        case 5:
            $temp = explode(".",$_FILES["file"]["name"]);
            $newfilename = "cancel-$_POST[account].".end($temp);
            $newfilename = str_replace(" ","_",$newfilename);
            if (!is_dir("$_POST[account]/cancel/")) {
                mkdir("$_POST[account]/cancel/", 0777, true);
            }
            if(move_uploaded_file($_FILES["file"]["tmp_name"], "$_POST[account]/cancel/" . $newfilename)){
                $db->query("UPDATE sludge_accounts SET cancel_letter ='$_POST[account]/cancel/$newfilename' WHERE account_ID=$_POST[account]");
                echo "Cancellation Request Uploaded<br/>";    
            }
            
    }
    
}


?>