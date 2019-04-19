<?php
   require_once 'utils/CookieChecker.php' ;

  class GetCookie{

    public function __construct(){}

     public function get_manager(){
      if(!isset($_COOKIE['manager_session'])){
                 return -1;
      }

      $cookie=new CookieChecker();
      $cookie->set_params(['id'=>'0','time'=>'1','signature'=>'2']);

      if(!$cookie->save_cookie('manager_session')){
        return -2;
      }
      return $cookie->get_param_data('id');

    }


    public function get_cookie($cookie_name,$params){
      $my_array=[];
      if(!isset($_COOKIE[$cookie_name])){
                 return $my_array;
      }
      $cookie=new CookieChecker();
      $cookie->set_params($params);
      if(!$cookie->save_cookie($cookie_name)){
        return $my_array;
      }
      return $cookie->get_all_data();
    }

    public function delete_cookie($cookie_name) {
      if (isset($_COOKIE['manager_session'])) {
          unset($_COOKIE['manager_session']);
          setcookie('manager_session', null, -1, '/');
          return true;
        }
    }

  }
 ?>
