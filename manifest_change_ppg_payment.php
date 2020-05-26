<style>
body{
    margin:10px 10px 10px 10px;
    padding:10px 10px 10px 10px;
}
</style>
<?php
include "protected/global.php";
ini_set("display_errors",0);
if(isset($_POST['change'])){
    $account = new Account($_POST['account_no']);
    $x = $db->query("SELECT schedule_id FROM sludge_grease_data_table WHERE schedule_id = $_POST[grease_id]");
    if( ( !isset($_POST['ccharge']) && strlen(trim($_POST['ccharge']) ==0 ) ) || $_POST['ccharge'] == 0  ){//reseting custom charge 
           $db->query("UPDATE sludge_grease_traps SET custom_charge = NULL WHERE grease_no = $_POST[grease_id]");
           if(count($x)>0){
                switch($account->payment_method){
                    case "Charge Per Pickup":
                        $old_charge = $account->grease_ppg;
                        break;
                    case "Per Gallon": case "Normal":
                        $old_charge = $account->grease_volume * $account->grease_ppg;
                        break;
                    case "No Pay":
                        $old_charge = 0;
                        break;
                    case "Charge Per Pickup":case "Index":
                        $old_charge = $account->grease_ppg; 
                        break;
                    
                    case "One Time Payment":
                        $gh = $db->query("SELECT * FROM sludge_data_table WHERE account_no = $this->account_number");
                        if(count($gh)>0){
                            $old_charge = 0;
                        } else if( count($gh) == 0 ){
                            $old_charge =  + $account->ppg_jacobsen_percentage;
                        }
                        break;
                    case "O.T.P. Per Gallon":
                        $gh = $db->query("SELECT * FROM sludge_data_table WHERE account_no = $this->account_number");
                        if(count($gh)>0){
                            $old_charge = ($account->grease_volume * $account->grease_ppg);
                        }else if( count($gh) == 0 ){
                            $old_charge = ($account->grease_volume * $account->grease_ppg) + $account->ppg_jacobsen_percentage;
                        }                            
                    break;
                    case "Cash On Delivery":
                    break;
                    default:
                        $old_charge = number_format(0,2);
                    break;
                }
                $db->query("UPDATE sludge_grease_data_table SET paid = $old_charge WHERE schedule_id=$_POST[grease_id]");
           }
    } else {
          $db->query("UPDATE sludge_grease_traps SET custom_charge = $_POST[ccharge] WHERE grease_no = $_POST[grease_id]");
         if(count($x)>0){
            $db->query("UPDATE sludge_grease_data_table SET paid = $_POST[ccharge] WHERE schedule_id = $_POST[grease_id]");       
         }
    }
    
   
    
    
    
    if(!isset($_POST['ppg']) && strlen(trim($_POST['ppg'])) && $_POST['ppg'] == 0){// reset or blank ppg
        $db->query("UPDATE sludge_grease_traps SET price_per_gallon=$account->grease_ppg WHERE grease_no = $_POST[grease_id]");    
         if(count($x)>0){
            $db->query("UPDATE sludge_grease_data_table SET ppg = $account->grease_ppg WHERE schedule_id=$_POST[grease_id]");
        }
    } else {
        $db->query("UPDATE sludge_grease_traps SET price_per_gallon= $_POST[ppg] WHERE grease_no = $_POST[grease_id]");
         if(count($x)>0){
            $db->query("UPDATE sludge_grease_data_table SET ppg = $_POST[ppg] WHERE schedule_id=$_POST[grease_id]");
        }
    }
    
    
    
    
    
    
}

$grease_stop = new Grease_Stop($_GET['schedule']);



?>

<form action="manifest_change_ppg_payment.php?schedule=<?php echo $_GET['schedule']; ?>" method="post">
<table style="width: 190px;"><tr><td><input type="text" placeholder="Price Per Gallon" name="ppg" value="<?php echo $grease_stop->ppg; ?>"/></td><td><input type="text" placeholder="Custom Charge" name="ccharge" value="<?php echo $grease_stop->custom_charge; ?>"/></td></tr>
<tr><td colspan="2" style="text-align: right;">
<input type="hidden" value="<?php echo $grease_stop->grease_no; ?>" name="grease_id"/>
<input type="hidden" value="<?php echo $grease_stop->account_number; ?>" name="account_no"/>
<input type="submit" value="Change Now" name="change"/></td></tr>
</table>
</form>