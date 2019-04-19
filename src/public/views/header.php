<!DOCTYPE html>
<html lang="en">

<head>

    <?php require_once 'utils/RestrictLogin.php'; $restrict_login = new RestrictLogin(); $restrict_login->handle(); ?>
    <?php
    require_once 'utils/ApiClient.php';
    include 'utils/Config.php';
      /*require_once 'utils/Cookie.php';
      $current_manager_id_cookie = new Cookie();
      $current_manager_id = $current_manager_id_cookie->get_cookie('manager_session');*/

    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
    <title><?php echo $config['header_title']; ?></title>
    <!-- Bootstrap Core CSS -->
    <link href="/assets/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/lib/calendar2/semantic.ui.min.css" rel="stylesheet">
    <link href="/assets/css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="/assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="/assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <link href="/assets/css/helper.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar">
    <!-- Preloader - style you can find in spinners.css -->
    <!--<div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>-->
    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <!-- header header  -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- Logo -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="/">
                        <!-- Logo icon --
                        <b><img src="/assets/images/logo.png" alt="homepage" class="dark-logo" /></b>
                        <!--End Logo icon -->
                        <!-- Logo text --
                        <span><img src="/assets/images/logo-text.png" alt="homepage" class="dark-logo" /></span>
                        <!-- End Logo text -->
                        <!-- Always-Showing Logo -->
                        <b><!--<img src="/assets/images/logo-text.png" alt="homepage" class="dark-logo" />--></b>
                        <!-- End Always-Showing Logo -->
                    </a>
                </div>
                <!-- End Logo -->
                <div class="navbar-collapse">
                    <!-- toggle and nav items -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu"></i></a> </li>

                    </ul>
                    <!-- User profile and search -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/assets/images/nav-profile.png" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><div class="col-12"><?php $api = new ApiClient(); $api->get_row('/zazu_manager/', $_SESSION['manager_id']); echo $api->get_resource()->name; ?></div></li>
                                    <li><a href="/managers/<?php echo $_SESSION['manager_id']; ?>"><i class="fa fa-user"></i>  <?php echo t('Profile'); ?></a></li>
                                    <li><a href="/logout"><i class="fa fa-power-off"></i>  <?php echo t('Logout'); ?></a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- End header header -->


        <!-- Left Sidebar -->
        <?php require_once 'utils/Menu.php';
          $menu = new Menu();
          $menu->show_menu();
        ?>
        <!-- Left Sidebar End -->


        <!-- Page wrapper  -->
        <div class="page-wrapper">
            <!-- Container fluid  -->
            <div class="container-fluid">
