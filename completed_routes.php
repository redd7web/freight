<?php
include "protected/global.php";
ini_set("display_errors",1);

$searcharg  = $_GET['searcharg'];
	$limit = $_GET['limit'];
	if (!$limit) $limit=10;
	$sercontact = '';
	$sercompny = '';
	$term = $_GET['term']; 

	 
		$query = "SELECT  route_id,ikg_manifest_route_number FROM freight_list_of_grease WHERE ( route_id like '%" . trim($_GET['term']) . "%' OR ikg_manifest_route_number  like '%" . trim($_GET['term']) . "%' )  AND status='completed'";  

	    // $query = $query." LIMIT  $limit"; Limit results based on the limit parameter from the ajax call. 

	$rows = $db->query($query);  // Populate result set
		// While we retrieve a row from the result set:
	$count=0;
	if (count($rows)>0){
		foreach($rows as $row){
			$companies[$count] = $row['ikg_manifest_route_number'].' ~ '.$row['route_id'];
			$count++;
		}
		echo  json_encode($companies);
	}

?>