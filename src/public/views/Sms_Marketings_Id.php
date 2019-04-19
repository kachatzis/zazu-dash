<?php require 'header.php'; ?>

<?php /* Disable page */ redirect('/'); ?>

<div class="row">
  <div class="col-md-6">
    <div class="card">

<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';
      include_once 'utils/Redirect.php';


      if($sms_marketing_id>0){

        // Edit Form

        $api = new ApiClient();
        $api->get_row('/zazu_sms_marketing/', $sms_marketing_id);

        if ($api->get_response_code()!=200){
          redirect('/sms_marketings');
        }

        // Get Related Customers
        $api_get_customers = new ApiClient();
        $api_get_customers->get_filter('/zazu_sms_marketing_customer/', '(sms_marketing_id='.$sms_marketing_id.')');

        //var_dump( $api->get_resource() );
        // Set Message if it's not set
        if (!$api->get_resource()->message){
          $form = new Form();
          $form->set_inputs([
            ['title'=>tr('Set a message'), 'required'=>true, 'type'=>'text', 'name'=>'message', 'value'=>'', 'placeholder'=>tr('Message')],
          ]);
          $form->set_buttons([
            ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update']
          ]);
          $form->show_form();
          $handler = new PostHandler();
          $handler->set_api_action_uri('/zazu_sms_marketing/');
          $handler->set_form_action_delete('/sms_marketings');
          $handler->set_form_action_update('/sms_marketings/');
          $handler->set_id($sms_marketing_id);
          $handler->set_id_name('sms_marketing_id');
          $handler->set_params([
            ['message'=>'string'],
          ]);
          $handler->handle();

        }



        // Set Customers 
        //elseif (!$api_get_customers->get_results_count()>0) {
        elseif (!$api->get_resource()->customers && !$api->get_resource()->is_executed ) { // TODO: Add functionality in the customer selections & continue with other settings ?>

          <?php 
          /* Redirect to customers page */
          redirect('/sms_marketings/' . $sms_marketing_id . '/customers?setup=');
          exit();

          ?>

          <h4>You can select Customers from here, or go to a specific Customers' page and subscribe to a marketing campaign.</h4><br>

          <!-- User Type -->
          <div class="form-group">
              <label>User Type</label>
              <select class="form-control custom-select" onchange="check_user_type(this);" onload="send_single_customers()">
                <option value=""> </option>
                  <option value="card_level" disabled>Card level</option>
                  <option value="single_customers" selected>Enter specific customers</option>
                  <option value="customer_list" disabled>Select customers from list</option>
              </select>
          </div>
          <!-- End: User Type -->

          <?php /*** ?>
          <!-- Card Level -->
          <div id="card_level" class="form-group" style="display: none;">
            <form method="POST" action="">
            <label class="col-md-12">Card Levels</label>
            <select multiple id="card_level_input" name="card_level_input[]" class="form-control custom-select" onchange="check_card_level(this);">
              <?php
                $api_card_levels = new ApiClient();
                $api_card_levels->get('/zazu_card_level/');
                foreach ($api_card_levels->get_resource() as $level_key=>$level_value) {
                  echo '<option value="'.$level_value->card_level_id.'">'.$level_value->title.'</option>';
                } ?>
            </select>
            <p>You can select multiple rows by holding "CTRL" or multiple continuous rows by holding "SHIFT".</p>
            <br>
            <input type="hidden" name="form" value="card_level">
            <button type="submit" class="btn btn-success" >Save</button>
            </form>
          </div>
          <?php ***/ ?>



          <?php
            /* Handle Card_Level form POST */
            if(isset($_POST['form'])){
              if($_POST['form']=='card_level'){
                $api_card_level = new ApiClient();
                foreach($_POST['card_level_input'] as $card_level){
                  $api_card_level->get_filter('/zazu_card_level/', '(card_level_id='.$card_level.')&limit=1');
                  if($api_card_level->get_results_count()>0){
                    // The card Level exists. Get all related customers
                    $api_related_customers = new ApiCLient();
                    $api_related_customers->get_filter('/zazu_customer/', '(card_level_id='.$card_level.')');
                    if($api_related_customers->get_results_count()>0){
                      foreach($api_related_customers->get_resource() as $api_row=>$customer){
                        // If the user is allowing sms marketing
                        if($customer->send_sms){
                          // Check if the user is already sunscribed
                          $api_check_existing_listing = new ApiClient();
                          $api_check_existing_listing->get_filter('/zazu_sms_marketing_customer/', '(customer_id='.$customer->customer_id.')and(sms_marketing_id='.$sms_marketing_id.')');
                          if($api_check_existing_listing->get_results_count()==0){
                            // Create customer-marketing relation
                            $api_post_customer_marketing = new ApiClient();
                            $api_post_customer_marketing->post('/zazu_sms_marketing_customer/', ['resource'=>['customer_id'=>$customer->customer_id, 'sms_marketing_id'=>$sms_marketing_id]]);
                          }
                        }
                      }
                    }
                  }
                }
                redirect('/sms_marketings/'.$sms_marketing_id);
              }
            }
           ?>
          <!-- End: Card Level -->

          <?php /*** ?>
          <!-- Customer List -->
          <div id="customer_list" class="form-group" style="display: none; height: 1020px;">
            <form method="POST" action="">
            <label class="col-md-12">Customers</label>
            <select disabled name="customer_list_input[]" id="customer_list_input" multiple class="form-control custom-select" onchange="check_customers(this);" style="height: 900px;">
              <?php
                $api_customers = new ApiClient();
                $api_customers->get_filter('/zazu_customer/', '(send_sms=1)');
                foreach ($api_customers->get_resource() as $customer_key=>$customer_row) {
                  echo '<option value="'.$customer_row->customer_id.'">'.$customer_row->last_name.' '.$customer_row->first_name.'</option>';
                } ?>
            </select>
            <p>You can select multiple rows by holding "CTRL" or multiple continuous rows by holding "SHIFT".</p>
            <br>
            <input type="hidden" name="form" value="customer_list">
            <button type="submit" class="btn btn-success" >Save</button>
            </form>
          </div>
          <?php ***/ ?>

          <?php
          /* Handle Customer_List form POST */
          if(isset($_POST['form'])){
            if($_POST['form']=='customer_list'){
              foreach($_POST['customer_list_input'] as $customer_input){
                $api_customer = new ApiCLient();
                $api_customer->get_filter('/zazu_customer/', '(customer_id='.$customer_input.')&limit=1');
                if($api_customer->get_results_count()>0){
                  foreach($api_customer->get_resource() as $api_row=>$customer){
                    // If the user is allowing sms marketing
                    if($customer->send_sms){
                      // Check if the user is already sunscribed
                      $api_check_existing_listing = new ApiClient();
                      $api_check_existing_listing->get_filter('/zazu_sms_marketing_customer/', '(customer_id='.$customer->customer_id.')and(sms_marketing_id='.$sms_marketing_id.')');
                      if($api_check_existing_listing->get_results_count()==0){
                        // Create customer-marketing relation
                        $api_post_customer_marketing = new ApiClient();
                        $api_post_customer_marketing->post('/zazu_sms_marketing_customer/', ['resource'=>['customer_id'=>$customer->customer_id, 'sms_marketing_id'=>$sms_marketing_id]]);
                      }
                    }
                  }
                }
              }
              redirect('/sms_marketings/'.$sms_marketing_id);
            }
          }
          ?>
          <!-- End: Customer List -->

          <!-- Customer String -->
          <div id="single_customers" class="form-group" style="display: none;">
            <form method="POST" action="">
              <label>Customers</label>
              <div>
                  <input id="single_customers_input" onchange="send_single_customers()" type="text" name="customers_string" placeholder="Enter customer cards separated by a comma" value="" class="form-control form-control-line">
              </div>
              <br>
              <input type="hidden" name="form" value="single_customers">
              <button type="submit" class="btn btn-success" >Save</button>
            </form>
          </div>
          <?php
          /* Handle Single_Customers form POST */
          if(isset($_POST['form'])){
            if($_POST['form']=='single_customers'){
              $customers_string = explode(',', $_POST['customers_string']);

              $api_single_customers = new ApiClient();
              $single_user_api_body = [];
              $single_user_api_body['resource']['customers'] = $_POST['customers_string'];
              $single_user_api_body['ids'] = 1;
              $api_single_customers->set_id_name('sms_marketing_id');
              $api_single_customers->patch('/zazu_sms_marketing/', $single_user_api_body);
              redirect('/sms_marketings/' . $sms_marketing_id);

              /*
              $customers_string = explode(',', $_POST['customers_string']);
              foreach($customers_string as $row_key=>$customer_input){
                $api_customer = new ApiCLient();
                $api_customer->get_filter('/zazu_customer/', '(card='.$customer_input.')&limit=1');
                if($api_customer->get_results_count()>0){
                  foreach($api_customer->get_resource() as $api_row=>$customer){
                    // If the user is allowing sms marketing
                    if($customer->send_sms){
                      // Check if the user is already sunscribed
                      $api_check_existing_listing = new ApiClient();
                      $api_check_existing_listing->get_filter('/zazu_sms_marketing_customer/', '(customer_id='.$customer->customer_id.')and(sms_marketing_id='.$sms_marketing_id.')');
                      if($api_check_existing_listing->get_results_count()==0){
                        // Create customer-marketing relation
                        $api_post_customer_marketing = new ApiClient();
                        $api_post_customer_marketing->post('/zazu_sms_marketing_customer/', ['resource'=>['customer_id'=>$customer->customer_id, 'sms_marketing_id'=>$sms_marketing_id]]);
                      }
                    }
                  }
                }
              }
              redirect('/sms_marketings/'.$sms_marketing_id);
              */
            }
          }
          ?>
          <!-- End: Customer String -->

          <!-- Start Script to handle inputs -->
          <script>
              function check_user_type(that) {
                document.getElementById("card_level").style.display = "none";
                document.getElementById("single_customers").style.display = "none";
                document.getElementById("customer_list").style.display = "none";
                switch(that.value){
                  case "card_level":
                    document.getElementById("card_level").style.display = "block";
                    break;
                  case "single_customers":
                    document.getElementById("single_customers").style.display = "block";
                    break;
                  case "customer_list":
                    document.getElementById("customer_list").style.display = "block";
                    break;
                }
              }

              function send_card_level(){
                /*var values = $('#card_level_input').val();
                alert(values);*/
              }

              function send_single_customers(){
                // Remove invalid characters from the input
                var values = document.getElementById('single_customers_input').value;
                values = values.replace(/[^0-9,]/g, "");
                document.getElementById('single_customers_input').value = values;
              }

              function send_customer_list(){
                /*var values = $('#customer_list_input').val();
                alert(values);*/
              }

              // Enable Single Customers by default
              document.getElementById("single_customers").style.display = "block";
          </script>
        <?php }



        // Set Date and time of execution 
        elseif ((!$api->get_resource()->scheduled_date || !$api->get_resource()->scheduled_time) && !$api->get_resource()->is_executed ) {

          $form = new Form();
          $form->set_inputs([
            ['title'=>tr('Execution Date'), 'required'=>true, 'type'=>'date', 'name'=>'scheduled_date', 'value'=>date('Y-m-d'), 'placeholder'=>''],
            ['title'=>tr('Execution Time'), 'required'=>true, 'type'=>'text', 'name'=>'scheduled_time', 'value'=>date('H:i:s'), 'js_regex'=>'/[^0-9:]/g', 'placeholder'=>'eg. 18:30:00', 'pattern'=>'[0-9:]{8}'],
          ]);
          // 'js_regex'=>'/[^0-9:]/g', older'=>'eg. 18:30'],
            

          $form->set_buttons([
            ['title'=>tr('Save'), 'enabled'=>true, 'value'=>'update']
          ]);
          $form->show_form();
          $handler = new PostHandler();
          $handler->set_api_action_uri('/zazu_sms_marketing/');
          $handler->set_form_action_update('/sms_marketings/');
          $handler->set_id($sms_marketing_id);
          $handler->set_id_name('sms_marketing_id');
          $handler->set_params([
            ['scheduled_date'=>'string'],
            ['scheduled_time'=>'string'],
          ]);
          $handler->handle();
        }



        // Settings Entered
        else{
          $form = new Form();
          $form->set_inputs([
            ['title'=>tr('Message'), 'name'=>'message', 'value'=>$api->get_resource()->message, 'type'=>'textarea', 'rows'=>2, 'disabled'=>true, 'placeholder'=>'', 'required'=>false],
            ['title'=>tr('Scheduled Date'), 'required'=>false, 'type'=>'text', 'name'=>'', 'value'=>$api->get_resource()->scheduled_date, 'placeholder'=>'', 'disabled'=>true],
            ['title'=>tr('Scheduled Time'), 'required'=>false, 'type'=>'text', 'name'=>'', 'value'=>$api->get_resource()->scheduled_time, 'disabled'=>true],
            ['title'=>tr('Execution Date'), 'required'=>false, 'type'=>'text', 'name'=>'', 'value'=>$api->get_resource()->execution_date, 'placeholder'=>'', 'disabled'=>true],
            ['title'=>tr('Execution Time'), 'required'=>false, 'type'=>'text', 'name'=>'', 'value'=>$api->get_resource()->execution_time, 'disabled'=>true],
            ['title'=>tr('Information'), 'required'=>false, 'type'=>'textarea', 'name'=>'', 'value'=>$api->get_resource()->info, 'disabled'=>true, 'placeholder'=>'', 'rows'=>'30']
          ]);
          $form->show_form();

        }



        // Create Form
      } else {

        // Create Form
        $form = new Form();
        $form->set_action('#');
        $form->set_method('POST');

        $form->set_inputs([
          ['title'=>tr('SMS Marketing'), 'required'=>false, 'type'=>'text', 'name'=>'title', 'value'=>'', 'placeholder'=>tr('Title')],
          ['title'=>tr('is_canceled'), 'type'=>'hidden', 'name'=>'is_canceled', 'value'=>'1'],
        ]);
        $form->set_buttons([
          ['title'=>tr('Create'), 'enabled'=>true, 'value'=>'create']
        ]);
        $form->show_form();

        $handler = new PostHandler();
        $handler->set_api_action_uri('/zazu_sms_marketing/');
        $handler->set_form_action_create('/sms_marketings/');
        $handler->set_id($sms_marketing_id);
        $handler->set_id_name('sms_marketing_id');
        $handler->set_params([
          ['title'=>tr('string')],
          ['is_canceled'=>'integer'],
        ]);
        $handler->handle();
      }


?>

    </div>
  </div>
  <div class="col-md-6">
    <div class="card">

      <!-- NOTES CONTENT -->
      <h3>SMS Marketing</h3>
      <?php if($sms_marketing_id>0){
          echo '<h4>'.$api->get_resource()->title.'</h4>';
       } ?>
    </div>
    <div class="card">
      <!-- NOTES CONTENT -->
      <h3><?php t('Related Customers'); ?></h3>
      <?php //if($sms_marketing_id>0 && $api_get_customers->get_results_count()>0){
        //var_dump( $api->get_resource() );
            if ($sms_marketing_id>0 && $api->get_resource()->customers != "") { ?>

        <a href="/sms_marketings/<?php echo $sms_marketing_id; ?>/customers"><button type="button" class="btn btn-primary"><?php t('Customers'); ?></button></a>

      <?php }else{
          echo 'No Customers...';
      } 
      ?>
    </div>
  </div>
</div>


<?php require 'footer.php'; ?>
