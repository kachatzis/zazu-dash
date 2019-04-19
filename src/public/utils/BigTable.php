
<?php

require_once 'utils/ApiClient.php';

class BigTable {

 public $table_name;
 public $header_array;
 public $body_array;
 public $action_uri;
 public $columns;
 public $action_column;
 public $show_footer;
 public $show_header;
 public $tr_action;
 public $table_description;
 public $header_titles;
 public $custom_columns;
 public $page;
 public $count;
 public $rows_per_page;
 public $post_table_filter;
 public $get_table_filter;

 public function __construct(){
   $this->table_name = '';
   $this->header_array = '';
   $this->body_array = '';
   $this->action_uri = '';
   $this->columns = -1;
   $this->action_column = '';
   $this->show_footer = true;
   $this->show_header = true;
   $this->tr_action = false;
   $this->table_description = '';
   $this->header_titles = '';
   $this->custom_columns = '';
   $this->page = 1;
   $this->count = 0;
   $this->rows_per_page = 35;
   $this->post_table_filter = '';
   $this->get_table_filter = '';
 }

 public function set_table_name ($table_name) {
   $this->table_name = $table_name;
 }

 public function set_table_description ($table_description) {
   $this->table_description = $table_description;
 }

 public function set_header_array ($header_array) {
   $this->header_array = $header_array;
 }

 public function set_body_array ($body_array) {
   $this->body_array = $body_array;
 }

 public function set_action_uri ($action_uri) {
   $this->action_uri = $action_uri;
 }

 public function set_columns ($columns) {
   $this->columns = $columns;
 }

 public function set_action_column ($action_column) {
   $this->action_column = $action_column;
 }

 public function set_show_header ($show_header) {
   $this->show_header = $show_header;
 }

 public function set_show_footer ($show_footer) {
   $this->show_footer = $show_footer;
 }

 public function set_tr_action ($tr_action) {
   $this->tr_action = $tr_action;
 }

 public function set_header_titles ($header_titles) {
   $this->header_titles = $header_titles;
 }

 public function set_custom_columns ($custom_columns) {
   $this->custom_columns = $custom_columns;
 }

 public function set_page ($page){
  $this->page = $page;
}

public function set_count ($count){
  $this->count = $count;
}

public function set_rows_per_page ($rows_per_page) {
  $this->rows_per_page = $rows_per_page;
}

public function set_post_table_filter ($post_table_filter){
  $this->post_table_filter = $post_table_filter;
}

public function set_get_table_filter ($get_table_filter){
  $this->get_table_filter = $get_table_filter;
}


private function paginate() {

  ?>


  <!-- Fix Small Table Style (aka hide things) -->
  <script>
    window.onload = function() {
      document.getElementById("example23_paginate").style.display = 'none';
      document.getElementById("example23_filter").style.display = 'none';
      document.getElementById("example23_info").style.display = 'none';

        /*
        var element = document.querySelector('[aria-label="<?php t('Συναλλαγή'); ?>: activate to sort column descending"]');
        element.parentNode.removeChild(element);
        */
      }
      //}
    </script>


    <div class="paginate_big_table" onload="hideSmallPagination()">

      <?php
      $float_page_count = ($this->count / $this->rows_per_page);
      $page_count = (int)($this->count / $this->rows_per_page);
      if ($float_page_count > $page_count){ $page_count++; }

      $this->check_current_page( $page_count );
      ?>


      <!-- First Page -->
      <?php
      if ($this->page > 1) {
        ?>

        <a href="?<?php echo $this->change_page_in_url(1); ?>">1</a>

        <?php
      }
      ?>

      <!-- Distance From First Page -->
      <div class="page_distance">
        <?php
        if ($this->page >  3 ) {
          ?>
          <p>...</p>
          <?php
        }
        ?>
      </div>

      <!-- Previous Page -->
      <?php
      if ($this->page > 2) {
        ?>
        <a href="?<?php echo $this->change_page_in_url(($this->page - 1)); ?>"><?php echo ($this->page - 1); ?></a>
        <?php
      }
      ?>

      <!-- Current Page -->

      <div class="current_page"><a href="?<?php echo $this->change_page_in_url($this->page); ?>"><?php echo $this->page; ?></a></div>

      <!-- Next Page -->
      <?php
      if ($this->page < ($page_count - 1) ) {
        ?>
        <a href="?<?php echo $this->change_page_in_url(($this->page + 1)); ?>"><?php echo ($this->page + 1); ?></a>
        <?php
      }
      ?>

      <!-- Distance From Last Page -->
      <div class="page_distance">
        <?php
        if ($this->page < ($page_count - 2) ) {
          ?>
          <p>...</p>
          <?php
        }
        ?>
      </div>


      <!-- Last Page -->
      <?php
      if ($this->page < ($page_count) ) {
        ?>
        <a href="?<?php echo $this->change_page_in_url( $page_count ); ?>"><?php echo ($page_count); ?></a>
        <?php
      }
      ?>

      <br>

      <!-- Results -->
      <?php
      echo tr('Results') . ': ' . $this->count;
      ?>

    </div>

    <?php



  }


