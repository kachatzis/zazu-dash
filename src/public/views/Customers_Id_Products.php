<?php require_once 'header.php'; ?>

      <!-- Use the next format, to add table-->


      <?php
      class Good {
        public $title;
        public $cost;

        public function __construct() { }
      }
      ?>


      <div class="row">
        <div class="col-md-2">
          <div class="card">
            <a href="/customers/<?php echo $customer_id; ?>"><button class="btn btn-info">Back</button></a>
          </div>
        </div>
        <div class="col-md-10">
          <div class="card">
            <div class="col-12">
              <a href="/customers/<?php echo $customer_id; ?>/products?show=product_list"><button class="btn btn-success">Product List</button></a>
              <a href="/customers/<?php echo $customer_id; ?>/products?show=sum_per_product"><button class="btn btn-success">Summarization Per Product</button></a>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">

            <?php

              require_once 'utils/Table.php';
              require_once 'utils/ApiClient.php';

              if(isset( $_GET['show'] )){
                if($_GET['show']=='product_list'){
                          $api_good_bought = new ApiClient();
                          $api_good_bought->get_filter('/zazu_transaction_good/', '(customer_id='.$customer_id.')');


                          if($api_good_bought->get_response_code()!=200){ ?>
                            <h2><?php t('Error! No data received');?> (<?php echo $api_good_bought->get_response_code(); ?>)</h2>
                          <?php } else {
                            if (!$api_good_bought->get_results_count()>0) { ?>
                              <h2><?php t('No results found!');?> (<?php echo $api_good_bought->get_response_code(); ?>)</h2>
                            <?php } else {
                              $table = new Table();
                              $table->set_table_name(tr('Products'));
                              $table->set_action_uri('/products/');
                              $table->set_action_column('good_id');
                              $table->set_body_array($api_good_bought->get_resource());
                              $table->set_columns(2);
                              $table->set_custom_columns([1=>['header'=>'title', 'title'=>tr('Product'), 'id_field'=>'good_id', 'api_uri'=>'/zazu_good/', 'resource_field'=>'title']]);
                              $table->set_header_array([2=>'cost']);
                              $table->set_header_titles([2=>tr('Purchases')]);
                              $table->set_tr_action(true);
                              echo $table->make_table();
                            }
                          }

                } elseif($_GET['show']=='sum_per_product'){

                            $good_counter = [];

                            $api_good = new ApiClient();
                            $api_good->get('/zazu_good/');


                            $api_transaction_good = new ApiClient();
                            $api_transaction_good->get_filter('/zazu_transaction_good/', '(customer_id='.$customer_id.')');

                            if($api_transaction_good->get_results_count()>0){
                              foreach($api_transaction_good->get_resource() as $row_key=>$row_val){
                                if(isset($good_counter[$row_val->good_id])){
                                  $good_counter[$row_val->good_id] += $row_val->cost;
                                } else {
                                  $good_counter[$row_val->good_id] = $row_val->cost;
                                }
                              }

                              $api_good = new ApiClient();
                              $api_good->get('/zazu_good/');

                              $table_body = [];

                              foreach($api_good->get_resource() as $row_key=>$row_val){
                                if(isset( $good_counter[$row_val->good_id] )){
                                  // Add Product to $table_body
                                  $good = new Good();                                       // ???
                                  $good->title = $row_val->title;
                                  $good->cost = $good_counter[$row_val->good_id];
                                  $table_body[$row_val->good_id] = $good;
                                }
                              }

                              // Make Table
                              $table = new Table();
                              $table->set_table_name(tr('Products'));
                              $table->set_body_array($table_body);
                              $table->set_tr_action(false);
                              $table->set_columns(2);
                              $table->set_header_array([1=>'title', 2=>'cost']);
                              $table->set_header_titles([1=>tr('Code'), 2=>tr('Purchases')]);
                              $table->make_table();
                            } else {
                              ?>
                                <h5><?php t('Nothing to be displayed here!');?></h5>
                              <?php
                            }

                }

              } else {
                redirect('/customers/'.$customer_id.'/products?show=product_list');
              }


            ?>

          </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <?php

              if($_GET['show']=='sum_per_product'){


                if($api_transaction_good->get_results_count()>0){
                  $chart_data = [];
                  
                  foreach($table_body as $key=>$row){
                    array_push($chart_data, ['title'=>$row->title, 'cost'=>$row->cost]);
                  }
                  

                  require 'utils/BarChart.php';
                  $chart = new BarChart();
                  $chart->set_name('products');
                  $chart->set_y_name('cost');
                  $chart->set_y_title('Sales (€)');
                  $chart->set_x_name('title');
                  $chart->set_data($chart_data);
                  $chart->draw();
                }


              }

            ?>
          </div>
        </div>
      </div>

<?php require_once 'footer.php'; ?>
