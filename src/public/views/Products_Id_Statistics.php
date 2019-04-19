<?php require_once 'header.php'; ?>

      <!-- Use the next format, to add table-->




      <div class="row">
        <div class="col-md-2">
          <div class="card">
            <a href="/products/<?php echo $good_id; ?>"><button class="btn btn-info">Back</button></a>
          </div>
        </div>
        <div class="col-md-10">
          
        </div>
        <div class="col-md-12">
          <div class="card">

            <?php

              require_once 'utils/Table.php';
              require_once 'utils/ApiClient.php';

            
                $api_good = new ApiClient();
                $api_good->get_filter('/zazu_transaction_good/', '(good_id='.$good_id.')and(is_canceled=0)');


                if($api_good->get_results_count()>0){

                  $data = [];
                  $data[0]['good'] = tr('Earnings');
                  $data[0]['cost'] = 0;
                  $data[1]['good'] = tr('Transactions');
                  $data[1]['cost'] = 0;
                  
                  foreach($api_good->get_resource() as $key=>$good){
                    /*$api_transaction = new ApiClient();
                    $api_transaction->get_row('/zazu_transaction/', $good->transaction_id);
                    if($api_transaction->get_response_code()==200){
                      array_push($chart_data, ['date'=>$api_transaction->get_resource()->transaction_date, 'cost'=>$good->cost]);
                    }*/
                    $data[0]['cost'] += $good->cost;
                    $data[1]['cost'] += 1;
                  }

                  

                  /*$api_good = new ApiClient();
                  $api_levels->get_row('/zazu_good/', $good_id);


                      $api = new ApiClient();
                      $api->get_count('/zazu_customer/', 
                        '(card_level_id='.$level->card_level_id.')&count_only=1');

                      if ($api->get_results_count()>=0){

                        array_push($data,
                            ['level'=>$level->title, 'population'=>$api->get_results_count()]);

                      }


                }
                else{
                  echo '<p>Not enough data to build a chart.</p>';
                }
              */
          require 'utils/BarChart.php';
          $chart = new BarChart();
          $chart->set_name(tr('Product'));
          $chart->set_y_name('cost');
          $chart->set_y_title(tr('Earnings'));
          $chart->set_x_name('good');
          $chart->set_data($data);
          $chart->draw();

}
            ?>
          </div>
        </div>
      </div>

<?php require_once 'footer.php'; ?>
