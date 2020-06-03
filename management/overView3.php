<?php


ini_set("display_errors",1);

$dbprefix = "gt";

$collected = 0;
$coming_up = 0;
$route_me =0;
$code_red =0;
$account_table = $dbprefix."_accounts";
$data_table = $dbprefix."_grease_data_table";
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

$criter ="";
$sched = "";
$when = "";

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
            case "fac10":
            if(isset($name)){
                $arrField[]=$value;}
            break;
            if(isset($name)){                
                $arrField[]=$value;}
                break;
            case "fac11":
            if(isset($name)){
                $arrField[] = $value;}
            break;
            case "fac12":
            if(isset($name)){
                $arrField[] = $value;}
            break;
            case "fac13":
            if(isset($name)){
                $arrField[] = $value;}
            break;
            case "fac15":
                if(isset($name)){
                    $arrField[] = $value;}
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
    
    if(!empty($arrField)){
        $criter =  " AND division IN( ".implode(" ,", $arrField)." ) ";
        
    }
    
   
    if(!empty($scheduled)){
        $sched = " AND (".implode(" AND ", $scheduled).")";
    }
    
    
    if(!empty($scheduled)){
        $when = " AND (".implode(" AND ", $start).")";
    }
    
    
     for($i=1;$i<14;$i++){
        if($month ==0 ){
            $month=12;
            $year = $year -1;
        }
        $bar_ask = "SELECT SUM( freight_grease_data_table.inches_to_gallons ) AS all_oil, AVG(freight_grease_data_table.inches_to_gallons) as avrg, COUNT( DISTINCT ( freight_grease_data_table.account_no ) ) AS dist_acc, MONTH( freight_grease_data_table.date_of_pickup ) AS month, YEAR(freight_grease_data_table.date_of_pickup) as year, COUNT(DISTINCT(freight_grease_data_table.account_no) ) as pickups FROM freight_grease_data_table LEFT JOIN freight_accounts ON freight_accounts.account_ID = freight_grease_data_table.account_no WHERE 1 $criter AND MONTH( date_of_pickup ) = '$month' AND YEAR( date_of_pickup ) =  '$year'"; 
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
      
    
     
    
     $c_r = "SELECT freight_grease_traps.*,freight_accounts.status,freight_accounts.division FROM freight_grease_traps INNER JOIN freight_accounts ON freight_accounts.account_ID = freight_grease_traps.account_no WHERE route_status in ('scheduled','enroute') AND freight_accounts.status='active' AND freight_grease_traps.code_red =1 AND service_date !='0000-00-00' AND service_date >'2015-06-01'";//select all schedules that are scheduled or enroute and in code red and account is active
     
    $enroute = "SELECT DISTINCT(freight_accounts.account_ID),freight_grease_traps.grease_no,freight_accounts.status FROM freight_grease_traps INNER JOIN freight_accounts ON freight_accounts.account_ID = freight_grease_traps.account_no WHERE route_status='enroute' AND freight_accounts.status='active' AND freight_grease_traps.grease_route_no IS NOT NULL AND service_date !='0000-00-00'AND service_date >'2015-06-01'";//select all stops that are enroute 
    
    $rest = "SELECT freight_grease_traps.account_no,freight_accounts.avg_gallons_per_Month/freight_accounts.barrel_capacity as per_full FROM freight_grease_traps LEFT JOIN freight_accounts on freight_grease_traps.account_no = freight_accounts.account_ID WHERE route_status='scheduled' AND code_red !=1 AND freight_accounts.status='active'AND service_date !='0000-00-00' AND service_date >'2015-06-01'";//select accounts who's oil level match criteria below and are not in code red and are scheduled for pickup
    
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
                                        FROM freight_grease_data_table AS l 
                                        WHERE date_of_pickup > DATE_SUB( NOW( ) , INTERVAL 1 YEAR ) GROUP BY MONTH( date_of_pickup ) ASC ORDER BY YEAR( date_of_pickup ) DESC , MONTH( date_of_pickup ) DESC 
) AS a  UNION ALL
                            SELECT * FROM (
                                   SELECT MONTH( date_of_pickup ) AS month , 
                                   YEAR( date_of_pickup ) AS year, 
                                   SUM( inches_to_gallons ) AS all_oil, 
                                   COUNT( * ) AS pickups, SUM( inches_to_gallons ) / COUNT( * ) AS avrg, 
                                   count( DISTINCT (account_no) ) AS dist_acc 
                                        FROM freight_grease_data_table WHERE YEAR( date_of_pickup ) = '$year' AND MONTH(date_of_pickup)  = '$month') AS b ORDER BY year ASC , MONTH ASC ";
   
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
    
    
    
    $c_r = "SELECT DISTINCT(freight_grease_traps.account_no),freight_grease_traps.grease_no,freight_accounts.status,freight_accounts.division FROM freight_grease_traps INNER JOIN freight_accounts ON freight_accounts.account_ID = freight_grease_traps.account_no WHERE route_status in ('scheduled','enroute') AND freight_accounts.status='active' AND freight_grease_traps.code_red =1 ";//select all schedules that are scheduled or enroute and in code red and account is active
     
    $enroute = "SELECT DISTINCT (freight_grease_traps.account_no) FROM freight_grease_traps INNER JOIN freight_list_of_grease ON freight_list_of_grease.route_id = freight_grease_traps.grease_route_no WHERE route_status = 'enroute'";//select all stops that are enroute 
    
    $rest = "SELECT DISTINCT(freight_grease_traps.account_no),freight_accounts.avg_gallons_per_Month/freight_accounts.barrel_capacity as per_full FROM freight_grease_traps LEFT JOIN freight_accounts on freight_grease_traps.account_no = freight_accounts.account_ID WHERE route_status='scheduled' AND code_red !=1 AND freight_accounts.status='active' ";//select accounts who's oil level match criteria below and are not in code red and are scheduled for pickup and are active;
    
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


    <style type="text/css">
     table#facs td{
        vertical-align:top;
        text-align:left;
     }
    #over_account,#over_locations,#not_collected{
        width: 210px;height:130px;float:left;overflow:hidden;padding:0px 0px 0px 0px;font-size:95%
        ;background:white;margin-top:10px;
       
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
    <div id="fullx" style="width: 1000px;height:150px;background:transparent;margin:auto;height:auto;">     
        
        
        <div id="over_locations" style="height: auto;width:500px;">
             <table class="datatable" id="oloc" style="font-size:12px;">
                <tr><td colspan="2">Accounts</td></tr>
                <tr><td style="width: 50%;">Serviced</td><td><?php 
               
                    $serv = $db->query("SELECT COUNT(  account_ID ) AS serviced FROM freight_accounts WHERE EXISTS (  SELECT DISTINCT (account_no ) FROM freight_grease_data_table WHERE 1 $when) AND  STATUS = 'Active' $criter
                            UNION ALL 
                                        SELECT COUNT(  account_ID ) AS not_served FROM freight_accounts WHERE STATUS = 'Active' AND account_ID NOT IN ( SELECT DISTINCT ( account_no ) FROM freight_grease_data_table WHERE 1 $when) $criter");
                    echo $serv[0]['serviced'];
                ?></td></tr>
                <tr><td style="width: 50%;">Not Serviced</td><td><?php
                    
                    echo $serv[1]['serviced'];
                ?></td></tr>
                <tr><td>New Accounts</td><td><?php $new = $db->query("SELECT COUNT(account_ID) as new FROM freight_accounts WHERE status='new' $criter $sched");
                echo $new[0]['new'];
                 ?></td></tr>
                
                <tr><td style="width: 50%;">All</td><td><?php $new = $db->query("SELECT COUNT(account_ID) as allx FROM freight_accounts WHERE status in ('active','new') $criter $sched");
                echo $new[0]['allx'];
                 ?></td></tr>
                <tr><td>Service Pickups</td><td><?php 
                    $bh = $db->query("SELECT COUNT( DISTINCT(account_no) ) AS all_picks FROM freight_grease_data_table WHERE 1 $when");
                    echo $bh[0]['all_picks'];
                ?></td></tr>
            </table>
            <div style="clear: both;"></div>
        </div>
        
    
        
        <div id="searchx" style="width: 500px;min-height:130px;background:rgb(242,242,242);float:left;margin-top:10px;height:auto;" class="datatable">     
                   
                <form action="management.php?task=overview" method="post">
            <table style="width: 100px;margin-left:24px;background:white;height:100px;width:100%;font-size:12px;" id="facs">
                <tr><td colspan="4" style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;Sort By</span></td></tr>
                <tr>
                    <td><input class="all" type="checkbox"/>&nbsp;All UC </td>
                    <td><input value="33" <?php if(isset($_POST['fac5'])){ echo "checked='checked'"; } ?>   name="fac5" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Chuck)</td>
                    <td><input value="25"  <?php if(isset($_POST['fac6'])){ echo "checked='checked'"; } ?>   name="fac6" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Ramon)</td>
                    
                    <td><input value="24" <?php if(isset($_POST['fac3'])){ echo "checked='checked'"; } ?>   name="fac3" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC)</td>
                </tr>
                <tr>
                    <td><input value="31"  <?php if(isset($_POST['fac7'])){ echo "checked='checked'"; } ?>   name="fac7" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Tony)</td>
                    <td><input value="32"  <?php if(isset($_POST['fac4'])){ echo "checked='checked'"; } ?>   name="fac4" type="checkbox" class="fac uc"/>&nbsp;LA Division(UC-Chato)</td>
                    <td><input value="8"  <?php if(isset($_POST['fac1'])){ echo "checked='checked'"; } ?>   name="fac1" type="checkbox" class="fac"/>&nbsp;Arizona Division(4)</td>
                    <td><input value="23"  <?php if(isset($_POST['fac2'])){ echo "checked='checked'"; } ?>   name="fac2" type="checkbox" class="fac"/>&nbsp;Coachella Division (UD)</td>
                    
                </tr>
                <tr>
                    <td><input value="22"  <?php if(isset($_POST['fac8'])){ echo "checked='checked'"; } ?>   name="fac8" type="checkbox" class="fac"/>&nbsp;San Diego Division (US)</td>
                    <td><input value="10"  <?php if(isset($_POST['fac10'])){ echo "checked='checked'"; } ?>  name="fac10" type="checkbox" class="fac selma"/>&nbsp;V-BAK</td>
                    <td><input value="11"  <?php if(isset($_POST['fac11'])){ echo "checked='checked'"; } ?>  name="fac11" type="checkbox" class="fac selma"/>&nbsp;V-Fresno</td>
                    <td><input value="5"  <?php if(isset($_POST['fac9'])){ echo "checked='checked'"; } ?>    name="fac9" type="checkbox"  class="fac" id="allselma"/>&nbsp;Selma (V)</td>
                </tr>
                <tr>
                    <td><input value="12"  <?php if(isset($_POST['fac12'])){ echo "checked='checked'"; } ?>   name="fac12" type="checkbox" class="fac selma"/>&nbsp;V-North</td>
                    <td><input value="13"  <?php if(isset($_POST['fac13'])){ echo "checked='checked'"; } ?>   name="fac13" type="checkbox" class="fac selma"/>&nbsp;V-Visalia</td>
                    <td><input value="15"  <?php if(isset($_POST['fac15'])){ echo "checked='checked'"; } ?>   name="fac15" type="checkbox" class="fac selma"/>&nbsp;W-Division</td>
                    <td>&nbsp;</td>
                </tr>
                
                <tr><td colspan="4">Date range for pie graph</td></tr>
                <tr><td colspan="4">
                    <input type="text" value="<?php if(isset($_POST['from'])){ echo $_POST['from'];  } ?>" placeholder="From" name="from" id="from" style="border-radius: 10px;"/>
                    <input type="text" value="<?php if(isset($_POST['to'])){ echo $_POST['to'];  } ?>" placeholder="To" name="to" id="to" /></td></tr>
                    <tr><td colspan="4" style="text-align: right;"><input type="submit" name="search_fac" value="Search Now" /></td></tr>
            </table>
            </form>
            <div style="clear: both;"></div>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>    
   <div id="canvas-holder" style="width: 500px;height:400px;float:left;padding:0px 0px 0px 0px;">
        <canvas id="chart-area" width="500" height="400"/>
        
    </div>

  <div class="actualNumbers" style="width:200px;float:left;min-height:300px;padding-top:50px;height:auto;">
        
 
        <table id="pienumbers" style="width: 400px;position:relative;top:20px;left:25%;">
            <tr style="background: green;color:white;"><td>Pickup Status</td><td>Amount</td></tr>
            <tr><td style="color: #F7464A;font-weight:bold;">Accounts Enroute</td><td><?php echo count($enr); ?></td></tr>
            <tr><td style="color: #46BFBD;font-weight:bold;">Recently Serviced</td><td><?php echo $collected; ?></td></tr>
            <tr><td style="color:#FDB45C;font-weight:bold;">Coming Up</td><td><?php echo $coming_up; ?></td></tr>
            <tr><td style="color: #949FB1; font-weight:bold;">Route Me</td><td><?php echo $route_me; ?></td></tr>
            <tr><td style="color: #4D5360;font-weight:bold;">Code Red</td><td><?php echo count($codes); ?></td></tr>
        </table>
 </div>
    <div style="clear: both;"></div>
        <div id="bar_holer" style="width: 760px;height:400px;float:left;margin-left:10px;">
    	   <canvas id="canvas" height="450" width="600"></canvas>
        </div>
        
    <div class="actualNumbers" style="width: 300px;float:left;min-height:300px;padding-top:10px;height:auto;margin-left:5px;">
        <table id="barnumbers" style="width: 100%;">
            <tr style="background: green;color:white;"><td>Month</td><td>Pick Ups</td><td>aGGP</td><td>Accounts</td></tr>
            <?php
            /*echo "<pre>";
            print_r($pick_up_counts);
            echo "</pre>";
            */
            if(count($pick_up_counts)>0){
                foreach($pick_up_counts as $ko){
                    if(!empty($ko)){
                        echo "<tr><td>$ko[month]</td><td  style='color:green;font-weight:bold;'>$ko[count]</td><td style='color:red;font-weight:bold;'>$ko[aGGP]</td><td style='color:blue;font-weight:bold;'>$ko[accounts]</td></tr>";
                    }
                }
            }
            ?>
        </table>
    </div>    

