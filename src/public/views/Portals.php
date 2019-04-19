<?php require_once 'header.php'; ?>

<?php /* Disable page */ redirect('/'); ?>

<div class="row">
    <div class="col-12">
      <div class="card">
        <a href="/portals/0"><button type="submit" class="btn btn-success"><?php t('Create');?></button></a>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
      

        require_once 'utils/Table.php';
        require_once 'utils/ApiClient.php';

        $api = new ApiClient();
        $api->get('/zazu_portal/');

        if($api->get_response_code()!=200){ ?>
          <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
        <?php } else {
          if (!$api->get_results_count()>0) { ?>
            <h2>No results found! (<?php echo $api->get_response_code(); ?>)</h2>
          <?php } else {
            $table = new Table();
            $table->set_table_name(tr('Portals'));
            $table->set_action_uri('/portals/');
            $table->set_action_column('portal_id');
            $table->set_body_array($api->get_resource());
            $table->set_columns(2);
            $table->set_custom_columns([2=>['header'=>'is_enabled', 'title'=>tr('Enabled'), 'type'=>'double-selection', 'on-1'=>tr('Yes'), 'on-0'=>tr('No'), 'resource_field'=>'name']]);
            $table->set_header_array([1=>'title']);
            $table->set_header_titles([1=>tr('Portal')]);
            $table->set_tr_action(true);
            echo $table->make_table();
          }
        }

      ?>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>
