<?php
include "protected/global.php";
include "source/scripts.php";
include "source/css.php";

$account = new Account($_GET['id']);
?>

<script type="text/javascript">
    Shadowbox.init();
</script>
<style>
body{
    background:white;
}
#table_labels td{ 
    text-align:right;
    vertical-align:top;
}

table#bottominfo td{
    padding:0px 0px 0px 0px;
    font-size:14px; 
    
}
.editable_field{
    border-spacing: 0px;
    border-collapse: collapse;
    border-left:3px solid #bbb;
}

.close {
    cursor:pointer;
}

select{
    float:right;
}
div.ui-datepicker{
 font-size:11px;
}

.ui-datepicker { 
  margin-left: -90px;
  z-index: 9999;
}

.field{
    border: 1px solid green;
    border-radius: 10px;
    font-size: 12px;
    height: 30px;
    padding: 5px;
    width: 100px;
}

</style>
<table style="width:100%;margin-top:30px;">
<tr><td style="text-align: right;font-size:12px;">Contract Signed On</td><td style="vertical-align: top;text-align:left;">
                
                <input type="text" id="csigned" name="csigned" class="field" rel="state_date" value="<?php echo $account->state_date; ?>"/>
                </td></tr>
                <tr><td style="text-align: right;font-size:12px;"   >Contract Expires On</td><td style="vertical-align: top;text-align:left;">
                <input type="text" id="expires" name="<br />expires" class="field" rel="expires" value="<?php echo $account->expires; ?>"/>
                </td></tr>
</table>
<script>

$(".field").change(function(){      
  var isname = $(this).attr('rel');
  alert(isname);
  $.post("accountpage_editables/changefield.php",{id:<?php echo $account->acount_id; ?>,field: $(this).attr('rel'), value: $(this).val() },function(data){
    alert("Information changed! "+data);
    if(isname = "name"){
        var anumber = $(this).attr('xlr');
        $("#acname").html("<a href='viewAccount.php?id="+anumber+">"+$(this).val()+"</a>");        }       
  });
});

$("#csigned").datepicker({ dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true });
$("#expires").datepicker({ dateFormat: "yy-mm-dd",changeMonth: true, changeYear: true });
</script>