<?php
if($_SERVER["REQUEST_METHOD"]==="POST"){
    //retrive dati
    $name=$_POST["name"];
    $bio=$_POST["bio"];
    $file=$_FILES["file"];
    //chiamata alle funzioni
    require_once "error.php";
    require_once "../../config_session.in.php";
    //error handler
    $ERRORS=[];
    if(empty($name) and empty($bio) and empty($file["name"])){
        $ERRORS["empty"]="Immettere dei dati";
    }
        if(empty($name) and isset($_SESSION["first-time"])){
            $ERRORS["empty-name"]="Immettere il nome";
        }
        if(isNameInvalid($name) and !empty($name)){
            $ERRORS["Invalid-name"]="Nome non valido";
        }
        if(isBioInvalid($bio) and !empty($bio)){
            $ERRORS["Invalid-bio"]="Biografia non valida";
        }
        if(!empty($file["name"])){
            if($file["error"] !== 0){
                $ERRORS["img_error"] = "C'è stato un errore caricando l'immagine";
            }
            if($file["size"]>5000000){
                $ERRORS["img_size"] = "Il file deve essere più piccolo di 5 MB";
            }
            if(isExtensionInvalid($file)){
                $ERRORS["img_extension"] = "Il file non è un immagine";
            }
        }
        

    //invio errori
    if($ERRORS){
        $_SESSION["info-errors"]=$ERRORS;
        $insertData=[
            "nome"=>$name,
            "bio"=>$bio,
        ];
        $_SESSION["info-data"]=$insertData;
        header("Location:../info.php?success=false");
        die();
    }
    //insert o update dei dati
    require_once "query.php";
    require_once "../../dbh.in.php";
    try{
        if(isset($_SESSION["first-time"])){
            if(!empty($file["name"])) $file=saveImg($file);
            else $file=null;
            insertInfo($pdo,$name,$bio,$file,$_SESSION["user_id"]);
            unset($_SESSION["first-time"]);
            $pdo=null;
            $stmt=null;
            header("Location:../../HomePage/index.php");
            exit();
        }else{
            $result=getUserInformation($pdo,$_SESSION["user_id"]);
            $name=empty($name) ? $result["nome"] : $name;
            $bio=empty($bio) ? $result["bio"] : $bio;
            if(empty($file["name"])){
                if(empty($result["pfp"])){
                    $file=null;
                }else{
                    if($_POST["removed"]){$file=null;unlink("../pfp/".$result["pfp"]);}
                    else $file=$result["pfp"];
                }
            }else if(!empty($result["pfp"])){
                $file=saveImg($file);
                unlink("../pfp/".$result["pfp"]);
            }else $file=saveImg($file);
            UpdateInfo($pdo,$name,$bio,$file,$_SESSION["user_id"]);
            $pdo=null;
            $stmt=null;
            header("Location:../info.php");
            exit();
        }
        
    }catch(PDOException $e){
        die("Error:".$e);
    }

}else{
    //header("Location:../info.php");
    die();
}
?>