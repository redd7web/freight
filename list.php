<?php
include "protected/global.php";
error_reporting(E_ALL);

$sql="SELECT * FROM sludge_ikg_utility ORDER BY route_id";

if ($result=mysqli_query($sql))
  {
  // Get field information for all fields
  while ($fieldinfo=mysqli_fetch_field($result))
    {
    echo "fsfgd";
    }
  // Free result set
  mysqli_free_result($result);
}
?>