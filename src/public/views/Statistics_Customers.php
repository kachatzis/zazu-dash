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

        /*$data = [];
        $api = new ApiClient();
        $api->get('/zazu_stats_customers/');
        if ($api->get_results_count()>0){
          foreach($api->get_resource() as $key=>$customers){
            array_push($data, ['date'=>$customers->date, 'population'=>$customers->value]);
          }

          $chart = new LineChart();
          $chart->set_name('transactions');
          $chart->set_y_name('population');
          $chart->set_y_title('Customer Registrations (€)');
          $chart->set_x_name('date');
          $chart->set_type('datetime');
          $chart->set_data($data);
          $chart->draw();
        }
        */

        $apicount = new ApiClient();
        $apicount->get_count('/zazu_customer/', '(is_deleted=0)');

        $api = new ApiClient();
        $api->get_filter('/zazu_customer/',
          '(is_deleted=0)&sort=registration_date&fields=registration_date,registration_time&order=registration_date%20DESC&limit=1000');

        if($api->get_results_count()>0 && $apicount->get_results_count()>0){
          $chart_data = [];
          $customer_count = $apicount->get_results_count();
          foreach($api->get_resource() as $key=>$customer){
            array_push($chart_data, ['date'=>$customer->registration_date.' '.$customer->registration_time, 'value'=>$customer_count]);
            $customer_count-=1;
          }
          $chart = new LineChart();
          $chart->set_name('transactions');
          $chart->set_y_name('value');
          $chart->set_y_title('Sales (€)');
          $chart->set_x_name('date');
          $chart->set_type('datetime');
          $chart->set_data(array_reverse($chart_data));
          //$chart->set_data_display_type('hour');
          $chart->set_min_period('DD');
          $chart->draw();
        }

      ?>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>
