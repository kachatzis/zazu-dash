<?php require_once 'header.php'; ?>

<div class="row">
    <div class="col-12">
      <div class="card">
        <a href="/card_levels/0"><button type="submit" class="btn btn-success"><?php t('Create');?></button></a>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
      <?php

        require_once 'utils/Table.php';
        require_once 'utils/ApiClient.php';

        $api = new ApiClient();
        $api->get_filter('/zazu_card_level/', '&sort=position');

        if($api->get_response_code()!=200){ ?>
          <h2><?php t('Error! No data received');?> (<?php echo $api->get_response_code(); ?>)</h2>
        <?php } else {
          if (!$api->get_results_count()>0) { ?>
            <h2><?php t('No results found!');?> (<?php echo $api->get_response_code(); ?>)</h2>
          <?php } else {
            $table = new Table();
            $table->set_table_name(tr('Card Levels'));
            $table->set_action_uri('/card_levels/');
            $table->set_action_column('card_level_id');
            $table->set_body_array($api->get_resource());
            $table->set_columns(3);
            $table->set_custom_columns([
                      3=>['header'=>'is_enabled', 'title'=>tr('Enabled'), 'type'=>'double-selection', 'on-1'=>tr('Yes'), 'on-0'=>tr('No')]
                  ]);
            $table->set_header_array([1=>'position', 2=>'title']);
            $table->set_header_titles([1=>tr('Position'), 2=>tr('Level')]);
            $table->set_tr_action(true);
            echo $table->make_table();
          }
        }

      ?>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>
