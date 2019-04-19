<?php

  //session_start();

  class LogoutHandler {

    public function __construct() {;}


    public function handle(){
      $this->destroy_session();
      $this->redirect_to_login();
    }


    public function destroy_session(){
      unset($_SESSION['manager_id']);
      session_unset();
      session_destroy();
    }


    public function redirect_to_login(){
      redirect('/login');
      /*?>
        <script type="text/javascript">
        window.location.href = '/login/';
        </script>
      <?php*/
    }


  }

 ?>
