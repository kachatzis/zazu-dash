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
          ['title'=>'Analytics Tracking ID (leave empty to disable Analytics)', 'required'=>false, 'type'=>'text', 'name'=>'analytics_tracking_id', 'value'=>$api->get_resource()->analytics_tracking_id, 'placeholder'=>'eg. UA-123456789-1', 'button_remove_content'=>true],
        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update'],
        ]);
        $form->show_form();


        // Setup PostHandler
        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_portal/');
        $handler->set_form_action_update_absolute('/portals/'.$portal_id.'/marketing');
        $handler->set_id($portal_id);
        $handler->set_id_name('portal_id');
        $handler->set_params([
          ['analytics_tracking_id'=>'string'],
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
              <h3>Marketing<h3>
              <a href="/portals/<?php echo $portal_id; ?>"><button type="submit" class="btn btn-info"><?php t('Back');?></button></a>
            </div>
          </div>
        <?php } ?>


  </div>

  </div>
</div>


<?php require_once 'footer.php'; ?>
