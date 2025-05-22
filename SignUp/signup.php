<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    //retrive dati
    $username=$_POST["username"];
    $pass=$_POST["pass"];
    $email=$_POST["email"];

    //chiamata alle funzioni
    require_once "error.php";
    require "../dbh.in.php";
    try{
        //Error handler
        $ERRORS=[];
        if(is_username_invalid($username)){
            $ERRORS["invalid_credentials"]="Credenziali non valide";
        }
        else if(username_already_used($pdo,$username)){
            $ERRORS["invalid_credentials"]="Credenziali non valide";
        }
        if(is_email_invalid($email)){
            $ERRORS["invalid_credentials"]="Credenziali non valide";
        }else if(email_already_used($pdo,$email)){
            $ERRORS["invalid_credentials"]="Credenziali non valide";
        }
        if(is_password_invalid($pass)){
            $ERRORS["invalid_credentials"]="Credenziali non valide";
        }
        //invio errori
        require_once "../config_session.in.php";
        if($ERRORS){
            $_SESSION["errors_signup"]=$ERRORS;
            $signupdata=[
                "username"=>$username,
                "email"=>$email
            ];
            $_SESSION["signup_data"]=$signupdata;
            header("Location:../index.php");
            die();
        }
        //creazione account
        $pass=password_hash($pass,PASSWORD_BCRYPT);
        require_once "query.php";
        createUser($pdo,$username,$email,$pass);
        $result=getUsername($pdo,$username);
        $_SESSION["user_id"] = $result["id_user"];
        $_SESSION["first-time"]=true;
        $_SESSION["user_username"] = htmlspecialchars($result["username"]);
        $_SESSION["last_regeneration"] = time();
        echo $result["id_user"];
        header("Location:../UserInfo/info.php");
    }catch(PDOException $e){
        die("Error:".$e);
    }
}
else{
    header("Location:../index.php");
    die();
}
?>