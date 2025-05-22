<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    //retrive dati
    $username=$_POST["username"];
    $email=$_POST["email"];
    $pass=$_POST["pass"];
    //chiamata alle funzioni
    require_once "error.php";
    require_once "../../config_session.in.php";
    require_once "query.php";
    require_once "../../dbh.in.php";
    try{
        //error handling
        $ERRORS=[];
        if(empty($username) and empty($email) and empty($pass)){
            $ERRORS["empty"]="Inserire dei dati";
        }else{
            if(!empty($username) and is_username_invalid($username)){
                $ERRORS["invalid"]="Credenziali non valide";
            }
            if(!empty($username) and is_username_taken($pdo,$username,$_SESSION["user_id"])){
                $ERRORS["invalid"]="Credenziali non valide";
            }
            if(!empty($email) and is_email_invalid($email)){
                $ERRORS["invalid"]="Credenziali non valide";
            }
            if(!empty($email) and is_email_taken($pdo,$email,$_SESSION["user_id"])){
                $ERRORS["invalid"]="Credenziali non valide";
            }
            if(!empty($pass) and is_password_invalid($pass)){
                $ERRORS["invalid"]="Credenziali non valide";
            }
        }
        //invio errori e dati
        if($ERRORS){
            $_SESSION["update_error"]=$ERRORS;
            $updateData=[
                "username"=>$username,
                "email"=>$email
            ];
            $_SESSION["update_data"]=$updateData;
            header("Location:../info.php");
            die();
        }
        //reset dei cookie per remember me
        if(isset($_COOKIE["username"]) and !empty($username)){
            setcookie("username",$username,time()+3600*24*30,"/");
        }
        if(isset($_COOKIE["password"]) and !empty($pass)){
            setcookie("password",$pass,time()+3600*24*30,"/");
        }
        //set delle variabili
        $result=getUser($pdo,$_SESSION["user_id"]);
        $username= empty($username) ? $result["username"] : $username;
        $email=empty($email) ? $result["email"] : $email;
        $pass=empty($pass) ? $result["pass"] : password_hash($pass,PASSWORD_BCRYPT);
        UpdateUser($pdo,$_SESSION["user_id"],$username,$email,$pass);
        $_SESSION["user_username"]=$username;
        $pdo=null;
        $stmt=null;
        header("Location:../info.php");
        exit();
    }catch(PDOException $e){
        die("Error:".$e);
    }
}else{
    die(header("Location:../info.php"));
}
?>