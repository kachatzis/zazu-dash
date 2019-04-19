<?php require_once 'header.php'; ?>

      <!-- Use the next format, to add table-->




      <div class="row">
        <div class="col-md-2">
          <div class="card">
            <a href="/stores/<?php echo $store_id; ?>"><button class="btn btn-info">Back</button></a>
          </div>
        </div>
        <div class="col-md-10">
          
        </div>
        <div class="col-md-12">
          <div class="card">

            <?php

              require_once 'utils/Table.php';
              require_once 'utils/ApiClient.php';

            
                $api_store = new ApiClient();
                $api_store->get_filter('/zazu_transaction/', '(store_id='.$store_id.')and(is_canceled=0)');

                if($api_store->get_results_count()>0){

                  $chart_data = [];
                  
                  foreach($api_store->get_resource() as $key=>$store){
                      array_push($chart_data, ['date'=>$store->transaction_date, 'cost'=>$store->money_spent]);
                  }
                  

                  require 'utils/LineChart.php';
                  $chart = new LineChart();
                  $chart->set_name('product_stats');
                  $chart->set_y_name('cost');
                  $chart->set_y_title('Sales (€)');
                  $chart->set_x_name('date');
                  $chart->set_data($chart_data);
                  $chart->set_type('datetime');
                  $chart->draw();

                }
                else{
                  echo '<p>Not enough data to build a chart.</p>';
                }



            ?>
          </div>
        </div>
      </div>

<?php require_once 'footer.php'; ?>
