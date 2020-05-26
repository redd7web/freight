<?php
$connect = mysql_connect("localhost","reddawg","quyle714");
mysql_select_db("grease_trap",$connect);
$csv_file = "Book1.csv"; // Name of your CSV file
$csvfile = fopen($csv_file, 'r');
$theData = fgets($csvfile);


$i = 0;
while (!feof($csvfile)) {
	$csv_data[] = fgets($csvfile, 1024);
	$csv_array = explode(",", $csv_data[$i]);
	$insert_csv = array();
	$insert_csv['container_label'] = $csv_array[0];
	$insert_csv['amount_holds'] = $csv_array[1];
	$insert_csv['gpi'] = $csv_array[2];
	$query = "INSERT INTO sludge_list_of_containers(container_id,container_label,amount_holds,gpi) VALUES(0,'".$insert_csv['container_label']."','".$insert_csv['amount_holds']."','".$insert_csv['gpi']."')";
	$n=mysql_query($query, $connect );
	$i++;
}
	fclose($csvfile);

?>