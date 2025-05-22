<?php
require_once "../config_session.in.php";
require_once "../dbh.in.php";
require_once "query.php";
$result=getUserInformation($pdo,$_SESSION["user_id"]);
$nome= isset($result["nome"]) ? htmlspecialchars($result["nome"]) : "";
$bio= isset($result["bio"]) ? htmlspecialchars($result["bio"]) : "";
$path= isset($result["pfp"]) ? htmlspecialchars($result["pfp"]) : "default.png";
$_SESSION["img"]=$path;
if(!isset($_SESSION["user_id"])) header("Location:../index.php");
if(!isset($_SESSION["first-time"]) and !$result) $_SESSION["first-time"]=true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../logo.ico">
    <title>Impostazioni</title>
</head>
<body class="body">
    <div id="personali" class="wrapper" style=<?php if(!isset($_SESSION["first-time"])) echo "position:absolute;z-index:10;display:none;";?>>
    <div style="display:flex;justify-content:center;"><?php if(!isset($_SESSION["first-time"])){ ?><i style="cursor:pointer;position: absolute;left:10%;top:9%;color:#722F37;border-style:solid;border-radius:50%" onclick="HidePersonali()" class="fa fa-arrow-left"></i><?php } ?><h1 style="font-size:20px;margin-bottom:30px;">Informazioni personali</h1></div>
    <div style="display:flex;flex-direction:row;justify-content:space-around;"><img id="img" src=<?php echo '"pfp/'.$path.'"';?> onclick="fileChoose()"><i id="x" class="fa fa-close img-remove" style="display:none;" onclick="ripristino()"></i><button class="img-btn2" onclick="fileChoose()">Cambia foto</button><button class="img-btn" onclick="remove()">Rimuovi foto</button></div>
    <form action="UpdateInfo/insert_info.php" method="POST" enctype="multipart/form-data">
        <div class="input-container">
        <div class="input-box"><input id="nome" type="text" placeholder="Nome" name="name" value=<?php if(isset($_SESSION["info-data"])) echo '"'.htmlspecialchars($_SESSION["info-data"]["nome"]).'"';else echo '"'.$nome.'"';?>></div>
        <div class="input-box"><input id="bio" type="text" placeholder="Qualcosa su di te" name="bio" value=<?php if(isset($_SESSION["info-data"])){echo '"'.htmlspecialchars($_SESSION["info-data"]["nome"]).'"';unset($_SESSION["info-data"]);}else echo '"'.$bio.'"';?>></div>
        <input type="file" name="file" id="file" style="display:none;">
        <input type="checkbox" name="removed" id="removed" style="display:none;">
        </div>
        <button type="submit" class="btn">Invio</button>
    </form>
    <div class="errors">
            <?php
                if(isset($_SESSION["info-errors"])){
                    foreach($_SESSION["info-errors"] as $error){
                        echo "<p class='form-error'>".$error."</p>";
                    }
                    unset($_SESSION["info-errors"]);
                }
            ?>
            </div>
    </div>
    <?php if(!isset($_SESSION["first-time"])){
        $result=getUser($pdo,$_SESSION["user_id"]);
        $username=$result["username"];
        $email=$result["email"];
    ?>
    <input id="burger" type="checkbox" role="button" aria-label="Display the menu" class="burger" onclick="toggleMenu()">
    <nav class="menu" id="menu">
        <div style="display:flex;flex-direction:row;"><img id="img" src="../logo.png" alt="logo"><h1 class="typewriter" style="color: #722F37;margin-top:25px;">Flow</h1></div>
        <a class="links" href="../HomePage/index.php"><i class="fa fa-home"></i><h1>Home</h1></a>
        <a class="links" href="../Search/index.php"><i class="fa fa-search"></i><h1>Cerca</h1></a>
        <div class="pfp-menu"  onclick="toggleProfile()"><img id="img" src=<?php echo '"../UserInfo/pfp/'.$path.'"'; ?> alt="">
            <div id="profile" class="profile" style="display: none;">
                <a href="../Profile/index.php" class="links"><i class="fa fa-user"></i><h1>Il tuo account</h1></a>
                <a href="../UserInfo/info.php" class="links link-active"><i class="fa fa-gear"></i><h1>Impostazioni</h1></a>
                <form class="links" action="../Logout/logout.php" method="POST" onclick="submit()" style="cursor:pointer;"><i class="fa fa-sign-out"></i><h1>Esci</h1></form>
            </div>
        </div>
    </nav>
    <div id="accesso" class="wrapper" style="position:absolute;z-index:10;display:none;">
    <div style="display:flex;justify-content:center;"><i style="cursor:pointer;position: absolute;left:10%;top:9%;color:#722F37;border-style:solid;border-radius:50%" onclick="HideAccesso()" class="fa fa-arrow-left"></i><h1 style="font-size:20px;margin-bottom:30px;">Informazioni d'accesso</h1></div>
    <form action="UpdateInfo/update_access.php" method="POST">
    <div class="input-box">
                <input type="text" placeholder="Email" name="email" id="email-signup" value=<?php if(isset($_SESSION["update_data"])) echo '"'.htmlspecialchars($_SESSION["update_data"]["email"]).'"';else echo '"'.htmlspecialchars($email).'"';?>>
                <i id="pass" class='fa fa-envelope'></i>
            </div>
            <div class="input-box">
                <input type="text" placeholder="Username" name="username" id="user-signup" value=<?php if(isset($_SESSION["update_data"])){echo '"'.htmlspecialchars($_SESSION["update_data"]["username"]).'"';unset($_SESSION["update_data"]);}else echo '"'.htmlspecialchars($username).'"';?>>
                <i class='fa fa-user'></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Password" name="pass">
                <i style="cursor: pointer;" class='pass fa fa-eye'></i>
            </div>
            <button type="submit" class="img-btn2" style="width: 100%;">Invio</button>
            
    </form>
    <button class="btn" style="width:100%;margin-top: 40px;" onclick="toggleDelete()">Elimina Account</button>
    <div class="wrapper" id="delete" style="display: none;position:absolute;top:0%;left:10%;background-color:#EFDFBB;width:80%">
    <h1 style="font-size:20px;margin-bottom:30px;">Cancellare il proprio account?</h1>
    <h2 style="margin-bottom: 15px;">Sei sicuro di voler cancellare il tuo account <span style="color: #722F37;">@<?php echo $result["username"];?></span>?</h2>
    <div style="display:flex;justify-content: center;"><img style="border-radius: 50%;margin-bottom:15px;aspect-ratrio:1-1;width: 200px;" src=<?php echo '"pfp/'.$path.'"';?>></div>
    <p style="color: gray;margin-bottom:15px;">"Ogni progresso non salvato andrà perduto definitivamente, vuoi procedere?"</p>
    <div style="display: flex; flex-direction: row; justify-content: space-between;"><button class="img-btn2" onclick="toggleDelete()">Annulla</button><form action="Delete/delete.php" method="POST"><button class="img-btn">Elimina</button></form></div>
    </div>
    <div class="errors">
            <?php
                if(isset($_SESSION["update_error"])){
                    foreach($_SESSION["update_error"] as $error){
                        echo "<p class='form-error'>".$error."</p>";
                    }
                    unset($_SESSION["update_error"]);
                }
                $pdo=null;
                $stmt=null;
            ?>
            </div>
    </div>
    <div>
        <div class="container" id="main" style="">
            <div style="display:flex;justify-content:center;"><a href="../Profile/"><i style="position: absolute;left:10%;top:9%;color:#722F37;border-style:solid;border-radius:50%" class="fa fa-arrow-left"></i></a><h1>Impostazioni</h1></div>
            <div class="box box-anim" onclick="ShowPersonali()">
                <h1>Informazioni personali</h1>
                <p>Modifica delle informazioni personali</p>
            </div>
            <div class="box box-anim" onclick="ShowAccesso()">
                <h1>Informazioni d'accesso</h1>
                <p>Modifica delle informazioni d'accesso</p>
            </div>
        </div>
    </div><?php
            }?>
    
