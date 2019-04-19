<?php require_once 'header.php'; ?>

<div class="row">
    <!--<div class="col-12">
      <div class="card">
        <a href=""><button type="submit" class="btn btn-success"></button></a>
      </div>
    </div>-->
    <div class="col-md-12">
      <div class="card">
      <?php

        require 'utils/LineChart.php';
        $api = new ApiClient();
        $api->get('/zazu_stats_sales/');

        /*$data = [];

        $api = new ApiClient();
        $api->get('/zazu_stats_sales/');
        if ($api->get_results_count()>0){
          foreach($api->get_resource() as $key=>$sales){
            array_push($data, ['date'=>$sales->date_time, 'value'=>$sales->value]);
          }

          $chart = new LineChart();
          $chart->set_name('transactions');
          $chart->set_y_name('value');
          $chart->set_y_title('Sales (€)');
          $chart->set_x_name('date');
          $chart->set_type('datetime');
          $chart->set_data($data);
          $chart->draw();

        }*/





        $date_limit = date('Y-m-d',
                strtotime('-4 months', strtotime(date('Y-m-d'))));

        $api = new ApiClient();
        $api->get_filter('/zazu_transaction/', '(is_processed=1)and(is_canceled=0)and(transaction_date>='.$date_limit.')&fields=transaction_date,transaction_time,money_spent&order=transaction_date%20DESC%2C%20transaction_id%20DESC&limit=1000');

          $my_array=$api->get_resource() ;
          $my_data=[];
          foreach($my_array as $transaction){
            array_push($my_data,['date'=>$transaction->transaction_date.' '.$transaction->transaction_time,'value'=>$transaction->money_spent]);
          }
          $chart = new LineChart();
          $chart->set_name('transactions');
          $chart->set_y_name('value');
          $chart->set_y_title('Sales (€)');
          $chart->set_x_name('date');
          $chart->set_data(($my_data));
          $chart->set_data_display_type('hour');
          $chart->set_type('datetime');
          $chart->draw();



      ?>
    </div>
  </div>
</div>
<?php require_once 'footer.php'; ?>
