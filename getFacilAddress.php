<?php
include "protected/global.php";

foreach ($facils as $name=>$value){
    if($_POST['facil'] == $name){
        echo $value;
    }
}

?>