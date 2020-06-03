<?php
ini_set("display_errors",0);

$dbprefix = "iwp";

$collected = 0;
$coming_up = 0;
$route_me =0;
$code_red =0;
$account_table = $dbprefix."_accounts";
$data_table = $dbprefix."_data_table";
$pick_up_counts = array(
    "month"=>"",
    "count"=>0,    
    "aGGP"=>0.0,
    "accounts"=>""
);

function translate($mont){
    switch($mont){
        case 1:
            return "Jan";
            break;
        case 2:
            return "Feb";
            break;    
        case 3:
            return "Mar";
            break;
        case 4:
            return "Apr";
            break;
        case 5:
            return "May";
            break;
        case 6:
            return "Jun";
            break;
        case 7:
            return "Jul";
            break;
        case 8:
            return "Aug";
            break; 
        case 9:
            return "Sep";
            break;
        case 10:
            return "Oct";
            break;
        case 11:
            return "Nov";
            break;
        case 12:
            return "Dec";
            break;
    }
}


$month = date("m");
$year = date("Y");





if(isset($_POST['search_fac'])){
    
    foreach($_POST as $name=>$value){
        switch($name){
            case "fac1":
                if(isset($name)){                   
                    $arrField[]= $value;} 
                break;
            case "fac2":
                if(isset($name)){                    
                    $arrField[]=$value;}
                break;
            case "fac3":
            if(isset($name)){               
                $arrField[]=$value; }
                break;
            case "fac4":
            if(isset($name)){                
                $arrField[]=$value;}
                break;
            case "fac5":
            if(isset($name)){                
                $arrField[]=$value;}
                break;
            case "fac6":
            if(isset($name)){                
                $arrField[]=$value;}
                break;
            case "fac7":
            if(isset($name)){                
                $arrField[]=$value;}
                break;
            case "fac8":
            if(isset($name)){                
                $arrField[]=$value;}
                break;
            case "fac9":
            if(isset($name)){                
                $arrField[]=$value;}
                break;
            case "from":
            if(strlen($value)>0){
                $scheduled[] = " freight_accounts.created >= '$value'  ";
                $start[] =" date_of_pickup >= '$value'";
            }
            break;
            case "to":
            if(strlen($value)>0){
                $scheduled[] = "  freight_accounts.created <= '$value'  ";
                $start[] =" date_of_pickup <= '$value'"; 
            }
            break;
              
        }
    }
    $criter ="";
    if(!empty($arrField)){
        $criter =  " AND division IN( ".implode(" ,", $arrField)." ) ";
    }
    
    $sched = "";
    if(!empty($scheduled)){
        $sched = " AND (".implode(" AND ", $scheduled).")";
    }
    
    $when = "";
    if(!empty($scheduled)){
        $when = " AND (".implode(" AND ", $start).")";
    }
    
    
     for($i=1;$i<14;$i++){
        if($month ==0 ){
            $month=12;
            $year = $year -1;
        }
        $bar_ask = "SELECT SUM( freight_data_table.inches_to_gallons ) AS all_oil, AVG(freight_data_table.inches_to_gallons) as avrg, COUNT( DISTINCT ( freight_data_table.account_no ) ) AS dist_acc, MONTH( freight_data_table.date_of_pickup ) AS month, YEAR(freight_data_table.date_of_pickup) as year, COUNT(freight_data_table.account_no) as pickups FROM freight_data_table LEFT JOIN freight_accounts ON freight_accounts.account_ID = freight_data_table.account_no WHERE 1 $criter AND MONTH( date_of_pickup ) = '$month' AND YEAR( date_of_pickup ) =  '$year'"; 
        $query = $db->query($bar_ask);
        //echo $bar_ask."<br/>";
        $pick_up_counts[] = array(        
            "month"=>translate($query[0]['month'])." ".$query[0]['year'],
            "count"=>$query[0]['pickups'],            
            "aGGP"=>number_format($query[0]['avrg'],2),
            "accounts"=>$query[0]['dist_acc']
        );
        
        $data_oil[] = array (
            "month"=>translate($query[0]['month'])." ".$query[0]['year'],
            "gallons"=>$query[0]['all_oil']
        );
        
        $month = $month - 1;
    } 
      
    
     
    
     $c_r = "SELECT freight_scheduled_routes.*,freight_accounts.status,freight_accounts.division FROM freight_scheduled_routes INNER JOIN freight_accounts ON freight_accounts.account_ID = freight_scheduled_routes.account_no WHERE route_status in ('scheduled','enroute') AND freight_accounts.status='active' AND freight_scheduled_routes.code_red =1 AND scheduled_start_date !='0000-00-00' AND scheduled_start_date >'2015-06-01";//select all schedules that are scheduled or enroute and in code red and account is active
     
    $enroute = "SELECT freight_scheduled_routes.schedule_id,freight_accounts.status FROM freight_scheduled_routes INNER JOIN freight_accounts ON freight_accounts.account_ID = freight_scheduled_routes.account_no WHERE route_status='enroute' AND freight_accounts.status='active' AND freight_scheduled_routes.route_id IS NOT NULL AND scheduled_start_date !='0000-00-00'AND scheduled_start_date >'2015-06-01'";//select all stops that are enroute 
    
    $rest = "SELECT freight_scheduled_routes.account_no,freight_accounts.avg_gallons_per_Month/freight_accounts.barrel_capacity as per_full FROM freight_scheduled_routes LEFT JOIN freight_accounts on freight_scheduled_routes.account_no = freight_accounts.account_ID WHERE route_status='scheduled' AND code_red !=1 AND freight_accounts.status='active'AND scheduled_start_date !='0000-00-00' AND scheduled_start_date >'2015-06-01";//select accounts who's oil level match criteria below and are not in code red and are scheduled for pickup
    
    $other = $db->query($rest);
    if(count($other)>0){
        foreach($other as $chk){
            if($chk['per_full']>0 && $chk['per_full']<.25){
                $collected++;
            } else if ($chk['per_full']>=.25 && $chk['per_full']<.5){
                $coming_up++;
            } else if($chk['per_full']>=.5 && $chk['per_full']<.75){
                $route_me++;
            }
        }
    }
    
    $enr = $db->query($enroute);      
    $codes = $db->query($c_r);
    
    
    
    
    
} else {//default
     
     $bar_ask = "SELECT * FROM ( 
                                    SELECT MONTH( date_of_pickup ) AS month , 
                                    YEAR( date_of_pickup ) AS year, 
                                    SUM(inches_to_gallons ) AS all_oil, 
                                    COUNT( * ) AS pickups, 
                                    SUM( inches_to_gallons ) / COUNT( * ) AS avrg, 
                                    count( DISTINCT ( account_no ) ) AS dist_acc 
                                        FROM freight_data_table AS l 
                                        WHERE date_of_pickup > DATE_SUB( NOW( ) , INTERVAL 1 YEAR ) GROUP BY MONTH( date_of_pickup ) ASC ORDER BY YEAR( date_of_pickup ) DESC , MONTH( date_of_pickup ) DESC 
) AS a  UNION ALL
                            SELECT * FROM (
                                   SELECT MONTH( date_of_pickup ) AS month , 
                                   YEAR( date_of_pickup ) AS year, 
                                   SUM( inches_to_gallons ) AS all_oil, 
                                   COUNT( * ) AS pickups, SUM( inches_to_gallons ) / COUNT( * ) AS avrg, 
                                   count( DISTINCT (account_no) ) AS dist_acc 
                                        FROM freight_data_table WHERE YEAR( date_of_pickup ) = '$year' AND MONTH(date_of_pickup)  = '$month') AS b ORDER BY year ASC , MONTH ASC ";
   
    $pick_up = $db->query($bar_ask);
    
    if(count($pick_up)>0){
    foreach($pick_up as $data){
            $pick_up_counts[] = array(
                "month"=>translate($data['month'])." ".$data['year'],
                "count"=>$data['pickups'],               
                "aGGP"=>number_format($data['avrg'],2),
                "accounts"=>$data['dist_acc']
            );
            
            $data_oil[] = array (
                "month"=>translate($data['month'])." ".$data['year'],
                "gallons"=>$data['all_oil']
            );
            
        }
     }     
    
    
    
    $c_r = "SELECT freight_scheduled_routes.schedule_id,freight_accounts.status,freight_accounts.division FROM freight_scheduled_routes INNER JOIN freight_accounts ON freight_accounts.account_ID = freight_scheduled_routes.account_no WHERE route_status in ('scheduled','enroute') AND freight_accounts.status='active' AND freight_scheduled_routes.code_red =1 AND scheduled_start_date !='0000-00-00' AND scheduled_start_date >'2015-06-01'";//select all schedules that are scheduled or enroute and in code red and account is active
     
    $enroute = "SELECT freight_scheduled_routes.schedule_id,freight_accounts.status FROM freight_scheduled_routes INNER JOIN freight_accounts ON freight_accounts.account_ID = freight_scheduled_routes.account_no WHERE route_status='enroute' AND freight_accounts.status='active' AND freight_scheduled_routes.route_id IS NOT NULL AND scheduled_start_date !='0000-00-00'AND scheduled_start_date >'2015-06-01'";//select all stops that are enroute 
    
    $rest = "SELECT freight_scheduled_routes.account_no,freight_accounts.avg_gallons_per_Month/freight_accounts.barrel_capacity as per_full FROM freight_scheduled_routes LEFT JOIN freight_accounts on freight_scheduled_routes.account_no = freight_accounts.account_ID WHERE route_status='scheduled' AND code_red !=1 AND freight_accounts.status='active' AND scheduled_start_date !='0000-00-00'AND scheduled_start_date >'2015-06-01'";//select accounts who's oil level match criteria below and are not in code red and are scheduled for pickup and are active;
    
    $other = $db->query($rest);
    if(count($other)>0){
        foreach($other as $chk){
            if($chk['per_full']>0 && $chk['per_full']<.25){
                $collected++;
            } else if ($chk['per_full']>=.25 && $chk['per_full']<.50){
                $coming_up++;
            } else if($chk['per_full']>=.50 && $chk['per_full']<.75){
                $route_me++;
            }
        }
    }
    
    
    
    $enr = $db->query($enroute);      
    $codes = $db->query($c_r);
     
    
}






