<?php require_once 'header.php'; ?>


<div class="row">
  <div class="col-md-6">
    <div class="card">

<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';

      if($store_id>0){

        // Edit Form

        $api = new ApiClient();
        $api->get_row('/zazu_store/', $store_id);

        if ($api->get_response_code()!=200){
          redirect('/stores');
        }

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $api_store_is_enabled = new ApiClient();
        $api_store_is_enabled->get_row('/zazu_store/', $store_id);

        $store_is_enabled_options = [];
        array_push($store_is_enabled_options, ['title'=>tr('Yes'), 'value'=>1, 'selected'=>(  $api_store_is_enabled->get_resource()->is_enabled=='1'  )]);
        array_push($store_is_enabled_options, ['title'=>tr('No'), 'value'=>0, 'selected'=>(  $api_store_is_enabled->get_resource()->is_enabled=='0'  )]);


        $form->set_inputs([
          ['title'=>tr('Name'), 'required'=>true, 'type'=>'text', 'name'=>'name', 'value'=>$api->get_resource()->name, 'placeholder'=>tr('Name')],
          ['title'=>tr('Code'), 'required'=>true, 'type'=>'text', 'name'=>'code', 'value'=>$api->get_resource()->code, 'placeholder'=>tr('Code')],
          ['title'=>tr('Description'), 'required'=>false, 'type'=>'text', 'name'=>'description', 'value'=>$api->get_resource()->description, 'placeholder'=>tr('Description')],
          ['title'=>tr('Enabled'), 'required'=>false, 'type'=>'select', 'name'=>'is_enabled', 'options'=>$store_is_enabled_options ],
        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update'],
          //['title'=>tr('Delete'), 'enabled'=>true, 'value'=>'delete']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_store/');
        $handler->set_form_action_delete('/stores');
        $handler->set_form_action_update('/stores/');
        $handler->set_id($store_id);
        $handler->set_id_name('customer_id');
        $handler->set_params([
          ['name'=>'string'],
          ['code'=>'string'],
          ['description'=>'string'],
          ['is_enabled'=>'integer'],
        ]);
        $handler->handle();

      } else {

        // Create Form

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $form->set_inputs([
          ['title'=>tr('Name'), 'required'=>true, 'type'=>'text', 'name'=>'name', 'value'=>'', 'placeholder'=>tr('Name')],
          ['title'=>tr('Code'), 'required'=>true, 'type'=>'text', 'name'=>'code', 'value'=>'', 'placeholder'=>tr('Code')],
          ['title'=>tr('Description'), 'required'=>false, 'type'=>'text', 'name'=>'description', 'value'=>'', 'placeholder'=>tr('Description')],
          ['title'=>tr('Enabled'), 'required'=>false, 'type'=>'select', 'name'=>'is_enabled', 'options'=>[
                            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>true], ['title'=>tr('No'), 'value'=>0, 'selected'=>false]
                            ]],
        ]);

        $form->set_buttons([
          ['title'=>tr('Create'), 'enabled'=>true, 'value'=>'create']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_store/');
        $handler->set_form_action_create('/stores/');
        $handler->set_id($store_id);
        $handler->set_id_name('store_id');
        $handler->set_params([
          ['name'=>'string'],
          ['code'=>'string'],
          ['description'=>'string'],
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
      <h3><?php t('Store'); ?></h3>
    </div>
  </div>

  <?php if($store_id>0){ ?>
  <div class="col-md-12">
    <div class="card">
      <h3><?php t('Statistics'); ?></h3>
      <a href="/stores/<?php echo $store_id; ?>/statistics"><button class="btn btn-info"><?php t('View Statistics'); ?></button></a>
    </div>
  </div>

  <?php } ?>

  </div>



<?php require_once 'footer.php'; ?>
