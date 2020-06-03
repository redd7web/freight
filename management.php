<?php 

 
include "protected/global.php"; $page = "Management";

if(isset($_GET['task'])){
    switch($_GET['task']){ //page headers
        case "vv":
            $page .= " | View Vehicle";
            break;
        case "overview":
            $page .= " | Overview";
            break;
        case "roi":
            $page .=" | R.O.I. Calculator";
            break;
        case "reports":
            $page .= " | Reports";        
            break;
        case "cancel":
            $page .= " | Account Cancellations";
            break;    
        case "expire":
            $page .= " | Account Expirations";
            break;
        case "freq":
            $page .= "| Pick Up Frequency";
            break;
        case "zero":
            $page .=" | Zero Gallon Pick Ups";
            break;
        case "oilperloc":
            $page .=" | Sludge Per Locations";
            break;
        case "delivery":
            $page .=" | Container Deliveries";
            break;
        case "theft":
            $page .=" | Sludge Theft";
            break;
        case "exports":
            $page .= " | Exports";
            break;
        case "indices":
            $page .=" | Indices";
            break;
        case "collected":
            $page .=" | Collected Fires";
                break;
        case "users":
            $page .=" | Users";
            break;
         case "adduser":
            $page .= "| Add Users";
            break;   
        case "staff":
            $page .=" | Staff(admins)";
            break;
        case "logs":
            $page .= " | Logs";
            break;
        case "vehicles":
            $page .= " | Vehicles";
            break;    
        case "indices":
            $page .= " | Payment Indices";
            break;
        case "friendly":
            $page .= " | Friendly Companies";
            break;
        case "csupport":
            $page .= " | Customer Support";
            break;
        case "notes": 
            $page .= " | Notes";
            break;       
         case "containers":
            $page .= " | Containers";        
            break; 
        case "newloc":
            $page .= " | New Locations";
            break;
        case "ops":
            $page .= " | Oil Pickups Summary";
            break;
        case "ocd":
            $page .= " | Oil Collection By Driver";
            break;
        case "xlog":
            $page .= " | Transaction Log";
            break;
        case "picknpay":
            $page .=" | Pickups & Payments";
            break;
         case "alloil":
            $page .=" | All Oil Collections";
            break; 
        case "affil":
            $page .=" | Affiliates per Route";
            break;
        case "driverslog":
            $page .=" | Driver's Log";
            break;
        case "roi":
            $page .=" | R.O.I. Calculator";
            break;
        case "gps":
            $page .=" | Sludge Pickup Summary";       
            break;
        case "gpexp":
            $page .=" | Sludge Pickup n Payments";
            break;
        case "asset":
            $page .=" | Asset List";
            break;
        case "creport":
            $page .=" | Credit Report";
            break;
        case "trailer":
            $page .=" | Trailer List";
            break;
        case "forecast":
            $page .=" | Sludge Forecast";
            break;
        case "gpexpout":
            $page.= " | Sludge Exports (Outbound)";
            break;
        case "gpsin":
            $page.= " | Sludge Report (Inbound)";
            break;
    }
}


if(isset($_SESSION['freight_id'])){    
    $person = new Person();
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
<?php include "source/header.php"; ?><!---
<div id="loading-screen">
    <p style="text-align: center;">
        Please be patient as report loads.
    </p>
</div> ---!>
<div id="wrapper">
    
    <div class="content-wrapper" style="height: auto;min-height:450px;">
    <?php
    if(isset($_SESSION['freight_id'])){
    
        if(isset($_GET['task'])){
            switch($_GET['task']){
                case "creport":
                    include "management/creditReport.php";
                    break;
                case "overview":
                    include "dash.php";
                    break;
                case "roi":
                    include "management/roi.php";
                    break;    
                case "cancel":
                    include "management/getAccountCancel.php";
                    break;
                case "expire":
                    include "management/getExpires.php";
                    break;
                case "freq":
                    include "management/getFrequ.php";
                    break;
                case "zero":
                    include "management/getZero.php";
                    break;
                case "theft":
                    include "management/getTheft.php";
                    break;
                case "delivery":
                    include "management/getDeliveries.php";
                    break;
                case "collected":
                    include "management/getCollections.php";
                    break;
                case "picknpay":
                    include "management/getPickPay.php";
                    break;    
                case "users":
                    include "management/getUsers.php";
                    break;
                case "adduser":
                    include "management/addUser.php";
                    break;
                case "staff":
                    include "management/getStaff.php";
                    break;
                case "vehicles":                
                    include "management/getVehicles.php";
                    break;
                case "friendly":
                    include "management/getFriendly.php";
                    break;      
                case "indices":
                    include "management/getIndices.php";
                    break;
                case "notes":
                    include "management/getNotes.php";
                    break;    
                case "newloc":
                    include "management/getNewLocations.php";
                    break;
                case "containers":
                    include "management/getContainers.php";
                    break;   
                case "csupport":
                    include "management/getCSupport.php";
                    break;
                case "xlog":
                    include "management/getXLog.php";
                    break;
                case "ops":
                    include "management/getOilPickUpSummary.php";
                    break;        
                case "ocd":
                    include "management/getOilCollectByDriver.php";
                    break;   
                case "alloil":
                    include "management/getAllOil.php";
                    break;
                case "oilperloc":
                    include "management/getOilPerLoc.php";
                    break;
                case "affil":
                    include "management/getAffilate.php";
                    break;
                case "driverslog":
                    include "management/driversLog.php";
                    break;
                case "roi":
                    include "management/roi.php";
                    break;
                case "gps":
                    include "management/getGrease.php";
                    break;
                case "gpexp":
                    include "management/getGreaseExp.php";
                    break;
                case "asset":
                    include "management/getContainers.php";
                    break;
                case "test":
                    include "dash.php";
                    break;
                case "gpexpout":
                    include "management/getGreaseExpOut.php";
                    break;
                case "trailer":
                    include "management/getTrailers.php";
                    break;
                case "gpsin":
                    include "management/getGreaseIn.php";
                    break;
                case "forecast":
                    $check_uc =  array(
                        24,30,31,32,33
                    );
                    
                    //if you are a facility manage of any uc facility or Richard Lopez
                    if(  ( isset($_SESSION['freight_id']) && in_array($person->facility ,$check_uc) && $person->isFacilityManager() ) || ( isset($_SESSION['freight_id']) && (  $person->user_id ==149 || $person->user_id ==137 || $person->user_id == 138  ) ) ){
                        
                        ?>
                        
                        <iframe style="width: 100%;border:0px solid #bbb;margin-top:10px;" frameborder="0" src="summary.php"></iframe>
                        <script>
                            $("#transparent").css("display","none");
                            $('iframe').load(function () {
                                $('iframe').height($('iframe').contents().height());
                            });
                        </script>
                        <?Php
                    }
                    break;
            }
        }
        
       }
        ?> 
 
  </div>
</div>

<?php  include "source/footer.php"; ?>

</body>
</html>