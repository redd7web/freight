<style>
a:active{
    text-decoration:none;
}
a:visited{
    text-decoration:none;
}


</style>
<table style="width: 1200px;margin:auto;" id="dash_table">

<tr>

    <td style="text-align: center;">
    
        <table><tr><td><h1><a href="viewAccount.php?id=201" target="_blank">Frac Tank 1</a></h1><iframe rel="201" id="guage201" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=201" style="width: 280px;float:left;height:550px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe><br /></td></tr><tr><td><input class="row_count201" type="hidden" readonly="" value="<?php $check_complete = $db->query("SELECT DISTINCT(sludge_list_of_grease.route_id),sludge_list_of_grease.status FROM sludge_list_of_grease LEFT JOIN sludge_grease_data_table ON sludge_grease_data_table.route_id = sludge_list_of_grease.route_id WHERE sludge_list_of_grease.status='completed' AND (sludge_grease_data_table.account_no = 201 OR sludge_grease_data_table.facility_origin =201 ) GROUP BY sludge_list_of_grease.route_id");echo count($check_complete); ?>"/>
        <br />
        <span id="ft201"></span></td></tr></table>
    
    </td>
    <td style="text-align: center;">
    
        <table><tr><td><h1><a href="viewAccount.php?id=202" target="_blank">Frac Tank 2</a></h1><iframe rel="202"  id="guage202" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=202" style="width: 280px;float:left;height:550px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe></td></tr><tr><td><input class="row_count202"  type="hidden" readonly="" value="<?php $check_complete202 = $db->query("SELECT DISTINCT(sludge_list_of_grease.route_id),sludge_list_of_grease.status FROM sludge_list_of_grease LEFT JOIN sludge_grease_data_table ON sludge_grease_data_table.route_id = sludge_list_of_grease.route_id WHERE sludge_list_of_grease.status='completed' AND (sludge_grease_data_table.account_no = 202 OR sludge_grease_data_table.facility_origin =202 ) GROUP BY sludge_list_of_grease.route_id");echo count($check_complete202); ?>"/>
        <span id="ft202"></span></td></tr></table>
    </td>
    
    
    <td style="text-align: center;">
    
        <table><tr><td><h1><a href="viewAccount.php?id=203" target="_blank">Frac Tank 3</a></h1><iframe rel="203"  id="guage203" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=203" style="width: 280px;float:left;height:550px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe></td></tr><tr><td><input class="row_count203"  type="hidden" readonly="" value="<?php $check_complete3 = $db->query("SELECT DISTINCT(sludge_list_of_grease.route_id),sludge_list_of_grease.status FROM sludge_list_of_grease LEFT JOIN sludge_grease_data_table ON sludge_grease_data_table.route_id = sludge_list_of_grease.route_id WHERE sludge_list_of_grease.status='completed' AND (sludge_grease_data_table.account_no = 203 OR sludge_grease_data_table.facility_origin =203 ) GROUP BY sludge_list_of_grease.route_id");echo count($check_complete3); ?>"/>
        <span id="ft203"></span></td></tr></table>
    </td></tr>

<tr>
    <td style="text-align: center;">
    
    <table><tr><td><h1><a href="viewAccount.php?id=204" target="_blank">Frac Tank 4</a></h1><iframe rel="204"  id="guage204" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=204" style="width: 280px;float:left;height:550px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe></td></tr><tr><td><input class="row_count204"  type="hidden" readonly="" value="<?php $check_complete4 = $db->query("SELECT DISTINCT(sludge_list_of_grease.route_id),sludge_list_of_grease.status FROM sludge_list_of_grease LEFT JOIN sludge_grease_data_table ON sludge_grease_data_table.route_id = sludge_list_of_grease.route_id WHERE sludge_list_of_grease.status='completed' AND (sludge_grease_data_table.account_no = 204 OR sludge_grease_data_table.facility_origin =204 ) GROUP BY sludge_list_of_grease.route_id");echo count($check_complete4); ?>"/>
    <span id="ft204"></span></td></tr></table>
    </td>
    
    
    <td style="text-align: center;">
        <table><tr><td><h1><a href="viewAccount.php?id=205" target="_blank">Frac Tank 5</a></h1><iframe rel="205"  id="guage205" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=205" style="width: 280px;float:left;height:550px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe></td></tr><tr><td><input class="row_count205"  type="hidden" readonly="" value="<?php $check_complete5 = $db->query("SELECT DISTINCT(sludge_list_of_grease.route_id),sludge_list_of_grease.status FROM sludge_list_of_grease LEFT JOIN sludge_grease_data_table ON sludge_grease_data_table.route_id = sludge_list_of_grease.route_id WHERE sludge_list_of_grease.status='completed' AND (sludge_grease_data_table.account_no = 205 OR sludge_grease_data_table.facility_origin =205 ) GROUP BY sludge_list_of_grease.route_id");echo count($check_complete5); ?>"/>
    <span id="ft205"></span></td></tr></table>
    
    </td>
    <td style="text-align: center;">
    <table><tr><td><h1><a href="viewAccount.php?id=206" target="_blank">Frac Tank 6</a></h1><iframe rel="206"  id="guage206" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=206" style="width: 280px;float:left;height:550px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe></td></tr><tr><td><input class="row_count206"  type="hidden" readonly="" value="<?php $check_complete6 = $db->query("SELECT DISTINCT(sludge_list_of_grease.route_id),sludge_list_of_grease.status FROM sludge_list_of_grease LEFT JOIN sludge_grease_data_table ON sludge_grease_data_table.route_id = sludge_list_of_grease.route_id WHERE sludge_list_of_grease.status='completed' AND (sludge_grease_data_table.account_no = 206 OR sludge_grease_data_table.facility_origin =206 ) GROUP BY sludge_list_of_grease.route_id");echo count($check_complete6); ?>"/>
    <span id="ft206"></span></td></tr></table>
    </td></tr>


