<?php
     $db->query("UPDATE freight_ikg_manifest_info SET can_close = $_GET[close] WHERE route_id=$_GET[route_id]");
     echo $_GET['close'];
?>