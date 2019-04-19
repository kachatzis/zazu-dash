<?php require_once 'header.php'; ?>

<div class="row">
    <div class="col-12">
      <div class="card">
        <a href="/transactions_unsync"><button type="submit" class="btn btn-success"><?php t('Unsynchronized Transactions'); ?></button></a>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
      <?php
 
        require_once 'utils/BigTable.php';
        require_once 'utils/ApiClient.php';

        /*$api = new ApiClient();
        $api->get_filter('/zazu_transaction/', '&sort=request_date');
        */
        $rows = 100;
        $page = 1;
        if (isset($_GET['page'])){
          $page = $_GET['page'];
        }
        $offset = ($page - 1) * $rows;

        $api = new ApiClient();
        $api->get_count('/zazu_transaction/',
          '(is_processed=1)'
        );
        $count = $api->get_results_count();

        if ($count>0){
          $api = new ApiClient();
          $api->get_filter('/zazu_transaction/',
            '(is_processed=1)&related=zazu_customer_by_customer_id&order=transaction_date%20DESC%2C%20transaction_id%20DESC&limit='.$rows.'&offset='.$offset
          );
        }

        if($api->get_response_code()!=200){ ?>
          <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
        <?php } else {
          if (!$api->get_results_count()>0) { ?>
            <h2>No results found! (<?php echo $api->get_response_code(); ?>)</h2>
          <?php } else {
            $table = new BigTable();
            $table->set_table_name(tr('Transactions'));
            $table->set_action_uri('/transactions/');
            $table->set_action_column('transaction_id');
            $table->set_body_array($api->get_resource());
            $table->set_columns(10);
            $table->set_page($page);
            $table->set_count($count);
            //$table->set_get_table_filter($customer_filter);
            $table->set_rows_per_page($rows);
            $table->set_custom_columns([
              4=>['header'=>'is_canceled', 'title'=>tr('Canceled'), 'type'=>'double-selection', 'on-1'=>tr('Yes'), 'on-0'=>tr('No')],
              5=>['header'=>'card', 'title'=>tr('Card'), 'type'=>'related', 'related_resource'=>'zazu_customer_by_customer_id', 'resource_name'=>'card'],
              9=>['header'=>'last_name', 'title'=>tr('Last Name'), 'type'=>'related', 'related_resource'=>'zazu_customer_by_customer_id', 'resource_name'=>'last_name'],
              10=>['header'=>'first_name', 'title'=>tr('Name'), 'type'=>'related', 'related_resource'=>'zazu_customer_by_customer_id', 'resource_name'=>'first_name'],
              ]);
            $table->set_header_array([1=>'transaction_id', 2=>'request_date', 3=>'request_time', 6=>'credit', 7=>'current_card_credit', 8=>'money_spent']);
            $table->set_header_titles([1=>tr('Transaction'), 2=>tr('Date'), 3=>tr('Time'), 6=>tr('Credits'), 7=>tr('Current Credit'), 8=>tr('Cost')]);
            $table->set_tr_action(true);
            $table->make_table();
          }
        }

      ?>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>