?>

<script type="text/javascript">

  // Load the Visualization API and the piechart package.
  google.load('visualization', '1.0', {'packages':['corechart']});

  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawChart);

  // Callback that creates and populates a data table,
  // instantiates the pie chart, passes in the data and
  // draws it.
  function drawChart() {
      var items2 = [
          ['Accounts Enroute', <?php echo count($enr);  ?>],
          ['Recently Serviced', <?php echo $collected; ?>],
          ['Coming Up', <?php echo $coming_up; ?>],
          ['Route Me',<?php echo $route_me; ?>],
          ['Code Red', <?php echo count($codes); ?>]
      ];
    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Month');
    data.addColumn('number', 'Pickup');
    data.addRows(items2);
    
    // Set chart options
    var options = {
         title:"Scheduled Pickups",
         width: 600,
         height: 400,
         colors: ['#ff69b4', '#00ff00', '#ffff00', '#FF6600','#FF0000']
    };
    
    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('pie'));        
    chart.draw(data, options);
    
    
        
    
    var items = [
            ['<?php echo $pick_up_counts[0]['month']; ?>', <?php echo $pick_up_counts[0]['count']; ?>,      <?php echo $pick_up_counts[0]['aGGP']; ?>,<?php echo $pick_up_counts[0]['accounts']; ?>],
          ['<?php echo $pick_up_counts[1]['month']; ?>',<?php echo $pick_up_counts[1]['count']; ?>,      <?php echo $pick_up_counts[1]['aGGP']; ?>,<?php echo $pick_up_counts[1]['accounts']; ?>],
          ['<?php echo $pick_up_counts[2]['month']; ?> ', <?php echo $pick_up_counts[2]['count']; ?>,    <?php echo $pick_up_counts[2]['aGGP']; ?>,<?php echo $pick_up_counts[2]['accounts']; ?>],
          ['<?php echo $pick_up_counts[3]['month']; ?> ', <?php echo $pick_up_counts[3]['count']; ?>,      <?php echo $pick_up_counts[3]['aGGP']; ?>,<?php echo $pick_up_counts[3]['accounts']; ?>],
          ['<?php echo $pick_up_counts[4]['month']; ?> ', <?php echo $pick_up_counts[4]['count']; ?>,    <?php echo $pick_up_counts[4]['aGGP']; ?>,<?php echo $pick_up_counts[4]['accounts']; ?>],
          ['<?php echo $pick_up_counts[5]['month']; ?> ',<?php echo $pick_up_counts[5]['count']; ?>,    <?php echo $pick_up_counts[5]['aGGP']; ?>,<?php echo $pick_up_counts[5]['accounts']; ?>],
          ['<?php echo $pick_up_counts[6]['month']; ?> ', <?php echo $pick_up_counts[6]['count']; ?>,    <?php echo $pick_up_counts[6]['aGGP']; ?>,<?php echo $pick_up_counts[6]['accounts']; ?>],
          ['<?php echo $pick_up_counts[7]['month']; ?> ',<?php echo $pick_up_counts[7]['count']; ?>,   <?php echo $pick_up_counts[7]['aGGP']; ?>,<?php echo $pick_up_counts[7]['accounts']; ?>],
          ['<?php echo $pick_up_counts[8]['month']; ?> ',<?php echo $pick_up_counts[8]['count']; ?>,    <?php echo $pick_up_counts[8]['aGGP']; ?>,<?php echo $pick_up_counts[8]['accounts']; ?>],
          ['<?php echo $pick_up_counts[9]['month']; ?> ',<?php echo $pick_up_counts[9]['count']; ?>,    <?php echo $pick_up_counts[9]['aGGP']; ?>,<?php echo $pick_up_counts[9]['accounts']; ?>],
          ['<?php echo $pick_up_counts[10]['month']; ?> ', <?php echo $pick_up_counts[10]['count']; ?>,   <?php echo $pick_up_counts[10]['aGGP']; ?>,<?php echo $pick_up_counts[10]['accounts']; ?>],
          ['<?php echo $pick_up_counts[11]['month']; ?>',<?php echo $pick_up_counts[11]['count']; ?>,<?php echo $pick_up_counts[11]['aGGP']; ?>, <?php echo $pick_up_counts[11]['accounts']; ?>],
          ['<?php echo $pick_up_counts[12]['month']; ?>',<?php echo $pick_up_counts[12]['count']; ?>,<?php echo $pick_up_counts[11]['aGGP']; ?>, <?php echo $pick_up_counts[12]['accounts']; ?>]
          
         
      ];    
      
    var data2 = new google.visualization.DataTable({
        
        
    });
    
    data2.addColumn('string','Months');    
    data2.addColumn('number','PickUps');
    data2.addColumn('number','aGGP');
    data2.addColumn('number','Acounts');
    data2.addRows(items.reverse());
   
   
    // Set chart options
    var options = {
        title:"Oil Statistics",
        width:700,
        height:800,
        colors: ['red', 'blue', 'green', 'yellow','pink'],
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: false,
        
        bars: 'vertical'


    };
   
    
    
    var chart2 = new google.visualization.BarChart(document.getElementById('bar'));
    chart2.draw(data2,options);
    
    
     $.each(items2.reverse(),function(key,value){
        $("#pienumbers tr:first").after("<tr><td>"+value[0]+"</td><td>"+value[1]+"</td></tr>");   
     });
     
     $.each(items.reverse(),function(key,value){
        $("#barnumbers tr:first").after("<tr><td style='font-size:12px;'>"+value[0]+"<td style='font-size:14px;'>"+value[1]+"</td><td style='font-size:14px;'>"+value[2]+"</td><td style='font-size:14px;'>"+value[3]+"</td></tr>");   
     });     
     
     var items3 = [
    <?php    
        foreach($data_oil as $oilz){
            $string .= "['$oilz[month]',$oilz[gallons]],";
        }
        echo trim($string,",");
        
    ?>
  ]
  
  
  var data3 = new google.visualization.DataTable({
        
        
    });
    
    data3.addColumn('string','Months');    
    data3.addColumn('number','Gallons');
    data3.addRows(items3.reverse());
   
   
    // Set chart options
    var options3 = {
        title:"Oil Collection",
        width:700,
        height:800,
        colors: ['red', 'blue'],
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: false,        
        bars: 'vertical'


    };
   
    
    
    var chart3 = new google.visualization.BarChart(document.getElementById('bar2'));
    chart3.draw(data3,options3);
  
  
  $.each(items3.reverse(),function(key,value){
        $("#oil_numbers tr:first").after("<tr><td style='font-size:12px;'>"+value[0]+"</td><td style='font-size:14px;'>"+value[1]+"</td></tr>");   
     });
     
  }
  
  	var pieData = [
				{
					value: 300,
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: "Red"
				},
				{
					value: 50,
					color: "#46BFBD",
					highlight: "#5AD3D1",
					label: "Green"
				{
					value: 100,
				},
					color: "#FDB45C",
					highlight: "#FFC870",
					label: "Yellow"
				},
				{
					value: 40,
					color: "#949FB1",
					highlight: "#A8B3C5",
					label: "Grey"
				},
				{
					value: 120,
					color: "#4D5360",
					highlight: "#616774",
					label: "Dark Grey"
				}

			];

			window.onload = function(){
				var ctx = document.getElementById("chart-area").getContext("2d");
				window.myPie = new Chart(ctx).Pie(pieData);
			};

  // Default to current month removed new accounts - collected to recently serviced 
