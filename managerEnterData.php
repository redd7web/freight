<?php
include "source/css.php";
include "source/scripts.php";

?>
<style type="text/css">
body{
    background:rgb(242,242,242);
}
</style>
<meta charset="UTF-8"/>
<div style=" display: block;width:400px;height:auto;margin:auto;" class="enter_pickup_data_box" id="enter_pickup_data_box">

<div class="pickup_location_view" style="margin:10px;margin-bottom:30px;width:100%;">
<div style="font-weight:bold;width:250px; margin:auto;margin-bottom:20px;">Report Completed Oil Pickup</div>
</div>
<div style="width:400px;margin:auto;" id="box">
<form id="enter_pickup_data_form" name="enter_pickup_data_form" action="javascript:do_nothing();"><input name="pickup_id" id="pickup_id" value="633885" type="hidden"/><input name="location_id" id="location_id" value="24847" type="hidden"/>

<table style="width: 100%;"><tbody><tr class="table_row">
<td class="table_label" align="right" nowrap="" valign="top">Total Gallons Retrieved</td>
<td class="table_data" align="left" valign="top"><input name="gallons_retrieved" id="gallons_retrieved" size="7" onchange="update_stuff();" value="0.0"> <span class="mini">( 30.3 Expected )</span></td>
</tr>
<tr class="table_row">
<td class="table_label" align="right" nowrap="" valign="top">Residual Content</td>
<td class="table_data" align="left" valign="top"><input name="residual_content" size="7" value="0.0"> <span class="mini">( Oil Left Behind )</span></td>
</tr>
<tr class="table_row">
<td class="table_label" align="right" nowrap="" valign="top"><span title="Infomation from the driver about the pickup.">Field Report</span></td>
<td class="table_data" align="left" valign="top"><textarea name="field_report" rows="5" cols="40"></textarea></td>
</tr>
<tr class="table_row">
<td class="table_label" align="right" nowrap="" valign="top">Now <input name="use_now" value="y" checked="" type="radio"></td>
<td class="table_data" align="left" valign="top"></td>
</tr>
<tr class="table_row">
<td class="table_label" align="right" nowrap="" valign="top"> OR </td>
<td class="table_data" align="left" valign="top"></td>
</tr>
<tr class="table_row">
<td class="table_label" align="right" nowrap="" valign="top">Date of Pickup<input name="use_now" value="n" type="radio"></td>
<td class="table_data" align="left" valign="top">
<input type="textfield" id="pickupdate"  />
</td>
</tr><td style="padding:30px;" colspan="2">
    <button style="float:right;">Cancel</button>
    <button style="margin-left:30px;float:right;" id="do_zone_button">Update</button></td></tr>
</tbody></table></form></div>
</div>
<script>
$("input#pickupdate").datepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
</script>