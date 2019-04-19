<?php require_once 'header.php'; ?>

<?php /* Disable page */ redirect('/'); ?>

      <!-- Use the next format, to add table-->

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <a href="/sms_marketings/0"><button type="submit" class="btn btn-success"><?php t('Create');?></button></a>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">

            <?php

              require_once 'utils/Table.php';
              require_once 'utils/ApiClient.php';

              $api = new ApiClient();
              $api->get('/zazu_sms_marketing/');

              if($api->get_response_code()!=200){ ?>
                <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
              <?php } else {
                if (!$api->get_results_count()>0) { ?>
                  <h2>No results found! (<?php echo $api->get_response_code(); ?>)</h2>
                <?php } else {
                  $table = new Table();
                  $table->set_table_name(t('SMS Marketing'));
                  $table->set_action_uri('/sms_marketings/');
                  $table->set_action_column('sms_marketing_id');
                  $table->set_body_array($api->get_resource());
                  $table->set_columns(1);
                  $table->set_header_array([1=>'title']);
                  $table->set_header_titles([1=>'Τίτλος']);
                  $table->set_tr_action(true);
                  $table->make_table();
                }
              }

            ?>

          </div>
        </div>
      </div>






<?php require_once 'footer.php'; ?>
