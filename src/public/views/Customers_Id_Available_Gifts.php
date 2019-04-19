<?php require 'header.php'; ?>
<?php require 'utils/LineChart.php'; ?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <a href="/customers/<?php echo $customer_id; ?>"><button class="btn btn-info"><?php t('Back'); ?></button></a>
    </div>
  </div>

  
    <div class="col-md-12">
      <div class="card">
      <?php

        require_once 'utils/Table.php';
        require_once 'utils/ApiClient.php';



        $api_customer = new ApiClient();
        $api_customer->get_row('/zazu_customer/', $customer_id);

        if ($api_customer->get_response_code()!=200){
          redirect('/customers/'.$customer_id);
        }

        $api = new ApiClient();
        $api->get_filter('/zazu_good/', '(is_gift=1)and(credits_cost<='.$api_customer->get_resource()->credit.')and(card_level_id='.$api_customer->get_resource()->card_level_id.')&limit=100');
        


        if($api->get_response_code()!=200){ ?>
          <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
        <?php } else {
          if (!$api->get_results_count()>0) { ?>
            <h2>No results found! (<?php echo $api->get_response_code(); ?>)</h2>
          <?php } else {
            $table = new Table();
            $table->set_table_name(tr('Gifts'));
            $table->set_action_uri('/products/');
            $table->set_action_column('good_id');
            $table->set_body_array($api->get_resource());
            $table->set_columns(2);
            /*$table->set_custom_columns([
              5=>['header'=>'is_processed', 'title'=>tr('Processed'), 'type'=>'double-selection', 'on-1'=>tr('Yes'), 'on-0'=>tr('No'), 'resource_field'=>'name'],
              6=>['header'=>'is_canceled', 'title'=>tr('Canceled'), 'type'=>'double-selection', 'on-1'=>tr('Yes'), 'on-0'=>tr('No'), 'resource_field'=>'name'],
            ]);*/
            $table->set_header_array([1=>'title', 2=>'credits_cost']);
            $table->set_header_titles([1=>tr('Gift'), 2=>tr('Credits')]);
            $table->set_tr_action(true);
            echo $table->make_table();
          }
        }

      ?>
    </div>
  </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>
