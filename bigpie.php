<?php
include "protected/global.php";
ini_set("display_errors",0);
$ikg_info = new Grease_IKG($_GET['route_id']);
//print_r($ikg_info);
$uv= 0;
$truck_cap = $db->query("SELECT max_capacity FROM freight_truck_id WHERE truck_id = $ikg_info->truck");

$collected = $db->query("SELECT coalesce(SUM(inches_to_gallons) ,0) as so_far FROM freight_grease_data_table WHERE route_id = $ikg_info->route_id");
$gv = $db->query("SELECT coalesce(SUM(volume),NULL,0) as volume FROM freight_grease_traps WHERE route_status ='enroute' AND grease_route_no	=$ikg_info->route_id");


if($ikg_info->trailer != 0 && strlen($ikg_info->trailer)>0)   {
    $trailer_info = $db->query("SELECT max_capacity FROM freight_trailers WHERE truck_id = $ikg_info->trailer");
    if(count($trailer_info)>0){
        $uv = $uv + $trailer_info[0]['max_capacity'];
        
    }
}

$uv += $truck_cap[0]['max_capacity'];


if($ikg_info->number_days_route>1){
    $uv = $uv *$ikg_info->number_days_route;
}





                            
$ih = round($gv[0]['volume']+$collected[0]['so_far']);
//echo $ih;
?>

<link rel="stylesheet" href="plugins/jqwidgets-ver3.4.0/jqwidgets/styles/jqx.base.css" type="text/css" />
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<link rel="stylesheet" href="../../jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="plugins/jqwidgets-ver3.4.0/scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets-ver3.4.0/scripts/demos.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets-ver3.4.0/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets-ver3.4.0/jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets-ver3.4.0/jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets-ver3.4.0/jqwidgets/jqxgauge.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets-ver3.4.0/jqwidgets/jqxtooltip.js"></script>    
    <script type="text/javascript">
        $(document).ready(function () {
            var labels = { visible: false, position: 'outside' };
              
            //Create jqxGauge
            $('#gauge').jqxGauge({
                max:<?php echo $uv; ?>,
                ranges: [
                         { startValue: 0, endValue: <?php echo $ih; ?>, style: { fill: '#00FF00', stroke: '#00FF00' }, startDistance: '5%', endDistance: '5%', endWidth: 18, startWidth: 18 },
                         { startValue: 0, endValue: <?php echo round($uv *.25); ?>, style: { fill: '#FF0000', stroke: '#FF0000' }, startDistance: '5%', endDistance: '5%', endWidth: 10, startWidth: 10 },
                         { startValue: <?php echo round($uv *.25); ?>, endValue: <?php echo round($uv *.5); ?>, style: { fill: '#0000FF', stroke: '#0000FF' }, startDistance: '5%', endDistance: '5%', endWidth: 10, startWidth: 10 },
                         { startValue: <?php echo round($uv *.5); ?>, endValue: <?php echo round($uv *.75); ?>, style: { fill: '#00FFFF', stroke: '#00FFFF' }, startDistance: '5%', endDistance: '5%', endWidth: 10, startWidth: 10 },
                          { startValue: <?php echo round($uv *.75); ?>, endValue: <?php echo round($uv); ?>, style: { fill: '#000000', stroke: '#000000' }, startDistance: '5%', endDistance: '5%', endWidth: 10, startWidth: 10 }
                         
                ],
                cap: { radius: 0.04 },
                caption: { offset: [0, -25], value: 'Collection Summary', position: 'bottom' },
                value: 0,
                style: { stroke: '#ffffff', 'stroke-width': '1px', fill: '#ffffff' },
                animationDuration: 1500,
                colorScheme: 'scheme04',
                labels: labels,
                ticksMinor: { interval: 1000, size: '5%'  },
                ticksMajor: { interval: 8300, size: '15%' },
                width:200,
                height:200
            });
            
            // set gauge's value.
            $('#gauge').jqxGauge('setValue', <?php echo $collected[0]['so_far'];   ?>);
            $('#gauge .jqx-gauge-range').eq(0).jqxTooltip({
                content: 'Estimated Left + Net Collected : <?php echo $ih; ?>',
                position: 'mouse'
            });
            $('#gauge .jqx-gauge-range').eq(1).jqxTooltip({
                content: '0 - <?php echo $uv *.25 ?>',
                position: 'mouse'
            });
            
            $('#gauge .jqx-gauge-range').eq(2).jqxTooltip({
                content: '<?php echo $uv *.25 ?> - <?php echo $uv *.5 ?>',
                position: 'mouse'
            });
            
            $('#gauge .jqx-gauge-range').eq(3).jqxTooltip({
                content: '<?php echo $uv *.5 ?> - <?php echo $uv *.75 ?>',
                position: 'mouse'
            });
            
            $('#gauge .jqx-gauge-range').eq(4).jqxTooltip({
                content: '<?php echo $uv *.75 ?> - <?php echo $uv;?>',
                position: 'mouse'
            });
            
            $("#gauge path").eq(5).jqxTooltip({
               content:'<?php echo $collected[0]['so_far']; ?>',
               position: 'mouse'
            });
            
        });
        
     
        
    </script>
</head>
<body class='default'>
    <div class="demo-gauge" style="width: 210px;float:left;">
        <div id="gauge" style="float: left;"></div>
    </div>
    <div id="legend" style="float: left;width:210px;height:200px;">
    <table style="width: 100%;">
    <tr><td><div class="box" style="width: 10px;height:10px;background:red"> </div></td><td>0 - <?php echo $uv *.25 ?></td></tr>
    <tr><td><div class="box" style="width: 10px;height:10px;background:blue    "> </div></td><td><?php echo $uv *.25 ?> - <?php echo $uv *.50 ?></td></tr>
    
    <tr><td><div class="box" style="width: 10px;height:10px;background:#00FFFF    "> </div></td><td><?php echo $uv *.50 ?> - <?php echo $uv *.75 ?></td></tr>
     <tr><td><div class="box" style="width: 10px;height:10px;background:#000000    "> </div></td><td><?php echo $uv *.75 ?> - <?php echo $uv; ?></td></tr>
     <tr><td><div class="box" style="width: 10px;height:10px;background:#D02841" title="Collected so far"></div></td><td><?php echo $collected[0]['so_far']; ?></td></tr>
     <tr><td><div class="box" style="width: 10px;height:10px;background:green    "> </div></td><td>Estimated Left + Net Collected = <?php echo number_format($ih,2); ?></td></tr>
     
    </table>
    </div>
</body>

</html>
 