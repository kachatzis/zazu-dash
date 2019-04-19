<?php

  //session_start();

  require_once 'utils/Cookie.php';

  class RestrictLogin {

    public function __construct(){;}

    public function handle(){
      if (! $this->get_manager()) $this->redirect_to_logout();
    }

    public function get_manager(){
      if(isset($_SESSION["manager_id"])) return 1;
      return 0;
    }

    public function redirect_to_logout(){
      header_redirect('/logout');
    }

  }

?>
