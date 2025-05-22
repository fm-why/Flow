<?php
function getUsername($pdo,$username){
    $query="SELECT * FROM users WHERE username = :username;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam("username", $username);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function getIdbyUsername($pdo,$username){
    $query="SELECT id_user FROM users WHERE username = :username;";
    $stmt = $pdo->prapare($query);
    $stmt->bindParam("username",$username);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function getEmail($pdo,$email){
    $query="SELECT email FROM users WHERE email = :email;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam("email",$email);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
function createUser($pdo,$username,$email,$pass){
    $query = "INSERT INTO users(username,email,pass) VALUES(:username, :email , :pass);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam("username",$username);
    $stmt->bindParam("email",$email);
    $stmt->bindParam("pass",$pass);
    $stmt->execute();
}
?>