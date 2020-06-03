<?php
include "protected/global.php";


if(isset($_GET['trailer'])){
    $trailer = new Trailer($_GET['trailer']);
    echo $trailer->ikg_decal;
    
}else {
    $trailer = new Trailer($_GET['license']);
    echo $trailer->lp_no;;
}



?>