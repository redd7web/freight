<?php
include "protected/global.php";
$account = new Account($_GET['account']);
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
    margin:10px 10px 10px 10px;
}
</style>
<div id="notesection" style="width: 362px;height:240px;border:0px solid green;border-radius: 5px;margin:auto;">
                    <table style="width: 362px;height:240px;">
                    <tr><td style="text-align: left;vertical-align:top;;height:25px;"><?php 
                    if(file_exists("$account->acount_id/contract")){//has the sub folder already been created?
                        if ($handle = opendir("$account->acount_id/contract/")) {
                            if(!is_dir_empty("$account->acount_id/contract/")){// is the folder empty?
                                while (false !== ($entry = readdir($handle))) {            
                                    if ($entry != "." && $entry != "..") {        
                                        echo "&nbsp;&nbsp;<a href='$account->acount_id/contract/$entry' target='_blank'>$entry</a>"; 
                                    }
                                }        
                            }
                            closedir($handle);
                        }    
                    }
                    ?></td></tr>
                    <tr><td style="text-align: left;vertical-align:top;;height:25px;"><?php 
                    
                    
                     
                    if(file_exists("$account->acount_id/poster")){
                          if ($handle = opendir("$account->acount_id/poster/")) {
                            if(!is_dir_empty("$account->acount_id/poster/")){
                                while (false !== ($entry = readdir($handle))) {            
                                    if ($entry != "." && $entry != "..") {        
                                        echo "<a href='$account->acount_id/poster/$entry' target='_blank'>Good Cleaing Practice Poster</a>";  
                                    }
                                }        
                            }
                            closedir($handle);
                        }    
                    }
                    ?></td></tr>
                    <tr><td style="text-align: left;vertical-align:top;;height:25px;">
                    <?php
                    
                    
                    if(file_exists("$account->acount_id/notices/")  && !is_dir_empty("$account->acount_id/notices/") ){
                        $dir = "$account->acount_id/notices";
                        $dh  = opendir($dir);
                        if(!is_dir_empty($dir)){
                            while (false !== ($filename = readdir($dh))) {
                                
                                if($filename !="."&& $filename !=".."){
                                    $files[] = $filename;
                                }
                            }
                            $count =1;
                            foreach($files as $file){
                                echo "
                                <a href='$account->acount_id/notices/$file' target='_blank'>View Notice $count</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
                                $count++;
                            }
                        }
                    }
                    ?>
                    </td></tr>
                    <tr><td style="text-align: left;vertical-align:top;;height:25px;"><?php
                    if(file_exists("$account->acount_id/photos/") && !is_dir_empty("$account->acount_id/photos/")){
                        $dir = "$account->acount_id/photos";
                        $dh  = opendir($dir);
                        
                        if(!is_dir_empty($dir)){    
                            while (false !== ($filename = readdir($dh))) {
                                
                                if($filename !="."&& $filename !=".."){
                                    $files[] = $filename;
                                }
                            }
                            $count =1;
                            foreach($files as $file){
                                echo "
                                <a href='$account->acount_id/photos/$file' target='_blank'>Photo $count</a> &nbsp;&nbsp;|&nbsp;&nbsp;";
                                $count++;
                            }
                        }
                    }
                    ?></td></tr>
                    <tr><td style="text-align: left;vertical-align:top;;height:25px;">
                    <?php 
                      
                    if(file_exists("$account->acount_id/cancel/")){
                            if ($handle = opendir("$account->acount_id/cancel/")) {
                                
                            if(!is_dir_empty("$account->acount_id/cancel")){    
                            while (false !== ($entry = readdir($handle))) {            
                                if ($entry != "." && $entry != "..") {        
                                      echo "<a href='$account->acount_id/cancel/$entry' target='_blank'>Cancellation Notice</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
                                }
                            }
                            }        
                            closedir($handle);
                        }
                             
                        
                    }
                    
                    
                    ?>
                    </td></tr>
                    </table>
                     <?php 
                     
                  
                    ?>
                     
                    </div>