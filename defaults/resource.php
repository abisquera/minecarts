
    <!-- Fonts-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <!-- Favicon-->
    <link rel="icon" href="<?=base_url()?>img/core-img/favicon.ico">
    <link rel="apple-touch-icon" href="<?=base_url()?>img/icons/icon-96x96.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=base_url()?>img/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="167x167" href="<?=base_url()?>img/icons/icon-167x167.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=base_url()?>img/icons/icon-180x180.png">
    <!-- CSS Libraries-->
    <link rel="stylesheet" href="<?=base_url()?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>css/animate.css">
    <link rel="stylesheet" href="<?=base_url()?>css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?=base_url()?>css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=base_url()?>css/bootstrap-icons.css">
    <link rel="stylesheet" href="<?=base_url()?>css/magnific-popup.css">
    <link rel="stylesheet" href="<?=base_url()?>css/ion.rangeSlider.min.css">
    <link rel="stylesheet" href="<?=base_url()?>css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?=base_url()?>css/apexcharts.css">
    <!-- Core Stylesheet-->
    <link rel="stylesheet" href="style.css">
    <!-- Web App Manifest-->
    <link rel="manifest" href="manifest.json">
    
    <!-- Footer Nav-->
    <!-- All JavaScript Files-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                callback.apply(context, args);
                }, ms || 0);
            };
        }
    </script>