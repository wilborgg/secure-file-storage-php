<?php
session_start();

if(!isset($_SESSION["auth_user"])){
    header("Location: login.php");
    exit;
}

if(!isset($_FILES["fileUpload"])||$_FILES["fileUpload"]["error"]!==UPLOAD_ERR_OK){
    $_SESSION["upload_error"] = "Upload failed.";
    header("Location: upload.php");
    exit;
}

$file = $_FILES["fileUpload"];

$size = 5*1024*1024;
if($file["size"]>$size){
    $_SESSION["upload_error"] = "File exceeded 5MB.";
    header("Location: upload.php");
    exit;
}

$boleh = ["pdf","docx","jpeg","jpg","png"];
$ext = strtolower(pathinfo($file["name"],PATHINFO_EXTENSION));
if(!in_array($ext,$boleh)){
    $_SESSION["upload_error"] = "File type is not allowed.";
    header("Location: upload.php");
    exit;
}

$storedName = bin2hex(random_bytes(16)) . "." . $ext;
$target = __DIR__ . "/uploads/" . $storedName;
if(!move_uploaded_file($file["tmp_name"],$target)){
    $_SESSION["upload_error"] = "Failed to save the file.";
    header("Location: upload.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=db_upload_exercise","root","");
$stmt = $pdo->prepare("INSERT INTO files (user_id,original_name,stored_name,file_size,file_type) VALUES (?,?,?,?,?)");
$stmt->execute([
    $_SESSION["auth_user"]["id"],
    $file["name"],
    $storedName,
    $file["size"],
    $ext
]);

header("Location: list.php");
exit;