<tr>
    <td style="text-align: center;">
    
    <table><tr><td><h1><a href="viewAccount.php?id=207" target="_blank">Frac Tank 7</a></h1><iframe rel="207"  id="guage207" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=207" style="width: 280px;float:left;height:550px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe></td></tr><tr><td><input class="row_count207"  type="hidden" readonly="" value="<?php $check_complete7 = $db->query("SELECT DISTINCT(sludge_list_of_grease.route_id),sludge_list_of_grease.status FROM sludge_list_of_grease LEFT JOIN sludge_grease_data_table ON sludge_grease_data_table.route_id = sludge_list_of_grease.route_id WHERE sludge_list_of_grease.status='completed' AND (sludge_grease_data_table.account_no = 207 OR sludge_grease_data_table.facility_origin =207 ) GROUP BY sludge_list_of_grease.route_id");echo count($check_complete7); ?>"/>
    <span id="ft207"></span></td></tr></table>
    </td>
    
    <td style="text-align: center;">
    
    <table><tr><td><h1><a href="viewAccount.php?id=208" target="_blank">Frac Tank 8</a></h1><iframe rel="208"  id="guage208" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=208" style="width: 280px;float:left;height:550px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe></td></tr><tr><td><input class="row_count208"  type="hidden" readonly="" value="<?php $check_complete8 = $db->query("SELECT DISTINCT(sludge_list_of_grease.route_id),sludge_list_of_grease.status FROM sludge_list_of_grease LEFT JOIN sludge_grease_data_table ON sludge_grease_data_table.route_id = sludge_list_of_grease.route_id WHERE sludge_list_of_grease.status='completed' AND (sludge_grease_data_table.account_no = 208 OR sludge_grease_data_table.facility_origin =208 ) GROUP BY sludge_list_of_grease.route_id");echo count($check_complete8); ?>"/>
    <span id="ft208"></span></td></tr></table>
    
    </td>
    <td style="text-align: center;">
    
    <table><tr><td><h1><a href="viewAccount.php?id=209" target="_blank">Frac Tank 9</a></h1><iframe rel="209"  id="guage209" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=209" style="width: 280px;float:left;height:550px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe></td></tr><tr><td><input class="row_count209"  type="hidden" readonly="" value="<?php $check_complete9 = $db->query("SELECT DISTINCT(sludge_list_of_grease.route_id),sludge_list_of_grease.status FROM sludge_list_of_grease LEFT JOIN sludge_grease_data_table ON sludge_grease_data_table.route_id = sludge_list_of_grease.route_id WHERE sludge_list_of_grease.status='completed' AND (sludge_grease_data_table.account_no = 209 OR sludge_grease_data_table.facility_origin =209 ) GROUP BY sludge_list_of_grease.route_id");echo count($check_complete9); ?>"/>
    <span id="ft209"></span></td></tr></table>
    </td></tr>



<tr>
    <td style="text-align: center;">
        <table><tr><td><h1><a href="viewAccount.php?id=210" target="_blank">Frac Tank 210</a></h1><iframe rel="210"  id="guage210" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=210" style="width: 280px;float:left;height:550px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe></td></tr><tr><td><input class="row_count210"  type="hidden" readonly="" value="<?php $check_complete10 = $db->query("SELECT DISTINCT(sludge_list_of_grease.route_id),sludge_list_of_grease.status FROM sludge_list_of_grease LEFT JOIN sludge_grease_data_table ON sludge_grease_data_table.route_id = sludge_list_of_grease.route_id WHERE sludge_list_of_grease.status='completed' AND (sludge_grease_data_table.account_no = 210 OR sludge_grease_data_table.facility_origin =210 ) GROUP BY sludge_list_of_grease.route_id");echo count($check_complete10); ?>"/><span id="ft210"></span></td></tr></table>
    </td>
    <td style="text-align: center;">
        <table><tr><td><h1><a href="viewAccount.php?id=211" target="_blank">Frac Tank 211</a></h1><iframe rel="211"  id="guage211" src="plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=211" style="width: 280px;float:left;height:550px;border:0px solid #bbb;overflow:hidden;" scrolling="no" frameborder="0"></iframe></td></tr><tr><td><input class="row_count211"  type="hidden" readonly="" value="<?php $check_complete11 = $db->query("SELECT DISTINCT(sludge_list_of_grease.route_id),sludge_list_of_grease.status FROM sludge_list_of_grease LEFT JOIN sludge_grease_data_table ON sludge_grease_data_table.route_id = sludge_list_of_grease.route_id WHERE sludge_list_of_grease.status='completed' AND (sludge_grease_data_table.account_no = 211 OR sludge_grease_data_table.facility_origin =211 ) GROUP BY sludge_list_of_grease.route_id");echo count($check_complete11); ?>"/><span id="ft211"></span></td></tr></table>
    </td>
