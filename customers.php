<?php 

include "protected/global.php"; $page = "customers";  
    
if ( isset($_GET['task']) ) {
    switch($_GET['task']){
          case "accounts":
            $page .=" | accounts";
            break;
          case "newaccount":
            $page .=" | add account";
            break;
          case "issues":
            $page .=" | service issues";
            break;
          case "tracker":
            $page .=" | Startup Tracker";
            break;
            case "services":
            $page .=" | Ending Services";
            break;
            
    }  
 
    
$person = new Person();
    
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="ReDDaWG" />
    <meta charset="UTF-8" />
   <?php include "source/css.php"; ?>
   <?php include "source/scripts.php"; ?> 
	<title>Customer Management System</title>
</head>

<body>
<?php include "source/header.php"; ?>

<div id="wrapper">

<div class="content-wrapper" style="min-height:450px;height: auto;">
<div class="panel-pane pane-views pane-site-channel" >
  
      
  
  <!--<div class="pane-content">-->
    <div class="view view-site-channel view-id-site_channel view-display-id-block_1 view-dom-id-414f899872b9e7e4b76dcc6bd791c02b">
        
      <div class="view-content">
        <table style="width: 100%;">
        <?php
        
        if(isset($_SESSION['freight_id'])){
            
        
        
            if( isset($_GET['task']) ) {
                switch($_GET['task']){
                    case "accounts":
                        include "getAccounts.php";
                        break;
                    case "newaccount":
                        include "addAcount.php";
                        break;
                    case "issues":
                        include "getIssues.php";
                        break;
                    case "tracker":
                        include "getTracker.php";
                        break;   
                    case "services":
                        include "getServices.php";
                        break;                     
                }                
            }
        }    
            
        ?>  
        </table>      
        </div>
  
  
    </div>
  
      
  
  
  
  
</div>  <!--</div>-->

  
  </div>

</div>
</div>

<?php   include "source/footer.php"; ?>

</body>
</html>