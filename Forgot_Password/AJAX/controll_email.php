<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    //retrive dati
    $email=$_POST["email"];
    //chiamata alle funzioni
    require_once "../../dbh.in.php";
    require_once "../SendEmail/error.php";
    require_once "../SendEmail/query.php";
    require_once "../../config_session.in.php";

    try{
        //error handling
        if(is_email_invalid($email)){
            echo '<p class="form-error">Email non valida</p>';
        }else{
            if(email_not_exist($pdo,$email)){
                echo '<p class="form-error">L\'email non è associata a nessun\'account</p>';
            }else echo "";
        }
/*
        if($ERRORS){
            $_SESSION["forgot_errors"]=$ERRORS;
            $forgotData=[
                "email"=>$email
            ];
            $_SESSION["forgot_data"]=$forgotData;
            header("Location:../index.php");
            $pdo=null;
            $stmt=null;
            die();
        }
*/
    }catch(PDOException $e){
        die("Error:".$e);
    }
}else{
    die(header("Location:../index.php"));
}
?>