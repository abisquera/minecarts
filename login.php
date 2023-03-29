
<?php
 session_start();
 if(isset($_SESSION["user"])){
  header("Location: index.php");
  exit;
}
require_once 'globalFn.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Affan - PWA Mobile HTML Template">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#0134d4">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags-->
    <!-- Title-->
    <title>Parking Parker</title>
    <?php
        include 'defaults/resource.php';
    ?>
  </head>
  <body>
    <!-- Preloader-->
    <div class="preloader d-flex align-items-center justify-content-center" id="preloader">
      <div class="spinner-grow text-primary" role="status">
        <div class="sr-only">Loading...</div>
      </div>
    </div>
    <!-- Internet Connection Status-->
    <div class="internet-connection-status" id="internetStatus"></div>

    <!-- Login Wrapper Area-->
    <div class="login-wrapper d-flex align-items-center justify-content-center">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-sm-9 col-md-7 col-lg-6 col-xl-5">
            <div class="text-center px-4"><img class="login-intro-img" src="img/core-img/pp-logo.png" alt=""></div>
            <!-- Register Form-->
            <div class="register-form px-4">
              <form onsubmit="event.preventDefault();" id="loginUser" >
                <div class="form-group">
                  <label class="form-label" for="contactNumber">Mobile Number</label>
                  <input class="form-control" id="contactNumber" type="tel" name="contactNumber" placeholder="+63" required>
                </div>
                <div class="form-group">
                  <input class="form-control" type="password" name="password" placeholder="Password" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Sign In</button>
              </form>
            </div>
            <!-- Login Meta-->
            <div class="login-meta-data text-center"><a class="stretched-link forgot-password d-block mt-3 mb-1" href="register.php">Register Here</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
    <!-- Footer Nav-->
    <!-- All JavaScript Files-->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/default/internet-status.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <script src="js/isotope.pkgd.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/default/dark-mode-switch.js"></script>
    <script src="js/ion.rangeSlider.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/default/active.js"></script>
    <script src="js/default/clipboard.js"></script>
    <!-- PWA-->
    <script src="js/pwa.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
  
  $('#loginUser').on("submit",function(){
    $.ajax({
      type: "GET",
      url: "<?=request_url('loginUser','get')?>",
      data: $('#loginUser').serializeArray()
    }).done(function(res){
      $(':input','#loginUser')
      .not(':button, :submit, :reset, :hidden')
      .val('')
      .prop('checked', false)
      .prop('selected', false);
      if(res.s == 'success')
        window.location.replace("<?=base_url()?>index.php");
      else 
        swal({
            title: res.h,
            text: res.m,
            icon: res.s
        })
    });
  })  
</script>