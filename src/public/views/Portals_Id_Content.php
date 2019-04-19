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
          ['title'=>tr('Header Title'), 'required'=>true, 'type'=>'text', 'name'=>'header_title', 'value'=>$api->get_resource()->header_title,  'placeholder'=>tr('Title of the pages'), 'button_remove_content'=>true],
          ['title'=>tr('Navigation Title'), 'required'=>false, 'type'=>'text', 'name'=>'nav_title', 'value'=>$api->get_resource()->nav_title,  'placeholder'=>tr('Title on the navigation'), 'button_remove_content'=>true],
          ['title'=>tr('Footer'), 'required'=>false, 'type'=>'text', 'name'=>'footer', 'value'=>$api->get_resource()->footer, 'placeholder'=>tr('Footer'), 'button_remove_content'=>true],
          ['title'=>tr('Contact Phone'), 'required'=>false, 'type'=>'text', 'name'=>'contact_phone1', 'value'=>$api->get_resource()->contact_phone1,  'placeholder'=>tr('Phone'), 'button_remove_content'=>true],
          ['title'=>tr('Contact Phone'), 'required'=>false, 'type'=>'text', 'name'=>'contact_phone2', 'value'=>$api->get_resource()->contact_phone2, 'placeholder'=>tr('Phone'), 'button_remove_content'=>true],
          ['title'=>tr('Contact Email'), 'required'=>false, 'type'=>'text', 'name'=>'contact_email1', 'value'=>$api->get_resource()->contact_email1, 'placeholder'=>tr('Email'), 'button_remove_content'=>true],
          ['title'=>tr('Contact Email'), 'required'=>false, 'type'=>'text', 'name'=>'contact_email2', 'value'=>$api->get_resource()->contact_email2, 'placeholder'=>tr('Email'), 'button_remove_content'=>true],
          ['title'=>tr('Contact Address'), 'required'=>false, 'type'=>'text', 'name'=>'contact_address', 'value'=>$api->get_resource()->contact_address, 'placeholder'=>tr('Address'), 'button_remove_content'=>true],

          ['title'=>'Slider HTML Content (Leave empty to hide the slider)', 'required'=>false, 'type'=>'textarea', 'rows'=>'20', 'name'=>'slider_html_content', 'disabled'=>false, 'value'=>$api->get_resource()->slider_html_content, 'placeholder'=>''],
          ['title'=>'Deal HTML Content (Leave empty to hide the deal)', 'required'=>false, 'type'=>'textarea', 'rows'=>'20', 'name'=>'deal_html_content', 'disabled'=>false, 'value'=>$api->get_resource()->deal_html_content, 'placeholder'=>''],


        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update']
        ]);
        $form->show_form();


        // Setup PostHandler
        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_portal/');
        $handler->set_form_action_update_absolute('/portals/'.$portal_id.'/content');
        $handler->set_id($portal_id);
        $handler->set_id_name('portal_id');
        $handler->set_params([
          ['header_title'=>'string'],
          ['nav_title'=>'string'],
          ['footer'=>'string'],
          ['contact_phone1'=>'string'],
          ['contact_phone2'=>'string'],
          ['contact_email1'=>'string'],
          ['contact_email2'=>'string'],
          ['contact_address'=>'string'],
          ['slider_html_content'=>'string'],
          ['deal_html_content'=>'string'],
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
              <h3>Content<h3>
              <a href="/portals/<?php echo $portal_id; ?>"><button type="submit" class="btn btn-info"><?php t('Back');?></button></a>
            </div>
          </div>
        <?php } ?>


  </div>

  </div>
</div>


<?php require_once 'footer.php'; ?>
