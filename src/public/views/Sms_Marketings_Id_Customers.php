<?php require 'header.php'; ?>


<?php /* Disable page */ redirect('/'); ?>

<div class="row">
  <div class="col-md-6">
    <div class="card">


<?php 

	require_once 'utils/Form.php';


	if ( $_SERVER['REQUEST_METHOD'] == 'GET' ){
		$api = new ApiClient();
		$api->get_row('/zazu_sms_marketing/', $sms_marketing_id);
	}

	?>

		  <!-- Customer String -->
          <div id="single_customers" class="form-group">
            <form id="single_customers_form" method="POST" 
            action="<?php if(isset($_GET['setup'])){echo '?setup=';} ?>">
              <label>Customers</label>
              <div width="100%">
                  <textarea id="single_customers_input" name="customers_string" form="single_customers_form" rows="20" style="clear:both;width:100%" onchange="send_single_customers()"><?php echo $api->get_resource()->customers ?></textarea>
              </div>
              <br>
              <input type="hidden" name="form" value="single_customers">
              <button type="submit" class="btn btn-success" 
              <?php if ($api->get_resource()->is_executed){ echo disabled; } ?> >Save</button>
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
              //var_dump($customers_string);
              //var_dump($_POST);
              if(isset($_GET['setup'])){
              	redirect('/sms_marketings/' . $sms_marketing_id );
              }else{
              	redirect('/sms_marketings/' . $sms_marketing_id . '/customers');
              }
            }
          }
          ?>
          <!-- End: Customer String -->

          <!-- Start Script to handle inputs -->
          <script>

              function send_single_customers(){
                // Remove invalid characters from the input
                var values = document.getElementById('single_customers_input').value;
                values = values.replace(/[^0-9,]/g, "");
                document.getElementById('single_customers_input').value = values;
              }
          </script>




    </div>
  </div>
  <div class="col-md-6">
    <div class="card">

      <!-- NOTES CONTENT -->
      <h3>SMS Marketing</h3>
      <h4>Customers in the campaign</h4>
      <a href="/sms_marketings/<?php echo $sms_marketing_id; ?>"><button type="button" class="btn btn-primary">Back</button></a>
    </div>

    <!--
    <div class="card">
      <h3>Remove customers from campaign</h3>
      <form method="POST" action="">
      	<input type="hidden" name="form" value="delete-all">
       <button type="submit" class="btn btn-primary">Remove all</button>
      </form>
    </div>
	-->

  </div>
</div>


<?php require 'footer.php'; ?>