</script>

    <style type="text/css">
     table#facs td{
        vertical-align:top;
        text-align:left;
     }
    #over_account,#over_locations,#not_collected{
        width: 210px;height:130px;float:left;overflow:hidden;padding:0px 0px 0px 0px;font-size:95%
        ;background:white;margin-top:10px;margin-right:10px;
       
    }
    
    .datatable{
        width:100%;
    }
    
    .datatable td{
        padding:0px 0px 0px 0px;        
    }
    
    .datatable tr:first-child td { 
        text-align:center;
        font-weight:bold;
    }
    
    #barnumbers td{
        padding:0px 0px 0px 0px;
        padding-top:10px;
        padding-bottom:10px;
        vertical-align:top;
        
    }
    
    li {
        text-align:left;
    }
    </style>
    </head>
    <body>
    <?php
    
    ?>
<div id="info_bar" style="width:100%;background:rgb(242,242,242);min-height:150px;height:auto;">
    <div id="fullx" style="width: 900px;height:130px;background:transparent;margin:auto;height:auto;">     
        
        
        <div id="over_locations">
           
        </div>
        
    
        
        <div id="searchx" style="width: 650px;min-height:130px;background:rgb(242,242,242);float:left;margin-top:10px;height:auto;" class="datatable">     
                   
                <form action="management.php?task=overview" method="post">
            <table style="width: 100px;margin-left:24px;background:white;height:100px;width:100%;" id="facs">
                <tr><td colspan="4" style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;Sort By</span></td></tr>
                <tr>
                    <td><input class="all" type="checkbox"/>&nbsp;All UC </td>
                    <td><input value="8"  <?php if(isset($_POST['fac1'])){ echo "checked='checked'"; } ?>   name="fac1" type="checkbox" class="fac"/>&nbsp;Arizona Division(4)</td>
                    <td><input value="23"  <?php if(isset($_POST['fac2'])){ echo "checked='checked'"; } ?>   name="fac2" type="checkbox" class="fac"/>&nbsp;Coachella Division (UD)</td>
                    <td><input value="24" <?php if(isset($_POST['fac3'])){ echo "checked='checked'"; } ?>   name="fac3" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC)</td>
                </tr>
                <tr>
                    <td><input value="33" <?php if(isset($_POST['fac5'])){ echo "checked='checked'"; } ?>   name="fac5" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Chuck)</td>
                    <td><input value="25"  <?php if(isset($_POST['fac6'])){ echo "checked='checked'"; } ?>   name="fac6" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Ramon)</td>
                    <td><input value="31"  <?php if(isset($_POST['fac7'])){ echo "checked='checked'"; } ?>   name="fac7" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Tony)</td>
                    <td><input value="32"  <?php if(isset($_POST['fac4'])){ echo "checked='checked'"; } ?>   name="fac4" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Chato)</td>
                </tr>
                <tr>
                    <td><input value="10"  <?php if(isset($_POST['fac10'])){ echo "checked='checked'"; } ?>  name="fac10" type="checkbox" class="fac selma"/>&nbsp;V-BAK</td>
                    <td><input value="11"  <?php if(isset($_POST['fac11'])){ echo "checked='checked'"; } ?>  name="fac11" type="checkbox" class="fac selma"/>&nbsp;V-Fresno</td>
                    <td><input value="22"  <?php if(isset($_POST['fac8'])){ echo "checked='checked'"; } ?>   name="fac8" type="checkbox" class="fac"/>&nbsp;San Diego Division (US)</td>
                    <td><input value="5"  <?php if(isset($_POST['fac9'])){ echo "checked='checked'"; } ?>    name="fac9" type="checkbox"  class="fac" id="allselma"/>&nbsp;Selma (V)</td>
                </tr>
                <tr>
                    <td><input value="12"  <?php if(isset($_POST['fac12'])){ echo "checked='checked'"; } ?>   name="fac12" type="checkbox" class="fac selma"/>&nbsp;V-North</td>
                    <td><input value="13"  <?php if(isset($_POST['fac13'])){ echo "checked='checked'"; } ?>   name="fac13" type="checkbox" class="fac selma"/>&nbsp;V-Visalia</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                
                <tr><td colspan="4">Date range for pie graph</td></tr>
                <tr><td colspan="4">
                    <input type="text" value="<?php echo $_POST['from']; ?>" placeholder="From" name="from" id="from" style="border-radius: 10px;"/>
                    <input type="text" value="<?php echo $_POST['to']; ?>" placeholder="To" name="to" id="to" /></td></tr>
                    <tr><td colspan="4" style="text-align: right;"><input type="submit" name="search_fac" value="Search Now" /></td></tr>
            </table>
            </form>
            <div style="clear: both;"></div>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>    



