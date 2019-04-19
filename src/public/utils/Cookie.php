<?php

class Cookie{

  public function __construct(){;}

  public function set_cookie($cookie_name, $cookie_value, $cookie_expire=86400*30){
    $cookie_expire += time(); // 1 month
    setcookie( $cookie_name, $cookie_value, $cookie_expire, "/" );
  }

  public function get_cookie($cookie_name){
    if(isset($_COOKIE[$cookie_name])){
      return $_COOKIE[$cookie_name];
    }
    return false;
  }

  public function delete_cookie($cookie_name){
    if(isset($_COOKIE[$cookie_name])){
      unset($_COOKIE[$cookie_name]);
      $this->set_cookie($cookie_name, 0, -1);
      return true;
    }
    return false;
  }

}

 ?>
