<?php 
//check se entrati da post altrimenti butta fuori
if($_SERVER["REQUEST_METHOD"]==="POST"){
    //retrive dati
    $username=$_POST["username"];
    $pass=$_POST["pass"];
    //chiamata alle funzioni
    require_once "../dbh.in.php";
    require_once "error.php";
    require_once "query.php";

    try{
        //check errori
        $result=getUsername($pdo,$username);
        $ERRORS=[];
        if(is_username_invalid($username)){
            $ERRORS["invalid_credentials"]="Credenziali non valide";
        }else{
            if(username_not_exist($result)){
                $ERRORS["invalid_credentials"]="Credenziali non valide";
            }
        }
        if(is_password_inccorrect($result,$pass) and (!isset($ERRORS["username_inexist"]) and !isset($ERRORS["Invalid_username"]))){
            $ERRORS["invalid_credentials"]="Credenziali non valide";
        }
        //invio errori
        require_once "../config_session.in.php";
        if($ERRORS){
            $_SESSION["errors_login"] = $ERRORS;
            $loginData = [
                "username"=> $_POST["username"],
            ];
            $_SESSION["login_data"] = $loginData;
            header("Location:../index.php");
            exit();
        }
        //set cookie per login automatico (REMEMBER ME)
        if(!empty($_POST["remember"])){
            setcookie("username",$username,time()+3600*24*30,"/");
            setcookie("password",$pass,time()+3600*24*30,"/");
        }
        //check se l'utente ha già caricato i dati e finalizzazione del login
        $_SESSION["user_id"] = $result["id_user"];
        if($result["isAdmin"]){$_SESSION["isAdmin"]=true;}
        $_SESSION["user_username"] = htmlspecialchars($result["username"]);
        $_SESSION["last_regeneration"] = time();
        $result=getUserInfo($pdo,$result["id_user"]);
        if(!$result){
            $_SESSION["first_time"]=true;
            header("Location: ../UserInfo/info.php");
        }
        else{
            header("Location: ../HomePage/index.php");
        }
        
        $pdo = null;
        $stmt = null;
        die();
    }catch(PDOException $e){
        die("Query failed: ". $e->getMessage());
    }
}
else{
    header("Location: ../index.php");
    die();
}