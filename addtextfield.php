
<?php

include "protected/global.php";
ini_set("display_errors",0);


//print_r($_GET);

$sched = $db->where("grease_route_no",$_GET['route_id'])->where("grease_no","$_GET[sched_id]")->get("freight_grease_traps","account_no,route_status,grease_no,grease_route_no,percent_split");//schedule info

$editable = $db->where('route_id',$_GET['route_id'])->get("freight_list_of_routes","status");

$nets = $db->where("route_id",$_GET['route_id'])->get("freight_ikg_grease");

if($editable[0]['route_status'] == "completed"){
    $read_only = "readonly=''";
}




echo "<tbody id='workid'>";

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



</script>