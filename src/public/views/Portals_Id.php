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
          ['title'=>tr('Title'), 'required'=>true, 'type'=>'text', 'name'=>'title', 'value'=>$api->get_resource()->title, 'disabled'=>true, 'placeholder'=>''],
          ['title'=>tr('Enabled'), 'required'=>true, 'type'=>'select', 'name'=>'is_enabled', 'options'=>[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->is_enabled], ['title'=>tr('No'), 'value'=>0, 'selected'=>!$api->get_resource()->is_enabled]
            ]],
        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update'],
          ['title'=>tr('Delete'), 'enabled'=>true, 'value'=>'delete'],
        ]);
        $form->show_form();


        // Setup PostHandler
        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_portal/');
        $handler->set_form_action_delete('/portals');
        $handler->set_form_action_update('/portals/');
        $handler->set_id($portal_id);
        $handler->set_id_name('portal_id');
        $handler->set_params([
          ['is_enabled'=>'string'],
        ]);
        $handler->handle();


      } else {

        // Create Form


        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');


        $form->set_inputs([
          ['title'=>tr('New Portal'), 'required'=>true, 'type'=>'text', 'name'=>'title', 'value'=>'', 'placeholder'=>tr('Title')],
          ['type'=>'hidden', 'name'=>'is_enabled', 'value'=>'0'],
        ]);

        $form->set_buttons([
          ['title'=>tr('Create'), 'enabled'=>true, 'value'=>'create']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_portal/');
        $handler->set_form_action_create('/portals/');
        $handler->set_id($portal_id);
        $handler->set_id_name('portal_id');
        $handler->set_params([
          ['title'=>tr('string')],
          ['is_enabled'=>'integer'],
        ]);
        $handler->handle();



      }

?>

    </div>
  </div>
  <div class="col-md-6">

    <?php if(!$portal_id>0) { ?>
      <div class="col-md-12">
        <div class="card">
          <!-- NOTES CONTENT -->
          <h3><?php t('Portal'); ?></h3>
        </div>
      </div>
    <?php } ?>

        <?php if($portal_id>0) { ?>
          <div class="col-md-12">
            <div class="card">
              <!-- NOTES CONTENT -->
              <h3><?php echo tr('Portal'). ': ' . $api->get_resource()->title; ?></h3>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <!-- NOTES CONTENT -->
              <h3>Settings</h3>
              <a href="/portals/<?php echo $portal_id; ?>/settings"><button class="btn btn-info">Change Portal Settings</button></a>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <!-- NOTES CONTENT -->
              <h3>Content</h3>
              <a href="/portals/<?php echo $portal_id; ?>/content"><button class="btn btn-info">Edit Content</button></a>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <!-- NOTES CONTENT -->
              <h3>Marketing</h3>
              <a href="/portals/<?php echo $portal_id; ?>/marketing"><button class="btn btn-info">Edit Analytics</button></a>
            </div>
          </div>
        <?php } ?>


  </div>

  </div>
</div>


<?php require_once 'footer.php'; ?>
