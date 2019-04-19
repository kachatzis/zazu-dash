<?php require_once 'header.php'; ?>


<div class="row">
  <div class="col-md-6">
    <div class="card">

<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';

      if($credit_offer_id>0){

        // Edit Form

        $api = new ApiClient();
        $api->get_row('/zazu_credit_offer/', $credit_offer_id);

        redirect('/products/'.$api->get_resource()->good_id.'/credit_offers/'.$credit_offer_id);


      } else {

        // Create Form
        // Not Available Directly from here. Only from specific product page.
        redirect('/credit_offers');

      }

?>

    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <!-- NOTES CONTENT -->
      <h3>Card Level</h3>
    </div>
  </div>
</div>


<?php require_once 'footer.php'; ?>
