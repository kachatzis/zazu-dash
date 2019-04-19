<?php require_once 'header.php'; ?>
<?php /* TODO: Fix This page. Maybe no custom form is needed, using a form-beform-the-form asking
      ** for credit or money type of achievement, and making the type not editable */ ?>

<div class="row">
  <div class="col-md-6">
    <div class="card">
<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';

      if($achievement_id>0){
        /* Edit */

        $api = new ApiClient();
        $api->get_row('/zazu_achievement/', $achievement_id);

        if ($api->get_response_code()!=200){
          redirect('/achievements');
        }

        foreach($api->get_resource() as $resource){
          $api_resource = $resource;
        }

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');


        /*$max_achievement_position = 1;
        foreach($api->get_resource() as $row_key=>$achievement){
          if( $achievement->position >= $max_achievement_position ) $max_achievement_position = $achievement->position + 10;
        }*/

        $api_card_levels = new ApiClient();
        $api_card_levels->get_filter('/zazu_card_level/', '(is_enabled=1)');
        $card_level_options = [];
        foreach($api_card_levels->get_resource() as $key=>$card_level){
          
          array_push($card_level_options, ['title'=>$card_level->title, 'value'=>$card_level->card_level_id, 'selected'=> ($api->get_resource()->card_level_id==$card_level->card_level_id) ]);
        }


        $form->set_inputs([
          ['title'=>tr('Title'), 'required'=>true, 'type'=>'text', 'name'=>'title', 'placeholder'=>tr('Title'), 'value'=>$api->get_resource()->title],
          ['title'=>tr('Level'), 'required'=>true, 'type'=>'select', 'name'=>'card_level_id', 'options'=>$card_level_options, 'placeholder'=>''],
          ['title'=>tr('Position'), 'required'=>true, 'type'=>'text', 'name'=>'position', 'value'=>$api->get_resource()->position, 'placeholder'=>tr('Position')],
          ['title'=>tr('Required Credits (Summed Up)'), 'required'=>true, 'type'=>'text', 'name'=>'min_previous_credits', 'placeholder'=>tr('Required Credits (Summed Up)'), 'value'=>$api->get_resource()->min_previous_credits ],
          ['title'=>tr('Enabled'), 'required'=>false, 'type'=>'select', 'name'=>'is_enabled', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>($api->get_resource()->is_enabled)], ['title'=>tr('No'), 'value'=>0, 'selected'=>!($api->get_resource()->is_enabled)]
                            ]],
        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update'],
          ['title'=>tr('Delete'), 'enabled'=>true, 'value'=>'delete']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_achievement/');
        $handler->set_form_action_delete('/achievements');
        $handler->set_form_action_update('/achievements/');
        $handler->set_id($achievement_id);
        $handler->set_id_name('achievement_id');
        $handler->set_params([
          ['title'=>'string'],
          ['position'=>'integer'],
          ['min_previous_credits'=>'integer'],
          ['card_level_id'=>'integer'],
          ['is_enabled'=>'integer'],
        ]);
        $handler->handle();



        /* End: Edit */
      } else {
        /* Create */

        // Create Form

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $api = new ApiClient();
        $api->get('/zazu_achievement/');

        $max_achievement_position = 1;
        foreach($api->get_resource() as $row_key=>$achievement){
          if( $achievement->position >= $max_achievement_position ) $max_achievement_position = $achievement->position + 10;
        }

        $api_card_levels = new ApiClient();
        $api_card_levels->get_filter('/zazu_card_level/', '(is_enabled=1)');
        $card_level_options = [];
        foreach($api_card_levels->get_resource() as $key=>$card_level){
          
          array_push($card_level_options, ['title'=>$card_level->title, 'value'=>$card_level->card_level_id, 'selected'=> false ]);
        }
        //array_push($card_level_options, ['title'=>'', 'value'=>'0', 'selected'=> 0 ]);


        $form->set_inputs([
          ['title'=>tr('Title'), 'required'=>true, 'type'=>'text', 'name'=>'title', 'placeholder'=>tr('Title'), 'value'=>''],
          ['title'=>tr('Level'), 'required'=>true, 'type'=>'select', 'name'=>'card_level_id', 'options'=>$card_level_options, 'placeholder'=>''],
          ['title'=>tr('Position'), 'required'=>true, 'type'=>'text', 'name'=>'position', 'value'=>$max_achievement_position, 'placeholder'=>tr('Position')],
          ['title'=>tr('Required Credits (Summed Up)'), 'required'=>true, 'type'=>'text', 'name'=>'min_previous_credits', 'placeholder'=>tr('Required Credits (Summed Up)'), 'value'=>'' ],
          ['title'=>tr('Enabled'), 'required'=>false, 'type'=>'select', 'name'=>'is_enabled', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>true], ['title'=>tr('No'), 'value'=>0, 'selected'=>false]
                            ]],
        ]);

        $form->set_buttons([
          ['title'=>tr('Create'), 'enabled'=>true, 'value'=>'create']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_achievement/');
        $handler->set_form_action_create('/achievements/');
        $handler->set_id($achievement_id);
        $handler->set_id_name('achievement_id');
        $handler->set_params([
          ['title'=>'string'],
          ['position'=>'integer'],
          ['min_previous_credits'=>'integer'],
          ['card_level_id'=>'integer'],
          ['is_enabled'=>'integer'],
        ]);
        $handler->handle();



      /* End: Create */
      }
      ?>

    </div> <!-- End: Card -->
  </div><!-- End: column -->
  <div class="col-md-3">
    <div class="card">
      <!-- NOTES CONTENT -->
      <h3><?php t('Achievement'); ?></h3>
    </div>
  </div>

<?php require_once 'footer.php'; ?>
