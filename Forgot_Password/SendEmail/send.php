<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include_once('../../vendor/autoload.php'); 
if($_SERVER["REQUEST_METHOD"]==="POST"){
    //retrive dati
    $email=$_POST["email"];
    //chiamata alle funzioni
    require_once "../../dbh.in.php";
    require_once "error.php";
    require_once "query.php";
    require_once "../../config_session.in.php";

    try{
        //error handling
        $ERRORS=[];
        if(is_email_invalid($email)){
            $ERRORS["send"]="È stata mandata un email con le istruzioni per il reset della password";
        }else{
            if(email_not_exist($pdo,$email)){
                $ERRORS["send"]="È stata mandata un email con le istruzioni per il reset della password";
            }
        }
        if(is_reset_alredy_available($pdo,$email)){
            $ERRORS["send"]="È stata mandata un email con le istruzioni per il reset della password";
        }

        if($ERRORS){
            $_SESSION["forgot_errors"]=$ERRORS;
            header("Location:../index.php");
            $pdo=null;
            $stmt=null;
            die();
        }
        $servermail=getenv("EMAIL");
        $serverpass=getenv("PASSWORD");
        $protocol=(!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]!== "off" || $_SERVER["SERVER_PORT"] == 443)?"https://":"http://";
        $domain=$_SERVER["HTTP_HOST"];
        $url=$protocol.$domain;
        $expFormat = mktime(date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y"));
        $expDate = date("Y-m-d H:i:s",$expFormat);
        $key = md5($email);
        $addKey = substr(md5(uniqid(rand(),1)),3,10);
        $key = $key . $addKey;
        AddTemp($pdo,$email,$key,$expDate);
        $output='<p>Caro user,</p>';
        $output.='<p>Per favore clicca il seguente link per ressettare la tua password.</p>';
        $output.='<p>-------------------------------------------------------------</p>';
        $output.='<p><a href="'.$url.'/Forgot_Password/reset-password.php?
        key='.$key.'&email='.$email.'&action=reset" target="_blank">
        '.$url.'/Forgot-Password/reset-password.php
        ?key='.$key.'&email='.$email.'&action=reset</a></p>';		
        $output.='<p>-------------------------------------------------------------</p>';
        $output.='<p>Questo link scadrà tra 1 giorno per motivi di sicurezza.</p>';
        $output.='<p>Se non hai richiesto il reset della password,nessuna azione sarà necessaria,la tua password non verrà resettata.
        È consigliato ,però, accedere al proprio account e cambiare le impostazioni di accesso in quanto qualcuno potrebbe averle indovinate.</p>';   	
        $body = $output; 
        $email_to = $email;
        $fromserver =$servermail ; 
        $subject="noreply-Reset Password";

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = "smtp.gmail.com"; // Enter your host here
        $mail->SMTPAuth = true;
        $mail->Username = $servermail; // Enter your email here
        $mail->Password = $serverpass; //Enter your password here
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = getenv("PORT");;
        $mail->IsHTML(true);
        $mail->From = $servermail;
        $mail->FromName = "Flow";
        $mail->Sender = $fromserver; // indicates ReturnPath header
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($email_to);

        if(!$mail->Send()){
            $ERRORS["mail"]=$mail->ErrorInfo;
        }else{
            $ERRORS["send"]="È stata mandata un email con le istruzioni per il reset della password";
        }
        $_SESSION["forgot_errors"]=$ERRORS;
        header("Location:../index.php");
        $pdo=null;
        $stmt=null;
        die();
    }catch(PDOException $e){
        die("Error:".$e);
    }
}else{
    die(header("Location:../index.php"));
}
?>