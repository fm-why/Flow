<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="../logo.ico">
    <title>Reset Password</title>
</head>
<body class="body">
    <?php
        require_once "../config_session.in.php";
        $valid=false;
        if(isset($_GET["key"]) and isset($_GET["email"]) and isset($_GET["action"]) and ($_GET["action"]=="reset") and !isset($_POST["action"])){
            require_once "../dbh.in.php";
            require_once "SendEmail/query.php";
            try{
                $key=$_GET["key"];
                $email=$_GET["email"];
                $result=getTemp($pdo,$email,$key);
                if($result){
                    $date=new DateTime($result["expDate"]);
                    $now=new DateTime();
                    if($date<$now){
                        $valid=false;
                        //deleteTemp($pdo,$email,$key);
                    }else $valid=true;
                }
            }catch(PDOException $e){die("Error:".$e);}
        }
        if($valid){
            $_SESSION["email"]=$email;
            $_SESSION["key"]=$key;
    ?>
    <div class="wrapper">
        <h1>Reset Password</h1>
        <form action="SendEmail/reset.php" method="post">
            <div class="input-box">
                <input type="password" placeholder="Password" name="pass" class="pwd">
                <i style="cursor: pointer;" class='pass fa fa-eye'></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Conferma Password" name="conf-pass" class="pwd">
                <i style="cursor: pointer;" class='pass fa fa-eye'></i>
            </div>
            <button type="submit" class="btn" id="btn">Invio</button>
        </form>
        <div id="error">
            <?php
                if(isset($_SESSION["reset_errors"])){
                    foreach($_SESSION["reset_errors"] as $error){
                        echo '<p class="form-error">'.$error.'</p>';
                    }
                    unset($_SESSION["reset_errors"]);
                }
            ?>
        </div>
    </div>
    <?php
    }
    else{
        if(!$valid and isset($_SESSION["reset"])){
            unset($_SESSION["reset"]);
    ?>
    <div class="wrapper">
        <h1>Congratulazioni</h1>
        <p>La tua password è stata aggiornata con successo. Puoi effettuare il log in </p>
        <a style="display:flex;gap:2%;color:#722F37;text-decoration:none;" href="../index.php"><i style="border-style:solid;border-radius:50%;cursor:pointer" class="fa fa-arrow-left"></i><p style="cursor:pointer;text-decoration:underline;">Torna indietro</p></a>
    </div>
    <?php
            }
            else{
    ?>
    <div class="wrapper">
        <h1>Sei arrivato tardi</h1>
        <p>Questo link è scaduto o non è valido. </p>
        <a style="display:flex;gap:2%;color:#722F37;text-decoration:none;" href="../index.php"><i style="border-style:solid;border-radius:50%;cursor:pointer" class="fa fa-arrow-left"></i><p style="cursor:pointer;text-decoration:underline;">Torna indietro</p></a>
    </div> <?php } 
    }
    ?>
</body>
<script>
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
    //funzione per l'aggiunta del delay
    function debounce(callback, wait) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(function () { callback.apply(this, args); }, wait);
        };
    }
    //funzionalità AJAX
    $("document").ready(()=>{
        $(".pwd:last").keyup(debounce( ()=>{
            if($(".pwd:last").val()!= ""){
                if($(".pwd:first").val()!==$(".pwd:last").val()){
                    $("#error").html("<p class='form-error'>La password non coincide");
                }else $("#error").html("");
            }else $("#error").html("");
        },1000))
    });
</script>
</html>

