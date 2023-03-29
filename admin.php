

<?php
  require_once 'globalFn.php';
  
  if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit;
  }

  if($_SESSION["user"]->userType != 1){
    header("Location: 404.php");
    exit;
  }


  $user = $_SESSION['user'];


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Parking Parker">
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
    <style>
      #map {
        height: 100%;
      }
      .custom-map-control-button {
        background-color: #fff;
        border: 0;
        border-radius: 2px;
        box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
        margin: 10px;
        padding: 0 0.5em;
        font: 400 18px Roboto, Arial, sans-serif;
        overflow: hidden;
        height: 40px;
        cursor: pointer;
      }
      .custom-map-control-button:hover {
        background: rgb(235, 235, 235);
      }

    </style>
  </head>
  <body>
    <!-- Internet Connection Status-->
    <div class="internet-connection-status" id="internetStatus"></div>
    <!-- Header Area-->
    <?php
      include 'defaults/top-nav.php';
    ?>
    <!-- Sidenav Black Overlay-->
    <div class="sidenav-black-overlay"></div>
    <!-- Side Nav Wrapper-->

    <?php
      include 'defaults/side-nav.php';
      // AIzaSyBBxji7963bwlFqigejxtoAcX8g8mZKDrk
      // AIzaSyCeB2QeJncymKenop5aI4S6b0svjMvRY4g
    ?>

    <div class="page-content-wrapper">
      <div class="container pt-3">
        <a class="btn btn-info mb-3" id="addAdminBtn">Add Admin </a>
          <!-- User Information-->
              <div id="adminContent" class="row">

              </div>
          </div>
        </div>
    </div>
    <div class="modal fade" id="modAddAdmin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addAdminLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form onsubmit="event.preventDefault();"  id="addAdmin" >
            <div class="modal-header">
              <h6 class="modal-title" id="addAdminLabel">Add Admin</h6>
              <button class="btn btn-close p-1 ms-auto" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="min-height:400px;">
              <div class="form-group">
                  <label class="form-label" for="contactNumber">Mobile</label>
                  <input type="text" class="form-control" name="contactNumber" required>
              </div>
              <div class="form-group">
                  <label class="form-label" for="firstName">First name</label>
                  <input type="text" class="form-control" name="firstName" placeholder="" required>
              </div>
              <div class="form-group">
                  <label class="form-label" for="middleName">Middle Name</label>
                  <input type="text"  class="form-control" name="middleName" placeholder="" >
              </div>
              <div class="form-group">
                  <label class="form-label" for="lastName">Last Name</label>
                  <input type="text"  class="form-control" name="lastName" placeholder="" required>
              </div>
              <div class="form-group">
                  <label class="form-label" for="email">Email</label>
                  <input type="text"  class="form-control" name="email" placeholder="" required>
              </div>
              <div class="form-group">
                  <label class="form-label" for="password">Password</label>
                  <input type="password"  class="form-control" name="password" placeholder="" required>
              </div>
              <div class="form-group">
                  <label class="form-label" for="rePassword">Re-password</label>
                  <input type="password"  class="form-control" name="rePassword" placeholder="" required>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-sm btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-sm btn-success " type="submit"><i class="fa fa-plus"></i> Add Admin</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php 
    $file = basename(__FILE__, '.php');
    include 'defaults/footer.php'; 
    ?>
    <!-- PWA-->
  </body>
</html>
<script>

  $(function(){
    loadAdmins()
  })  
  let mod = $("#modAddAdmin")
  $("#addAdminBtn").on("click",function(){
    mod.modal("show")
  })

  function loadAdmins()
  {
    let content = $("#adminContent")
    $.get("<?=request_url("loadAdmins")?>",
        function (data, textStatus, jqXHR) {
          if(data.length){
            let r = ""
            $.each(data, function (a, b) { 
               r += `
                <div class='col-md-4'>
                  <div class="card user-info-card mb-3">
                      <div class="card-body d-flex align-items-center">
                        <div class="user-info">
                            <div class="d-flex align-items-center">
                            <h4 class="mb-1" id="">${b.firstName} ${b.lastName}</h4>
                            </div>
                            <p class="mb-0">${b.contactNumber} </p>
                            <p class="mb-0">${b.email} </p>
                            <p class="mb-0 mt-2">
                            <a class="btn btn-sm float-right ${b._action}"  title="Lock/Unlock" onClick='adminLock(${b.userId},${b.isLocked})'>
                              <i class='fa fa-${b.isLocked == 1 ? "unlock" : "lock" } text-${b.isLocked ? "primary" : "info" }'></i>
                            </a>
                            <a class="btn btn-sm float-right ${b._action}"  title="Delete" onClick='adminDelete(${b.userId})'>
                              <i class='fa fa-trash text-danger'></i>
                            </a>
                            </p>
                        </div>
                      </div>
                  </div>
                </div>
               `
            });
            content.html(r)
          }
        }
    );
  }

  mod.find("form").on("submit",function(){
    let data = $(this).serializeArray()

    $.post("<?=request_url("addAdmin","post")?>", data,
      function (res, textStatus, jqXHR) {
        swal(res.h,res.m,res.s)
        
        if(res.s =="success"){
          mod.modal("hide")
          $(':input',"#addAdmin")
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .prop('checked', false)
            .prop('selected', false);
            loadAdmins()
        }
          
          
      }
    );

  })
  
  function adminLock(id,lock){
    swal({
      title: "Lock / Unlock Admin",
      text: "Are you sure you want to Lock / Unlock this account?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.post("<?=request_url("adminLock","POST")?>", {id : id, lock : lock},
          function (res, textStatus, jqXHR) {
            swal(res.h,res.m,res.s)
            loadAdmins()
          }
        );
      }
    });
  }
  function adminDelete(id){
    swal({
      title: "Are you sure?",
      text: "Once deleted, you will not be able to recover this Admin account!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.post("<?=request_url("adminDelete","POST")?>", {id : id},
          function (res, textStatus, jqXHR) {
            swal(res.h,res.m,res.s)
            loadAdmins()
          }
        );
      }
    });

  }
</script>