<?php
require_once "../config_session.in.php";
if(isset($_SESSION["user_id"]) and $_SERVER["REQUEST_METHOD"]==="POST"){
    session_destroy();
    exit(header("Location:../"));
}
?>