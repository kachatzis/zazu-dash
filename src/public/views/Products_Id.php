<?php require_once 'utils/RestrictLogin.php'; $restrict_login = new RestrictLogin(); $restrict_login->handle(); ?>

<?php 
        require_once 'utils/PostHandler.php';
        // Setup PostHandler
        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_good/');
        $handler->set_form_action_create('/products/');
        $handler->set_form_action_delete('/products');
        $handler->set_form_action_update('/products/');
        $handler->set_id($good_id);
        $handler->set_id_name('good_id');
        $handler->set_params([
          ['title'=>tr('string')],
          ['unit'=>'string'],
          ['is_gift'=>'integer'],
          ['credits_cost'=>'integer'],
          ['card_level_id'=> 'integer'],
          ['is_enabled'=>'integer'],
        ]);
        $handler->handle();
?>






<?php require_once 'header.php'; ?>


<div class="row">
  <div class="col-md-6">
    <div class="card">

<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';

      if($good_id>0){

        // Edit Form

        $api = new ApiClient();
        $api->get_row('/zazu_good/', $good_id);

        if ($api->get_response_code()!=200){
          redirect('/products');
        }

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $api_card_levels = new ApiClient();
        $api_card_levels->get_filter('/zazu_card_level/', '(is_enabled=1)');
        $card_level_options = [];
        foreach($api_card_levels->get_resource() as $key=>$card_level){
          array_push($card_level_options, ['title'=>$card_level->title, 'value'=>$card_level->card_level_id, 'selected'=> ($api->get_resource()->card_level_id==$card_level->card_level_id) ]);
        }
        /*array_push($card_level_options, ['title'=>'', 'value'=>'0', 'selected'=> 0 ]);*/

        $unit_name_list = [];
        //$available_units = ['€', 'Kg', 'm', 'L', 'mL'];
        $available_units = ['€'];
        foreach($available_units as $available_unit){
          array_push($unit_name_list, ['title'=>$available_unit, 'value'=>$available_unit, 'selected'=> ($api->get_resource()->unit==$available_unit) ]);
        }

        //var_dump($api->get_resource()->is_gift);

        $is_gift_options = [];
        array_push($is_gift_options, ['title'=>tr('Yes'), 'value'=>1, 'selected'=>( $api->get_resource()->is_gift )]);
        array_push($is_gift_options, ['title'=>tr('No'), 'value'=>0, 'selected'=>( !$api->get_resource()->is_gift )]);

        $form->set_inputs([
          ['title'=>tr('Title'), 'required'=>true, 'type'=>'text', 'name'=>'title', 'value'=>$api->get_resource()->title, 'placeholder'=>tr('Τίτλος')],
          ['title'=>tr('Unit'), 'required'=>true, 'disabled'=>true, 'type'=>'select', 'name'=>'unit', 'options'=>$unit_name_list],
          ['title'=>tr('Gift'), 'required'=>true, 'type'=>'select','disabled'=>true, 'name'=>'is_gift', 'options'=>$is_gift_options, 'onchange'=>'gift_change_script()'],
          ['title'=>tr('Credits Cost'), 'required'=>false, 'type'=>'text', 'name'=>'credits_cost', 'value'=>$api->get_resource()->credits_cost, 'placeholder'=>'Credits Cost (for gifts only)'],
          ['title'=>tr('Card Level'), 'required'=>false, 'type'=>'select', 'name'=>'card_level_id', 'options'=>$card_level_options],
          ['title'=>tr('Enabled'), 'name'=>'is_enabled', 'type'=>'select', 'options'=>[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>$api->get_resource()->is_enabled], ['title'=>tr('No'), 'value'=>0, 'selected'=>!($api->get_resource()->is_enabled)] ]],
        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update']
        ]);
        $form->show_form();


        ?>

        <script>
          function gift_change_script(){
            var select_box = document.getElementById("is_gift");
            var val =  select_box.options[select_box.selectedIndex].value;
            if (val == 0) {
              document.getElementById("credits_cost_div").style.display = "none";
              document.getElementById("card_level_id_div").style.display = "none";
            } else {
              document.getElementById("credits_cost_div").style.display = "block";
              document.getElementById("card_level_id_div").style.display = "block";
            }
          }
          window.onload = gift_change_script;
        </script>

        <?php



        /*$handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_good/');
        $handler->set_form_action_delete('/products');
        $handler->set_form_action_update('/products/');
        $handler->set_id($good_id);
        $handler->set_id_name('good_id');
        $handler->set_params([
          ['title'=>tr('string')],
          ['unit'=>'string'],
          ['is_gift'=>'integer'],
          ['credits_cost'=>'integer'],
          ['card_level_id'=> 'integer'],
        ]);
        $handler->handle();*/

      } else {

        // Create Form

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $unit_name_list = [];
        //$available_units = ['€', '$', 'Kg', 'm', 'L', 'mL'];
        $available_units = ['€'];
        foreach($available_units as $available_unit){
          array_push($unit_name_list, ['title'=>$available_unit, 'value'=>$available_unit, 'selected'=>false ]);
        }

        $is_gift_options = [];
        if (isset($_GET['gift'])){
          array_push($is_gift_options, ['title'=>tr('Yes'), 'value'=>1, 'selected'=>true]);
        } else {
          array_push($is_gift_options, ['title'=>tr('No'), 'value'=>0, 'selected'=>true]);
        }

        $form->set_inputs([
          ['title'=>tr('Title'), 'required'=>true, 'type'=>'text', 'name'=>'title', 'value'=>'', 'placeholder'=>tr('Τίτλος')],
          ['title'=>tr('Unit'), 'required'=>true, 'disabled'=>true, 'type'=>'select', 'name'=>'unit', 'options'=>$unit_name_list],
          ['title'=>tr('Gift'), /*'disabled'=>true,*/ 'required'=>true, 'type'=>'select', 'name'=>'is_gift', 'options'=>$is_gift_options/*[
            ['title'=>tr('Yes'), 'value'=>1, 'selected'=>isset($_GET['gift'])], ['title'=>tr('No'), 'value'=>0, 'selected'=>!isset($_GET['gift'])]
            ]*/],
          ['title'=>'', 'name'=>'is_enabled', 'type'=>'hidden', 'value'=>'0'],
        ]);

        $form->set_buttons([
          ['title'=>tr('Create'), 'enabled'=>true, 'value'=>'create']
        ]);
        $form->show_form();



        /*$handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_good/');
        $handler->set_form_action_create('/products/');
        $handler->set_id($good_id);
        $handler->set_id_name('good_id');
        $handler->set_params([
          ['title'=>tr('string')],
          ['unit'=>'string'],
          ['is_gift'=>'integer'],
        ]);
        $handler->handle();*/

      }

