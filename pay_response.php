<?php
    include "protected/global.php";
    
    $dte = date("Y-m-d");
    $dataString="";
    $string = $_POST['parmList'];
    $dataString = explode('|',$_POST['parmList']);
 
    //place all the following file related code as per your requirement.
    //May be open the file and write string afer all the detail is available - after the loop.
    //close the file at the end.
    
 
   //parse through the response.
  
   foreach ($dataString as $pair ){
      $tmp = explode('~',$pair);
     $vars[$tmp[0]] = $tmp[1];
   }

  
    //search through the name/value pairs for the all the parameters
    foreach($vars as $key => $value){
  
        if ( $key == "ORDERID" ) {
      
            if ( $value != "" ){
            // do you code to write it to file as you want. $key .'='.$value
            // you can also join the string till the end and write it to the file at the end
            // I have just assigned the value
            $order_id = $value;
            
            }
        }elseif ( $key == "TRANSACTIONID" ){
            // do you code to write it to file as you want. $key .'='.$value
            // you can also join the string till the end and write it to the file at the end
            $transaction_id = $value; 
        }elseif ( $key == "APPMSG" ){
            // do you code to write it to file as you want. $key .'='.$value
            // you can also join the string till the end and write it to the file at the end
            $response_msg = $value; 
        }elseif ( $key == "CSCRESPONSE" ){
            // do you code to write it to file as you want. $key .'='.$value
            // you can also join the string till the end and write it to the file at the end
            $csc_response = $value; 
        }elseif ( $key == "AVSRESPONSE" ){
            // do you code to write it to file as you want. $key .'='.$value
            // you can also join the string till the end and write it to the file at the end
            $avs_response = $value; 
        }elseif ( $key == "EMAIL" ){
            // do you code to write it to file as you want. $key .'='.$value
            // you can also join the string till the end and write it to the file at the end
            $email = $value; 
        }
        $db->query("UPDATE freight_pay_trace SET status =1,date_paid ='$dte',transaction_id = $transaction_id WHERE schedule_id=$order_id ");
  
    }
    
    /*$dataStringx = implode(":",$dataString);
    $myfile = fopen("pay_response_$dte.txt", "w"); 
    fwrite($myfile, $dataStringx);
    fclose($myfile);*/
    
    ?>

?>