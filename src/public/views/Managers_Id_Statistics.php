<?php require_once 'header.php'; ?>

      <!-- Use the next format, to add table-->


      <div class="row">
        <div class="col-md-2">
          <div class="card">
            <a href="/managers/<?php echo $manager_id; ?>"><button class="btn btn-info"><?php echo t('Back'); ?></button></a>
          </div>
        </div>
        <div class="col-md-10">

        </div>
        <div class="col-md-12">
          <div class="card">

            <?php


              require_once 'utils/ApiClient.php';



                  $api = new ApiClient();
                  $api->get_filter('/zazu_transaction/', '(manager_id='.$manager_id.')');

                  if($api->get_results_count()>0){
                    require 'utils/LineChart.php';
                    $chart_data = [];
                    foreach($api->get_resource() as $key=>$transaction){
                      array_push($chart_data, ['date'=>$transaction->transaction_date.' '.$transaction->transaction_time, 'value'=>$transaction->money_spent]);
                    }
                    $chart = new LineChart();
                    $chart->set_name('manager_transactions');
                    $chart->set_y_name('value');
                    $chart->set_y_title('Value');
                    $chart->set_x_name('date');
                    $chart->set_type('datetime');
                    $chart->set_data($chart_data);
                    $chart->set_data_display_type('');
                    $chart->draw();
                  }


                else{
                  echo '<p>Not enough data to build a chart.</p>';
                }



            ?>
          </div>
        </div>


        <div class="col-md-12">
      <div class="card">
      <?php

        require_once 'utils/Table.php';

        if($api->get_response_code()!=200){ ?>
          <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
        <?php } else {
          if (!$api->get_results_count()>0) { ?>
            <h2>No results found! (<?php echo $api->get_response_code(); ?>)</h2>
          <?php } else {
            $table = new Table();
            $table->set_table_name(tr('Transactions'));
            $table->set_action_uri('/transactions/');
            $table->set_action_column('transaction_id');
            $table->set_body_array($api->get_resource());
            $table->set_columns(6);
            $table->set_custom_columns([
              5=>['header'=>'is_processed', 'title'=>tr('Processed'), 'type'=>'double-selection', 'on-1'=>tr('Yes'), 'on-0'=>tr('No'), 'resource_field'=>'name'],
              6=>['header'=>'is_canceled', 'title'=>tr('Canceled'), 'type'=>'double-selection', 'on-1'=>tr('Yes'), 'on-0'=>tr('No'), 'resource_field'=>'name'],
            ]);
            $table->set_header_array([1=>'transaction_id', 4=>'credit', 3=>'money_spent', 2=>'transaction_date']);
            $table->set_header_titles([1=>tr('Transaction'), 4=>tr('Credits'), 3=>tr('Cost'), 2=>tr('Date')]);
            $table->set_tr_action(true);
            echo $table->make_table();
          }
        }

      ?>
    </div>
  </div>


      </div>

<?php require_once 'footer.php'; ?>
