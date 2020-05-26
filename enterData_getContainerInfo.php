<?php
include "protected/global.php";
$containers =  $db->where("account_no",$_GET['account'])->get($dbprefix."_containers");

if(count($containers)>0){
    foreach($containers as $barrels){
        
    

?>

<tr>
    <td style="text-align: right;">Inches Collected</td>
    <td style="text-align: left;">
    <input type="text" id="inchescollected" name="inchescollected" placeholder="0.0" style="width: 50px;"/> 
    <input type="text" readonly  name="gpicalc"  id="gpicalc" style="width: 50px;"/> <span style="font-size: 10px;">Gallons Collected</span>&nbsp;/&nbsp;<span class="outof" ></span><span style="font-size: 9px;color:#bbbbbb;"> Expected</span> 
    </td>
</tr>
<tr>
    <td style="text-align: right;vertical-align:top;">Inches Left Behind<!---Oil Left over ---!></td>
    <td style="text-align: left;vertical-align:top;">
        <input type="text" id="inchesleftover" name="inchesleftover" placeholder="0.0" style="width: 50px;"/>
        <input type="text" readonly id="inchtogallonleftover" name="inchtogallonleftover" style="width: 50px;" /> <span style="font-size: 10px;">Gallons Onsite</span>&nbsp;/&nbsp;<span class="outof" ></span>
    </td>
</tr>

<?php
}
}

?>