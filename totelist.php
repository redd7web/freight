<script src="js/jquery-1.11.1.js" type="text/javascript"></script>

<?php
include "protected/global.php";

$account = new Account($_POST['id']);
?>
            
             <table style="width:100%;margin-top:5px;" id="containerlistxx">
                <tr><td colspan="2" style="text-align: center;"> <span style="font-weight:bold;font-size:20px;">Add Containment</span><br />Choose a container from the list, then click the "+"</td></tr>
                <tr>

                    <td style="width:50%;">&nbsp;<input type="submit" value=""  style="width: 25px;height:25px;background:transparent url(img/plus.png) no-repeat center center; background-size:contain;" id="addContainer"  title="Select then add container"/></td>   
                    <td style="text-align: left;vertical-align: top;">&nbsp;<?php containerList(0); ?></td>
                               
                </tr>
                
                <tr><td colspan="3">
               <div id="containment" style="width: 390px;height:100px;overflow-y:scroll;background:transparent;overflow-x:hidden;border:1px dotted black;">
               <?php 
                     //$holders = $db->query("SELECT entry,container,account_no FROM freight_containers WHERE account_no=$account->acount_id");
                   //where("delivery_date",)->where("account_no",$account->acount_id)->get($dbprefix."_containers","entry,container,account_no");
                   if(count($account->barrel_info)>0){
                        foreach($account->barrel_info as $totes){
                            $check = $db->query("SELECT * FROM freight_utility WHERE account_no = $account->acount_id AND container_label= $totes[container_id] AND type_of_service =3 AND date_of_service ='0000-00-00'");
                            
                            if(count($check)>0){
                                $stat = " (delivery pending)";
                            }
                            
                            
                            echo "<div class='toterow' style='width:100%;height:30px;margin-bottom:5px;padding:5px 5px 5px 5px;'>
                            <div class='lefthold' style='width:50%;float:left;'>"
                                .$totes['container_label'].$stat.
                            "</div>
                            <div class='righthold' style='width:50%;height:16px;background:url(img/delete-icon.jpg) no-repeat left top;cursor:pointer;float:left;' account='$totes[account_no]' entry ='".$totes['entry']."'>
                            &nbsp;
                            </div>
                          </div>"; 
                        }
                    }    
               ?>
               </div>
               </td></tr>
               <tr><td colspan="2"><input type="hidden" id="acc_no" value="<?php echo $account->acount_id; ?>"/></td></tr>
               </table>   
               <script>
              $("#addContainer").click(function(){
                
                   $.post("addcont.php",{account_no:$("input#acc_no").val() , container_id: $("#container_size").val()},function(data){                    
                    $("#guage").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=<?php echo $account->acount_id; ?>');
                    alert("Container Added!");
                    
                    $.post("totelist.php",{id:<?php echo $account->acount_id; ?>},function(data){
                        $("#totelist").html(data);
                    });
                    $.post("showsiren.php",{id :<?php echo $account->acount_id;  ?>},function(data){
                            $("#siren").html(data); 
                        });
                   });
                   return false; 
                });
                
                $(".righthold").on("click",function(){
                    
                    //alert( $(this).attr('account')+" "+$(this).attr('entry')  );
                    
                    /**/
                    $.post('deleteTote.php',{
                            account:$(this).attr('account') ,
                            entry:$(this).attr('entry')},function(data){
                                alert('Container removed');
                                $("#guage").attr('src','plugins/jqwidgets-ver3.4.0/demos/jqxgauge/settings.php?account_no=<?php echo $account->acount_id; ?>');
                                $.post("totelist.php",{id:<?php echo $account->acount_id; ?>},function(data){
                                    $("#totelist").html(data);
                                }); 
                        
                                $.post("showsiren.php",{id :<?php echo $account->acount_id;  ?>},function(data){
                                    $("#siren").html(data); 
                                });
                            });
                    });
                
               </script>