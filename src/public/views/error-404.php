<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
    <title><?php echo $config['header_title']; ?></title>
    <!-- Bootstrap Core CSS -->
    <link href="/assets/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/assets/css/helper.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar">

    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- Main wrapper  -->
    <div class="error-page" id="wrapper">
        <div class="error-box">
            <div class="error-body text-center">
                <h1>404</h1>
                <h3 class="text-uppercase"><?php t('PAGE NOT FOUND');?></h3>
                <p class="text-muted m-t-30 m-b-30"></p>
                <a class="btn btn-info btn-rounded waves-effect waves-light m-b-40" href="/"><?php t('Back');?></a>
            </div>
            <footer class="footer text-center">&copy; <?php echo date('Y').' '; echo $config['footer_title']; ?></footer>
        </div>
    </div>
    <!-- End Wrapper -->
    <!-- All Jquery -->
    <script src="/assets/js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript --
    <script src="/assets/js/lib/bootstrap/js/popper.min.js"></script>
    <script src="/assets/js/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript --
    <script src="/assets/js/jquery.slimscroll.js"></script>
    <!--Menu sidebar --
    <script src="/assets/js/sidebarmenu.js"></script>
    <!--stickey kit --
    <script src="/assets/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="/assets/js/custom.min.js"></script>

</body>

</html>
