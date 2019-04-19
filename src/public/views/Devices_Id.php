<?php require_once 'header.php'; ?>


<div class="row">
  <div class="col-md-6">
    <div class="card">

<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';

      if($device_id>0){

        // Edit Form

        $api = new ApiClient();
        $api->get_row('/zazu_device/', $device_id);

        if ($api->get_response_code()!=200){
          redirect('/devices');
        }

        $api_resource = $api->get_resource();

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $device_is_enabled_options = [];
        array_push($device_is_enabled_options, ['title'=>tr('Yes'), 'value'=>1, 'selected'=>(  $api_resource->is_enabled=='1'  )]);
        array_push($device_is_enabled_options, ['title'=>tr('No'), 'value'=>0, 'selected'=>(  $api_resource->is_enabled=='0'  )]);

        $api_stores = new ApiClient();
        $api_stores->get_filter('/zazu_store/', '(is_enabled=1)');
        $store_options = [];
        foreach($api_stores->get_resource() as $store_key=>$store){
          array_push($store_options, ['title'=>$store->name.' ('.$store->code.')', 'value'=>$store->store_id, 'selected'=>( $api_resource->store_id == $store->store_id )]);
        }


        $form->set_inputs([
          ['title'=>tr('Device'), 'required'=>true, 'type'=>'text', 'name'=>'code', 'value'=>$api_resource->code, 'placeholder'=>tr('Device')],
          ['title'=>tr('Description'), 'required'=>false, 'type'=>'text', 'name'=>'description', 'value'=>$api_resource->description, 'placeholder'=>tr('Description')],
          ['title'=>tr('Store'), 'required'=>true, 'type'=>'select', 'name'=>'store_id', 'options'=>$store_options ],
          ['title'=>tr('Enabled'), 'required'=>true, 'type'=>'select', 'name'=>'is_enabled', 'options'=>$device_is_enabled_options ],
        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update'],
          ['title'=>tr('Delete'), 'enabled'=>true, 'value'=>'delete']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_device/');
        $handler->set_form_action_delete('/devices');
        $handler->set_form_action_update('/devices/');
        $handler->set_id($device_id);
        $handler->set_id_name('device_id');
        $handler->set_params([
          ['code'=>'string'],
          ['description'=>'string'],
          ['store_id'=>'integer'],
          ['is_enabled'=>'integer']
        ]);
        $handler->handle();

      } else {

        // Create Form

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $api_stores = new ApiClient();
        $api_stores->get_filter('/zazu_store/', '(is_enabled=1)');
        $store_options = [];
        foreach($api_stores->get_resource() as $store_key=>$store){
          array_push($store_options, ['title'=>$store->name.' ('.$store->code.')', 'value'=>$store->store_id, 'selected'=>false]);
        }

        $form->set_inputs([
          ['title'=>tr('Device'), 'required'=>true, 'type'=>'text', 'name'=>'code', 'value'=>'', 'placeholder'=>tr('Device')],
          ['title'=>tr('Description'), 'required'=>false, 'type'=>'text', 'name'=>'description', 'value'=>'', 'placeholder'=>tr('Description')],
          ['title'=>tr('login'), 'required'=>true, 'type'=>'hidden', 'name'=>'login', 'value'=>rand(1111000, 9999900)],
          ['title'=>tr('password'), 'required'=>true, 'type'=>'hidden', 'name'=>'password', 'value'=>rand(11110900, 99999090)],
          ['title'=>tr('Store'), 'required'=>true, 'type'=>'select', 'name'=>'store_id', 'options'=>$store_options ],
          ['title'=>tr('Enabled'), 'required'=>true, 'type'=>'select', 'name'=>'is_enabled', 'options'=>[
                          ['title'=>tr('Yes'), 'value'=>1, 'selected'=>true],
                          ['title'=>tr('No'), 'value'=>0, 'selected'=>false],
                        ]],
        ]);

        $form->set_buttons([
          ['title'=>tr('Create'), 'enabled'=>true, 'value'=>'create']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_device/');
        $handler->set_form_action_create('/devices/');
        $handler->set_id($device_id);
        $handler->set_id_name('device_id');
        $handler->set_params([
          ['code'=>'string'],
          ['description'=>'string'],
          ['store_id'=>'integer'],
          ['is_enabled'=>'integer'],
          ['login'=>'string'],
          ['password'=>'string'],
        ]);
        $handler->handle();

      }

?>

    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <!-- NOTES CONTENT -->
      <h3><?php t('Device'); ?></h3>
    </div>
  </div>
</div>


<?php require_once 'footer.php'; ?>
