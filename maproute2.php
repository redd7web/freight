<?php
include "protected/global.php";
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if(isset($_POST['newr'])){
    
    header("location:$_POST[link]");
} else if(isset($_POST['oldr'])){
    header("location:$_POST[link]");
}

class TSP {
	private $locations 	= array();		// all locations to visit
	private $longitudes = array();
	private $latitudes 	= array();
	private $shortest_route = array();	// holds the shortest route
	private $shortest_routes = array();	// any matching shortest routes
	private $shortest_distance = 0;		// holds the shortest distance
	private $all_routes = array();		// array of all the possible combinations and there distances


	// add a location
	public function add($name,$longitude,$latitude){
		$this->locations[$name] = array('longitude'=>$longitude,'latitude'=>$latitude);
	}
	// the main function that des the calculations
	public function compute(){
	   
		$locations = $this->locations;


		foreach ($locations as $location=>$coords){
			$this->longitudes[$location] = $coords['longitude'];
			$this->latitudes[$location] = $coords['latitude'];
		}
		$locations = array_keys($locations);
        

		$this->all_routes = $this->array_permutations($locations);

        echo "computing..<br/>";
		foreach ($this->all_routes as $key=>$perms){
			$i=0;
			$total = 0;
			foreach ($perms as $value){
				if ($i<count($this->locations)-1){
					$total+=$this->distance($this->latitudes[$perms[$i]],$this->longitudes[$perms[$i]],$this->latitudes[$perms[$i+1]],$this->longitudes[$perms[$i+1]]);
				}
				$i++;
			}
			$this->all_routes[$key]['distance'] = $total;
			if ($total<$this->shortest_distance || $this->shortest_distance ==0){
				$this->shortest_distance = $total;
				$this->shortest_route = $perms;
				$this->shortest_routes = array();
			}
			if ($total == $this->shortest_distance){
				$this->shortest_routes[] = $perms;
			}
		}
	}
	// work out the distance between 2 longitude and latitude pairs
	function distance($lat1, $lon1, $lat2, $lon2) { 
		if ($lat1 == $lat2 && $lon1 == $lon2) return 0;
		$unit = 'M';	// miles please!
		$theta = $lon1 - $lon2; 
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
		$dist = acos($dist); 
		$dist = rad2deg($dist); 
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);


