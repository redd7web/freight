
<?php

include "protected/global.php";
ini_set("display_errors",0);


//print_r($_GET);

$sched = $db->where("grease_route_no",$_GET['route_id'])->where("grease_no","$_GET[sched_id]")->get("sludge_grease_traps","account_no,route_status,grease_no,grease_route_no,percent_split");//schedule info

$editable = $db->where('route_id',$_GET['route_id'])->get("sludge_list_of_routes","status");

$nets = $db->where("route_id",$_GET['route_id'])->get("sludge_ikg_grease");

if($editable[0]['route_status'] == "completed"){
    $read_only = "readonly=''";
}




echo "<tbody id='workid'>";
echo "<tr>
    <td style='text-align:right;'>Net Collected</td>
    <td style='text-align:left;'><input type='text' id='net_weight' value='".$nets[0]['net_weight']."' /></td>
</tr>";
echo "<tr>
        <td  style='text-align:right;'>Percent Split</td>
        <td  style='text-align:left;'><input type='text' value='".$sched[0]['percent_split']."' placeholder='Percent Split' id='percent_split'/></td>\
     </tr>
     <tr>
        <td  style='text-align:right;'>Approx Collected</td>
        <td  style='text-align:left;'><input type='text' placeholder='Aprox. Collected' id='collected' /></td>
      </tr>
<tr><td  style='text-align:right;vertical-align:top;'>Est Lbs</td><td  style='text-align:left;'><input type='text' id='est_lbs' /></td></tr>
<tr><td  style='text-align:right;vertical-align:top;'>Departure</td><td  style='text-align:left;'><input type='text' id='departure'  /></td></tr>
<tr><td  style='text-align:right;vertical-align:top;'>Arrival</td><td  style='text-align:left;'><input type='text' id='arrival' /></td></tr>
<tr><td  style='text-align:right;vertical-align:top;'>Mileage</td><td  style='text-align:left;'><input type='text' id='mileage' /></td></tr>
<tr><td  style='text-align:right;vertical-align:top;'>Field Note</td><td  style='text-align:left;'><textarea id='field_notes' style='height:100px;'></textarea></td></tr><tr><td colspan='2' style='text-align:right;width:150px;'><input type='submit' id='pickup_complete' value='Complete & Print Receipt'/></td></tr>
";
echo "</tbody>";
 ?>
<script>
var net_collected = "<?php 
    if(strlen( trim($nets[0]['net_weight'] ) )  >0) { 
        echo $nets[0]['net_weight'];  
    } else { 
        echo "0"; 
    }   ?>";

$("input#arrival").datetimepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
$("input#departure").datetimepicker({dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true});
$("input#collected").val(  ($("input#percent_split").val()/100) *net_collected );
$("input#est_lbs").val( $("input#collected").val() /8.34  );

$("input#percent_split").change(function(){
    $("input#collected").val( ($(this).val()/100)  * net_collected  ); 
    $("input#est_lbs").val( $("input#collected").val()/8.34 );
});



</script>