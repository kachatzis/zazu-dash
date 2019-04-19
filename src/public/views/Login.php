<?php

date_default_timezone_set('Europe/Athens');
//include_once 'utils/Redirect.php';
include 'utils/Config.php';

// Set first login trial
if(!isset($_SESSION['login_try_time'])){
  //$_SESSION['login_try_time'] = date('Y-m-d H:i:s');
  $_SESSION['login_try_time'] = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s')+$config['seconds_first_login_try']-$config['seconds_between_login_tries'], date('n'), date('j'), date('Y')));
  redirect('/login');
}

if(isset($_POST['username'])&&isset($_POST['password'])) {
  // POST

  if(isset($_SESSION['login_try_time'])){
    $login_try_time = strtotime($_SESSION['login_try_time']);
    $current_time = strtotime(date('Y-m-d H:i:s'));
    if($current_time - $login_try_time < $config['seconds_between_login_tries']){
      // Login Try without passing the time limit (possible threat), Reset timer.
      $_SESSION['login_try_time'] = date('Y-m-d H:i:s');
      redirect('/login');
      exit();
    }
  }

  // Set login try
  $_SESSION['login_try_time'] = date('Y-m-d H:i:s');

  // Checks login
  require_once 'utils/LoginHandler.php';

  $login_handler = new LoginHandler();
  $login_handler->set_api_uri('/zazu_manager/');
  $login_handler->username_key = 'dashboard_login';
  $login_handler->password_key = 'dashboard_password';
  $login_handler->handle();

} else {
    // Show GET
 ?>



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
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
    <title><?php echo $config['header_title']; ?></title>
    <!-- Bootstrap Core CSS -->
    <link href="/assets/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/helper.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">

    <!-- Progress Bar Style -->
    <style>
      #progress {
        width: auto;
        background-color: white;
        margin-top: -20px;
        margin-left: -20px;
        margin-right: -20px;
      }
      #progressbar {
        width: 0%;
        height: 2.5px;
        background: linear-gradient(to right, #5da1f4 0%, #39dd30 100%);
      }
    </style>


</head>

<body class="fix-header fix-sidebar">
    <!-- Preloader - style you can find in spinners.css -->