<div id="oils" style="width:100%;margin:auto;margin-top:10px;min-height:750px;height:auto;">
    <div id="bar2" style="width: 760px;height:400px;float:left;margin-left:10px;">
        <canvas id="canvas2" height="375" width="600"></canvas>
    </div>
     <div class="actualNumbers" style="width: 30%;float:left;min-height:300px;padding-top:50px;height:auto;">
        <table id="oil_numbers" style="width: 100%;">
             <tr style="background: green;color:white;"><td>Month</td><td>Oil</td></tr>
             <?php
             if(count($data_oil)){
                 foreach($data_oil as $oilz){
                    echo "<tr><td>$oilz[month]</td><td>$oilz[gallons]</td></tr>";
                 }
             }
             ?>
        </table>
    </div>
</div>

 <div style="clear: both;"></div>
 <script>
 
 var bCD2 = {
    labels:[<?php 
            if(count($pick_up_counts)>0){
                foreach($pick_up_counts as $ko){
                    if(!empty($ko)){
                        echo '"'.$ko['month'].'",';
                    }
                }
            }
        ?>
    ],
    datasets:[
        {
            fillColor : "rgba(220,220,220,0.5)",
			strokeColor : "rgba(220,220,220,0.8)",
			highlightFill: "rgba(220,220,220,0.75)",
			highlightStroke: "rgba(220,220,220,1)",
            data:[
                <?php
                     if(count($data_oil)){
                        foreach($data_oil as $oilz){
                            echo $oilz['gallons'].",";
                        }
                     }
                ?>
            ]
        }
    ]
    
 }
 
 var barChartData = {
		labels : [<?php 
            if(count($pick_up_counts)>0){
                foreach($pick_up_counts as $ko){
                    if(!empty($ko)){
                        echo '"'.$ko['month'].'",';
                    }
                }
            }
        ?>],
		datasets : [
			{
				fillColor : "rgba(0,255,0,0.5)",
				strokeColor : "rgba(0,255,0,0.8)",
				highlightFill: "rgba(0,255,0,0.75)",
				highlightStroke: "rgba(0,255,0,1)",
				data : [<?php 
                    if(count($pick_up_counts)>0){
                        foreach($pick_up_counts as $ko){
                            if(!empty($ko)){
                                echo '"'.$ko['count'].'",';
                            }
                        }
                    }
                ?>],
               
			},
            {
                fillColor:"rgba(255,0,0,0.5)",
                strokeColor : "rgba(255,0,0,0.8)",
				highlightFill: "rgba(255,0,0,0.75)",
				highlightStroke: "rgba(255,0,0,1)",
                data:[
                    <?php 
                        if(count($pick_up_counts)>0){
                            foreach($pick_up_counts as $ko){
                                if(!empty($ko)){
                                    echo '"'.$ko['aGGP'].'",';
                                }
                            }
                        }
                    ?>
                ],
                
            },
            {
                fillColor:"rgba(0,0,255,0.5)",
                strokeColor : "rgba(0,0,255,0.8)",
				highlightFill: "rgba(0,0,255,0.75)",
				highlightStroke: "rgba(0,0,255,1)",
                data:[
                    <?php 
                        if(count($pick_up_counts)>0){
                            foreach($pick_up_counts as $ko){
                                if(!empty($ko)){
                                    echo '"'.$ko['accounts'].'",';
                                }
                            }
                        }
                    ?>
                ]
            }
		]

	}
 
 var pieData = [
				{
					value:  <?php echo count($enr);  ?>,
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: "Accounts Enroute"
				},
				{
					value:  <?php echo $collected; ?>,
					color: "#46BFBD",
					highlight: "#5AD3D1",
					label: "Recently Serviced"
				},
				{
					value: <?php echo $coming_up; ?>,
					color: "#FDB45C",
					highlight: "#FFC870",
					label: "Coming Up"
				},
				{
					value: <?php echo $route_me; ?>,
					color: "#949FB1",
					highlight: "#A8B3C5",
					label: "Route Me"
				},
				{
					value: 120,
					color: "#4D5360",
					highlight: "#616774",
					label: "Code Red"
				}

			];

	
			var ctx = document.getElementById("chart-area").getContext("2d");
			window.myPie = new Chart(ctx).Pie(pieData);
            
            var cty = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(cty).Bar(barChartData, {
		         responsive : true
            });
            
            var ctz =  document.getElementById("canvas2").getContext("2d");
            window.myBar = new Chart(ctz).Bar(bCD2, {
		         responsive : true
            });
		
 
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
 
 //$("#over_locations").css('background','url(../img/loading.gif) no-repeat center center');

 
 
 
 </script>
<div style="clear: both;"></div>