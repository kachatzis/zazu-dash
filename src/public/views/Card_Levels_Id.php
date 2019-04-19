<?php require_once 'header.php'; ?>


<div class="row">
  <div class="col-md-6">
    <div class="card">

<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';

      if($card_level_id>0){

        // Edit Form

        $api = new ApiClient();
        $api->get_row('/zazu_card_level/', $card_level_id);

        if ($api->get_response_code()!=200){
          redirect('/card_levels');
        }

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $api_card_level_is_enabled = new ApiClient();
        $api_card_level_is_enabled->get_row('/zazu_card_level/', $card_level_id);

        $card_level_is_enabled_options = [];
        array_push($card_level_is_enabled_options, ['title'=>tr('Yes'), 'value'=>1, 'selected'=>(  $api_card_level_is_enabled->get_resource()->is_enabled=='1'  )]);
        array_push($card_level_is_enabled_options, ['title'=>tr('No'), 'value'=>0, 'selected'=>(  $api_card_level_is_enabled->get_resource()->is_enabled=='0'  )]);


        $form->set_inputs([
          ['title'=>tr('Level'), 'required'=>true, 'type'=>'text', 'name'=>'title', 'value'=>$api->get_resource()->title, 'placeholder'=>tr('Level')],
          ['title'=>tr('Position'), 'required'=>true, 'type'=>'text', 'name'=>'position', 'value'=>$api->get_resource()->position, 'placeholder'=>tr('Position')],
          ['title'=>tr('Enabled'), 'required'=>false, 'type'=>'select', 'name'=>'is_enabled', 'options'=>$card_level_is_enabled_options ],
        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_card_level/');
        $handler->set_form_action_delete('/card_levels');
        $handler->set_form_action_update('/card_levels/');
        $handler->set_id($card_level_id);
        $handler->set_id_name('card_level_id');
        $handler->set_params([
          ['title'=>'string'],
          ['position'=>'integer'],
          ['is_enabled'=>'integer']
        ]);
        $handler->handle();

      } else {

        // Create Form

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $api = new ApiClient();
        $api->get('/zazu_card_level/');
        $max_card_level_position = 1;
        foreach($api->get_resource() as $row_key=>$card_level){
          if( $card_level->position >= $max_card_level_position ) $max_card_level_position = $card_level->position + 10;
        }


        $form->set_inputs([
          ['title'=>tr('Level'), 'required'=>true, 'type'=>'text', 'name'=>'title', 'value'=>'', 'placeholder'=>tr('Level')],
          ['title'=>tr('Position'), 'required'=>true, 'type'=>'text', 'name'=>'position', 'value'=>$max_card_level_position, 'placeholder'=>tr('Position')],
          ['title'=>tr('Enabled'), 'required'=>false, 'type'=>'select', 'name'=>'is_enabled', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>true], ['title'=>tr('No'), 'value'=>0, 'selected'=>false]
                            ]],
        ]);

        $form->set_buttons([
          ['title'=>tr('Create'), 'enabled'=>true, 'value'=>'create']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_card_level/');
        $handler->set_form_action_create('/card_levels/');
        $handler->set_id($card_level_id);
        $handler->set_id_name('card_level_id');
        $handler->set_params([
          ['title'=>'string'],
          ['position'=>'integer'],
          ['is_enabled'=>'integer'],
        ]);
        $handler->handle();

      }

?>

    </div>
  </div>
  <div class="col-md-6">
    <div class="col-md-12">
      <div class="card">
        <!-- NOTES CONTENT -->
        <h3><?php t('Card Level'); ?></h3>
      </div>
    </div>


    <?php if($card_level_id>0){ ?>
      <div class="col-md-12">
        <div class="card">
          <!-- Portal Settings -->
          <h3><?php t('Portal'); ?></h3>
          <a href="/card_levels/<?php echo $card_level_id; ?>/portal"><button class="btn btn-info"><?php t('View Settings'); ?></button></a>
        </div>
      </div>
    <?php } ?>
  </div>


</div>


<?php require_once 'footer.php'; ?>