</tr>



</table>
<script>
function load_level(){
    
    $.get("entries_sludge.php",{tank:201},function(data){
         if( ( ( data *1)  != ( $(".row_count201").val() *1 ) ) && data !="-1" ){
            $("#guage201").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=201'); 
            $(".row_count201").val(data); 
         };
    });
    
    $.get("last_stop.php",{tank:201},function(data){
       $("#ft201").html(data); 
    });
    
    $.get("entries_sludge.php",{tank:202},function(data){
         if( ( ( data *1)  != ( $(".row_count202").val() *1 ) ) && data !="-1" ){
            $("#guage202").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=202'); 
            $(".row_count202").val(data); 
         };
    });
    
    $.get("last_stop.php",{tank:202},function(data){
       $("#ft202").html(data); 
    });
    
    $.get("entries_sludge.php",{tank:203},function(data){
         if( ( ( data *1)  != ( $(".row_count203").val() *1 ) ) && data !="-1" ){
            $("#guage203").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=203'); 
            $(".row_count203").val(data); 
         };
    });
    
    $.get("last_stop.php",{tank:203},function(data){
       $("#ft203").html(data); 
    });
    
    $.get("entries_sludge.php",{tank:204},function(data){
         if( ( ( data *1)  != ( $(".row_count204").val() *1 ) ) && data !="-1" ){
            $("#guage204").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=204'); 
            $(".row_count204").val(data); 
         };
    });
    
    $.get("last_stop.php",{tank:204},function(data){
       $("#ft204").html(data); 
    });
    
    $.get("entries_sludge.php",{tank:205},function(data){
         if( ( ( data *1)  != ( $(".row_count205").val() *1 ) ) && data !="-1" ){
            $("#guage205").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=205'); 
            $(".row_count205").val(data); 
         };
    });
    
    $.get("last_stop.php",{tank:205},function(data){
       $("#ft205").html(data); 
    });
    
    $.get("entries_sludge.php",{tank:206},function(data){
         if( ( ( data *1)  != ( $(".row_count206").val() *1 ) ) && data !="-1" ){
            $("#guage206").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=206'); 
            $(".row_count206").val(data); 
         };
    });
    
    $.get("last_stop.php",{tank:206},function(data){
       $("#ft206").html(data); 
    });
    
    $.get("entries_sludge.php",{tank:207},function(data){
         if( ( ( data *1)  != ( $(".row_count207").val() *1 ) ) && data !="-1" ){
            $("#guage207").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=207'); 
            $(".row_count20").val(data); 
         };
    });
    
    $.get("last_stop.php",{tank:207},function(data){
       $("#ft207").html(data); 
    });
    
    $.get("entries_sludge.php",{tank:208},function(data){
         if( ( ( data *1)  != ( $(".row_count208").val() *1 ) ) && data !="-1" ){
            $("#guage208").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=208'); 
            $(".row_count208").val(data); 
         };
    });
    
    $.get("last_stop.php",{tank:208},function(data){
       $("#ft208").html(data); 
    });
    
    $.get("entries_sludge.php",{tank:209},function(data){
         if( ( ( data *1)  != ( $(".row_count209").val() *1 ) ) && data !="-1" ){
            $("#guage209").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=209'); 
            $(".row_count209").val(data); 
         };
    });
    
    $.get("last_stop.php",{tank:209},function(data){
       $("#ft209").html(data); 
    });
    
    $.get("entries_sludge.php",{tank:210},function(data){
         if( ( ( data *1)  != ( $(".row_count210").val() *1 ) ) && data !="-1" ){
            $("#guage210").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=210'); 
            $(".row_count210").val(data); 
         };
    });
    
    $.get("last_stop.php",{tank:210},function(data){
       $("#ft210").html(data); 
    });
    
    $.get("entries_sludge.php",{tank:211},function(data){
         if( ( ( data *1)  != ( $(".row_count211").val() *1 ) ) && data !="-1" ){
            $("#guage211").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=211'); 
            $(".row_count211").val(data); 
         };
    });
    
    $.get("last_stop.php",{tank:211},function(data){
       $("#ft211").html(data); 
    });
    
}

$("#dash_table").on("click",".ikg_grease",function(){
   $(this).submit();
});

$(document).ready(function(){
    setInterval('load_level()',10000);
});
</script>