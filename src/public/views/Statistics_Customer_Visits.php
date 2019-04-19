<?php require_once 'utils/RestrictLogin.php'; $restrict_login = new RestrictLogin(); $restrict_login->handle(); ?>
    <?php
    //require_once 'utils/ApiClient.php';
    //require_once 'utils/Config.php';
    ?>





<?php 

  /*if (isset($_GET['post_table_filter'])){
    $customer_filter = $_GET['post_table_filter'];
  } else {*/

    // TODO: Add parameter cheking and string striping before using those.
    $customer_filter = '';
    if($_SERVER['REQUEST_METHOD']=='GET'){
      
      if(isset($_GET['min_date'])&&strlen($_GET['min_date'])>0){
        $params['params']['min_date'] = $_GET['min_date'];
      }
      if(isset($_GET['max_date'])&&strlen($_GET['max_date'])>0){
        $params['params']['max_date'] = $_GET['max_date'];
      }
      if(isset($_GET['min_time'])&&strlen($_GET['min_time'])>0){
        $params['params']['min_time'] = $_GET['min_time'];
      }
      if(isset($_GET['max_time'])&&strlen($_GET['max_time'])>0){
        $params['params']['max_time'] = $_GET['max_time'];
      }
      if(isset($_GET['min_transactions'])&&strlen($_GET['min_transactions'])>0){
        $params['params']['min_transactions'] = (int)$_GET['min_transactions'];
      }
      if(isset($_GET['max_transactions'])&&strlen($_GET['max_transactions'])>0){
        $params['params']['max_transactions'] = (int)$_GET['max_transactions'];
      }
      if(isset($_GET['is_processed'])&&strlen($_GET['is_processed'])>0){
        $params['params']['is_processed'] = (int)$_GET['is_processed'];
      }
      if(isset($_GET['is_canceled'])&&strlen($_GET['is_canceled'])>0){
        $params['params']['is_canceled'] = (int)$_GET['is_canceled'];
      }
      if(isset($_GET['min_cost'])&&strlen($_GET['min_cost'])>0){
        $params['params']['min_cost'] = (float)$_GET['min_cost'];
      }
      if(isset($_GET['max_cost'])&&strlen($_GET['max_cost'])>0){
        $params['params']['max_cost'] = (float)$_GET['max_cost'];
      }
      if(isset($_GET['store_id'])/*&&strlen($_GET['store_id'])>0*/){
        $params['params']['store_id'] = $_GET['store_id'];
      }

      $params['wrapper'] = 'resource';
      $params['params']['page_limit']=30000;
      $params['params']['page_offset']=0;
  
    }
    
    /*}*/
    ?>
