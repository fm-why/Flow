<?php 
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        //retrive dati
        require_once "../../config_session.in.php";
        $pass=$_POST["pass"];
        $conferma=$_POST["conf-pass"];
        $email=$_SESSION["email"];
        $key=$_SESSION["key"];
        //chiamata alle funzioni
        require_once "error.php";
        require_once "../../dbh.in.php";
        //error handling
            try{
                $ERRORS=[];
                if($pass!=$conferma){
                    $ERRORS["diseguali"]="La password non corrisponde";
                }else{
                    if(is_password_invalid($pass)){
                        $ERRORS["invalid"]="La password non è valida";
                    }
                }
                if($ERRORS){
                    $_SESSION["reset_errors"]=$ERRORS;
                    header("Location:../reset-password.php?key=".$key."&email=".$email."&action=reset");
                    unset($_SESSION["email"]);
                    unset($_SESSION["key"]);
                    die();
                }
                unset($_SESSION["email"]);
                unset($_SESSION["key"]);
                require_once "query.php";
                updatePassword($pdo,password_hash($pass,PASSWORD_BCRYPT),$email);
                removeTemp($pdo,$email);
                if(isset($_COOKIE["password"])){
                    unset($_COOKIE['password']); 
                    setcookie('password', '', -1, '/'); 
                }
                $pdo=null;
                $stmt=null;
                $_SESSION["reset"]=true;
                header("Location:../reset-password.php");
                exit();
                
        }catch(PDOException $e){die("Error:".$e);}


    }else die(header("Location:../reset-password.php"));
?>