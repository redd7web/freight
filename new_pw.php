<?php
// Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.
  
 
  
  if(isset($_POST['ss'])){
     function better_crypt($input, $rounds = 7)
  {
    $salt = "";
    $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
    for($i=0; $i < 22; $i++) {
      $salt .= $salt_chars[array_rand($salt_chars)];
    }
    return crypt($input, sprintf('$2y$%02d$', $rounds) . $salt);
  }

  $password_hash = better_crypt("Pass123",15);
  
   
   if(crypt($_POST['compare'], $password_hash) == $password_hash){
     echo "logged in!<br/>";
   } else {
    echo $password_hash." : ".$password_compare;
    echo " try again<br/>";
   }
  }

  

?>
"Pass123"<br />
<form action="new_pw.php" method="post">
<input type="text" id="compare" name="compare"/>
<input type="submit" name="ss"/>
</form>