<?php require_once 'header.php'; ?>

<div class="row">
  <div class="col-md-12">
    <div class="card">

      <?php

      require_once 'utils/ApiClient.php';

      $api_customer = new ApiClient();
      $api_customer->get_filter('/zazu_customer/', '(is_enabled=1)and(customer_id='.$customer_id.')&limit=1');
      if ($api_customer->get_results_count()<1){
        redirect('/customers/'.$customer_id);
      }

      $level_id = $api_customer->get_resource()[0]->card_level_id;
      $credit = $api_customer->get_resource()[0]->credit;

      $api_gifts = new ApiClient();
      $api_gifts->get_filter('/zazu_good/', '(is_gift=1)and(is_enabled=1)and(card_level_id='.$level_id.')and(credits_cost<='.$credit.')&fields=good_id,credits_cost,title');

      ?>



      <?php if (isset($_GET['success'])){ ?>
        <div class="col-md-9">
          <h3><?php t('Transaction Successful'); ?>!</h3>
        </div>
        <br>
        <a href="/customers/<?php echo $customer_id; ?>"><button type="submit" class="btn btn-success"><?php t('Back');?></button></a>
      <?php } ?>


      <?php if (isset($_GET['error'])){ ?>
        <div class="col-md-9">
          <h3><?php t('Transaction Failed'); ?>!</h3>
        </div>
      <?php } ?>





      <?php
      if($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['success'])){
        ?>


        <a href="/customers/<?php echo $customer_id; ?>"><button type="submit" class="btn btn-success"><?php t('Back');?></button></a>
          <br>

        <?php

        foreach($api_gifts->get_resource() as $key=>$gift){
          ?>

          <form action="" method="POST">
            <input type="hidden" name="validate_single_gift" value="">  
            <input type="hidden" name="gift" value="<?php echo $gift->good_id; ?>">
            <div class="col-md-9">
              <div class="row">
                <div class="col-md-9">
                  <div class="row">
                    <?php echo $gift->title; ?>
                  </div>
                  <div class="row">
                    <div><?php echo tr('Credits') .': '; ?></div>
                    <div><?php echo $gift->credits_cost; ?></div>
                  </div>
                </div>
                <div class="col-md-3">
                  <button type="submit" class="btn btn-success">  <?php t('Select');?></button>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              
            </div>
          </form>
          <br>
          <?php
        }
        ?>


        <br>
        <div class="col-md-9"></div>
        <div class="col-md-3">
          <div class="row">
            <div><?php echo tr('Customer Credits') . ': '; ?></div>
            <div><?php echo $credit; ?></div>
          </div>
        </div>
      <?php } ?>


      <?php if (isset($_POST['validate_single_gift'])){ ?>
        <?php
        $gift_found = false;
        foreach($api_gifts->get_resource() as $key=>$gift){
          if($_POST['gift']==$gift->good_id){
            $selected_gift = $gift;
            $gift_found = true;
          }
        }
        if (!$gift_found) {
              redirect('/customers/'.$customer_id.'/redemption');
        }

        ?>
        <br>
        <div class="col-md-9">
          <form action="" method="POST">
            <input type="hidden" name="make_transaction_single_gift" value="">
            <input type="hidden" name="gift" value="<?php echo $selected_gift->good_id; ?>">
          <button type="submit" class="btn btn-success"><?php t('Redeem Gift');?></button>
        </div>
        <br>
        <div class="col-md-3">
          <div class="row">
            <h3><?php echo $selected_gift->title; ?></h3>
          </div>
          <div class="row">
            <div><?php echo tr('Customer Credits') . ': '; ?></div>
            <div><?php echo $credit; ?></div>
          </div>
          <div class="row">
            <div><?php echo tr('Transaction Credits') . ': '; ?></div>
            <div><?php echo $selected_gift->credits_cost; ?></div>
          </div>
        </div>
      <?php } ?>






      <?php if (isset($_POST['make_transaction_single_gift'])){ ?>
        <?php
        $gift_found = false;
        foreach($api_gifts->get_resource() as $key=>$gift){
          if($_POST['gift']==$gift->good_id){
            $selected_gift = $gift;
            $gift_found = true;
          }
        }
        if (!$gift_found) {
              redirect('/customers/'.$customer_id.'/redemption');
        }

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
        $transaction_body['resource']['device_id'] = $api_device->device_id;
        $transaction_body['resource']['customer_id'] = $customer_id;
        $transaction_body['resource']['manager_id'] = $_SESSION['manager_id'];
        $transaction_body['resource']['money_spent'] = 0;
        $transaction_body['resource']['request_date'] = date('Y-m-d');
        $transaction_body['resource']['request_time'] = date('H:i:s');
        $transaction_body['resource']['transaction_date'] = date('Y-m-d');
        $transaction_body['resource']['transaction_time'] = date('H:i:s');
        $transaction_body['resource']['credit'] = -$selected_gift->credits_cost;
        $transaction_body['resource']['is_processed'] = 0;
        $transaction_body['resource']['is_canceled'] = 0;
        $transaction_body['resource']['previous_card_credit'] = $api_customer->get_resource()[0]->credit;


        $transaction_api = new ApiClient();
        $transaction_api->post('/zazu_transaction/', $transaction_body );

        if ($transaction_api->get_response_code()!=200){
          redirect('/customers/'.$customer_id.'/redemption?error=transaction_failed');
        }
        //var_dump($transaction_api);

        $good_body = [];
        $good_body['resource']['transaction_id'] = $transaction_api->get_resource()->transaction_id;
        $good_body['resource']['cost'] = 0;
        $good_body['resource']['is_canceled'] = 0;
        $good_body['resource']['is_processed'] = 0;
        $good_body['resource']['good_id'] = $selected_gift->good_id;
        $good_body['resource']['customer_id'] = $customer_id;

        $good_api = new ApiClient();
        $good_api->post('/zazu_transaction_good/', $good_body);
        //var_dump($good_body);
        //var_dump($good_api);

        if($good_api->get_response_code()!=200){
          redirect('/customers/'.$customer_id.'/redemption?error=good_transaction_failed');
        }

        redirect('/customers/'.$customer_id.'/redemption?success=&transaction='.$transaction_api->get_resource()->transaction_id);
        

        ?>
        <br>
        <div class="col-md-9">
          <p>Please wait while the transaction is being processed...</p>
        </div>
      <?php } ?>



    </div>
  </div>
</div>



<?php require_once 'footer.php'; ?>