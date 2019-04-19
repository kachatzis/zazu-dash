<?php

  require 'restclient.php';

  class ApiClient {

    public $base_url;
    public $debug;
    public $response_json;
    public $response_code;
    public $results_count;
    public $id;
    public $resource;
    public $force_debug;
    public $api_key;
    public $id_name;
    public $result;
    public $filter;


    public function __construct(){

      require 'utils/Config.php';
      $this->base_url = $config['api_base_url'];
      $this->debug = false;        // Warning: Debuggin breakes the PostHandler
      $this->force_debug = false;  // and other classes.
      $this->response_json = '';
      $this->response_code = 0;
      $this->results_count = -1;
      $this->id = -1;
      $this->resource = '';
      $this->api_key = $config['api_secret_key'];
      $this->id_name = 'id';
      $this->result = '';
      $this->filter = '';
    }

    public function get_result(){ 
      return $this->result; 
    }


    public function get_response_json() {
      return $this->response_json;
    }

    public function get_response_code() {
      return $this->response_code;
    }

    public function get_results_count() {
      return $this->results_count;
    }

    public function get_id() {
      return $this->id;
    }

    public function set_id_name($id_name) {
      $this->id_name = $id_name;
    }

    public function get_resource() {
      return $this->resource;
    }




    public function get_row($uri, $id) {
      /* GET (Object) */

      $api = new RestClient([
          'base_url' => $this->base_url,  // Base Url
          'format' => "json"              // Response Format
      ]);
      $result = $api->execute($uri. $id .'/' , 'GET', [], [  // Request
          "X-DreamFactory-API-Key"=>$this->api_key
      ]);

      $this->response_code = $result->info->http_code;
      $this->results_count = -1;
      $this->response_json = [];
      $this->response_json = $result->decode_response();
      if($this->response_code == 200 && !$this->force_debug){   // If success
        $this->results_count = 1;
        $this->resource = $this->response_json;
      } elseif ($this->debug || $this->force_debug) {
        var_dump ($result);
      }

    }


    public function get($uri) {
      /* GET (Array) */

      $api = new RestClient([
          'base_url' => $this->base_url,  // Base Url
          'format' => "json",                                   // Response Format
      ]);
      $result = $api->execute($uri.'/' , 'GET', [], [  // Request
        'X-HTTP-Method-Override' => 'GET',
        'Content-Type' => 'application/json',
        "X-DreamFactory-API-Key"=>$this->api_key
      ]);

      $this->response_code = $result->info->http_code;
      $this->results_count = -1;
      $this->response_json = [];
      $this->response_json = $result->decode_response();
      if($this->response_code == 200 && !$this->force_debug){   // If success
        $this->results_count = count($this->response_json->resource);
        $this->resource = $this->response_json->resource;

      } elseif ($this->debug || $this->force_debug) {
        var_dump ($result);
      }

  }


  public function get_filter($uri, $filter) {
    /* GET (Array) */

    $api = new RestClient([
        'base_url' => $this->base_url,  // Base Url
    ]);
    $result = $api->execute($uri.'?filter='.$filter, 'GET', [], [  // Request
      'X-HTTP-Method-Override' => 'GET',
      'Content-Type' => 'application/json',
      "X-DreamFactory-API-Key"=>$this->api_key
    ]);

    $this->response_code = $result->info->http_code;
    $this->results_count = -1;
    $this->response_json = [];
    $this->response_json = $result->decode_response();
    if($this->response_code == 200 && !$this->force_debug){   // If success

      $this->results_count = count($this->response_json->resource);
      $this->resource = $this->response_json->resource;

    } elseif ($this->debug || $this->force_debug) {
      var_dump ($result);
    }

  }


  public function get_count($uri, $filter) {
    /* GET (Array) */

    $api = new RestClient([
        'base_url' => $this->base_url,  // Base Url
    ]);
    $result = $api->execute($uri.'?count_only=1&filter='.$filter, 'GET', [], [  // Request
      'X-HTTP-Method-Override' => 'GET',
      'Content-Type' => 'application/json',
      "X-DreamFactory-API-Key"=>$this->api_key
    ]);

    $this->response_code = $result->info->http_code;
    $this->results_count = -1;
    $this->response_json = [];
    //var_dump($result->response);
    //$this->response_json = $result->decode_response();
    if($this->response_code == 200 && !$this->force_debug){   // If success

      //$this->results_count = count($this->response_json->resource);
      //$this->resource = $this->response_json->resource;
      $this->results_count = $result->response;

    } elseif ($this->debug || $this->force_debug) {
      var_dump ($result);
    }

  }


  public function post($uri, $body) {

    /* POST */

    $api = new RestClient([
        'base_url' => $this->base_url,  // Base Url
    ]);

    $result = $api->execute( $uri.'?', 'POST' , json_encode($body, true) , [  // Request
          'X-HTTP-Method-Override' => 'POST',
          'Content-Type' => 'application/json',
          "X-DreamFactory-API-Key"=>$this->api_key
          ]  );

    $this->id = -1;
    $this->response_code = $result->info->http_code;
    $this->response_json = $result->decode_response();
    if(($this->response_code == 200 || $this->response_code == 201) && !$this->force_debug){   // If success

      $this->resource = $this->response_json->resource[0];
      $id_name=$this->id_name;
      if(isset($this->resource->$id_name)) $this->id = $this->resource->$id_name;
      $this->result = $this->response_json;

    } elseif ($this->debug || $this->force_debug) {
      var_dump($this->id);
      var_dump($this->response_json);
    }

  }


  public function delete($uri, $id) {

    /* DELETE */

    $body = [
      'body' => 0,
    ];

    $api = new RestClient([
        'base_url' => $this->base_url,  // Base Url
    ]);

    $result = $api->execute( $uri.$id, 'DELETE' , json_encode($body, true) , [  // Request
          'X-HTTP-Method-Override' => 'DELETE',
          'Content-Type' => 'application/json',
          "X-DreamFactory-API-Key"=>$this->api_key
          ]  );

    $this->id = -1;
    $this->response_code = $result->info->http_code;
    $this->response_json = $result->decode_response();
    if($this->response_code == 200 && !$this->force_debug){   // If success
    } elseif ($this->debug || $this->force_debug) {
      var_dump ($result);
    }

  }



    public function delete_filter($uri, $filter) {

    /* DELETE FILTER */

    $body = [
      'body' => 0,
    ];

    $api = new RestClient([
        'base_url' => $this->base_url,  // Base Url
    ]);

    $result = $api->execute( $uri.'?'.$filter, 'DELETE' , json_encode($body, true) , [  // Request
          'X-HTTP-Method-Override' => 'DELETE',
          'Content-Type' => 'application/json',
          "X-DreamFactory-API-Key"=>$this->api_key
          ]  );

    $this->id = -1;
    $this->response_code = $result->info->http_code;
    $this->response_json = $result->decode_response();
    if($this->response_code == 200 && !$this->force_debug){   // If success
    } elseif ($this->debug || $this->force_debug) {
      var_dump ($result);
    }

  }


  public function patch($uri, $body) {

    /* PATCH */

    $api = new RestClient([
        'base_url' => $this->base_url,  // Base Url
    ]);

    $result = $api->execute( $uri, 'PATCH' , json_encode($body, true) , [
          'X-HTTP-Method-Override' => 'PATCH',
          'Content-Type' => 'application/json',
          'X-DreamFactory-API-Key'=>$this->api_key
          ] );

    $this->response_code = $result->info->http_code;
    $this->response_json = $result->decode_response();
    if($this->response_code == 200 && !$this->force_debug){   // If success

    } elseif ($this->debug || $this->force_debug) {
      $this->response_json = $result->decode_response();
      var_dump($this->resource);

    }

  }


  public function post_proc($uri, $body) {

    /* POST */

    $api = new RestClient([
        'base_url' => str_replace('_table', '_proc', $this->base_url)  // Base Url
    ]);

    $result = $api->execute( $uri.'?', 'POST' , json_encode($body, true) , [  // Request
          'X-HTTP-Method-Override' => 'POST',
          'Content-Type' => 'application/json',
          "X-DreamFactory-API-Key"=>$this->api_key
          ]  );

    $this->id = -1;
    $this->response_code = $result->info->http_code;
    $this->response_json = $result->decode_response();
    if(($this->response_code == 200 || $this->response_code == 201) && !$this->force_debug){   // If success

      $this->resource = $this->response_json->resource;
      $id_name=$this->id_name;
      if(isset($this->resource->$id_name)) $this->id = $this->resource->$id_name;
      $this->result = $this->response_json;

    } elseif ($this->debug || $this->force_debug) {
      var_dump($this->id);
      var_dump($this->response_json);
    }

  }

}

 ?>
