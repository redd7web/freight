<script src="js/jquery-1.11.1.js"></script>
<script type="text/javascript">
var new_string="";

</script>
<style>
body{
    padding:10px 10px 10px 10px;
    margin:0px 0px 0px 0px;
}

#myDummyTable tr:hover{
    border :1px solid black;
    background:black;
    color:white;
}

#myDummyTable tr{
    border: 0px solid black;
    background:white;
    color:black;
}
</style>
<table id="myDummyTable" style="width: 100%;">
<tr><td><input type="submit" class="save" value="Save"/></td></tr>
<?php
include "protected/global.php";
ini_set("display_errors",0);
switch($_GET['type']){
    case "oil":
        $front = new IKG($_GET['route_id']);
        ?>
        <script>
          new_string="<?php foreach($front->scheduled_routes as $stopx){//completed stops already get put in list
                $stop_accountx = new Scheduled_Routes($stopx);
                if($stop_accountx->route_status=="completed"){
                    echo "$stop_accountx->account_number|";
                }
          } ?>";
          
        
        </script>
        
        <?php
        $count = 1;
        foreach($front->scheduled_routes as $stops){
            $stop_account = new Scheduled_Routes($stops);
            if($stop_account->route_status !="completed"){//change order of uncompleted stops
                 echo "<tr class='row' rel='$stop_account->account_number'> <td style='font-size:20px;'>$stop_account->account_name</td><td><div class='down' style='width:100px;height:100px;background:url(img/down_arrow.png) no-repeat left top;cursor:pointer;margin-right:10px;float:left;'></div>
            <div class='up' style='width:100px;height:100px;background:url(img/up_arrow.png) no-repeat left top;cursor:pointer;float:left;'></div></td></tr>";
            }            $count++;
        }
       
       
    break;
    case "grease":
        $single = new Grease_IKG($_GET['route_id']);
        ?>
        <script>
          new_string="<?php 
           if(count($single->scheduled_routes)>0){
            foreach($single->scheduled_routes as $stopx){//completed stops already get put in list
                    $stop_accountx = new Grease_Stop($stopx);
                    if($stop_accountx->route_status=="completed"){
                        echo "$stop_accountx->account_number|";
                    }
              }
          } ?>";
        </script>
        <?php  
        $count = 1;
        if(count($single->scheduled_routes)>0){
            foreach($single->scheduled_routes as $stops){
                $stop_account = new Grease_Stop($stops);
                if($stop_account->route_status !="completed"){//change order of uncompleted stops
                     echo "<tr class='row' rel='$stop_account->account_number'> <td style='font-size:20px;'>$stop_account->account_name</td><td><div class='down' style='width:100px;height:100px;background:url(img/down_arrow.png) no-repeat left top;cursor:pointer;margin-right:10px;float:left;'></div>
                <div class='up' style='width:100px;height:100px;background:url(img/up_arrow.png) no-repeat left top;cursor:pointer;float:left;'></div></td></tr>";
                }            $count++;
            }
        }
        
    break;
    case "util":
        $util = new Container_Route($_GET['route_id']);
        ?>
        <script>
          new_string="<?php 
           if(count($util->scheduled_routes)>0){
            foreach($util->scheduled_routes as $stopx){//completed stops already get put in list
                    $stop_accountx = new Util_Stop($stopx);
                    if($stop_accountx->route_status=="completed"){
                        echo "$stop_accountx->account_number|";
                    }
              }
          } ?>";
        </script>
        <?php  
        $count = 1;
        if(count($util->scheduled_routes)>0){
            foreach($util->scheduled_routes as $stops){
                $stop_account = new Util_Stop($stops);
                if($stop_account->route_status !="completed"){//change order of uncompleted stops
                     echo "<tr class='row' rel='$stop_account->account_number'> <td style='font-size:20px;'>$stop_account->account_name</td><td><div class='down' style='width:100px;height:100px;background:url(img/down_arrow.png) no-repeat left top;cursor:pointer;margin-right:10px;float:left;'></div>
                <div class='up' style='width:100px;height:100px;background:url(img/up_arrow.png) no-repeat left top;cursor:pointer;float:left;'></div></td></tr>";
                }            $count++;
            }
        }
    break;
  
}

?>
<tr><td><input type="submit" class="save" value="Save"/></td></tr>
</table>
<script>
 $(".up,.down").click(function(){
    var row = $(this).parents("tr:first");
    if ($(this).is(".up")) {
        row.insertBefore(row.prev());
        //row.css('background','green');
    } else {
        row.insertAfter(row.next());
        //row.css('background','green');
    }
   
    
});
    
  
$(".save").click(function(){
            if(confirm("Are you Sure you want to keep changes?")){
                $(".row").each(function(i){
                     new_string += $(this).attr('rel')+"|"; 
                }).promise().done(function(){
                    $.post("updateRouteStops.php",{new_account_numbers:new_string,route_id:<?php echo $_GET['route_id']; ?>,type: "<?php echo $_GET['type']; ?>"},function(data){
                        alert(data);
                    });
                    /*alert(new_string);*/
                });
             }   
          });
</script>
        
