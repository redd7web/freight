
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <style type="text/css">
  header .sign:after{
  content:"+";
  display:inline-block;      
}
.header.expand .sign:after{
  content:"-";
 }
  </style>
  
<?php
include "protected/global.php";
ini_set("display_errors",1);



$hv = $db->query("SELECT MONTH( freight_grease_data_table.date_of_pickup ) AS
month , YEAR( date_of_pickup ) AS year
FROM freight_grease_data_table
WHERE freight_grease_data_table.facility_origin =$_GET[fac]
GROUP BY MONTH( freight_grease_data_table.date_of_pickup ) , YEAR( freight_grease_data_table.date_of_pickup )
ORDER BY date_of_pickup DESC ");


if(count($hv)>0){
    
    echo "<table style='width:1200px;'>";
    foreach($hv as $stop){
         echo "<tr class='header'><td colspan='8' style='text-align:center;background:#ccc;border:1px solid black;' >"; 
         switch($stop['month']){
            case 1:
                echo "Jan";
            break;
            case 2:
                echo "Feb";
            break;
            case 3:
                echo "Mar";
            break;
            case 4:
                echo "Apr";
            break;
            case 5:
                echo "May";
            break;
            case 6:
                echo "Jun";
            break;
            case 7:
                echo "Jul";
            break;
            case 8:
                echo "Aug";
            break;
            case 9:
                echo "Sep";
            break;
            case 10:
                echo "Oct";
            break;
            case 11:
                echo "Nov";
            break;
            case 12:
                echo "Dec";
            break;
        } 
        echo " $stop[year]  <span class='sign'></span></td></tr>";
        
         echo"<tr><td style='width:300px;'>Account</td><td>Picked Up</td><td>PPG</td><td>paid</td><td>Payment Type</td><td>date of pickup</td><td>schedule id</td><td>route id</td></tr>";
       
        $secondary  = $db->query("SELECT freight_grease_data_table.paid, freight_grease_data_table.inches_to_gallons,freight_grease_data_table.account_no,route_id,schedule_id,date_of_pickup,ppg,freight_grease_traps.payment_method, MONTH(freight_grease_data_table.date_of_pickup) as month FROM freight_grease_data_table INNER JOIN freight_grease_traps ON 
            freight_grease_traps.account_no = freight_grease_data_table.account_no AND 
            freight_grease_traps.grease_no = freight_grease_data_table.schedule_id AND 
            freight_grease_traps.grease_route_no = freight_grease_data_table.route_id WHERE freight_grease_data_table.facility_origin = $_GET[fac] AND  MONTH(date_of_pickup) ='$stop[month]' AND YEAR(date_of_pickup) = '$stop[year]' ORDER BY date_of_pickup DESC");
       
        if(count($secondary)>0){
            foreach($secondary as $stopp){
                 echo "<tr class='child'><td>".account_NumToName($stopp['account_no'])." </td><td>$stopp[inches_to_gallons] </td><td> ".number_format($stopp['ppg'],2)."  </td><td>".number_format( $stopp['paid'],2)."  </td><td>$stopp[payment_method]</td><td> $stopp[date_of_pickup]  </td><td>$stopp[schedule_id]  </td><td> $stopp[route_id] </td><tr/>";
            }
        }
       
    }
}
echo "</table>";

?>
<script>
$('.header').click(function(){
     var $this = $(this);
    $(this).nextUntil('tr.header').slideToggle(100).promise().done(function () {
        
    });
});

$('tr.header').siblings('[class^=child]').hide();
</script>