  private function change_page_in_url($page) {
    $url_filter = explode('?', $_SERVER['REQUEST_URI'])[1] ;
    $string = $url_filter;
    $pattern = '/page=(\d+)/i';
    $replacement = 'page='.$page;
    $url = preg_replace($pattern, $replacement, $string);

    return $url;
  }

  private function check_current_page( $page_count ) {
    /* Set to page 1, if not set */
    if (!isset($_GET['page'])){
      redirect( '?' . explode('?', $_SERVER['REQUEST_URI'])[1] . '&page=1');
    }

    /* Check Current page number not exceeding */
    // Not making it into here, stuck in "No Results (200)" Error.
    /*if ( ((int)$_GET['page']) > $page_count){
      redirect( '?' . change_page_in_url($page_count) );
    }*/
  }


  public function make_table () {
   if(count($this->body_array)>0) { ?>



     <!--===============================================================================================-->
     <link rel="icon" type="image/png" href="/assets/bigtable/images/icons/favicon.ico"/>
     <!--===============================================================================================-->
     <link rel="stylesheet" type="text/css" href="/assets/bigtable/vendor/bootstrap/css/bootstrap.min.css">
     <!--===============================================================================================-->
     <link rel="stylesheet" type="text/css" href="/assets/bigtable/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
     <!--===============================================================================================-->
     <link rel="stylesheet" type="text/css" href="/assets/bigtable/vendor/animate/animate.css">
     <!--===============================================================================================-->
     <link rel="stylesheet" type="text/css" href="/assets/bigtable/vendor/select2/select2.min.css">
     <!--===============================================================================================-->
     <link rel="stylesheet" type="text/css" href="/assets/bigtable/vendor/perfect-scrollbar/perfect-scrollbar.css">
     <!--===============================================================================================-->
     <link rel="stylesheet" type="text/css" href="/assets/bigtable/css/util.css">
     <link rel="stylesheet" type="text/css" href="/assets/bigtable/css/main.css">
     <link rel="stylesheet" type="text/css" href="/assets/bigtable/css/pointer.css">
     <!--===============================================================================================-->

     <!--===============================================================================================-->
     <script src="/assets/vendor/jquery/jquery-3.2.1.min.js"></script>
     <!--===============================================================================================-->
     <script src="/assets/vendor/bootstrap/js/popper.js"></script>
     <script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
     <!--===============================================================================================-->
     <script src="/assets/vendor/select2/select2.min.js"></script>
     <!--===============================================================================================-->
     <script src="/assets/js/main.js"></script>

     <style>
     .row100 td {
        cursor: pointer;
      }
      .search-action, tbody tr td:last-child {
        text-align: center;
      }

      .table100 {
        overflow-x: scroll;
        overflow-y: hidden;
        white-space: nowrap;
    </style>

  <div class="table100 ver2 m-b-110">
    <table data-vertable="ver2">
    </div>
    <thead>
      <tr class="row100 head">

                        <?php // Show Header
                        for($i=1; $i<=$this->columns; $i++)  {
                         if( isset($this->custom_columns[$i]) ) {
                          echo '<th>'.$this->custom_columns[$i]['title'].'</th>';
                        } else {
                          echo '<th>'.$this->header_titles[$i].'</th>';
                        }
                      }
                      ?>

                    </tr>
                  </thead>

                  <?php// } ?>
                  <?php if($this->show_footer) { ?>
                    <tfoot>
                      <tr>
                        <?php // Show Footer
                        for($i=1; $i<=$this->columns; $i++)  {
                         if( isset($this->custom_columns[$i]) ) {
                          echo '<th class="column100 column'.$i.' data-column="column'.$i.'">'.$this->custom_columns[$i]['title'].'</th>';
                        } else {
                          echo '<th class="column100 column'.$i.' data-column="column'.$i.'">'.$this->header_titles[$i].'</th>';
                        }
                      }
                      ?>

                    </tr>
                  </tfoot>
                <?php } ?>
                <tbody>

                  <?php foreach($this->body_array as $key=>$row) {  $action_column=$this->action_column; ?>



                    <tr class="row100" <?php if ($this->tr_action) { ?> onclick="window.location='<?php  echo(  $this->action_uri . $row->$action_column ); ?>';"  <?php } ?> >

                      <?php for ($i=1; $i<=$this->columns; $i++) {

                        if ( isset($this->custom_columns[$i]) ){
                                    // Show Custom Column
                          if(isset($this->custom_columns[$i]['type'])){
                            if($this->custom_columns[$i]['type'] == 'double-selection'){
                                        // Double Selection Column
                              $col = $this->custom_columns[$i]['header'];
                              if($row->$col){
                                echo '<td class="column100 column'.$i.'" data-column="column'.$i.'">'.$this->custom_columns[$i]['on-1'].'</td>';
                              }else{
                                echo  '<td class="column100 column'.$i.'" data-column="column'.$i.'">'.$this->custom_columns[$i]['on-0'].'</td>';
                              }
                            } else if ($this->custom_columns[$i]['type'] == 'related'){


                              // Related Column
                              //var_dump($this->custom_columns[$i]['related_field']);
                              $related_col = $this->custom_columns[$i]['related_resource'];
                              $related_resource = $this->custom_columns[$i]['resource_name'];
                              //$col = $this->custom_columns[$i]['header'];
                              echo '<td class="column100 column'.$i.'" data-column="column'.$i.'">'.
                                    $row->$related_col->$related_resource
                                .'</td>';


                            }
                          } else {
                                       // Object Column
                            $custom_col_api = new ApiClient();
                            $id_field = $this->custom_columns[$i]['id_field'];
                            $resource_field = $this->custom_columns[$i]['resource_field'];
                            $custom_col_api->get_row( $this->custom_columns[$i]['api_uri'] , $row->$id_field );
                            echo ' <td class="column100 column'.$i.'" data-column="column'.$i.'">'.$custom_col_api->get_resource()->$resource_field.'</td>';
                          }
                        } else {
                                    // Show Regular Column
                          $col = $this->header_array[$i];
                          echo  '<td class="column100 column'.$i.'" data-column="column'.$i.'">'.$row->$col.'</td>';
                        } ?>
                      <?php }} ?>
                    </tr>

                  </tbody>

                </table>
                <?php $this->paginate(); ?>
              </div>
              <?php
            }else {
              ?>
              <h5><?php t('Nothing to be displayed here!'); ?></h5>
              <?php
            }
          }

        }

        ?>
