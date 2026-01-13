<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: login_image.php");
    exit;
}

require __DIR__ . '/config.php';

if(!isset($_GET['id'])){
    header("Location: gallery_image.php");
    exit;
}

$loginUserId = (int)$_SESSION['user_id'];
$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM images WHERE id = :id");
$stmt->execute([':id' => $id]);
$image = $stmt->fetch();

if(!$image){
    header("Location: gallery_image.php");
    exit;
}

if ((int)$image['user_id'] !== $loginUserId){
    header("Location: gallery.php");
    exit;
}

$filepath = __DIR__ . '/uploads/' . $image['filename'];
if(is_file($filepath)){
    unlink($filepath);
}

$stmt = $pdo->prepare("DELETE FROM images WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: gallery_image.php");
exit;