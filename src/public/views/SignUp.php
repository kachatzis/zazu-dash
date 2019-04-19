<?php
  include 'utils/Config.php';
  if(!$config['is_enabled_manager_signup']){
    // Redirect if signup is disabled
    header_redirect('/');
  } else { ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title><?php echo $config['header_title']; ?></title>
    <!-- Bootstrap Core CSS -->
    <link href="../assets/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../assets/css/helper.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar">
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- Main wrapper  -->
    <div id="main-wrapper">

        <div class="unix-login">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="login-content card">
                            <div class="login-form">


                                <form action="" method="POST">
                                  <div class="form-group">
                                      <label>First / Last Name</label>
                                      <input name="name" type="text" class="form-control" placeholder="First / Last Name" required="">
                                  </div>
                                  <div class="form-group">
                                      <label>Username</label>
                                      <input name="username" type="text" class="form-control" placeholder="Username" required="">
                                  </div>
                                  <div class="form-group">
                                      <label>Password</label>
                                      <input name="password" type="password" class="form-control" placeholder="Password" required="">
                                  </div>
                                  <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Register</button>
                                </form>

                                    <?php
                                      if($config['is_enabled_manager_signup']){
                                        if(isset( $_POST['username'] )){
                                          $username = $_POST['username'];
                                          $password = $_POST['password'];
                                          $name = $_POST['name'];

                                          require 'utils/Hash.php';
                                          $hash = new Hash();
                                          $hash->set_type('password');
                                          $hash->set_word($password);
                                          $password = $hash->hash();

                                          // Continue Signup

                                          // Insert Class
                                          require_once 'utils/ApiClient.php';

                                          // Create api object
                                          $api = new ApiClient();

                                          // POST
                                          $api->set_id_name('manager_id');
                                          $api->post('/zazu_manager/', [
                                            'resource'=> [
                                              'dashboard_login' => $username,
                                              'dashboard_password' => $password,
                                              'name' => $name,
                                              'is_enabled'=> 0
                                            ]
                                          ]);
                                          redirect('/');
                                        }
                                      }
                                    ?>



                                </div>
                              </div>
                          </div>
                    </div>
                  </div>
                </div>

              </div>
<!-- End Wrapper -->
<!-- All Jquery -->
<script src="../assets/js/lib/jquery/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="../assets/js/lib/bootstrap/js/popper.min.js"></script>
<script src="../assets/js/lib/bootstrap/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="../assets/js/jquery.slimscroll.js"></script>
<!--Menu sidebar -->
<script src="../assets/js/sidebarmenu.js"></script>
<!--stickey kit -->
<script src="../assets/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
<!--Custom JavaScript -->
<script src="../assets/js/custom.min.js"></script>

</body>

</html>
<?php } ?>