<div id="chart_div" style="width:100%;margin:auto;margin-top:10px;min-height:750px;height:auto;">

    <div id="pie" style="width: 70%;height:400px;float:left;padding:0px 0px 0px 0px;"></div>
    
    <div class="actualNumbers" style="width: 30%;float:left;min-height:300px;padding-top:50px;height:auto;">
        <table id="pienumbers" style="width: 50%;position:relative;top:20px;left:25%;">
            <tr style="background: green;color:white;"><td>Pickup Status</td><td>Amount</td></tr>
        </table>
    </div>
    
    
    <div id="bar" style="width: 600px;height:700px;float:left;"></div>
    
    
    
    <div class="actualNumbers" style="width: 300px;float:right;min-height:300px;padding-top:10px;height:auto;margin-left:5px;">
        <table id="barnumbers" style="width: 100%;">
            <tr style="background: green;color:white;"><td>Month</td><td>Pick Ups</td><td>aGGP</td><td>Accounts</td></tr>
           
        </table>
    </div>
    
   <div style="clear: both;"></div>
</div>

<div id="oils" style="width:100%;margin:auto;margin-top:10px;min-height:750px;height:auto;">
    <div id="bar2" style="width: 600px;height:700px;float:left;"></div>
     <div class="actualNumbers" style="width: 30%;float:right;min-height:300px;padding-top:50px;height:auto;">
        <table id="oil_numbers" style="width: 50%;position:relative;top:20px;left:25%;">
             <tr style="background: green;color:white;"><td>Month</td><td>Oil</td></tr>
        </table>
    </div>
