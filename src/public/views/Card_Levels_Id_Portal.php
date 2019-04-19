<?php require_once 'header.php'; ?>


<div class="row">
  <div class="col-md-6">
    <div class="card">

<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';


        $api = new ApiClient();
        $api->get_row('/zazu_card_level/', $card_level_id);

        // Edit Form

        // TODO: Fix the html content text area to show the string as is, without enrichment.

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $form->set_inputs([
          ['title'=>tr('Title'), 'required'=>false, 'type'=>'text', 'name'=>'portal_title', 'disabled'=>false, 'value'=>$api->get_resource()->portal_title, 'placeholder'=>tr('Title')],
          ['title'=>tr('URL'), 'required'=>false, 'type'=>'text', 'name'=>'portal_url', 'disabled'=>false, 'value'=>$api->get_resource()->portal_url, 'placeholder'=>'eg. gold'],
          ['title'=>tr('Small Description'), 'required'=>false, 'type'=>'textarea', 'rows'=>'2', 'name'=>'portal_small_description', 'disabled'=>false, 'value'=>$api->get_resource()->portal_small_description, 'placeholder'=>'  Small description displayed in multiple products view'],
          ['title'=>tr('Is Enabled in Portal'), 'required'=>true, 'type'=>'select', 'name'=>'portal_is_enabled', 'disabled'=>false, 'placeholder'=>tr('Title'), 'options'=>[
                    ['title'=>tr('Yes'), 'value'=>1, 'selected'=> ($api->get_resource()->portal_is_enabled=='1')], ['title'=>tr('No'), 'value'=>0, 'selected'=> (!$api->get_resource()->portal_is_enabled=='1')] ]],
          ['title'=>tr('HTML Content'), 'required'=>false, 'type'=>'textarea', 'rows'=>'20', 'name'=>'portal_html_content', 'disabled'=>false, 'value'=>$api->get_resource()->portal_html_content, 'placeholder'=>''],
          ['title'=>tr('Image'), 'required'=>false, 'type'=>'file', 'name'=>'portal_photo', 'disabled'=>false, 'preview'=>$api->get_resource()->portal_photo ],
        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update']
        ]);
        $form->show_form();


        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_card_level/');
        $handler->set_form_action_update_absolute('/card_levels/'.$card_level_id.'/portal');
        $handler->set_id($card_level_id);
        $handler->set_params([
            ['portal_title'=>'string'],
            ['portal_small_description'=>'string'],
            ['portal_is_enabled'=>'integer'],
            ['portal_html_content'=>'string'],
            ['portal_url'=>'string'],
            ['portal_photo'=>'photo'],
        ]);
        $handler->handle();


?>

    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <!-- NOTES CONTENT -->
      <h3>Portal Settings for <?php echo $api->get_resource()->title; ?></h3>
      <a href="/card_levels/<?php echo $card_level_id; ?>"><button class="btn btn-info"><?php t('Back');?></button></a>
    </div>
  </div>
</div>


<?php require_once 'footer.php'; ?>
