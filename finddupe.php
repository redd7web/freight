<?php
include "protected/global.php";

$k = $db->query("SELECT account_no, grease_no, count( account_no ) AS how_many
FROM sludge_grease_traps
WHERE route_status
IN (
'scheduled'
)
GROUP BY account_no
HAVING count( * ) >=2");

foreach($k as $x){
   
    echo "<br/><br/>";
    echo  account_NumToName($x['account_no'])." $x[grease_no]"." $x[how_many]<br/>";
    $bv = $db->query("SELECT grease_no,route_status FROM sludge_grease_traps WHERE account_no = $x[account_no] AND route_status ='scheduled'");
        echo "**********<br/>";
        if(count($bv)>0){
            foreach($bv as $b){
                echo $b['grease_no']." $b[route_status]<br/>";
            }
        }
        
        if(count($bv)== 2){
             echo "delete - ".$bv[count($bv)-1]['grease_no']."<br/>";
             $delete[]=$bv[count($bv)-1]['grease_no'];
        } else if (count($bv ==3)){
             echo "delete - ".$bv[count($bv)-2]['grease_no']."<br/>";
             echo "delete - ".$bv[count($bv)-1]['grease_no']."<br/>";
             $delete[] =$bv[count($bv)-2]['grease_no'];
             $delete[] =$bv[count($bv)-1]['grease_no'];
        }
        
        
        echo "**********<br/>";
        echo "<br/><br/>";
}

if(!empty($delete)){
    echo "DELETE FROM sludge_grease_traps WHERE grease_no IN(".implode(",",$delete).")<br/>";
     unset($delete);
    $db->query("DELETE FROM sludge_grease_traps WHERE grease_no IN(".implode(",",$delete).")");
}

?>