<?php require_once 'utils/RestrictLogin.php'; $restrict_login = new RestrictLogin(); $restrict_login->handle(); ?>

<?php 
        require_once 'utils/PostHandler.php';
        // Setup PostHandler
        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_customer/');
        $handler->set_form_action_delete('/customers');
        $handler->set_form_action_update('/customers/');
        $handler->set_form_action_create('/customers/');
        $handler->set_id($customer_id);
        $handler->set_id_name('customer_id');
        $handler->set_params([
          ['first_name'=>'string'],
          ['last_name'=>'string'],
          ['phone_mobile'=>'string'],
          ['city'=>'string'],
          ['address'=>'string'],
          ['country'=>'string'],
          ['postal_code'=>'string'],
          ['phone_home'=>'string'],
          ['send_sms'=>'integer'],
          ['birthday'=>'string'],
          ['send_email'=>'integer'],
          ['email1'=>'string'],
          ['email2'=>'string'],
          ['card'=>'integer'],
          ['registration_store_id'=>'integer'],
          ['registration_date'=>'string'],
          ['registration_time'=>'string'],
          ['card_level_id'=>'integer'],
          ['is_professional'=>'integer'],
          ['is_individual'=>'integer'],
          ['buys_gas'=>'integer'],
          ['buys_heating_oil'=>'integer'],
          ['buys_pellet'=>'integer'],
          ['is_enabled'=>'integer']
        ]);
        $handler->handle();
?>


<?php require_once 'header.php'; ?>
<?php // TODO: Add page for sms marketing subscription ?>

<div class="row">
  <div class="col-md-6">
    <div class="card">

