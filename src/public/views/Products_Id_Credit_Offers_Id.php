<?php require_once 'header.php'; ?>


<div class="row">
  <div class="col-6">
    <div class="card">

<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';

      if($credit_offer_id>0){

        // Edit Form

        $api = new ApiClient();
        $api->get_row('/zazu_credit_offer/', $credit_offer_id);

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $api_good = new ApiClient();
        $api_good->get_row('/zazu_good/', $good_id);

        $form->set_inputs([
          ['title'=>tr('Product'), 'required'=>true, 'type'=>'text', 'name'=>'title', 'disabled'=>true, 'value'=>$api_good->get_resource()->title, 'placeholder'=>''],
          ['title'=>tr('Unit'), 'required'=>true, 'type'=>'text', 'name'=>'unit_name', 'disabled'=>true, 'value'=>$api_good->get_resource()->unit, 'placeholder'=>''],
          ['title'=>'Credits / '.$api_good->get_resource()->unit, 'required'=>true, 'type'=>'text', 'name'=>'credits_per_unit', 'value'=>$api->get_resource()->credits_per_unit, 'placeholder'=>'Credits/Unit'],
        ]);

        $form->set_buttons([
          ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update'],
          ['title'=>tr('Delete'), 'enabled'=>true, 'value'=>'delete']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_credit_offer/');
        $handler->set_form_action_delete('/products/'.$good_id.'/credit_offers');
        $handler->set_form_action_update('/products/'.$good_id.'/credit_offers/');
        $handler->set_id($credit_offer_id);
        $handler->set_id_name('credit_offer_id');
        $handler->set_params([
            ['credits_per_unit'=>'float'],
        ]);
        $handler->handle();

      } else {

        // Create Form

        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $api_good = new ApiClient();
        $api_good->get_row('/zazu_good/', $good_id);

        $form->set_inputs([
          ['title'=>tr('Product'), 'required'=>true, 'type'=>'hidden', 'name'=>'good_id', 'value'=>$good_id ],
          ['title'=>'Credits / '.$api_good->get_resource()->unit, 'required'=>true, 'type'=>'text', 'name'=>'credits_per_unit', 'value'=>'', 'placeholder'=>tr('Credits')],
        ]);

        $form->set_buttons([
          ['title'=>tr('Create'), 'enabled'=>true, 'value'=>'create']
        ]);
        $form->show_form();



        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_credit_offer/');
        $handler->set_form_action_create('/products/'.$good_id.'/credit_offers/');
        $handler->set_id($credit_offer_id);
        $handler->set_id_name('credit_offer_id');
        $handler->set_params([
          ['good_id'=>'integer'],
          ['credits_per_unit'=>'float'],
        ]);
        $handler->handle();

      }

?>

    </div>
  </div>
  <div class="col-3">
    <div class="card">
      <!-- NOTES CONTENT -->
      <h3>Credits for <?php echo $api_good->get_resource()->title; ?></h3>
      <a href="/products/<?php echo $good_id; ?>/credit_offers"><button class="btn btn-info">Back</button></a>
    </div>
  </div>
</div>


<?php require_once 'footer.php'; ?>