</body>
<script>
    //apertura schermata profilo nel menu
    function toggleProfile(){
        const profile=document.getElementById("profile");
        if(profile.style.display==="block"){
            profile.style.display="none";
        }else{
            profile.style.display="block";
        }
    }
    //funzione per aprire il menu su mobile
    function toggleMenu(){
        menu=document.getElementById("menu");
        if(menu.style.display==="none"){
            menu.style.display="block";
        }else{
            menu.style.display="none";
        }
    }
    //funzionalità AJAX
    $("document").ready(() => {
         //aggiunge nell'html display:none al menù se ci troviamo su un dispositivo mobile
        if(window.matchMedia("(max-width:600px)").matches){
            document.getElementById("menu").style.display="none";
        }
        $("#nome").keyup(debounce( ()=>{
            if($("#nome").val() != ""){
                $.post("AJAX/controll_name.php",{ nome : $("#nome").val()},function(data,status){
                    console.log(data);
                    if(data==""){
                        $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Nome non valido</p>',""));
                        $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Immettere il nome</p>',""));
                    }
                    else{
                        if(!($(".errors:first").html().includes("Nome") && $(".errors:first").html().includes("nome"))){
                            $(".errors:first").html($(".errors:first").html()+data);
                        }
                        else{
                            $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Nome non valido</p>',data));
                            $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Immettere il nome</p>',data));
                        }
                    }
                })
            }else{
                $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Nome non valido</p>',""));
                $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Immettere il nome</p>',""));
            }
        },1000));
        $("#bio").keyup(debounce( ()=>{
            if($("#bio").val() != ""){
                $.post("AJAX/controll_bio.php",{ bio : $("#bio").val()},function(data,status){
                    if(data==""){
                        $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Biografia non valida</p>',""));
                    }
                    else{
                        if(!($(".errors:first").html().includes("Biografia"))){
                            $(".errors:first").html($(".errors:first").html()+data);
                        }
                        else{
                            $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Biografia non valida</p>',data));
                            
                        }
                    }
                })
            }else{
                $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Biografia non valida</p>',""));
            }
        },1000));
        $("#file").on("change", () => {
            $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Il file deve essere più piccolo di 2 MB</p>',""));
            $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Il file non è un immagine</p>',""));
            if($("#file").val()===""){
                if("<?php echo $path;?>" == "default.png"){
                    document.getElementById("x").style="display:none;";
                }
                $("#img").attr("src","pfp/default.png");
            }else{
                const [file]= $("#file").prop('files');
                if(!(file.name.includes(".png")||file.name.includes(".jpeg")||file.name.includes(".jpg"))){
                    $(".errors:first").html($(".errors:first").html()+'<p class="form-error">Il file non è un immagine</p>');
                    document.getElementById("file").value="";

                }else{
                    if(file.size>5000000){
                        $(".errors:first").html($(".errors:first").html()+'<p class="form-error">Il file deve essere più piccolo di 5 MB</p>');
                        document.getElementById("file").value="";
                    }else{
                        document.getElementById("removed").checked=false;
                        document.getElementById("x").style="display:block;";
                        $("#img").attr("src",URL.createObjectURL(file));
                    }
                }
                
            }
        });
});

