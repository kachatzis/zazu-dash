<?php

class CookieChecker {
      public $cookie_name;
      public $cookie_data;
      public $timeout;
      public $params;
      public $secret;


    public function __construct(){
      $cookie_data = [];
      $cookie_name='manager_session';
      $params=[];
      //standard code for each parameter for the decoding
      $secret='amsdng254rair4350dfjuad';
      // Sessions time out after 3 hours
      $timeout = 10800;
    }

    public function set_params($params){$this->params=$params;}

    public function get_all_data() { return $this->cookie_data;}

    public function save_cookie($cookie){
      if(isset ($_COOKIE[$cookie])){
        $this->cookie_name=$cookie ;
        $array=explode(':',$_COOKIE[$cookie]);
        foreach($this->params as $key=>$value){
          $this->cookie_data[$key]=$array[$value];
        }
        return true;
      }
      return false;
    }

    public function get_param_data($param){
      if(isset ($this->cookie_data[$param])){
        return $this->cookie_data[$param] ;
      }
      return '' ;
    }

    public function check_user_cookie(){
        if ($cookie_data['time']> time() + $this->timeout) {
            return false;
        }
        if ($cookie_data['signature'] !== $this->generateUserSignature($this->cookie_data)) {
            return false;
        }
        return true;
    }

    public function generateUserSignature($array) {
      $stringToSign='';
      foreach($array as $key=>$value){
        $stringToSign=$stringToSign.$array[$key].'\n';
      }
      $stringToSign =$stringToSign.$_SERVER['REMOTE_ADDR'];
     return hash_hmac('SHA1',$stringToSign,$this->secret);
    }

    public function delete_data(){
      $this->params=[];
      $this->cookie_data=[];
      if(isset ($_COOKIE[$this->cookie_name])){
        setcookier($this->cookie_name,'',1);
      }
      $this->cookie_name='';
    }

}

?>
