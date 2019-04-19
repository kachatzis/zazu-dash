<?php
    require_once 'GetCookie.php' ;
    require_once 'ApiClient.php';

    class ManagerCheck{

      public $cookie;
      public $id;

      public function manager_check(){

        $this->cookie=new GetCookie();
        $this->id=$this->cookie->get_manager();

        if($this->id>0){
          $api=new ApiClient();
          $api->get_filter('/zazu_manager/', '(manager_id='.$this->id.')and(is_enabled=1)&limit=1');
          if($api->get_results_count()>0){
            return true;
          }else{
            return false;
          }
        }
        return false;
      }


      public function check(){

        if(!$this->manager_check()){
          ?>
            <script type="text/javascript">
            window.location.href = '/login/';
            </script>
          <?php
        }

      }

    }
 ?>
