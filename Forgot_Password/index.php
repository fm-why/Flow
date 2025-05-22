<?php
    require_once "../config_session.in.php";
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
    <title>Password Dimenticata</title>
</head>
<body class="body">
    <div class="wrapper">
        <i id="x" class="fa fa-close" style="position:absolute;left:5%;top:5%;color:#722F37;cursor:pointer;" onclick="back()"></i>
        <h1 style="white-space: nowrap">Trova il tuo account</h1>
        <p style="color:grey;">Per modificare la password, inserisci l'indirizzo email associato al tuo account.</p>
        <form action="SendEmail/send.php" method="POST">
            <div class="input-box">
                <input type="text" placeholder="Email" name="email" id="email-signup" value=<?php if(isset($_SESSION["forgot_data"])){echo '"'.$_SESSION["forgot_data"]["email"].'"';unset($_SESSION["forgot_data"]);}?>>
                <i id="pass" class='fa fa-envelope'></i>
            </div>
            <button type="submit" class="btn">Invio</button>
        </form>
        <div class="errors">
            <?php
                if(isset($_SESSION["forgot_errors"])){
                    foreach($_SESSION["forgot_errors"] as $error){
                        echo "<p class='form-error'>".$error."</p>";
                    }
                    unset($_SESSION["forgot_errors"]);
                }
            ?>
            </div>
    </div>

</body>

<script>
    //funzione per reindirizzamento alla pagina di login
    function back(){
        window.location.href = "../index.php";
    }
     //aggiunta delay alla funzione 
    function debounce(callback, wait) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(function () { callback.apply(this, args); }, wait);
        };
    }
    //funzioni AJAX
    $("document").ready(() =>{
        $("#email-signup").keyup(debounce( ()=>{
            if($("#email-signup").val() != ""){
                $.post("AJAX/controll_email.php",{ email : $("#email-signup").val()},function(data,status){
                    $(".errors").html(data);
                    
                })
            }else{
                $(".errors").html("");
            }
        },1000));
    });
</script>
</html>