<?php if(!isset($_GET['export'])){ ?>

    <?php require_once 'header.php'; ?>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="row">
            <div class="col-md-12">
              <!-- Tab panes -->
              <form id="search_form" action="#" method="GET" target="_blank">
                <div class="tab-content">

                  <div class="tab-pane active" id="info" role="tabpanel">
                    <div class="p-20">


                      <!-- Dates -->
                      <div class="row">
                          <div class="col-md-6">
                            <p><?php echo tr('From').' ('.tr('Date').')'; ?>:</p>
                            <input required type="date" name="min_date" class="form-control form-control-line"
                            value="<?php if(isset($_GET['min_date']) && $_GET['min_date']!='') echo $_GET['min_date']; ?>">
                          </div>
                          <div class="col-md-6">
                            <p><?php echo tr('To').' ('.tr('Date').')'; ?>:</p>
                            <input required type="date" name="max_date" class="form-control form-control-line"
                            value="<?php if(isset($_GET['max_date']) && $_GET['max_date']!=''){ echo $_GET['max_date']; }else{ echo date('Y-m-d'); } ?>">
                          </div>
                      </div>
                      <br>



                      <!-- Times -->
                      <div class="row">
                          <div class="col-md-6">
                            <p><?php echo tr('From').' ('.tr('Time').')'; ?>:</p>
                            <input required type="text" placeholder="00:00:00" pattern="[0-9:]{8}" name="min_time" class="form-control form-control-line"
                            value="<?php if(isset($_GET['min_time']) && $_GET['min_time']!='') echo $_GET['min_time']; ?>">
                          </div>
                          <div class="col-md-6">
                            <p><?php echo tr('To').' ('.tr('Time').')'; ?>:</p>
                            <input required type="text" placeholder="23:59:59" pattern="[0-9:]{8}" name="max_time" class="form-control form-control-line"
                            value="<?php if(isset($_GET['max_time']) && $_GET['max_time']!='') echo $_GET['max_time']; ?>">
                          </div>
                      </div>
                      <br>



                      <!-- Visits -->
                      <div class="row">
                          <div class="col-md-6">
                            <p><?php t('Minimum Visits'); ?>:</p>
                            <input required type="number" pattern="[0-9]{0,15}" min="0" max="999999999" name="min_transactions" class="form-control form-control-line"
                            value="<?php if(isset($_GET['min_transactions']) && $_GET['min_transactions']!='') echo $_GET['min_transactions']; ?>">
                          </div>
                          <div class="col-md-6">
                            <p><?php t('Maximum Visits'); ?>:</p>
                            <input required type="number" pattern="[0-9]{0,15}" min="0" max="999999999" name="max_transactions" class="form-control form-control-line"
                            value="<?php if(isset($_GET['max_transactions']) && $_GET['max_transactions']!=''){ echo $_GET['max_transactions'];} ?>">
                          </div>
                      </div>
                      <br>



                      <!-- Cost -->
                      <div class="row">
                          <div class="col-md-6">
                            <p><?php t('Minimum Cost'); ?>:</p>
                            <input required type="number" pattern="[0-9.]{0,15}" min="0" max="999999999" name="min_cost" class="form-control form-control-line"
                            value="<?php if(isset($_GET['min_cost']) && $_GET['min_cost']!='') echo $_GET['min_cost']; ?>">
                          </div>
                          <div class="col-md-6">
                            <p><?php t('Maximum Cost'); ?>:</p>
                            <input required type="number" pattern="[0-9.]{0,15}" min="0" max="999999999" name="max_cost" class="form-control form-control-line"
                            value="<?php if(isset($_GET['max_cost']) && $_GET['max_cost']!='') echo $_GET['max_cost']; ?>">
                          </div>
                      </div>
                      <br>




                      <!-- Booleans -->
                      <div class="row">
                          <div class="col-md-6">
                            <p><?php t('Processed'); ?>:</p>
                            <select required name="is_processed" class="form-control form-control-line">
                              <option value=""> - </option>';
                              <option 
                              <?php if(isset($_GET['is_processed']) && $_GET['is_processed']=='1') echo 'selected'; ?>
                              value="1"><?php t('Yes'); ?></option>
                              <option 
                              <?php if(isset($_GET['is_processed']) && $_GET['is_processed']=='0') echo 'selected'; ?>
                              value="0"><?php t('No'); ?></option>
                            </select>
                          </div>
                          <div class="col-md-6">
                            <p><?php t('Canceled'); ?>:</p>
                            <select required name="is_canceled" class="form-control form-control-line">
                              <option value=""> - </option>';
                              <option 
                              <?php if(isset($_GET['is_canceled']) && $_GET['is_canceled']=='1') echo 'selected'; ?>
                              value="1"><?php t('Yes'); ?></option>
                              <option 
                              <?php if(isset($_GET['is_canceled']) && $_GET['is_canceled']=='0') echo 'selected'; ?>
                              value="0"><?php t('No'); ?></option>
                            </select>
                          </div>
                      </div>
                      <br>

 


                      <!-- Store -->
                      <div class="row">
                          <div class="col-md-6">
                            <p><?php t('Store'); ?>:</p>
                            <select multiple required name="store_id[]" class="form-control form-control-line"
                            style="height: 100px;">
                              <?php
                              $api_store = new ApiClient();
                              $api_store->get_filter('/zazu_store/', '(is_enabled=1)');
                              if($api_store->get_response_code()==200){
                                /*echo '<option value=""> - </option>';*/
                                foreach($api_store->get_resource() as $row=>$store_row){ ?>
                                  <option 
                                  <?php if(isset($_GET['store_id']) && $_GET['store_id']==$store_row->store_id) echo 'selected'; ?>
                                  value="<?php echo $store_row->store_id; ?>"><?php echo $store_row->name.' ('.$store_row->code.')'; ?></option>
                                <?php }
                              }
                              ?>
                            </select>
                          </div>
                          <div class="col-md-6">
                            <br><br>
                            <button type="submit" form="search_form" class="btn btn-success"><?php t('Export');?></button>
                          </div>
                      </div>
                      <br>



                      <script>
                        window.onmousedown = function (e) {
                          var el = e.target;
                          if (el.tagName.toLowerCase() == 'option' && el.parentNode.hasAttribute('multiple')) {
                              e.preventDefault();

                              // toggle selection
                              if (el.hasAttribute('selected')) el.removeAttribute('selected');
                              else el.setAttribute('selected', '');

                              // hack to correct buggy behavior
                              var select = el.parentNode.cloneNode(true);
                              el.parentNode.parentNode.replaceChild(select, el.parentNode);
                          }
                      }
                      </script>

                    </div>
                    <input type="hidden" name="export" value="">
                  </form>

                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="card">
    <?php

    /*require_once 'utils/BigTable.php';
    require_once 'utils/ApiClient.php';
    $rows = 35;

    $page = 1;
    if (isset($_GET['page'])){
      $page = $_GET['page'];
    }
    $offset = ($page - 1) * $rows;

    $params['params']['page_offset'] = $offset;
    $params['params']['page_limit'] = $rows;

    
    $api = new ApiClient();
    $api->post_proc('/count_customer_table_count_transactions/', $params );


    $count = $api->get_resource()[0]->count;

    if ($count>0){
      $api = new ApiClient();
      $api->post_proc('/customer_table_count_transactions/', $params );
    
var_dump($params);

    if($api->get_response_code()!=200){ ?>
      <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
    <?php } else {
      if (!$api->get_results_count()>0) { ?>
        <h2><?php t('No results found!');?> (<?php echo $api->get_response_code(); ?>)</h2>
      <?php } else {
        $table = new BigTable();
        $table->set_table_name(tr('Customers'));
        $table->set_action_uri('/customers/');
        $table->set_action_column('customer_id');
        $table->set_body_array($api->get_resource());
        $table->set_columns(5);
        $table->set_page($page);
        $table->set_count($count);
        $table->set_get_table_filter('');
        $table->set_rows_per_page($rows);
        $table->set_header_array(
          [1=>'first_name', 2=>'last_name',3=>'card', 4=>'credit', 4=>'last_visit_date', 5=>'transactions']);
        $table->set_header_titles(
          [1=>tr('First Name'), 2=>tr('Last Name'), 3=>tr('Card'), 4=>tr('Last Visit'), 5=>tr('Visits')]);
        $table->set_tr_action(true);
        $table->make_table();
      }
    }
    }
  */
    ?>
  </div>
</div>








<?php } else { ?>

<!-- Export View -->

  <button onclick="window.print();return false;">Print</button>
  <a href="?<?php echo str_replace('export=', '', $_SERVER['QUERY_STRING']); ?>"><button>Back</button></a>


  <?php

    $headers = [];
    $headers[0] = ['title'=>'Card', 'name'=>'card'];
    $headers[1] = ['title'=>'First Name', 'name'=>'first_name'];
    $headers[2] = ['title'=>'Last Name', 'name'=>'last_name'];
    $headers[3] = ['title'=>'Visits', 'name'=>'transactions'];
    $headers[4] = ['title'=>'Last Visit', 'name'=>'last_visit_date'];
    $headers[5] = ['title'=>'Credits', 'name'=>'credit'];
    
    require 'utils/ApiClient.php';

    $stores_id_array = $params['params']['store_id'];
    $stores_resource = array();
    $count = 0;

    foreach ( $stores_id_array as $store_id_val ) {
      $params['params']['store_id'] = $store_id_val;

      $api = new ApiClient();
      $api->post_proc('/count_customer_table_count_transactions/', $params );

      if ($api->get_resource()[0]->count>0) {
        $count += $api->get_resource()[0]->count;

        $api = new ApiClient();
        $api->post_proc('/customer_table_count_transactions/', $params );
        $stores_resource = array_merge($stores_resource, $api->get_resource());
      }  

    }
    

    ?>

      <div class="col-md-12">
        <h3>Results: <?php echo $count; ?></h3>
        <br><br>
      </div>

    <?php

    if ($count>0){
      /*$api = new ApiClient();
      $api->post_proc('/customer_table_count_transactions/', $params );*/
    

    if($api->get_response_code()!=200){ ?>
      <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
    <?php } else {
      if (!$api->get_results_count()>0) { ?>
        <h2><?php t('No results found!');?> (<?php echo $api->get_response_code(); ?>)</h2>
      <?php } else {
        /*var_dump($api->get_resource());
        var_dump($api->get_response_code());
        var_dump($api->get_response_json());
        var_dump($api);
        var_dump($params);*/

        echo '<table style="width:100%">';
        echo '<tr>';
        echo '<th></th>';
        foreach($headers as $header_key=>$header){
          echo '<th>';
          echo $header['title'];
          echo '</th>';
        }
        echo '</tr>';

        foreach($stores_resource as $key=>$row){ 

          echo '<tr>';

          echo '<th>';
          echo $key+1;
          echo '</th>';

          foreach($headers as $header_key=>$header){
            $row_name = $header['name'];
            echo '<th>';
            echo $row->$row_name;
            echo '</th>';
          }

          echo '</tr>';

        }

        echo '</table>';

      }
    }
    }

    ?>



<?php } ?>


<?php require_once 'footer.php'; ?>