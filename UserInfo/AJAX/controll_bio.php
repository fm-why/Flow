<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $bio=$_POST["bio"];
    if(strlen($bio)>255){
        echo "<p class='form-error'>Biografia non valida</p>";
    }
    else echo "";
}else die(header("Location: ../info.php"));
?>