//funzioni per l'apertura dei menù
function ShowAccesso(){
    document.getElementById("main").style="filter: blur(2px);"
    boxes=document.getElementsByClassName("box");
    for(let box of boxes){
        box.classList.remove("box-anim");
        box.classList.add("disable");
    }
    document.getElementById("accesso").style="position:absolute;z-index:10;display:block;";
}
function HideAccesso(){
    document.getElementById("main").style="";
    boxes=document.getElementsByClassName("box");
    for(let box of boxes){
        box.classList.add("box-anim");
        box.classList.remove("disable");
    }
    document.getElementById("accesso").style="position:absolute;z-index:10;display:none;";
}
function ShowPersonali(){
    document.getElementById("main").style="filter: blur(2px);"
    boxes=document.getElementsByClassName("box");
    for(let box of boxes){
        box.classList.remove("box-anim");
        box.classList.add("disable");
    }
    document.getElementById("personali").style="position:absolute;z-index:10;display:block;";
}
function HidePersonali(){
    document.getElementById("main").style="";
    boxes=document.getElementsByClassName("box");
    for(let box of boxes){
        box.classList.add("box-anim");
        box.classList.remove("disable");
    }
    document.getElementById("personali").style="position:absolute;z-index:10;display:none;";
}
function toggleDelete(){
    const deletes=document.getElementById("delete");
    if(deletes.style.display==="block"){
        deletes.style.display="none";
    }else{
        deletes.style.display="block";
    }
} 
//aggiunta delay alla funzione 
 function debounce(callback, wait) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(function () { callback.apply(this, args); }, wait);
        };
    }
//funzione per la selezione del file cliccando sull'immagine
    function fileChoose(){
        document.getElementById("file").click();
    }
    //funzione per la rimozione della foto
    function remove(){
        document.getElementById("file").value="";
        document.getElementById("removed").checked=true;
        $("#file").trigger("change");
        if("<?php echo $path;?>"!=="default.png"){
            document.getElementById("x").style="display:block;";
        }
        $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Il file deve essere più piccolo di 2 MB</p>',""));
        $(".errors:first").html($(".errors:first").html().replace('<p class="form-error">Il file non è un immagine</p>',""));
    }
    //funzione per ripristinare l'immagine di partenza

    function ripristino(){
        document.getElementById("img").src="pfp/<?php echo $path;?>";
        document.getElementById("file").value="";
        document.getElementById("x").style="display:none;";
        document.getElementById("removed").checked=false;
    }
    //funzione per mostrare/nascondere la password nella form di login/signup
    function showHide(input, showText) {
        if (input.getAttribute("type") === "password") {
            input.setAttribute("type", "text");
            showText.classList.remove("fa-eye");
            showText.classList.add("fa-eye-slash");
            placeholder=input.value;
            const letters="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            let iterations=0;
            const interval=setInterval(() => {
                input.value=input.value.split("").map((letter,index)=>{
                    if(index<iterations){
                        return placeholder[index];
                    }
                    return letters[Math.floor(Math.random()*52)]}).join("");
                if(iterations>=placeholder.length) clearInterval(interval);
                iterations+=1/3;
            },3);

        } else {
            showText.classList.remove("fa-eye-slash");
            showText.classList.add("fa-eye");
            value=input.value;
            placeholder="•".repeat(value.length);
            const letters="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            let iterations=0;
            const interval=setInterval(() => {
                input.value=input.value.split("").map((letter,index)=>{
                    if(index<iterations){
                        return placeholder[index];
                    }
                    return letters[Math.floor(Math.random()*52)]}).join("");
                if(iterations>=placeholder.length){
                    clearInterval(interval);
                    input.setAttribute("type", "password");
                    input.value=value;
                }
                iterations+=1/3;
            },3);
            
        }
    }
    //aggiunta degli event listener alle icone con chiamata alla funzione
    passs=document.getElementsByClassName("pass");
    for(let pass of passs){
        pass.addEventListener("click",()=>{
            showHide(pass.previousElementSibling,pass);
        });
    }
</script>
</html>