<?php
session_start();

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

$pdo = new PDO("mysql:host=localhost;dbname=db_upload_exercise","root","");
$stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if(!$user||!password_verify($password,$user["password"])){
    $_SESSION["login_error"] = "Wrong username or password combination.";
    header("Location: login.php");
    exit;
}

session_regenerate_id(true);
$_SESSION["auth_user"] = [
    "id" => $user["id"],
    "username" => $user["username"]
];
header("Location: index.php");
exit;