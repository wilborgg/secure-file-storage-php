<?php
session_start();

$username = trim($_POST["username"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";

if($username===""){
    $_SESSION["register_error"] = "Username must not be empty.";
    header("Location: register.php");
    exit;
}
elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $_SESSION["register_error"] = "Email must be a valid email format.";
    header("Location: register.php");
    exit;
}
elseif(strlen($password)<8){
    $_SESSION["register_error"] = "Password must be at least 8 characters long.";
    header("Location: register.php");
    exit;
}
elseif($password!==$confirm_password){
    $_SESSION["register_error"] = "Password must perfectly match the Confirm Password.";
    header("Location: register.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=db_upload_exercise","root","");
$stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE username = ? OR email = ?");
$stmt->execute([$username,$email]);
$ada = $stmt->fetch();

if($ada){
    if($ada["username"]===$username){
        $_SESSION["register_error"] = "Username already exist.";
        header("Location: register.php");
        exit;
    }
    else{
        $_SESSION["register_error"] = "Email already exist.";
        header("Location: register.php");
        exit;
    }
}

$hash = password_hash($password,PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users(username, email, password) VALUES (?, ?, ?)");
$stmt->execute([$username,$email,$hash]);

header("Location: login.php");
exit;