<?php require_once 'header.php'; ?>
<?php // TODO: Add page for sms marketing subscription ?>

<?php /* Disable page */ redirect('/'); ?>

<div class="row">
  <div class="col-md-6">
    <div class="card">

<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';

      if($portal_id>0){
        // Edit Form

        // Get Current Customer
        $api = new ApiClient();
        $api->get_row('/zazu_portal/', $portal_id);

        if($api->get_response_code()!=200) { echo redirect('/portals');}

        // Create Form
        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');


        // Set Form
        $form->set_inputs([
          ['title'=>tr('Login page'), 'required'=>true, 'type'=>'select', 'name'=>'show_login_form', 'options'=>[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->show_login_form], ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->show_login_form]]],
          /*['title'=>tr('SignUp page'), 'required'=>true, 'type'=>'select', 'name'=>'show_signup_form', 'options'=>[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->show_signup_form], ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->show_signup_form]]],*/
          ['title'=>tr('Contact form'), 'required'=>true, 'type'=>'select', 'name'=>'show_contact_form', 'options'=>[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->show_contact_form], ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->show_contact_form]]],
          /*['title'=>tr('Customer can request card printing'), 'required'=>true, 'type'=>'select', 'name'=>'show_card_print_button', 'options'=>[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->show_card_print_button], ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->show_card_print_button]]],*/
          ['title'=>tr('Maintenance page'), 'required'=>true, 'type'=>'select', 'name'=>'show_maintenance', 'options'=>[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->show_maintenance], ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->show_maintenance]]],
          ['title'=>tr('Show gifts in landing'), 'required'=>true, 'type'=>'select', 'name'=>'show_gifts_in_landing', 'options'=>[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->show_gifts_in_landing], ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->show_gifts_in_landing]]],
          ['title'=>tr('Show levels in landing'), 'required'=>true, 'type'=>'select', 'name'=>'show_levels_in_landing', 'options'=>[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->show_levels_in_landing], ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->show_levels_in_landing]]],
          ['title'=>tr('Show deals in landing'), 'required'=>true, 'type'=>'select', 'name'=>'show_landing_deal', 'options'=>[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->show_landing_deal], ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->show_landing_deal]]],
          ['title'=>tr('Allow customers to view their transactions'), 'required'=>true, 'type'=>'select', 'name'=>'show_account_transactions', 'options'=>[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->show_account_transactions], ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->show_account_transactions]]],
          ['title'=>tr('Show slider in landing page <br>(CAUTION: This might break the landing page if the next division is not edited accordingly.)'), 'required'=>true, 'type'=>'select', 'name'=>'show_landing_slider', 'options'=>[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->show_landing_slider], ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->show_landing_slider]]],
        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update']
        ]);
        $form->show_form();


        // Setup PostHandler
        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_portal/');
        $handler->set_form_action_update_absolute('/portals/'.$portal_id.'/settings');

        $handler->set_id($portal_id);
        $handler->set_id_name('portal_id');
        $handler->set_params([
          ['show_login_form'=>'integer'],
          ['show_signup_form'=>'integer'],
          ['show_contact_form'=>'integer'],
          ['show_card_print_button'=>'integer'],
          ['show_maintenance'=>'integer'],
          ['show_gifts_in_landing'=>'integer'],
          ['show_levels_in_landing'=>'integer'],
          ['show_account_transactions'=>'integer'],
          ['show_landing_slider'=>'integer'],
          ['show_landing_deal'=>'integer'],
        ]);
        $handler->handle();


      } else {

        redirect('/portals');

      }

?>

    </div>
  </div>
  <div class="col-md-6">


        <?php if($portal_id>0) { ?>
          <div class="col-md-12">
            <div class="card">
              <!-- NOTES CONTENT -->
              <h3><?php echo tr('Portal'). ': ' . $api->get_resource()->title; ?></h3>
              <h3>Settings<h3>
              <a href="/portals/<?php echo $portal_id; ?>"><button type="submit" class="btn btn-info"><?php t('Back');?></button></a>
            </div>
          </div>
        <?php } ?>


  </div>

  </div>
</div>


<?php require_once 'footer.php'; ?>
