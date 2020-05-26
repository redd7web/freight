<?php
ini_set("display_errors",1);
include "protected/global.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>Live Demo of Google Maps Geocoding Example with PHP</title>
     
    <style>
    body{
        font-family:arial;
        font-size:.8em;
        padding:10px 10px 10px 10px;
    }
     
    input[type=text]{
        padding:0.5em;
        width:20em;
    }
     
    input[type=submit]{
        padding:0.4em;
    }
     
    #gmap_canvas{
        width:100%;
        height:30em;
    }
     
    #map-label,
    #address-examples{
        margin:1em 0;
    }
    </style>
 
</head>
<body>
 
<?php
if($_POST){
 
    // get latitude, longitude and formatted address
    $data_arr = geocode($_POST['address']);
    
    // if able to geocode the address
    if($data_arr != "REQUEST_DENIED"){
         
        $latitude = $data_arr[0];
        $longitude = $data_arr[1];
        $formatted_address = $data_arr[2];
        echo "Proper Address:".$formatted_address."<br/>"; 
        $buffer = explode(",",$formatted_address);
           
        echo "address: ".trim($buffer[0])."<br/>";
        echo "city: ".trim($buffer[1])."<br/>";
        $city_zip = explode(" ",trim($buffer[2]));
        echo "state: ".trim($city_zip[0])."<br/>";
        echo "zip: ".trim($city_zip[1]);
        
        $db->query("UPDATE sludge_accounts SET address='$buffer[0]', city='$buffer[1]',state='$city_zip[0]',zip='$city_zip[1]' WHERE account_ID = $ ");
    ?>
 
    <!-- google map will be shown here -->
    <div id="gmap_canvas">Loading map...</div>
    <div id='map-label'>Map shows approximate location.</div>
 
    <!-- JavaScript to show google map -->
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script> 
    <script type="text/javascript">
        function init_map() {
            var myOptions = {
                zoom: 14,
                center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
            marker = new google.maps.Marker({
                map: map,
                position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
            });
            infowindow = new google.maps.InfoWindow({
                content: "<?php echo $formatted_address; ?>"
            });
            google.maps.event.addListener(marker, "click", function () {
                infowindow.open(map, marker);
            });
            infowindow.open(map, marker);
        }
        google.maps.event.addDomListener(window, 'load', init_map);
    </script>
 
    <?php
 
    // if unable to geocode the address
    }else{
        echo "$data_arr";
    }
}
?>

<!-- enter any address -->
<form action="validate_address.php?account=<?php echo $_GET['account']; ?>" method="post">
    <input type='text' name='address' placeholder='Enter any address here' value="<?php 
        if(isset($_GET['account'])){
            $account = new Account($_GET['account']);
            echo $account->address.", ".$account->city.", ".$account->state." ".$account->zip;
        }
    ?>" />
    <input type='submit' value='Verify!' />
</form>
 
<?php
 
// function to geocode address, it will return false if unable to geocode address
function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "http://maps.google.com/maps/api/geocode/json?address={$address}&sensor=false";
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){ 
        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
         
        // verify if data is complete
        if($lati && $longi && $formatted_address){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        }else{
            return $resp['status'];
        }
         
    }else{
        return $resp['status'];
    }
}
?>
 
</body>
</html>