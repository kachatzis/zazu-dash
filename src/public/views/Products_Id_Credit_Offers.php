<?php require_once 'header.php'; ?>

<?php 

  require_once 'utils/ApiClient.php';
  $api = new ApiClient();
  $api->get_filter('/zazu_credit_offer/', '(good_id='.$good_id.')');

?>

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="col-12">
          <?php if ($api->get_results_count()<=0){ ?>
          <a href="/products/<?php echo $good_id; ?>/credit_offers/0"><button type="submit" class="btn btn-success"><?php t('Create');?></button></a>
          <?php } ?>
          <a href="/products/<?php echo $good_id; ?>"><button class="btn btn-info"><?php t('Back');?></button></a>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
      <?php

        require_once 'utils/Table.php';

        $api_good = new ApiClient();
        $api_good->get_row('/zazu_good/', $good_id);
        
        if($api->get_response_code()!=200){ ?>
          <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
        <?php } else {
          if (!$api->get_results_count()>0) { ?>
            <h2>No results found! (<?php echo $api->get_response_code(); ?>)</h2>
          <?php } else {
            $table = new Table();
            $table->set_table_name(tr('Credits'));
            $table->set_action_uri('/products/'.$good_id.'/credit_offers/');
            $table->set_action_column('credit_offer_id');
            $table->set_body_array($api->get_resource());
            $table->set_columns(1);
            $table->set_header_array([1=>'credits_per_unit']);
            $table->set_header_titles([1=>'Credits / '.$api_good->get_resource()->unit]);
            $table->set_tr_action(true);
            echo $table->make_table();
          }
        }

      ?>
    </div>
  </div>




</div>

<?php require_once 'footer.php'; ?>