<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';

      if($customer_id>0){

        // Get Current Customer
        $api = new ApiClient();
        $api->get_row('/zazu_customer/', $customer_id);

        if ($api->get_response_code()!=200){
          redirect('/customers');
        }

        // If there is no card, create it
        if ($api->get_resource()->card<=0) {

          // Find an unused Card Number
          $new_card = -1;
          do{
            $new_card = rand(110002, 999898);
            $api_find_card = new ApiClient();
            $api_find_card->get_filter('/zazu_customer/', '(card='.$new_card.')&limit=1');
          }
          while( $api_find_card->get_results_count() != 0 );

          // Update new card
          $api_create_card = new ApiClient();
          $api_create_card->patch('/zazu_customer/', ['resource'=>['card'=>$new_card,], 'ids'=>[$customer_id]]);


          $api = new ApiClient();
          $api->get_row('/zazu_customer/', $customer_id);

        }


        // Edit Form

        // Show Side Menu
        $show_side_menu = 1;

        $card_code = $api->get_resource()->card;
        $card_credit = $api->get_resource()->credit;
        $card_level_id = $api->get_resource()->card_level_id;
        $api_card_level = new ApiClient();
        $api_card_level->get_row('/zazu_card_level/', $card_level_id);
        $card_level = $api_card_level->get_resource()->title;

        // Create Form
        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        // Create Store List / TODO: Remove not selected stores, bc the selection is disabled
        $api_selected_store = new ApiClient();
        $api_selected_store->get_filter('/zazu_store/', '(store_id='.$api->get_resource()->registration_store_id.')&limit=1');
        $store_list = [];
        foreach($api_selected_store->get_resource() as $row_key=>$store_resource){
          $registration_store = $store_resource->name;
        }

        // Create Sms and Email send options
        $send_sms_options = [];
        array_push($send_sms_options, ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->send_sms]);
        array_push($send_sms_options, ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->send_sms]);
        $send_email_options = [];
        array_push($send_email_options, ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->send_email]);
        array_push($send_email_options, ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->send_email]);

        // Show Card Level
        $api_card_level = new ApiCLient();
        $api_card_level->get_row('/zazu_card_level/', $api->get_resource()->card_level_id);
        $card_level = $api_card_level->get_resource()->title;

        // Set Form
        $form->set_inputs([
          ['title'=>tr('First Name'), 'required'=>true, 'type'=>'text', 'name'=>'first_name', 'value'=>$api->get_resource()->first_name, 'placeholder'=>tr('First name')],
          ['title'=>tr('Last Name'), 'required'=>true, 'type'=>'text', 'name'=>'last_name', 'value'=>$api->get_resource()->last_name, 'placeholder'=>tr('Last name')],
          ['title'=>tr('Mobile Phone'), 'required'=>false, 'type'=>'text', 'name'=>'phone_mobile', 'value'=>$api->get_resource()->phone_mobile, 'placeholder'=>tr('Mobile Phone')],
          ['title'=>tr('Phone'), 'required'=>false, 'type'=>'text', 'name'=>'phone_home', 'value'=>$api->get_resource()->phone_home, 'placeholder'=>tr('Phone')],
          ['title'=>tr('Primary Email'), 'required'=>false, 'type'=>'text', 'name'=>'email1', 'value'=>$api->get_resource()->email1, 'placeholder'=>tr('Primary Email')],
          ['title'=>tr('Secondary Email'), 'required'=>false, 'type'=>'text', 'name'=>'email2', 'value'=>$api->get_resource()->email2, 'placeholder'=>tr('Secondary Email')],
          ['title'=>tr('Send SMS Marketing'), 'required'=>false, 'type'=>'select', 'name'=>'send_sms', 'options'=>$send_sms_options],
          ['title'=>tr('Send Email Marketing'), 'required'=>false, 'type'=>'select', 'name'=>'send_email', 'options'=>$send_email_options],
          ['title'=>tr('Birthday'), 'required'=>false, 'type'=>'date', 'name'=>'birthday', 'value'=>$api->get_resource()->birthday, 'placeholder'=>''],
          ['title'=>tr('Address'), 'required'=>false, 'type'=>'text', 'name'=>'address', 'value'=>$api->get_resource()->address, 'placeholder'=>tr('Address')],
          ['title'=>tr('City'), 'required'=>false, 'type'=>'text', 'name'=>'city', 'value'=>$api->get_resource()->city, 'placeholder'=>tr('City')],
          ['title'=>tr('Postal Code'), 'required'=>false, 'type'=>'text', 'name'=>'postal_code', 'value'=>$api->get_resource()->postal_code, 'placeholder'=>tr('Phone mobile')],
          ['title'=>tr('Country'), 'required'=>false, 'type'=>'text', 'name'=>'country', 'value'=>$api->get_resource()->country, 'placeholder'=>tr('Phone mobile')],
          ['title'=>tr('Registration Store'), 'required'=>false, 'type'=>'text', 'name'=>'registration_store_id', 'value'=>$registration_store, 'disabled'=>true, 'placeholder'=>''],
          ['title'=>tr('Registration'), 'required'=>false, 'type'=>'text', 'name'=>'registration_date_time', 'value'=>($api->get_resource()->registration_date.' '.$api->get_resource()->registration_time), 'disabled'=>true, 'placeholder'=>''],
          ['title'=>tr('Card Level'), 'required'=>false, 'type'=>'text', 'name'=>'card_level_id', 'value'=>$card_level, 'placeholder'=>tr('Card Level'), 'disabled'=>true],
          ['title'=>tr('Card'), 'required'=>true, 'type'=>'text', 'name'=>'card', 'value'=>$api->get_resource()->card, 'placeholder'=>tr('Card')],
          ['title'=>tr('Enabled'), 'required'=>true, 'type'=>'select', 'name'=>'is_enabled', 'options'=>[['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->is_enabled],['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->is_enabled]]],
          ['title'=>tr('Individual'), 'required'=>false, 'type'=>'select', 'name'=>'is_individual', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>($api->get_resource()->is_individual)], ['title'=>tr('No'), 'value'=>0, 'selected'=>!($api->get_resource()->is_individual)] ]],
          ['title'=>tr('Professional Customer'), 'required'=>false, 'type'=>'select', 'name'=>'is_professional', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>($api->get_resource()->is_professional)], ['title'=>tr('No'), 'value'=>0, 'selected'=>!($api->get_resource()->is_professional)] ]],
          ['title'=>tr('Interest in Heating Oil'), 'required'=>false, 'type'=>'select', 'name'=>'buys_heating_oil', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>($api->get_resource()->buys_heating_oil)], ['title'=>tr('No'), 'value'=>0, 'selected'=>!($api->get_resource()->buys_heating_oil)] ]],
          ['title'=>tr('Interest in Gas'), 'required'=>false, 'type'=>'select', 'name'=>'buys_gas', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>($api->get_resource()->buys_gas)], ['title'=>tr('No'), 'value'=>0, 'selected'=>!($api->get_resource()->buys_gas)] ]],
          ['title'=>tr('Interest in Pellet'), 'required'=>false, 'type'=>'select', 'name'=>'buys_pellet', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>($api->get_resource()->buys_pellet)], ['title'=>tr('No'), 'value'=>0, 'selected'=>!($api->get_resource()->buys_pellet)] ]],

        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update']
        ]);
        $form->show_form();

        

      } else {

        // Create Form

        // Hide Side Menu
        $show_side_menu = 0;

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $api_store = new ApiClient();
        $api_store->get_filter('/zazu_store/', '(is_enabled=1)and(is_deleted=0)');
        $store_list = [];
        if($api_store->get_results_count()>0) {
          foreach($api_store->get_resource() as $store_resource){
            array_push($store_list, ['title'=>$store_resource->name, 'value'=>$store_resource->store_id, 'selected'=>false]);
          }
        }

        // Find first card Level
        $api_first_card_level = new ApiClient();
        $api_first_card_level->set_id_name('card_level_id');
        $api_first_card_level->get_filter('/zazu_card_level/', '(is_enabled=1)&sort=position&limit=1');
        foreach($api_first_card_level->get_resource() as $row_key=>$row){
          $card_level_id = $row->card_level_id;
        }

        $form->set_inputs([
          ['title'=>tr('First Name'), 'required'=>true, 'type'=>'text', 'name'=>'first_name', 'value'=>'', 'placeholder'=>tr('First Name')],
          ['title'=>tr('Last Name'), 'required'=>true, 'type'=>'text', 'name'=>'last_name', 'value'=>'', 'placeholder'=>tr('Last Name')],
          ['title'=>tr('Mobile Phone'), 'required'=>false, 'type'=>'text', 'name'=>'phone_mobile', 'value'=>'', 'placeholder'=>tr('Mobile Phone')],
          ['title'=>tr('Phone'), 'required'=>false, 'type'=>'text', 'name'=>'phone_home', 'value'=>'', 'placeholder'=>tr('Phone')],
          ['title'=>tr('Primary Email'), 'required'=>false, 'type'=>'email', 'name'=>'email1', 'value'=>'', 'placeholder'=>tr('Primary Email')],
          ['title'=>tr('Secondary Email'), 'required'=>false, 'type'=>'email', 'name'=>'email2', 'value'=>'', 'placeholder'=>tr('Secondary Email')],
          ['title'=>tr('Send SMS Marketing'), 'required'=>false, 'type'=>'select', 'name'=>'send_sms', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>true], ['title'=>tr('No'), 'value'=>0, 'selected'=>false] ]],
          ['title'=>tr('Send Email Marketing'), 'required'=>false, 'type'=>'select', 'name'=>'send_email', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>true], ['title'=>tr('No'), 'value'=>0, 'selected'=>false] ]],
          ['title'=>tr('Birthday'), 'required'=>false, 'type'=>'date', 'name'=>'birthday', 'value'=>'', 'placeholder'=>''],

          ['title'=>tr('Address'), 'required'=>false, 'type'=>'text', 'name'=>'address', 'value'=>'', 'placeholder'=>tr('Address')],
          ['title'=>tr('City'), 'required'=>false, 'type'=>'text', 'name'=>'city', 'value'=>'', 'placeholder'=>tr('City')],
          ['title'=>tr('Postal Code'), 'required'=>false, 'type'=>'text', 'name'=>'postal_code', 'value'=>'', 'placeholder'=>tr('Postal Code')],
          ['title'=>tr('Country'), 'required'=>false, 'type'=>'text', 'name'=>'country', 'value'=>'Ελλάδα', 'placeholder'=>tr('Country')],
          ['title'=>tr('Registration Store'), 'required'=>true, 'type'=>'select', 'name'=>'registration_store_id', 'options'=>$store_list ],
          ['title'=>'', 'required'=>true, 'type'=>'hidden', 'name'=>'card_level_id', 'value'=>$card_level_id, 'placeholder'=>'', 'disabled'=>true],
          ['title'=>'', 'required'=>true, 'type'=>'hidden', 'name'=>'registration_date', 'value'=>date("Y-m-d"), 'placeholder'=>''],
          ['title'=>'', 'required'=>true, 'type'=>'hidden', 'name'=>'registration_time', 'value'=>date("h:i"), 'placeholder'=>tr('Registration time')],
          ['title'=>tr('Individual'), 'required'=>false, 'type'=>'select', 'name'=>'is_individual', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>true], ['title'=>tr('No'), 'value'=>0, 'selected'=>false] ]],
          ['title'=>tr('Professional Customer'), 'required'=>false, 'type'=>'select', 'name'=>'is_professional', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>false], ['title'=>tr('No'), 'value'=>0, 'selected'=>true] ]],
          ['title'=>tr('Interest in Heating Oil'), 'required'=>false, 'type'=>'select', 'name'=>'buys_heating_oil', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>false], ['title'=>tr('No'), 'value'=>0, 'selected'=>true] ]],
          ['title'=>tr('Interest in Gas'), 'required'=>false, 'type'=>'select', 'name'=>'buys_gas', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>false], ['title'=>tr('No'), 'value'=>0, 'selected'=>true] ]],
          ['title'=>tr('Interest in Pellet'), 'required'=>false, 'type'=>'select', 'name'=>'buys_pellet', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>false], ['title'=>tr('No'), 'value'=>0, 'selected'=>true] ]],

        ]);

        $form->set_buttons([
          ['title'=>tr('Create'), 'enabled'=>true, 'value'=>'create']
        ]);
        $form->show_form();


      }

