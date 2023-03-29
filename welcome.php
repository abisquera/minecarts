<?php
session_start();

if(!isset($_SESSION["isLogged"])){
    session_destroy();
    header("Location: login.php");
    exit;
}



echo $_SESSION['contactNumber'];
echo "<br>";
echo $_SESSION['password'];
?>













<a href="logout.php"> Logout</a>