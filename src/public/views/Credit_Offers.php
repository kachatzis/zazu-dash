<?php require_once 'header.php'; ?>

<div class="row">
  <!--
    <div class="col-12">
      <div class="card">
        <a href="/credit_offers/0"><button type="submit" class="btn btn-success">Create</button></a>
      </div>
    </div>
  -->
    <div class="col-md-12">
      <div class="card">
      <?php

        require_once 'utils/Table.php';
        require_once 'utils/ApiClient.php';

        $api = new ApiClient();
        $api->get('/zazu_credit_offer/');

        if($api->get_response_code()!=200){ ?>
          <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
        <?php } else {
          if (!$api->get_results_count()>0) { ?>
            <h2>No results found! (<?php echo $api->get_response_code(); ?>)</h2>
          <?php } else {
            $table = new Table();
            $table->set_table_name(t('Credits'));
            $table->set_action_uri('/credit_offers/');
            $table->set_action_column('credit_offer_id');
            $table->set_body_array($api->get_resource());
            $table->set_columns(1);
            $table->set_header_array([1=>'credits_per_unit']);
            $table->set_header_titles([1=>'Credits per Unit']);
            $table->set_tr_action(true);
            $table->make_table();
          }
        }

      ?>
    </div>
  </div>
  <div class="col-12">
    <div class="card">
      <p><?php t('Help: To create a new Credits listing, click on the \'View Credits\' button, in the page of the product you want the offer to be created for.'); ?></p>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>
