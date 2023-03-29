
    <div class="footer-nav-area" id="footerNav">
      <div class="container px-0">
        <!-- Paste your Footer Content from here-->
        <!-- Footer Content-->
        <div class="footer-nav position-relative shadow-sm footer-style-two">
            <ul class="h-100 d-flex align-items-center justify-content-between ps-0">
                <li class="<?=(1==1 ? "active" : "")?>"><a href="<?=base_url()?>user.php"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                    </svg></a></li>
                <li class="active"><a href="<?=base_url()?>index.php"><svg width="22" height="22" viewBox="0 0 16 16" class="bi bi-house" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
                    <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
                    </svg></a></li>
                <li class="<?=(1==1 ? "active" : "")?> <?=($_SESSION["user"]->userType == 2 ? "d-none" : "")?>"><a href="<?=base_url()?>map.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                      <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                    </svg></a></li>
            </ul>
                <!-- # Footer Two Layout End-->
        </div>
      </div>
    </div>
    <!-- All JavaScript Files-->
    <script src="<?=base_url()?>js/bootstrap.bundle.min.js"></script>
    <script src="<?=base_url()?>js/jquery.min.js"></script>
    <script src="<?=base_url()?>js/default/internet-status.js"></script>
    <script src="<?=base_url()?>js/waypoints.min.js"></script>
    <script src="<?=base_url()?>js/jquery.easing.min.js"></script>
    <script src="<?=base_url()?>js/wow.min.js"></script>
    <script src="<?=base_url()?>js/owl.carousel.min.js"></script>
    <script src="<?=base_url()?>js/jquery.counterup.min.js"></script>
    <script src="<?=base_url()?>js/jquery.countdown.min.js"></script>
    <script src="<?=base_url()?>js/imagesloaded.pkgd.min.js"></script>
    <script src="<?=base_url()?>js/isotope.pkgd.min.js"></script>
    <script src="<?=base_url()?>js/jquery.magnific-popup.min.js"></script>
    <script src="<?=base_url()?>js/default/dark-mode-switch.js"></script>
    <script src="<?=base_url()?>js/ion.rangeSlider.min.js"></script>
    <script src="<?=base_url()?>js/jquery.dataTables.min.js"></script>
    <script src="<?=base_url()?>js/default/active.js"></script>
    <script src="<?=base_url()?>js/default/clipboard.js"></script>
    <!-- PWA-->
    <script src="<?=base_url()?>js/pwa.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
