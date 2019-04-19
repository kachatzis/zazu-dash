<?php require_once 'header.php'; ?>

      <!-- Use the next format, to add table-->

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <a href="/products/0?gift"><button type="submit" class="btn btn-success"><?php t('Create');?></button></a>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">

            <?php

              require_once 'utils/Table.php';
              require_once 'utils/ApiClient.php';

              $api = new ApiClient();
              $api->get_filter('/zazu_good/', '(is_gift=1)');

              if($api->get_response_code()!=200){ ?>
                <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
              <?php } else {
                if (!$api->get_results_count()>0) { ?>
                  <h2>No results found! (<?php echo $api->get_response_code(); ?>)</h2>
                <?php } else {
                  $table = new Table();
                  $table->set_table_name(tr('Gifts'));
                  $table->set_action_uri('/products/');
                  $table->set_action_column('good_id');
                  $table->set_body_array($api->get_resource());
                  $table->set_columns(3);
                  $table->set_custom_columns([
                      2=>['header'=>'card_level_id', 'title'=>tr('Level'), 'id_field'=>'card_level_id', 'api_uri'=>'/zazu_card_level/', 'resource_field'=>'title'],
                      3=>['header'=>'is_enabled', 'title'=>tr('Enabled'), 'type'=>'double-selection', 'on-1'=>tr('Yes'), 'on-0'=>tr('No')]
                  ]);
                  $table->set_header_array([1=>'title']);
                  $table->set_header_titles([1=>'Τίτλος']);
                  $table->set_tr_action(true);
                  echo $table->make_table();
                }
              }

            ?>

          </div>
        </div>
      </div>






<?php require_once 'footer.php'; ?>