		if ($unit == "K") {
			return ($miles * 1.609344); 
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
	// work out all the possible different permutations of an array of data
	private function array_permutations($items, $perms = array( )) {
		static $all_permutations;
		if (empty($items)) {
			$all_permutations[] = $perms;
		}  else {
			for ($i = count($items) - 1; $i >= 0; --$i) {
				$newitems = $items;
				$newperms = $perms;
				list($foo) = array_splice($newitems, $i, 1);
				array_unshift($newperms, $foo);
				$this->array_permutations($newitems, $newperms);
			}
		}
		return $all_permutations;
	}
	// return an array of the shortest possible route
	public function shortest_route(){
		return $this->shortest_route;
	}
	// returns an array of any routes that are exactly the same distance as the shortest (ie the shortest backwards normally)
	public function matching_shortest_routes(){
		return $this->shortest_routes;
	}
	// the shortest possible distance to travel
	public function shortest_distance(){
	   echo "Shortest distance: <br/>";
		return $this->shortest_distance;
	}
	// returns an array of all the possible routes
	public function routes(){
		return $this->all_routes;
	}
}


$tsp = new TSP;
$account_table = $dbprefix."_accounts";
$ikg_info = new IKG($_GET['ikg']);

$account_info = new Account();
$person = new Person();

//var_dump($person);

$address="";
$latlong = $account_info->singleField( $ikg_info->account_numbers[0],'latitude').",".$account_info->singleField($ikg_info->account_numbers[0],"longitude");
$destin[] = str_replace(" ","+",$facils[$person->facility]);
$old[] = str_replace(" ","+",$facils[$person->facility]);

foreach($ikg_info->account_numbers as $numbs){
    echo "Routing for ". $account_info->singleField($numbs,"name") ."<br/>";
    $address ="";
    $address .= str_replace(" ","+", $account_info->singleField($numbs,"address") ).",+";
    $address .= str_replace(" ","+", $account_info->singleField($numbs,"city")  ).",+";
    $address .= $account_info->singleField($numbs,"state")."+";
    $address .= $account_info->singleField($numbs,"zip");
    //echo $address."<br/>";
    $old[] = $address;
    $tsp->add($account_info->singleField($numbs,"name"),$account_info->singleField($numbs,"latitude"),$account_info->singleField($numbs,"longitude"));
}

//var_dump($old);

$tsp->compute();

echo 'Shortest Distance: '.$tsp->shortest_distance();


$yu = $tsp->shortest_route();
echo "<br/><br/>";

$start = count($tsp->shortest_route());
for($i = $start-1; $i>-1;$i--){
    echo  "<b>",$yu[$i]."</b><br/><br/>";
    $buff = $db->query("SELECT account_ID FROM $account_table WHERE name like '%".$yu[$i]."%'");
    $address ="";
    $address .= str_replace(" ","+", $account_info->singleField($buff[0]['account_ID'],"address") ).",+";
    $address .= str_replace(" ","+", $account_info->singleField($buff[0]['account_ID'],"city")  ).",+";
    $address .= $account_info->singleField($buff[0]['account_ID'],"state")."+";
    $address .= $account_info->singleField($buff[0]['account_ID'],"zip");
    
    
    $destin[] = $address;
}

$link ="https://www.google.com/maps/dir/".implode("/",$destin)."/".$coords[$person->facility];
$link2 ="https://www.google.com/maps/dir/".implode("/",$old)."/".$coords[$person->facility];
echo '<br /><span style="float:left;">Shortest suggested route, keep changes?:</span>  <span id="yes" style="cursor:pointer;text-decoration:underline;color:blue;float:left;">Yes</span>  | <span id="no" style="cursor:pointer;text-decoration:underline;color:blue;">No</span>';

var_dump($destin);


//https://www.google.com/maps/dir/10802+Palma+Vista+Ave,+Garden+Grove,+CA,+USA/Orchard+Dr,+California/13332+Barnett+Way,+Garden+Grove,+CA+92843/15927+Mt+Matterhorn+St,+Fountain+Valley,+CA+92708/@33.8006345,-117.9476294,12z/data=!3m1!4b1!4m30!4m29!1m5!1m1!1s0x80dd2839104366cb:0x2f0491e4dd658cb3!2m2!1d-117.944558!2d33.799711!1m5!1m1!1s0x80dcd144c71f6301:0x134f1e6aa3380b3a!2m2!1d-117.8153673!2d33.8703063!1m5!1m1!1s0x80dd27fb7823a349:0x726265bb97a3833!2m2!1d-117.933795!2d33.769035!1m5!1m1!1s0x80dcd88420e19f41:0x767f1ef0726f4204!2m2!1d-117.91732!2d33.732338!2m2!2b1!3b1!3e0!4e1
//echo "https://www.google.com/maps/dir/".implode("/",$destin)."/@".$coords[$person->facility].",12z/";
//header("location:https://www.google.com/maps/dir/".implode("/",$destin)."/@".$coords[$person->facility].",12z/");



?>
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<form style="float:left;" action="maproute.php?ikg=<?php echo $_GET['ikg']; ?>" method="post" id="newroute"><input type="hidden" id="newr" name="newr" value="1"/><input type="hidden" name="link" value="<?php echo $link ?>"/></form>


<form style="float:left;" action="maproute.php?ikg=<?php echo $_GET['ikg']; ?>" method="post" id="oldroute"><input type="hidden" id="oldr" name="oldr" value="1"/><input type="hidden" name="link" value="<?php echo $link2 ?>"/></form>

<script>

$("#yes").click(function(){
    $("#newroute").submit();
});


$("#no").click(function(){
    $("#oldroute").submit();
});
</script>



