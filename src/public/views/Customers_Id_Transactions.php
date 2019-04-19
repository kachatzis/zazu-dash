<?php require 'header.php'; ?>
<?php require 'utils/LineChart.php'; ?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <a href="/customers/<?php echo $customer_id; ?>"><button class="btn btn-info">Back</button></a>
    </div>
  </div>


<?php
        $api = new ApiClient();
        $api->get_filter('/zazu_transaction/', '(customer_id='.$customer_id.')&order=request_date%20ASC%2C%20request_time%20ASC');
        ?>
  
    <div class="col-md-12">
      <div class="card">
      <?php

        require_once 'utils/Table.php';
        require_once 'utils/ApiClient.php';

        

        if($api->get_response_code()!=200){ ?>
          <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
        <?php } else {
          if (!$api->get_results_count()>0) { ?>
            <h2>No results found! (<?php echo $api->get_response_code(); ?>)</h2>
          <?php } else {
            $table = new Table();
            $table->set_table_name('Transactions');
            $table->set_action_uri('/transactions/');
            $table->set_action_column('transaction_id');
            $table->set_body_array($api->get_resource());
            $table->set_columns(7);
            $table->set_custom_columns([
              7=>['header'=>'is_canceled', 'title'=>tr('Canceled'), 'type'=>'double-selection', 'on-1'=>tr('Yes'), 'on-0'=>tr('No'), 'resource_field'=>'name'],
            ]);
            $table->set_header_array([3=>'transaction_id', 1=>'request_date', 2=>'request_time', 4=>'credit', 5=>'current_card_credit', 6=>'money_spent']);
            $table->set_header_titles([3=>tr('Transaction'), 1=>tr('Date'), 2=>tr('Time'), 4=>tr('Credits'), 5=>tr('Current Credit'), 6=>tr('Cost')]);
            $table->set_tr_action(true);
            echo $table->make_table();
          }
        }

      ?>
    </div>
  </div>




   <!-- Stats -->
  <div class="col-md-12">
    <div class="card">


      

        <?php
        
        if($api->get_results_count()>0){
          $chart_data = [];
          foreach($api->get_resource() as $key=>$transaction){
            //if ($transaction->transaction_date != "" && $transaction->transaction_time != "" && $transaction->money_spent != 0)
            array_push($chart_data, ['date'=>$transaction->transaction_date.' '.$transaction->transaction_time, 'value'=>$transaction->money_spent]);
          }
          $chart = new LineChart();
          $chart->set_name('customer_transactions');
          $chart->set_y_name('value');
          $chart->set_y_title('Value');
          $chart->set_x_name('date');
          $chart->set_type('datetime');
          $chart->set_data($chart_data);
          $chart->draw();
        }
      ?>


    </div>
  </div>



  </div>
</div>

<?php require_once 'footer.php'; ?>
