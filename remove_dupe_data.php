<?php

include "protected/global.php";

$i = $db->query("SELECT entry_number,schedule_id,account_no,count(schedule_id) as num_dupes FROM freight_grease_data_table group by schedule_id having count(*) >= 2");
if(count($i)>0){
    foreach($i as $dupe){
        $selected = $db->query("SELECT entry_number,schedule_id,account_no,route_id FROM freight_grease_data_table WHERE schedule_id = $dupe[schedule_id] AND account_no = $dupe[account_no]");
        if(count($selected)>0){
            $i=0;
            echo "<br/><br/>-----------------------------------------<br/>";
            
            
            foreach($selected as $individual){
                if($i>0){
                    $delete[] = $individual['entry_number'];
                }
                $i++;
            }
            echo "<br/>-----------------------------------------------------------<br/><br/>";
        }
    }
}


echo "duplicate entries to delete: ".implode(",",$delete);

?>