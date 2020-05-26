<?php
//from etner grease data

include "protected/global.php";
include "plugins/phpToPDF/phpToPDF.php";
$person = new Person();
ini_set("display_errors",1);
function is_dir_empty($dir) {
  if (!is_readable($dir)) return NULL; 
  $handle = opendir($dir);
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      return FALSE;
    }
  }
  return true;
}

$grease_ikg = new Grease_IKG($_POST['route_id']);

$tyc = $db->query("SELECT COALESCE(SUM(inches_to_gallons),NULL,0) as all_gal FROM sludge_grease_data_table WHERE route_id=$_POST[route_id]");



$buffer = array(
    "status"=>"completed",
    'completed_date' =>date("Y-m-d H:i:s"),
    "collected"=>$tyc[0]['all_gal'],
    "stops"=>$count
);

$b3 = array(
    'completed_date' =>date("Y-m-d")
);
$db->query("UPDATE sludge_grease_traps SET grease_route_no=null, route_status='scheduled' WHERE grease_route_no = $_POST[route_id] AND route_status IN('enroute','scheduled')");//return uncompleted stops

$db->where('route_id',$_POST['route_id'])->update($dbprefix."_list_of_grease",$buffer);
$db->where('route_id',$_POST['route_id'])->update($dbprefix."_ikg_grease",$b3);
$db->query("UPDATE sludge_grease_traps SET completed_date='".date("Y-m-d")."' WHERE grease_route_no = $_POST[route_id]");



$head ='From: invoice@iwpusa.com' . "\r\n" .
    'Reply-To: no-replyr@iwpusa.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion()."\r\n MIME-Version: 1.0\r\n";
    $head .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';
   
    
    
    
    

$aco_nums ="";
$count = 0;

//***************************MARK ROUTE COMPLETED*******************************************************
$db->query("UPDATE sludge_ikg_grease SET completed_date ='".date("Y-m-d")."' WHERE route_id = $_POST[route_id]");
$db->query("UPDATE sludge_list_of_grease SET status='completed',completed_date ='".date("Y-m-d")."' WHERE route_id=$_POST[route_id]");
//***************************MARK ROUTE COMPLETED*******************************************************
















?>