</div>

 <div style="clear: both;"></div>
 <script>
 $(".all").click(function(){
    if( $(this).is(":checked")  ) {
      $('.uc').each(function() { //loop through each checkbox
            this.checked = true;  //select all checkboxes with class "checkbox1"              
      }); 
    } else {
        $('.uc').each(function() { //loop through each checkbox
            this.checked = false;  //select all checkboxes with class "checkbox1"              
      }); 
    }
 });
 
 $("#all_selma").click(function(){
    if( $(this).is(":checked") ){
        $('.selma').each(function() { //loop through each checkbox
            this.checked = true;  //select all checkboxes with class "checkbox1"              
      });
    } else {
        $('.selma').each(function() { //loop through each checkbox
            this.checked = false;  //select all checkboxes with class "checkbox1"              
      }); 
    }
 });
 
 $("#over_locations").css('background','url(../img/loading.gif) no-repeat center center');

 
 <?php
 if(isset($_POST['search_fac'])){
    ?>
     $("#over_locations").css('background','url(../img/loading.gif) no-repeat center center');
     $.post("management/get_over.php",{searched:1,facs:"<?php 
      
      if(!empty($arrField)){
        $facs = " AND division IN( ".implode(" ,", $arrField)." )";
      }
      echo $facs
     ?>"<?php 
     if(!empty($start)){
        echo " ,dates:  \"AND ".implode(" AND ",$start )."\"";
     }
      
     ?><?php 
        if(!empty($scheduled)){
            echo ",created:  \"AND ".implode(" AND ",$scheduled )."\"";
        }
     ?>},function(data){
            alert(data);
            $("#over_locations").html(data);
             $("#over_locations").css('background','none');
     });
    <?php
 }else {
    ?>
         $.post("management/get_over.php",function(data){
                $("#over_locations").html(data);
                 $("#over_locations").css('background','none');
         });
    <?php
 }   
 
 ?>
 
 </script>
<div style="clear: both;"></div>