<?php  include 'utils/Config.php'; ?>

    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- Main wrapper  -->
    <div id="main-wrapper" style="background-color:#efedfb;">

        <div class="unix-login">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="login-content card">

                          <?php if($config['general_christmas_mode']){?>
                          <img src="../assets/images/santa_hat_1.png" style="    height: 70px;
                            right: 0;
                            position: absolute;
                            margin-top: -36px;
                            margin-right: -31px;
                            top: 0; ">
                          <?php } ?>

                          <div id="progress">
                            <div id="progressbar"></div>
                          </div>
                            <div class="login-form">
                                <h4><?php t('Login');?></h4>
                                <p id="countdown" style="visibility: hidden;">0s</p>

                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label><?php t('USERNAME');?></label>
                                        <input id="username" name="username" type="text" class="form-control" placeholder="<?php t('Username');?>" maxlength="30" autofocus required>
                                    </div>
                                    <div class="form-group">
                                        <label><?php t('PASSWORD');?></label>
                                        <input id="password" name="password" type="password" class="form-control" placeholder="<?php t('Password');?>" maxlength="40" required>
                                    </div>

                                    <?php // TODO: Add Language functionality (create cookie on login) ?>
                                    <div class="form-group">
                                        <label><?php t('LANGUAGE');?></label>
                                        <select id="language" name="language" type="select" class="form-control"  onchange="languageChange()">
                                          <option <?php if(isset($_COOKIE['locale'])){if($_COOKIE['locale']=='el_gr'){echo 'selected';}}else{echo 'selected';}?> value="el_gr">Ελληνικά</option>
                                          <option <?php if(isset($_COOKIE['locale'])){if($_COOKIE['locale']=='en_us'){echo 'selected';}}?> value="en_us">English</option>
                                        </select>
                                    </div>


                                    <button id="submit" type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30"><?php t('SIGN IN');?></button>

                                </form>
                                <?php if($config['is_enabled_manager_signup']){ ?>
                                  <a href="/signup"><button class="btn btn-primary btn-flat m-b-30 m-t-30"><?php t('SIGN UP');?></button></a>
                                <?php } ?>
                                <input type="hidden" id="last_login_time" value="<?php echo $_SESSION['login_try_time']; ?>" />
                                <input type="hidden" id="next_try_seconds" value="<?php echo $config['seconds_between_login_tries']; ?>" />
                                <input type="hidden" id="curr_language" value="<?php if(isset($_COOKIE['locale'])){echo $_COOKIE['locale'];}else{echo 'el_gr';}?>"

                                <div class="register-link m-t-15 text-center">
                                    <p><a href=""><?php echo $config['footer_title']; ?></a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input id="test" type="hidden" value="<?php t('test'); ?>">
    </div>
    <!-- End Wrapper -->
    <!-- Language Script -->
    <script>
      function languageChange(){
        var value = document.getElementById('language').value;
        if(value=="en_us"||value=="el_gr"){
          cname = "locale"; // Cookie Name
          cvalue = value;   // Cookie Value
          var exdays = 30;  // Expiration Days
          var d = new Date();
          d.setTime(d.getTime() + (exdays*24*60*60*1000));
          var expires = "expires="+ d.toUTCString();
          document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
          var curr_language = document.getElementById('curr_language').value;

          if(curr_language!=value){
            window.location.href = '/login';
          }
        }
      }
      languageChange(); // Run when the form loads, to create the default cookie
    </script>


    <!-- Translation testing -->
    <script>
        var value = document.getElementById('test').value;
        if(value==""){
            window.location.href = '/login';
        }
    </script>

    <!-- Display the countdown timer in an element -->



    <!-- Login Timeout Script -->
    <script>
    // Set the date we're counting down to
    //var countDownDate = new Date("Jan 5, 2019 15:37:25").getTime();
    var countDownDateValue= document.getElementById("last_login_time").value;
    var countDownDate = new Date(countDownDateValue).getTime();
    var next_try_seconds = parseInt(document.getElementById("next_try_seconds").value);
    var starting_time = 0;

    // Update the count down every 1 second
    var x = setInterval(function() {

      // Get todays date and time
      var now = new Date().getTime();

      // Find the distance between now and the count down date
      var distance = countDownDate + next_try_seconds*1000 - now;

      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      if(starting_time == 0){
        starting_time = distance;
      }

      var percentage = 100 * distance /  starting_time ;

      document.getElementById("progressbar").style.width = percentage+"%";

      // Display the result in the element with id="demo"
      document.getElementById("countdown").innerHTML = parseInt(distance/1000) + "s";
      document.getElementById("countdown").style.visibility = 'hidden';
      document.getElementById("username").disabled = false;
      document.getElementById("password").disabled = false;
      document.getElementById("language").disabled = false;
      document.getElementById("submit").disabled = true;

      // If the count down is finished, write some text
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("progressbar").style.width = '0%';
        document.getElementById("countdown").innerHTML = "0s";
        document.getElementById("countdown").style.visibility = 'hidden';
        document.getElementById("username").disabled = false;
        document.getElementById("password").disabled = false;
        document.getElementById("language").disabled = false;
        document.getElementById("submit").disabled = false;
      }
    }, 25);
    </script>


    <!-- All Jquery -->
    <script src="/assets/js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="/assets/js/lib/bootstrap/js/popper.min.js"></script>
    <script src="/assets/js/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="/assets/js/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="/assets/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="/assets/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="/assets/js/custom.min.js"></script>

<!--
<script src="https://cdn.chatkwik.com/cdn/widget/576c4e77a3295d94be8dbcc634cac24d1ff14207effef09ede0a0b6269a21cd5" type="text/javascript"> </script>
-->


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-123328210-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-123328210-3');
</script>


</body>

</html>

<?php } // End Post Check ?>
