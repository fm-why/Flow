<?php require_once "config_session.in.php";
if(isset($_SESSION["user_id"])) header("Location: HomePage/index.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="logo.ico">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <title>Flow</title>
</head>
<body class="body">
    <div class="background">
        <div class="logo-text"><h2 class="text-shadow">Life is a river</h2><h2 style="color:#722F37 ;" class="typewriter">Follow the flow</h2></div>
    <img src="logo.png" alt="" class="logo1">
    <div class="wrapper" style="width: 400px;display:flex;flex-direction:column;gap:15px;" id="control">
        <h1>Iscriviti adesso.</h1>
        <button class="btn" onclick="showSignup()">Registrati</button>
        <div class="text-divider"><h2>Hai già un account?</h2></div>
        <button class="img-btn2" style="width:100%" onclick="showLogin()">Accedi</button>

    </div>
    </div>
<div class="wrapper" style=<?php if(isset($_SESSION["signup"])){echo "display:none;";} else{echo "display:none;";}?> id="login">
<i style="cursor:pointer;position: absolute;left:10%;top:9%;color:#722F37;border-style:solid;border-radius:45%" onclick="HideLogin()" class="fa fa-close"></i>
    <form action="Login/login.php" method="post">
            <h1>Log in</h1>
            <div class="input-box">
                <input type="text" placeholder="Username" name="username" id="user-login" value=<?php if(isset($_COOKIE["username"])){echo htmlspecialchars($_COOKIE["username"]);}else if(isset($_SESSION["login_data"])){echo '"'.htmlspecialchars($_SESSION["login_data"]["username"]).'"';unset($_SESSION["login_data"]);}?>>
                <i class='fa fa-user'></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Password" name="pass" value="<?php if(isset($_COOKIE["password"])) echo htmlspecialchars($_COOKIE["password"]);?>">
                <i style="cursor: pointer;" class='pass fa fa-eye'></i>
            </div>
            <div class="remember-forget">
                <label ><input type="checkbox" name="remember">
                Remember me</label>
                <a href="Forgot_Password"> Forgot Password?</a>
            </div>
            <button type="submit" class="btn">Log in</button>
            <div class="register-link">
                <p>Non hai un account?  <a class="switch">Registrati</a></p>
            </div>
            <div class="errors">
            <?php
                if(isset($_SESSION["errors_login"])){
                    foreach($_SESSION["errors_login"] as $error){
                        echo "<p class='form-error'>".$error."</p>";
                    }
                    unset($_SESSION["errors_login"]);
                }
            ?>
            </div>
        </form>
    </div>
    <div class="wrapper" style=<?php if(isset($_SESSION["signup"])){echo "display:block;";unset($_SESSION["signup"]);} else{echo "display:none;";}?> id="signup">
    <i style="cursor:pointer;position: absolute;left:10%;top:9%;color:#722F37;border-style:solid;border-radius:45%" onclick="HideSignup()" class="fa fa-close"></i>
        <form action="SignUp/signup.php" method="post">
            <h1>Sign up</h1>
            <div class="input-box">
                <input type="text" placeholder="Email" name="email" id="email-signup" value=<?php if(isset($_SESSION["signup_data"])) echo '"'.htmlspecialchars($_SESSION["signup_data"]["email"]).'"';?>>
                <i id="pass" class='fa fa-envelope'></i>
            </div>
            <div class="input-box">
                <input type="text" placeholder="Username" name="username" id="user-signup" value=<?php if(isset($_SESSION["signup_data"])){echo '"'.htmlspecialchars($_SESSION["signup_data"]["username"]).'"';unset($_SESSION["signup_data"]);}?>>
                <i class='fa fa-user'></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Password" name="pass">
                <i style="cursor: pointer;" class='pass fa fa-eye'></i>
            </div>
            
            <button type="submit" class="btn">Sign up</button>
            <div class="register-link">
                <p>Hai già un account?<a class="switch">Log in</a></p>
            </div>
            <div class="errors">
            <?php
                if(isset($_SESSION["errors_signup"])){
                    foreach($_SESSION["errors_signup"] as $error){
                        echo "<p class='form-error'>".$error."</p>";
                    }
                    unset($_SESSION["errors_signup"]);
                }
            ?>
            </div>
        </form>
    </div>
</body>
<script>

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
    //switch tra form di login e signup
    switches=document.getElementsByClassName("switch");
    containers=document.getElementsByClassName("wrapper");
    containers=Array.from(containers);
    containers.shift();
    for(let i=0;i<2;i++){
        switches[i].addEventListener("click",()=>{
            if(containers[i].style.display==="block"){
                containers[i].style.display="none";
                containers[i===0 ? i+1 : i-1].style.display="block";
            }
            else{
                containers[i].style.display="block";
                containers[i===0 ? i+1 : i-1].style.display="none";
            }
        });
    }
    //funzione per mostare la form di login
    function showLogin(){
        control=document.getElementById("control")
        login=document.getElementById("login");
        login.style.display="block";
        control.style.display="none";
    }
    //funzione per mostare la form di signup
    function showSignup(){
        control=document.getElementById("control")
        signup=document.getElementById("signup");
        signup.style.display="block";
        control.style.display="none";
    }
    //funzione per nascondere la form di login
    function HideLogin(){
        control=document.getElementById("control")
        login=document.getElementById("login");
        login.style.display="none";
        control.style.display="block";
    }
    //funzione per nascondere la form di signup
    function HideSignup(){
        control=document.getElementById("control")
        signup=document.getElementById("signup");
        signup.style.display="none";
        control.style.display="block";
    }
    //aggiunta delay alla funzione 
    function debounce(callback, wait) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(function () { callback.apply(this, args); }, wait);
        };
    }
    //funzionalità AJAX per la verifica dell'username nella login
    $("document").ready(()=>{
        $("#user-login").keyup(debounce( ()=>{
            if($("#user-login").val() != ""){
                $.post("Login/AJAX/controll_username.php",{ username : $("#user-login").val()},function(data,status){
                    $(".errors:first").html(data);
                })
            }else{
                $(".errors:first").html("");
            }
        },1000))
        
    })


</script>
</html>