?>

    </div>
  </div>



  <div class="col-md-6">
    <div class="col-md-12">
      <div class="card">
        <!-- NOTES CONTENT -->
        <h3>
        <?php
          if (isset($_GET['gift'])){
            t('Gift');
          } else {
            if ($good_id>0){
              if ($api->get_resource()->is_gift){
                t('Gift');
              } else {
                t('Product');
              }
            } else {
              t('Product');
            }
          }

        ?>
        </h3>
      </div>
    </div>

  <?php if($good_id>0){ ?>
   
    <?php if (!$api->get_resource()->is_gift) { ?>
    <div class="col-md-12">
      <div class="card">
        <!-- Credit Offers -->
        <h3><?php t('Credits System'); ?></h3>
        <?php
          $api_credits = new ApiClient();
          $api_credits->get_filter('/zazu_credit_offer/', '(good_id='.$good_id.')');
          if($api_credits->get_results_count()>0){
            foreach($api_credits->get_resource() as $api_key=>$api_credit_row){
              echo '<p>'.$api_credit_row->credits_per_unit.' / '.$api->get_resource()->unit.'</p>';
            }
          }
         ?>
        <a href="/products/<?php echo $good_id; ?>/credit_offers"><button class="btn btn-info"><?php t('View Credits'); ?></button></a>
      </div>
    </div>
     <?php } ?>

    <div class="col-md-12">
      <div class="card">
        <!-- Portal Settings -->
        <h3><?php t('Portal'); ?></h3>
        <a href="/products/<?php echo $good_id; ?>/portal"><button class="btn btn-info"><?php t('View Settings'); ?></button></a>
      </div>
    </div>



          <div class="col-md-12">
            <div class="card">
              <!-- NOTES CONTENT -->
              <h3><?php t('Statistics'); ?></h3>
              <a href="/products/<?php echo $good_id; ?>/statistics"><button class="btn btn-info"><?php t('View Statistics'); ?></button></a>
            </div>
          </div>
  <?php } ?>
</div>


<?php require_once 'footer.php'; ?>
