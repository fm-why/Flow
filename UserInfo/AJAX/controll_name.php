<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $name=$_POST["nome"];
    require_once "../../config_session.in.php";
    if(empty($name) and $_SESSION["first_time"]){
        echo "<p class='form-error'>Immettere il nome</p>";
    }
    if(strlen($name)>30 and !empty($name)){
        echo "<p class='form-error'>Nome non valido</p>";
    }
    else echo "";
}else die(header("Location: ../info.php"));
?>