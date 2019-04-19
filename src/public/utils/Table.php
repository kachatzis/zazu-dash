<?php

  require_once 'utils/ApiClient.php';

 class Table {

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


   public function make_table () {
     if(count($this->body_array)>0) { ?>


     <style>
     .table-pointer { cursor: pointer; }
     </style>

     <div class="card0">
           <div class="card-body">
             <h4 class="card-title">  <?php echo  $this->table_name; ?>  </h4>
             <h6 class="card-subtitle"> <?php echo  $this->table_description; ?> </h6>
             <div class="table-responsive m-t-40">
                 <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                   <?php if($this->show_header) { ?>
                    <thead>
                        <tr>

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
                    <?php } ?>
                    <?php if($this->show_footer) { ?>
                    <tfoot>
                        <tr>

                          <?php // Show Footer
                          for($i=1; $i<=$this->columns; $i++)  {
                             if( isset($this->custom_columns[$i]) ) {
                                echo '<th>'.$this->custom_columns[$i]['title'].'</th>';
                              } else {
                                echo '<th>'.$this->header_titles[$i].'</th>';
                              }
                            }
                          ?>

                        </tr>
                    </tfoot>
                    <?php } ?>
                    <tbody>

                      <?php foreach($this->body_array as $key=>$row) {  $action_column=$this->action_column; ?>

                          <tr class="table-pointer" <?php if ($this->tr_action) { ?> onclick="window.location='<?php  echo(  $this->action_uri . $row->$action_column ); ?>';" <?php } ?> >

                          <?php for ($i=1; $i<=$this->columns; $i++) {

                                    if ( isset($this->custom_columns[$i]) ){
                                      // Show Custom Column
                                      if(isset($this->custom_columns[$i]['type'])){
                                        if($this->custom_columns[$i]['type'] == 'double-selection'){
                                          // Double Selection Column
                                          $col = $this->custom_columns[$i]['header'];
                                          if($row->$col){
                                            echo '<th>'.$this->custom_columns[$i]['on-1'].'</th>';
                                          }else{
                                            echo '<th>'.$this->custom_columns[$i]['on-0'].'</th>';
                                          }
                                        }
                                       } else {
                                         // Object Column
                                        $custom_col_api = new ApiClient();
                                        $id_field = $this->custom_columns[$i]['id_field'];
                                        $resource_field = $this->custom_columns[$i]['resource_field'];
                                        $custom_col_api->get_row( $this->custom_columns[$i]['api_uri'] , $row->$id_field );
                                        echo '<th>'.$custom_col_api->get_resource()->$resource_field.'</th>';
                                      }
                                    } else {
                                      // Show Regular Column
                                      $col = $this->header_array[$i];
                                      echo '<th>'.$row->$col.'</th>';
                                    } ?>
                          <?php } ?>
                          </tr>

                      <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>




<?php
} else {
  ?>
  <h5>Nothing to be displayed here!</h5>
  <?php
}
   }

 }

?>
