<?php require_once 'header.php'; ?>

<div class="row">
    <!--<div class="col-12">
      <div class="card">
        <a href=""><button type="submit" class="btn btn-success"></button></a>
      </div>
    </div>-->
    <div class="col-md-12">
      <div class="card">

        <!--<h5>This chart is currently not available due to calculation difficulties.<br>Your system administrator has already been notified and will be fixed soon!</h5>
        -->

        <?php 

        //require 'utils/BarChart.php';

        /*$api = new ApiClient();
        $api->get('/zazu_stats_levels/');

        $data = [];
        if ($api->get_results_count()>0){
          foreach( $api->get_resource() as $key=>$level ){
            array_push($data, ['level'=>$level->level_title, 'population'=>$level->value]);
          }
        }


        $chart = new BarChart();
        $chart->set_name(tr('Levels'));
        $chart->set_y_name('population');
        $chart->set_y_title(tr('Population'));
        $chart->set_x_name('level');
        $chart->set_data($data);
        $chart->draw();*/

        ?>


      <?php

      $data = [];

      $api_levels = new ApiClient();
      $api_levels->get_filter('/zazu_card_level/', '(is_enabled=1)and(is_deleted=0)&fields=card_level_id,title');

      if ($api_levels->get_results_count()>0){
        foreach($api_levels->get_resource() as $key=>$level){

          $api = new ApiClient();
          $api->get_count('/zazu_customer/', 
            '(card_level_id='.$level->card_level_id.')&count_only=1');

          if ($api->get_results_count()>=0){

            array_push($data,
                ['level'=>$level->title, 'population'=>$api->get_results_count()]);

          }

        }

      }
      
        /*$api = new ApiClient();
        $api->get_filter('/zazu_customer/', 
          '(is_enabled=1)and(is_deleted=0)&sort=card_level_id&fields=card_level_id&count_only=1');

        var_dump( $api->get_response_json() );

        if ($api->get_results_count()>0){
          foreach( $api->get_resource() as $key=>$level ){
            if (!isset($table[ $key ])){
              $table[ $key ][ 'population' ] = 0;
            }
            $table[ $key ][ 'level' ] = $level->title;
            $table[ $key ][ 'population' ] ++; 
          }
          */
          require 'utils/BarChart.php';
          $chart = new BarChart();
          $chart->set_name(tr('Levels'));
          $chart->set_y_name('population');
          $chart->set_y_title(tr('Population'));
          $chart->set_x_name('level');
          $chart->set_data($data);
          $chart->draw();

        //}
      

      ?>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>