<?php require_once 'header.php'; ?>


<div class="row">
  <div class="col-md-6">
    <div class="card">

<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';
      require 'utils/Hash.php';

      if($manager_id>0){

        // Edit Form

        $api = new ApiClient();
        $api->get_row('/zazu_manager/', $manager_id);

        if ($api->get_response_code()!=200){
          redirect('/managers');
        }


        $api_resource = $api->get_resource();


        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');


        /*$api_stores = new ApiClient();
        $api_stores->get_filter('/zazu_store/', '(is_enabled=1)');
        $store_options = [];
        foreach($api_stores->get_resource() as $store_key=>$store){
          array_push($store_options, ['title'=>$store->name.' ('.$store->code.')', 'value'=>$store->store_id, 'selected'=>( $api_resource->store_id == $store->store_id )]);
        }*/



        $default_admin = false;
        $restrict_pass_change = false;


        if ($api->get_resource()->dashboard_login=="admin"){
          $default_admin = true;
        }


        if( $_SESSION['manager_id'] != $api->get_resource()->manager_id && $default_admin ){
          $restrict_pass_change = true;
        }


        $form->set_inputs([
          ['title'=>tr('Name'), 'required'=>true, 'type'=>'text', 'name'=>'name', 'value'=>$api->get_resource()->name, 'disabled'=>$default_admin ,'placeholder'=>tr('Name')],
          ['title'=>tr('Username'), 'required'=>true, 'type'=>'text', 'disabled'=>$default_admin, 'name'=>'dashboard_login', 'value'=>$api->get_resource()->dashboard_login, 'placeholder'=>tr('Login Username')],
          ['title'=>tr('Password'), 'required'=>false, 'type'=>'password', 'disabled'=>$restrict_pass_change, 'name'=>'dashboard_password', 'value'=>'', 'placeholder'=>tr('Leave blank for no change')],
          ['title'=>tr('Administrator'), 'required'=>true, 'disabled'=>$default_admin, 'type'=>'select', 'name'=>'is_admin', 'options'=>[
                          ['title'=>tr('Yes'), 'value'=>1, 'selected'=> ($api->get_resource()->is_admin) ],
                          ['title'=>tr('No'), 'value'=>0, 'selected'=> (!$api->get_resource()->is_admin) ],
                        ] ],
          ['title'=>tr('Employee'), 'required'=>true, 'type'=>'select', 'name'=>'is_employee', 'options'=>[
                          ['title'=>tr('Yes'), 'value'=>1, 'selected'=> ($api->get_resource()->is_employee) ],
                          ['title'=>tr('No'), 'value'=>0, 'selected'=> (!$api->get_resource()->is_employee) ],
                        ] ],
          ['title'=>tr('Enabled'), 'required'=>true, 'type'=>'select', 'disabled'=>$default_admin, 'name'=>'is_enabled', 'options'=>[
                          ['title'=>tr('Yes'), 'value'=>1, 'selected'=> ($api->get_resource()->is_enabled) ],
                          ['title'=>tr('No'), 'value'=>0, 'selected'=> (!$api->get_resource()->is_enabled) ],
                        ] ],
        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_manager/');
        $handler->set_form_action_update('/managers/');
        $handler->set_id($manager_id);
        $handler->set_id_name('manager_id');
        $handler->set_params([
          ['name'=>'string'],
          ['is_admin'=>'integer'],
          ['is_employee'=>'integer'],
          ['dashboard_login'=>'string'],
          ['dashboard_password'=>'password_crypt'],
          ['is_enabled'=>'integer'],
        ]);
        $handler->handle();

      } else {

        // Create Form

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $password = rand(1111090000, 9999909000);


        $form->set_inputs([
          ['title'=>tr('Name'), 'required'=>true, 'type'=>'text', 'name'=>'name', 'value'=>'', 'placeholder'=>tr('Name')],
          ['title'=>tr('Username'), 'required'=>true, 'type'=>'text', 'name'=>'dashboard_login', 'value'=>'', 'placeholder'=>tr('Login Username')],
          ['title'=>tr('Password'), 'required'=>true, 'type'=>'text', 'name'=>'dashboard_password', 'value'=>$password],
          ['title'=>tr('Is Administrator'), 'required'=>true, 'type'=>'select', 'name'=>'is_admin', 'options'=>[
                          ['title'=>tr('Yes'), 'value'=>1, 'selected'=>false],
                          ['title'=>tr('No'), 'value'=>0, 'selected'=>true],
                        ] ],
          ['title'=>tr('Is Employee'), 'required'=>true, 'type'=>'select', 'name'=>'is_employee', 'options'=>[
                          ['title'=>tr('Yes'), 'value'=>1, 'selected'=>true],
                          ['title'=>tr('No'), 'value'=>0, 'selected'=>false],
                        ] ],
        ]);

        $form->set_buttons([
          ['title'=>tr('Create'), 'enabled'=>true, 'value'=>'create']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_manager/');
        $handler->set_form_action_create('/managers/');
        $handler->set_id($manager_id);
        $handler->set_id_name('manager_id');
        $handler->set_params([
          ['name'=>'string'],
          ['is_admin'=>'integer'],
          ['is_employee'=>'integer'],
          ['dashboard_login'=>'string'],
          ['dashboard_password'=>'password_crypt'],
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
        <?php if($manager_id>0){
          if($api->get_resource()->is_admin){ echo '<h3>'.tr('Manager').'</h3>'; } else { echo '<h3>'.tr('Employee').'</h3>'; }
          echo '<h4>'.$api->get_resource()->name.'</h4>';
        }else{
          echo '<h3>'.tr('Managers - Employees').'</h3>';
        } ?>
      </div>
    </div>

    <?php if($manager_id>0){ ?>
    <div class="col-md-12">
      <div class="card">
        <h3><?php t('Statistics'); ?></h3>
        <a href="/managers/<?php echo $manager_id; ?>/statistics"><button class="btn btn-info"><?php t('View Statistics'); ?></button></a>
      </div>
    </div>
    <?php } ?>

  </div>

</div>


<?php require_once 'footer.php'; ?>
