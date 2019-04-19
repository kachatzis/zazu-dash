<?php require_once 'utils/RestrictLogin.php'; $restrict_login = new RestrictLogin(); $restrict_login->handle(); ?>

<?php 
      if($_SERVER['REQUEST_METHOD']=='POST'){
        require_once 'utils/PostHandler.php';

        if($_POST['form']=='cancel_transaction'){
          // Cancel Transaction
          $_POST['is_canceled'] = 1;
          $_POST['form-send'] = 'update';

        }

        if($_POST['form']=='restore_transaction'){
          // Restore Transaction
          $_POST['is_canceled'] = 0;
          $_POST['form-send'] = 'update';

        }



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_transaction/');
        $handler->set_form_action_update('/transactions/');
        $handler->set_id($transaction_id);
        $handler->set_id_name('transaction_id');
        $handler->set_params([
          ['is_canceled'=>'integer'],
        ]);
        $handler->handle();
      }
?>






<?php require_once 'header.php'; ?>




            

        <?php

      require 'utils/Form.php';
      require 'utils/Table.php';

      if(!$transaction_id>0) redirect('/transactions');

        // Edit Form

        $api = new ApiClient();
        $api->get_row('/zazu_transaction/', $transaction_id);

        if ($api->get_response_code()!=200){
          redirect('/transactions');
        }

        if($api->get_resource()->is_imported){ ?>
          <div class="col-md-12">
            <div class="row">
              <div class="alert alert-danger">
                <?php t('This is an imported Transaction. No changes are allowed!'); ?>
              </div>
            </div>
          </div>
        <?php }


        $api_customer = new ApiClient();
        $api_customer->get_row('/zazu_customer/', $api->get_resource()->customer_id);

        $api_transaction_goods = new ApiClient();
        $api_transaction_goods->get_filter('/zazu_transaction_good/', '(transaction_id='.$transaction_id.')');

        class BodyRow{
          public $good_title;
          public $cost;
          public $is_canceled;
          public $good_id;
        }


        $good_table = [];

        foreach($api_transaction_goods->get_resource() as $row=>$transaction_good){

          $api_good = new ApiClient();
          $api_good->get_row('/zazu_good/', $transaction_good->good_id);

          $body_row = new BodyRow();
          $body_row->good_title   = $api_good->get_resource()->title;
          $body_row->cost         = $transaction_good->cost;
          $body_row->is_canceled  = $transaction_good->is_canceled;
          $body_row->good_id      = $api_good->get_resource()->good_id;

          $good_table[$api_good->get_resource()->good_id] = $body_row;

        }


        $api_manager = new ApiClient();
        $api_manager->get_row('/zazu_manager/', $api->get_resource()->manager_id);
        $manager_name = $api_manager->get_resource()->name;

        $api_store = new ApiClient();
        $api_store->get_row('/zazu_store/', $api->get_resource()->store_id);
        $store_name = $api_store->get_resource()->name . '('.$api_store->get_resource()->code.')';

        $api_device = new ApiClient();
        $api_device->get_row('/zazu_device/', $api->get_resource()->device_id);
        $device_name = $api_device->get_resource()->code;

        $api_customer = new ApiClient();
        $api_customer->get_row('/zazu_customer/', $api->get_resource()->customer_id);
        $customer_name = $api_customer->get_resource()->first_name.' '.$api_customer->get_resource()->last_name. ' ('.$api_customer->get_resource()->card.')';


        ?>

        <div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="row">

        <?php




        ?><div class="col-md-6"><?php


        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        

        $form->set_inputs([
          ['title'=>tr('Transaction'), 'type'=>'text', 'name'=>'0', 'disabled'=>true,'value'=>$api->get_resource()->transaction_id],
          ['title'=>tr('Credits'), 'type'=>'text', 'name'=>'0', 'disabled'=>true,'value'=>$api->get_resource()->credit],
          ['title'=>tr('Transaction Date'), 'type'=>'text', 'name'=>'0', 'disabled'=>true,'value'=>$api->get_resource()->transaction_date.' '.$api->get_resource()->transaction_time],
          ['title'=>tr('Request Date'), 'type'=>'text', 'name'=>'0', 'disabled'=>true,'value'=>$api->get_resource()->request_date.' '.$api->get_resource()->transaction_time],
          ['title'=>tr('Credits before Transaction'), 'type'=>'text', 'name'=>'0', 'disabled'=>true,'value'=>$api->get_resource()->previous_card_credit],
          ['title'=>tr('Credits after Transaction'), 'type'=>'text', 'name'=>'0', 'disabled'=>true,'value'=>$api->get_resource()->current_card_credit],
          ['title'=>tr('Transaction cost'), 'type'=>'text', 'name'=>'0', 'disabled'=>true,'value'=>$api->get_resource()->money_spent.' €'],

          ['title'=>tr('Employee'), 'type'=>'text', 'name'=>'0', 'disabled'=>true,'value'=>$manager_name],
          ['title'=>tr('Store'), 'type'=>'text', 'name'=>'0', 'disabled'=>true,'value'=>$store_name],
          ['title'=>tr('Device'), 'type'=>'text', 'name'=>'0', 'disabled'=>true,'value'=>$device_name],
          ['title'=>tr('Customer'), 'type'=>'text', 'name'=>'0', 'disabled'=>true,'value'=>$customer_name],


          ['title'=>tr('Rolled Back'), 'type'=>'select', 'name'=>'0', 'disabled'=>true,'options'=>[
              ['title'=>tr('Yes'), 'value'=>'1', 'selected'=> ($api->get_resource()->is_rolled_back) ], ['title'=>tr('No'), 'value'=>'0', 'selected'=> (!$api->get_resource()->is_rolled_back)]
          ]],
          ['title'=>tr('Canceled'), 'type'=>'select', 'name'=>'0', 'disabled'=>true,'options'=>[
              ['title'=>tr('Yes'), 'value'=>'1', 'selected'=> ($api->get_resource()->is_canceled) ], ['title'=>tr('No'), 'value'=>'0', 'selected'=> (!$api->get_resource()->is_canceled)]
          ]],
          ['title'=>tr('Processed'), 'type'=>'select', 'name'=>'0', 'disabled'=>true,'options'=>[
              ['title'=>tr('Yes'), 'value'=>'1', 'selected'=> ($api->get_resource()->is_processed) ], ['title'=>tr('No'), 'value'=>'0', 'selected'=> (!$api->get_resource()->is_processed)]
          ]],
          

        ]);

        

        $form->show_form();

        ?></div><?php




        ?><div class="col-md-6"><?php
        
        if($api->get_response_code()!=200){ ?>
          <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
        <?php } else {
          if (!$api->get_results_count()>0) { ?>
            <h2>No results found! (<?php echo $api->get_response_code(); ?>)</h2>
          <?php } else {
            $table = new Table();
            $table->set_table_name(tr('Products'));
            $table->set_action_uri('/products/');
            $table->set_action_column('good_id');
            $table->set_body_array($good_table);
            $table->set_columns(3);
            //$table->set_custom_columns([4=>['header'=>'is_processed', 'title'=>tr('Processed'), 'type'=>'double-selection', 'on-1'=>'Yes', 'on-0'=>'No', 'resource_field'=>'name']]);
            $table->set_header_array([1=>'good_title', 2=>'cost', 3=>'is_canceled']);
            $table->set_header_titles([1=>tr('Product'), 2=>tr('Cost'), 3=>tr('Canceled')]);
            $table->set_tr_action(true);


            $table->make_table();

            

          }
        }
        ?>

        <br><br><br>

            <!-- Credit Offers -->
            <div class="row">
              <div class="col-12">
                <h3><?php t('Transaction'); ?></h3>
                
                  <?php if($api->get_resource()->is_imported){ ?>
                      <p ><?php t('You cannot change Imported Transactions.'); ?></p>
                  <?php } else if(!$api->get_resource()->is_canceled){ ?>
                    <form  action="" method="POST"><input type="hidden" name="form" value="cancel_transaction">
                      <button type="submit" class="btn btn-warning"><?php t('Cancel Transaction'); ?></button>
                    </form>
                  <?php } else if($api->get_resource()->is_canceled){ ?>
                    <form action="" method="POST"><input type="hidden" name="form" value="restore_transaction_">
                      <button type="submit" class="btn btn-warning disabled"><?php t('Restore Transaction'); ?>(Not Available)</button>
                    </form>
                  <?php } ?>


                  <a href="/customers/<?php echo $api->get_resource()->customer_id; ?>"><button class="btn btn-info"><?php t('Customer'); ?></button></a>
                  <a href="/managers/<?php echo $api->get_resource()->manager_id; ?>"><button class="btn btn-info"><?php t('Employee'); ?></button></a>
            </div>
          </div>


        <?php



        ?></div><?php



      

?>
      </div>
    </div>
  </div>


  
</div>


<?php require_once 'footer.php'; ?>
