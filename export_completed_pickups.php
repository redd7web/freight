<?php
include "protected/global.php";
?>
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/auto.css"/>
<style type="text/css">
div.ui-datepicker{
 font-size:10px;
}
body{
    font-family:arial;
}
</style>
<form action="export_account_completed_route.php" method="post" target="_blank">
<table style="width: 200px;margin-top:4px;">
    <tr><td style="text-align:right;">From: </td><td style="text-align:left;"><input id="from" name="from" type="text"/></td></tr>
    <tr><td style="text-align:right;">To: </td><td  style="text-align:left;"><input id="to" name="to" type="text"/></td></tr>
    <tr><td colspan="2" style="text-align: right;"><input type="submit" name="export_now" value="Export now"/></td></tr>
</table>
<input type="hidden" name="account_no" value="<?php echo $_GET['account_no']; ?>"/>
</form>
<script>
$("#to,#from").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true,yearRange: "1:c+10"});
</script>