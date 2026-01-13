<?php
session_start();
if(!isset($_SESSION['login'])){
    header("location: login_image.php");
    exit;
}
else{
    session_destroy();
    header("location: login_image.php");
    exit;
}

?>