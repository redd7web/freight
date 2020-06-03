<?php
$result = $db->query("SELECT * FROM freight_users WHERE roles like '%admin%'");

?>

<style type="text/css">
.tableNavigation {
    width:1000px;
    text-align:center;
    margin:auto;
}
.tableNavigation ul {
    display:inline;
    width:1000px;
}
.tableNavigation ul li {
    display:inline;
    margin-right:5px;
}

td{
    background:transparent;
    border:0px solid #bbb;  
    padding:0px 0px 0px 0px;  
}

tr.even{
    background:-moz-linear-gradient(center top , #F7F7F9, #E5E5E7);
}

tr.odd{
    background:transparent;
}
.setThisRoute{ 
    z-index:9999;
}
</style>
<script>

$(document).ready(function(){
   $('#myTable').dataTable({
        "lengthMenu": [ [10, 25, 50,100,150, -1], [10, 25, 50,100,150, "All"] ]
   }); 
});
</script>
<table style="width: 100%;margin:auto;" id="myTable" >
<thead>
<tr style="background:url(img/biotanestrip.jpg) repeat-x left top;background-size:contain;">

    <th class="cell_label">ID</th>
    <th class="cell_label">Employee Num</th>
    <th class="cell_label">Name</th>
    <th class="cell_label">Phone</th>
    <th class="cell_label">User</th>
    <th class="cell_label">Title</th>
</tr>
   </thead>
    <tbody>
<?php
                 
                 
                    
                  
                  
                  if(count($result)) { 
                    foreach($result as $user){
                        
                        echo "<tr>
                        <td><span style='font-size:9px;'>$user[user_id]</span></td>
                        <td>&nbsp;</td>
                        <td style='padding:0px 0px 0px 0px;'><a style='font-size:9px;text-decoration:underline;color:blue;' href='viewUser.php?id=$user[user_id]'>$user[last] $user[first]</a></td>
                        <td><span style='font-size:9px;'>($user[areacode]) $user[phone]</span></td>
                        <td><span style='font-size:9px;'>$user[login_name]</span></td></td>
                        <td>$user[title]</td>
                        </tr>";
                        
                    }
                  } 
                  ?>
                  </tbody>
</table>