<?php

  require_once 'utils/ApiClient.php';

  class PostHandler {


    public $form_action_create;
    public $form_action_update;
    public $form_action_update_absolute;
    public $form_action_delete;
    public $api_action;
    public $params;
    public $body;
    public $api_action_uri;
    public $id;
    public $id_name;
    public $force_debug;
    private $config;




    public function __construct() {
      $this->form_action = '#';
      $this->api_action = 'update';
      $this->body = [];
      $this->api_action_uri;
      $this->force_debug = false; // Functionality not added yet

      require 'utils/Config.php';
      $this->config = $config;
    }

    public function set_id($id) { $this->id = $id; }

    public function set_api_action_uri($api_action_uri) { $this->api_action_uri = $api_action_uri; }

    public function set_id_name($id_name) { $this->id_name = $id_name; }

    public function set_form_action_create($form_action_create) { $this->form_action_create = $form_action_create; }

    public function set_form_action_update($form_action_update) { $this->form_action_update = $form_action_update; }

    public function set_form_action_update_absolute($form_action_update_absolute) { $this->form_action_update_absolute = $form_action_update_absolute; }

    public function set_form_action_delete($form_action_delete) { $this->form_action_delete = $form_action_delete; }



    public function get_post_param($param) {
      if(isset($_POST[$param])){
        return $_POST[$param];
      } else {
        return;
      }
    }

    public function get_photo_param($param) {
      // TODO: Add file extension check
      if(isset($_FILES[$param])){
        $data = file_get_contents($_FILES[$param]['tmp_name']);
        $data = base64_encode($data);
        //$data = mysql_real_escape_string($data);
        return $data;
      } else {
        return 0;
      }
    }


    public function create_body() {
      foreach ($this->params as $param) {
        foreach ($param as $param_key=>$param_val){

          if($param_val=='integer'&&strlen( $this->get_post_param($param_key)) >0){
              $this->body['resource'][$param_key]=(integer)$this->get_post_param($param_key);

          }elseif($param_val=='float'&&$this->get_post_param($param_key)){
              $this->body['resource'][$param_key]=(float)$this->get_post_param($param_key);

          }elseif($param_val=='photo'&&$this->get_photo_param($param_key)){
              if($this->get_photo_param($param_key)){
                $this->body['resource'][$param_key]=$this->get_photo_param($param_key);
              }

          }elseif($param_val=='password_crypt'&&$this->get_post_param($param_key)){
              require_once 'utils/Hash.php';
              $hash = new Hash();
              $hash->set_type('password');
              $hash->set_word( $this->get_post_param($param_key) );
              $this->body['resource'][$param_key]=
                $hash->hash();


          }elseif($this->get_post_param($param_key)){ // eg. string
              $this->body['resource'][$param_key]=$this->escape_string( $this->get_post_param($param_key) );
          }

        }
      }
      if ($this->api_action == 'PATCH' || $this->api_action == 'DELETE') {
        $this->body['ids'] = $this->id;
      }
    }


    public function escape_string($string){
      return str_replace('"', '\"', $string);
    }


    public function get_api_action(){
      if(isset($_POST['form-send'])){
        switch( $_POST['form-send'] ) {
          case 'update':
            $this->api_action = 'PATCH';
            break;
          case 'create':
            $this->api_action = 'POST';
            break;
          case 'delete':
            $this->api_action = 'DELETE';
            break;
        }
        if($this->api_action_uri){
          return true;
        } else { return true; }
      } else {
        return false;
      }
    }


    public function set_params ($params) { $this->params = $params; }


    public function handle(){
      if($this->get_api_action()) {
        $this->create_body();
        $this->exec_query();
      }

    }


    public function exec_query() {
      switch( $this->api_action ) {
        case 'POST':
          $this->exec_query_post();
          break;
        case 'PATCH':
          $this->exec_query_patch();
          break;
        case 'DELETE':
          $this->exec_query_delete();
          break;
      }
    }


    public function exec_query_post() {
      $api = new ApiClient();
      $api->set_id_name($this->id_name);
      $api->post($this->api_action_uri, $this->body);
      $this->id = $api->get_id();
      if($this->id < 0) {
        $this->id=0;
      }
      // TODO: Add GET error message & add card in '..._Id' pages to display it

      if(!$this->force_debug){
        if ($this->form_action_create){
          redirect($this->form_action_create.$this->id);
        }
      }

      if($this->force_debug){
        var_dump($api->get_response_json());
        var_dump($this->body);
        var_dump($_POST);
        exit();
      }


    }

    public function exec_query_patch() {
      $api = new ApiClient();
      $api = new ApiClient();
      $api->set_id_name($this->id_name);
      $api->patch($this->api_action_uri, $this->body);
      //$api->set_id_name($this->id_name);
      //$api->patch($this->api_action_uri, $this->id, $this->body);
      
      if(!$this->force_debug){
        if ($this->form_action_update_absolute){
          redirect($this->form_action_update_absolute);
        }
        if ($this->form_action_update){
          redirect($this->form_action_update.$this->id);
        }
      }

      if($this->force_debug){
        var_dump($api->get_response_json());
        var_dump($this->body);
        var_dump($_POST);
      }
    }

    public function exec_query_delete() {
      $api = new ApiClient();
      $api->set_id_name($this->id_name);
      $api->delete($this->api_action_uri, $this->id);

      if ($this->form_action_delete){
        redirect($this->form_action_delete);
      }
    }

  }

 ?>
