<?php
include "source/scripts.php";
echo '<form id="ikg" method="post" action="grease_ikg.php"><input type="hidden" name="util_routes" value="'.$_GET['route_id'].'">
<input type="hidden" name="from_routed_grease_list" value="1"></form>';

?>

<script>
$("document").ready(function(){
    $("#ikg").submit();    
});
</script>