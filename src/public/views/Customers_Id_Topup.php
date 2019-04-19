<?php require_once 'header.php'; ?>

    <?php

      $MAX_TOPUP_CREDITS = 100000;

      $customer = new ApiClient();
      $customer->get_row('/zazu_customer/', $customer_id);
      if ($customer->get_results_count()<0){
        redirect('/customers/'.$customer_id);
        exit();
      }

      // If form POSTed
      if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['topup'])){
        $topup = (int)$_POST['topup'];
        $current = (int)$customer->get_resource()->credit;
        $previous = (int)$customer->get_resource()->previous_credit;
        // Refresh for invalid values
        if ($topup==0 || $topup<-$MAX_TOPUP_CREDITS || $topup>$MAX_TOPUP_CREDITS){
          redirect('/customers/'.$customer_id.'/topup?invalid=');
          exit();
        }

        // Refresh for invalid customer credits
        if ( ($current + $topup) < 0 ){
           redirect('/customers/'.$customer_id.'/topup?invalid=');
          exit();
        }

        // Handle
        /*$body['resource']['credit'] = $current + $topup;
        $body['resource']['previous_credit'] = $previous + $topup;
        $body['ids'] = $customer_id;
        $update = new ApiClient();
        $update->patch('/zazu_customer/', $body);*/
        $api_device = new ApiClient();
        $api_device->get_filter('/zazu_device/', '(code=SYSTEM)&limit=1');
        if ($api_device->get_results_count() < 1){
          redirect('/customers/'.$customer_id.'/redemption');
        }
        $api_device = $api_device->get_resource()[0];

        $api_store = new ApiClient();
        $api_store->get_filter('/zazu_store/', '(code=SYSTEM)&limit=1');
        if ($api_store->get_results_count() < 1){
          redirect('/customers/'.$customer_id.'/redemption');
        }
        $api_store = $api_store->get_resource()[0];


        $transaction_body = [];
        $transaction_body['resource']['store_id'] = $api_store->store_id;
        $transaction_body['resource']['is_topup'] = 1;
        $transaction_body['resource']['device_id'] = $api_device->device_id;
        $transaction_body['resource']['customer_id'] = $customer_id;
        $transaction_body['resource']['manager_id'] = $_SESSION['manager_id'];
        $transaction_body['resource']['money_spent'] = 0;
        $transaction_body['resource']['request_date'] = date('Y-m-d');
        $transaction_body['resource']['request_time'] = date('H:i:s');
        $transaction_body['resource']['transaction_date'] = date('Y-m-d');
        $transaction_body['resource']['transaction_time'] = date('H:i:s');
        $transaction_body['resource']['credit'] = $topup;
        $transaction_body['resource']['is_processed'] = 0;
        $transaction_body['resource']['is_canceled'] = 0;
        $transaction_body['resource']['previous_card_credit'] = $customer->get_resource()->credit;


        $transaction_api = new ApiClient();
        $transaction_api->post('/zazu_transaction/', $transaction_body );








        if ($transaction_api->get_response_code()!=200){
          redirect('/customers/'.$customer_id.'/topup?error='.$transaction_api->get_response_code());
          exit();
        } else {
          redirect('/customers/'.$customer_id.'/topup?success=');
          exit();
        }

      }

    ?>


      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <a href="/customers/<?php echo $customer_id; ?>"><button class="btn btn-info"><?php t('Back'); ?></button></a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="col-12">

              <?php if (isset($_GET['invalid'])){ ?>
                <h5 style="color: #f15050;"><?php t('Invalid Value!'); ?></h5>
              <?php } ?>

              <?php if (isset($_GET['success'])){ ?>
                <h5 style="color: #2cd034;"><?php t('Success!'); ?></h5>
              <?php } ?>

              <?php if (isset($_GET['error'])){ ?>
                <h5 style="color: #f15050;"><?php t('Error!'); echo ' ('.$_GET['error'].')'; ?></h5>
              <?php } ?>

              <form id="topup" method="POST" action="">

                <div class="col-md-12">
                  <input type="number" 
                  pattern="[0-9]{0,15}" 
                  min="-<?php echo $MAX_TOPUP_CREDITS; ?>" max="<?php echo $MAX_TOPUP_CREDITS; ?>" 
                  name="topup" class="form-control form-control-line"
                  value="">
                </div>

                <br>
                <br>

                <div class="col-md-12">
                  <button type="submit" 
                  form="topup" class="btn btn-success"><?php t('Add/Remove');?>
                  </button>
                </div>

              </form>

            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">

            <div class="col-md-12">
              <div class="card">
                <!-- NOTES CONTENT -->
                <h3><?php t('Customer'); ?></h3>
              </div>
            </div>

              <div class="col-md-12">
                <div class="card">
                  <!-- NOTES CONTENT -->
                  <h3><?php t('Card'); ?></h3>
                  <h5><?php echo $customer->get_resource()->card; ?></h5>
                  <h5><?php t('Credits'); ?>: <?php echo $customer->get_resource()->credit; ?></h5>
                </div>
              </div>


          </div>
        </div>
      </div>

<?php require_once 'footer.php'; ?>
