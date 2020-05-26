
<?php
include "source/scripts.php";
?>
<form action="../machforms/machform/index.php" method="post" id="auto">
<input type="hidden" value="INET@iwpusa.com" name="admin_username" readonly=""/>
<input type="hidden" value="Formlogin1" name="admin_password" readonly="" />
</form>
<script>
$("document").ready(function(){
   $("#auto").submit(); 
});
</script>