?>

    </div>
  </div>
  <div class="col-md-6">

        <div class="col-md-12">
          <div class="card">
            <!-- NOTES CONTENT -->
            <h3><?php t('Customer'); ?></h3>
          </div>
        </div>

        <?php if($show_side_menu) { ?>
          <div class="col-md-12">
            <div class="card">
              <!-- NOTES CONTENT -->
              <h3><?php t('Card'); ?></h3>
              <h5><?php echo $api->get_resource()->card; ?></h5>
              <h5><?php t('Credits'); ?>: <?php echo $card_credit; ?></h5>
              <h5><?php t('Level'); ?>: <?php echo $card_level; ?></h5>
              <h5><?php t('Credits History'); ?>: <?php echo $api->get_resource()->previous_credit; ?></h5>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <!-- NOTES CONTENT -->
              <h3><?php t('Transactions'); ?></h3>
              <a href="/customers/<?php echo $customer_id; ?>/transactions"><button class="btn btn-info"><?php t('View Transactions'); ?></button></a>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <!-- NOTES CONTENT -->
              <h3><?php t('Products'); ?></h3>
              <a href="/customers/<?php echo $customer_id; ?>/products"><button class="btn btn-info"><?php t('View Products'); ?></button></a>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <!-- NOTES CONTENT -->
              <h3><?php t('Credits'); ?></h3>
              <a href="/customers/<?php echo $customer_id; ?>/topup"><button class="btn btn-info"><?php t('Add/Remove Credits'); ?></button></a>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <!-- NOTES CONTENT -->
              <h3><?php t('Gifts'); ?></h3>
                <a href="/customers/<?php echo $customer_id; ?>/available_gifts"><button class="btn btn-info"><?php t('View Available Gifts'); ?></button></a>
              <br>
                <a href="/customers/<?php echo $customer_id; ?>/redemption"><button class="btn btn-info"><?php t('Redeem Gift'); ?></button></a>
            </div>
          </div>
        <?php } ?>


  </div>

  </div>
</div>


<?php require_once 'footer.php'; ?>
