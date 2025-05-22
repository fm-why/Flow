<?php
function getEmail($pdo,$email){
    $query="SELECT email FROM users WHERE email = :email;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam("email",$email);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function AddTemp($pdo,$email,$key,$expDate){
    $query="INSERT INTO password_reset_temp(email,`key`, expDate) VALUES (:email, :key, :expDate);";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("email",$email);
    $stmt->bindParam("key",$key);
    $stmt->bindParam("expDate",$expDate);
    $stmt->execute();
}
function getTempFromEmail($pdo,$email){
    $query="SELECT * FROM password_reset_temp WHERE  email=:email;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("email",$email);
    $stmt->execute();

    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}

function getTemp($pdo,$email,$key){
    $query="SELECT * FROM password_reset_temp WHERE  email=:email and `key`=:key;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("email",$email);
    $stmt->bindParam("key",$key);
    $stmt->execute();

    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function updatePassword($pdo,$pass,$email){
    $query="UPDATE users SET pass=:pass WHERE email=:email;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("pass",$pass);
    $stmt->bindParam("email",$email);
    $stmt->execute();

}
function removeTemp($pdo,$email){
    $query="DELETE FROM password_reset_temp WHERE email=:email";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam("email",$email);
    $stmt->execute();
}
?>