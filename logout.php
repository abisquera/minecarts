<?php
session_start();

unset($_SESSION["isLogged"]);

if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(!isset($_SESSION['contactNumber']) || !isset($_GET['s'])){
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if($_POST['contactNumber'] == "" || $_POST['password'] == ""){
        session_destroy();
        header("Location: login.php");
        exit;
    }
    else {
        if($_POST['contactNumber'] == $_SESSION['contactNumber'] && $_POST['password'] ==  $_SESSION['password']){
            $_SESSION["isLogged"] = 1;
            header("Location: index.php");
            exit;
        }
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Parking Parker</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <a href="index.php"><b>Project</b>Title</a>
  </div>
  <!-- User name -->
  <div class="lockscreen-name"><?=$_SESSION['contactNumber'];?></div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="assets/dist/img/user1-128x128.jpg" alt="User Image">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form  action="logout.php" method="post" class="lockscreen-credentials">
      <div class="input-group">
        <input type="hidden" name="contactNumber" value="<?=$_SESSION['contactNumber'];?>">
        <input type="password" name="password" class="form-control" placeholder="password">

        <div class="input-group-append">
          <button type="submit" class="btn">
            <i class="fas fa-arrow-right text-muted"></i>
          </button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
    Enter your password to retrieve your session
  </div>
  <div class="text-center">
    <a href="logout.php">Or sign in as a different user</a>
  </div>
  <div class="lockscreen-footer text-center">
    Copyright &copy; 2014-2021 <b><a href="https://adminlte.io" class="text-black">AdminLTE.io</a></b><br>
    All rights reserved
  </div>
</div>
<!-- /.center -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

