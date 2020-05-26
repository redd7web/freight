

<?php 


    include "protected/global.php"; $page = "scheduling";
    $person = new Person();



if(isset($_GET['task'])){
    switch($_GET['task']){ //page headers
        case "cop":
            $page .= " | Completed Oil Pickups";
            break;
        case "rop":
            $page .=" | Routed Oil Pickups";
            break;
        case "fgrid":
            $page .=" | Facility Grid";
            break;
         case "freport":
            $page .=" | Facility Report";
            break;
        case "oilongoing":
            $page .=" | Oil Code Red Ongoing";
            break;
        case "oilcomplete":
            $page .=" | Completed Oil Code Red";
            break;
        case "crequest":
            $page .=" | Container Requests";
            break;
        case "utilongoign":
            $page .=" | Utility Code Red";
            break;
        case "utilalarmcomplete":
            $page .=" | Utility Completed Code Red";    
            break;
        case "schoipu":
            $page .=" | Schedule Oil Pickups";
            break;
        case "cuc":
            $page .=" | Completed Utility Calls";
            break;
        case "suc":
            $page .=" | Scheduled Utility Calls";
            break;
        case "ruc":
            $page .=" | Routed Utility Calls";
            break;
        case "sgt":
            $page .=" | Scheduled Grease Traps";
            break;
        case "rgt":
            $page .=" | Routed Grease Traps";          
            break;
        case "cgt":
            $page .=" | Completed Grease Traps";
            break;
        case "shifts":
            $page .=" | Shifts";
            break;
        case "pickexp":
            $page .=" | Pickup Exports";
            break;
        case "lor":
            $page .=" | List of Routes";
            break;
    }
}

?>


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
<div id="loader" style="text-align: center;position:fixed;background:rgba(255,255,255,.5) url(img/loading.gif) no-repeat center 200px;z-index:9999;"></div> 

<div id="wrapper">

<div id="spacer" style="width: 100%;height:10px;">&nbsp;</div>

<div class="content-wrapper" style="min-height:450px;height: auto;">
<div class="panel-pane pane-views pane-site-channel" >
  
      
  
  <!--<div class="pane-content">-->
    <div class="view view-site-channel view-id-site_channel view-display-id-block_1 view-dom-id-414f899872b9e7e4b76dcc6bd791c02b">
      <div class="view-content">
        
    <?php
     if(isset($_SESSION['sludge_id'])){
    
        if(isset($_GET['task'])){
            switch($_GET['task']){
                  
                case "fgrid":
                    include "getFacGrid.php";
                    break; 
                case "freport":
                    include "getFacReport.php";
                    break;       
                case "freportarea":
                    include "getFrarea.php";
                    break;
                case "oilcomplete":
                    include "getCompletedOilFires.php";
                    break;
                case "oilongoing":
                    include "getFireCrackers.php";
                    break;
                case "crequest":
                    include "getContainerRequests.php";
                    break;
                case "rop":
                    include "getOilRoutedPickups.php";
                    break;
                case "cop":
                    include "getOilCompletedPickups.php";
                    break;  
                case "shifts":
                    include "getShifts.php";
                    break;
                case "pickexp":
                    include "getPickExp.php";
                    break;
                case "utilongoign":
                    include "getUtilAlarms.php";
                    break;
                case "schoipu":
                    include "getScheduledOilPickups.php";
                    break;
                case "cuc":
                    include "getCompletedUtils.php";
                    break;
                case "suc":
                    include "getScheduledUtilityCalls.php";
                    break;
                case "ruc":
                    include "getScheduledUtilityRoutedCalls.php";
                    break;
                case "sgt":
                    include "getScheduledGreaseTraps.php";
                    break;
                case "rgt":
                    include "getRoutedGreaseTraps.php";
                    break;
                case "cgt":
                    include "getCompletedGreaseTraps.php";
                    break;
                case "utilalarmcomplete":
                    include "getUtilAlarmsCompleted.php";
                    break;
                case "lor":
                    include "getListOfRoutes.php";
                    break;
                default:
                    echo "";
                    break;
            }    
        }
    
        
    }
    
    ?>
    </div>
</div>  <!--</div>-->

  
  </div>

</div>
</div>

<?php 


include "source/footer.php"; ?>

</body>
</html>
