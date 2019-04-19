<?php require 'header.php'; ?>


<div class="row">


  <!--<div class="col-12">
    <div class="card">
      <h3>Welcome to</h3>
      <img src="/assets/images/logo-text.png" height="160" width="100" >
    </div>
  </div>-->


<?php
	/* Sales Card */
	$apiCountSales = new ApiClient();
	$apiCountSales->get_count('/zazu_transaction/', '(is_processed=1)and(is_canceled=0)&');
	if ($apiCountSales->get_response_code()==200){
		?>
		<div class="col-md-3" style="cursor: pointer;" onclick="window.location.replace('/transactions?page=1');">
		  	<div class="card p-30">
		  		<div class="media">
		  			<div class="media-left meida media-middle">
		  				<span><i class="fa fa-shopping-cart f-s-40 color-success"></i></span>
		  			</div>
		  			<div class="media-body media-text-right">
		  				<h2><?php echo $apiCountSales->get_results_count(); ?></h2>
		  				<p class="m-b-0"><?php t('Transactions'); ?></p>
		  			</div>
		  		</div>
		  	</div>
		  </div>
		<?php
	}

?>






<?php
	/* Stores Card */
	$apiCountStores = new ApiClient();
	$apiCountStores->get_count('/zazu_store/', '(is_enabled=1)&');
	if ($apiCountStores->get_response_code()==200){
		?>
		<div class="col-md-3" style="cursor: pointer;" onclick="window.location.replace('/stores');">
		  	<div class="card p-30">
		  		<div class="media">
		  			<div class="media-left meida media-middle">
		  				<span><i class="fa fa-archive f-s-40 color-warning"></i></span>
		  			</div>
		  			<div class="media-body media-text-right">
		  				<h2><?php echo $apiCountStores->get_results_count(); ?></h2>
		  				<p class="m-b-0"><?php t('Stores'); ?></p>
		  			</div>
		  		</div>
		  	</div>
		  </div>
		<?php
	}

?>



<?php
	/* Customers Card */
	$apiCountCustomers = new ApiClient();
	$apiCountCustomers->get_count('/zazu_customer/', '(is_enabled=1)and(is_deleted=0)&');
	if ($apiCountCustomers->get_response_code()==200){
		?>
		<div class="col-md-3" style="cursor: pointer;" onclick="window.location.replace('/customers?page=1');">
		  	<div class="card p-30">
		  		<div class="media">
		  			<div class="media-left meida media-middle">
		  				<span><i class="fa fa-user f-s-40 color-danger"></i></span>
		  			</div>
		  			<div class="media-body media-text-right">
		  				<h2><?php echo $apiCountCustomers->get_results_count(); ?></h2>
		  				<p class="m-b-0"><?php t('Customers'); ?></p>
		  			</div>
		  		</div>
		  	</div>
		  </div>
		<?php
	}

?>



<?php
	/* Weekly Customers Card */
	$date_limit = date('Y-m-d', 
                strtotime('-1 week', strtotime(date('Y-m-d'))));
	$apiCountCustomers = new ApiClient();
	$apiCountCustomers->get_count('/zazu_customer/', '(is_enabled=1)and(is_deleted=0)and(registration_date>'.$date_limit.')&');
	if ($apiCountCustomers->get_response_code()==200){
		?>
		<div class="col-md-3" style="cursor: pointer;" onclick="window.location.replace('/customers?min_registration_date=<?php echo $date_limit; ?>&sort=registration_date&sort_verb=DESC&page=1');">
		  	<div class="card p-30">
		  		<div class="media">
		  			<div class="media-left meida media-middle">
		  				<span><i class="fa fa-user f-s-40 color-danger"></i></span>
		  			</div>
		  			<div class="media-body media-text-right">
		  				<h2><?php echo $apiCountCustomers->get_results_count(); ?></h2>
		  				<p class="m-b-0"><?php t('Weekly Registrations'); ?></p>
		  			</div>
		  		</div>
		  	</div>
		  </div>
		<?php
	}

?>




<div class="col-md-12">
      <div class="card">
      <?php

        require_once 'utils/Table.php';
        require_once 'utils/ApiClient.php';

        $api = new ApiClient();
        $api->get_filter('/zazu_transaction/', '(transaction_id>0)and(is_processed=1)&order=transaction_date%20DESC%2C%20transaction_id%20DESC&limit=8');

        if($api->get_response_code()!=200){ ?>
          <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
        <?php } else {
          if (!$api->get_results_count()>0) { ?>
            <h2>No results found! (<?php echo $api->get_response_code(); ?>)</h2>
          <?php } else {
            $table = new Table();
            $table->set_table_name(tr('Latest Transactions'));
            $table->set_action_uri('/transactions/');
            $table->set_action_column('transaction_id');
            $table->set_body_array($api->get_resource());
            $table->set_columns(9);
            $table->set_custom_columns([
				7=>['header'=>'last_name', 'title'=>tr('Name'), 'id_field'=>'customer_id', 'api_uri'=>'/zazu_customer/', 'resource_field'=>'last_name'],
              	8=>['header'=>'first_name', 'title'=>tr('Last Name'), 'id_field'=>'customer_id', 'api_uri'=>'/zazu_customer/', 'resource_field'=>'first_name'],
              	4=>['header'=>'is_canceled', 'title'=>tr('Canceled'), 'type'=>'double-selection', 'on-1'=>tr('Yes'), 'on-0'=>tr('No')],
              	9=>['header'=>'card', 'title'=>tr('Card'), 'id_field'=>'customer_id', 'api_uri'=>'/zazu_customer/', 'resource_field'=>'card'],
              ]);
            $table->set_header_array([1=>'transaction_id', 2=>'transaction_date', 3=>'transaction_time', 5=>'credit', 6=>'money_spent']);
            $table->set_header_titles([1=>tr('Transaction'), 2=>tr('Date'), 3=>tr('Time'), 5=>tr('Credits'), 6=>tr('Cost')]);
            $table->set_tr_action(true);
            $table->make_table();
          }
        }

      ?>
    </div>
</div>

<!--<div class="row">-->
    <div class="col-md-12">
      <div class="card">
      <?php

        require 'utils/LineChart.php';

        $api = new ApiClient();
        $api->get_filter('/zazu_transaction/', '(is_processed=1)and(is_canceled=0)&fields=transaction_date,transaction_time,money_spent&order=transaction_date%20DESC%2C%20transaction_id%20DESC&limit=30');
        
        if($api->get_results_count()>0){
          $chart_data = [];
          foreach($api->get_resource() as $key=>$transaction){
            array_push($chart_data, ['date'=>$transaction->transaction_date.' '.$transaction->transaction_time, 'value'=>$transaction->money_spent]);
          }

          $chart = new LineChart();
          $chart->set_name('transactions');
          $chart->set_y_name('value');
          $chart->set_y_title('Sales (€)');
          $chart->set_x_name('date');
          $chart->set_type('datetime'); 
          $chart->set_data($chart_data);
          $chart->set_data_display_type('hour');
          $chart->draw();
        }
        

      ?>
    </div>
  </div>
<!--</div>-->

</div>

<?php /*if ($config['general_christmas_mode']) { ?>
<div>
	<img src="/assets/images/santa_drawing_1.png" style="height: 70px;">
</div>
<?php }*/ ?>


<?php require 'footer.php'; ?>
