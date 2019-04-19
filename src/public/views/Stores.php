<?php require_once 'header.php'; ?>

<div class="row">
    <div class="col-12">
      <div class="card">
        <a href="/stores/0"><button type="submit" class="btn btn-success"><?php t('Create');?></button></a>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
      <?php

        require_once 'utils/Table.php';
        require_once 'utils/ApiClient.php';

        $api = new ApiClient();
        $api->get('/zazu_store/');

        if($api->get_response_code()!=200){ ?>
          <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
        <?php } else {
          if (!$api->get_results_count()>0) { ?>
            <h2>No results found! (<?php echo $api->get_response_code(); ?>)</h2>
          <?php } else {
            $table = new Table();
            $table->set_table_name(tr('Stores'));
            $table->set_action_uri('/stores/');
            $table->set_action_column('store_id');
            $table->set_body_array($api->get_resource());
            $table->set_columns(3);
            $table->set_header_array([1=>'code', 2=>'name', 3=>'description']);
            $table->set_header_titles([1=>tr('Code'), 2=>tr('Name'), 3=>tr('Description')]);
            $table->set_tr_action(true);
            echo $table->make_table();
          }
        }

      ?>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>
