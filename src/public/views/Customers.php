<?php require_once 'header.php'; ?>


<?php

  /*if (isset($_GET['post_table_filter'])){
    $customer_filter = $_GET['post_table_filter'];
  } else {*/

    // TODO: Add parameter cheking and string striping before using those.
    $customer_filter = '';
    if($_SERVER['REQUEST_METHOD']=='GET'){

      if(isset($_GET['string_search'])&&strlen($_GET['string_search'])>0){
        $string_search = $_GET['string_search'];
      }
      if(isset($_GET['level'])&&strlen($_GET['level'])>0){
        $level = $_GET['level'];
      }
      if(isset($_GET['registration_store'])&&strlen($_GET['registration_store'])>0){
        $registration_store = $_GET['registration_store'];
      }
      if(isset($_GET['min_registration_date'])&&strlen($_GET['min_registration_date'])>0){
        $min_registration_date = $_GET['min_registration_date'];
      }
      if(isset($_GET['max_registration_date'])&&strlen($_GET['max_registration_date'])>0){
        $max_registration_date = $_GET['max_registration_date'];
      }
      if(isset($_GET['min_last_visit_date'])&&strlen($_GET['min_last_visit_date'])>0){
        $min_last_visit_date = $_GET['min_last_visit_date'];
      }
      if(isset($_GET['max_last_visit_date'])&&strlen($_GET['max_last_visit_date'])>0){
        $max_last_visit_date = $_GET['max_last_visit_date'];
      }
      if(isset($_GET['min_credit'])&&strlen($_GET['min_credit'])>0){
        $min_credit = $_GET['min_credit'];
      }
      if(isset($_GET['max_credit'])&&strlen($_GET['max_credit'])>0){
        $max_credit = $_GET['max_credit'];
      }
      if(isset($_GET['card'])&&strlen($_GET['card'])>0){
        $card = $_GET['card'];
      }
      if(isset($_GET['sms_marketing'])&&strlen($_GET['sms_marketing'])>0){
        $sms_marketing = $_GET['sms_marketing'];
      }





      if(isset($string_search)){
        $customer_filter .= '((first_name%20LIKE%20'.urlencode( '%'.$string_search.'%').')or(last_name%20LIKE%20'.urlencode('%'.$string_search.'%').')or(phone_mobile%20LIKE%20'.urlencode('%'.$string_search.'%').')or(phone_home%20LIKE%20'.urlencode('%'.$string_search.'%').')or(email1%20LIKE%20'.urlencode('%'.$string_search.'%').')or(email2%20LIKE%20'.urlencode('%'.$string_search.'%').'))and';
      }
      if(isset($level)){
        $customer_filter .= '(card_level_id='.$level.')and';
      }
      if(isset($registration_store)){
        $customer_filter .= '(registration_store_id='.$registration_store.')and';
      }
      if(isset($min_registration_date)){
        $customer_filter .= '(registration_date>='.$min_registration_date.')and';
      }
      if(isset($max_registration_date)){
        $customer_filter .= '(registration_date<='.$max_registration_date.')and';
      }
      if(isset($min_last_visit_date)){
        $customer_filter .= '(last_visit_date>='.$min_last_visit_date.')and';
      }
      if(isset($max_last_visit_date)){
        $customer_filter .= '(last_visit_date<='.$max_last_visit_date.')and';
      }
      if(isset($min_credit)){
        $customer_filter .= '(credit>='.$min_credit.')and';
      }
      if(isset($max_credit)){
        $customer_filter .= '(credit<='.$max_credit.')and';
      }
      if(isset($sms_marketing)){
        $customer_filter .= '(send_sms='.$sms_marketing.')and';
      }
      if(isset($card)){
        $customer_filter .= '(card='.$card.')and';
      }

      $customer_filter .= '(_)';
      $customer_filter = str_replace(  '(_)', '', str_replace('and(_)', '', $customer_filter));


      // Sorting
      if (isset($_GET['sort']) && isset($_GET['sort_verb']) && strlen($_GET['sort'])>0 && ($_GET['sort_verb']=='ASC'||$_GET['sort_verb']=='DESC')){
          $sort = $_GET['sort'];
          $sort_verb = $_GET['sort_verb'];

          switch($sort){
            case 'last_visit_date':
            case 'card':
            case 'last_name':
            case 'registration_date':
              $customer_filter .= '&order='.$sort.'%20'.$sort_verb;
              break;
            default:
              break;
          }
      }

    }

  /*}*/
?>

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="row">
          <div class="col-md-3">
            <a href="/customers/0"><button type="submit" class="btn btn-success"><?php t('Create');?></button></a>
          </div>
          <br>
        </div>
        <div class="row">
          <div class="col-md-12">

                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#info" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down"><?php t('Info'); ?></span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#level" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down"><?php t('Level'); ?></span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#marketing" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down"><?php t('Marketing'); ?></span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#registration" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down"><?php t('Registration'); ?></span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#card" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down"><?php t('Card'); ?></span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#visits" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down"><?php t('Visits'); ?></span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#sort" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down"><?php t('Sort'); ?></span></a> </li>
                    &nbsp;&nbsp;<button type="submit" form="search_form" class="btn btn-default"><?php t('Search');?></button>
                    &nbsp;&nbsp;<button type="submit" form="clear_form" class="btn btn-default"><?php t('Clear');?></button>

                  </ul>

                  <form id="clear_form" method="GET" action="?">
                  </form>
                  <!-- Tab panes -->
                  <form id="search_form" action="?page=1" method="GET">
                    <div class="tab-content">

                      <div class="tab-pane active" id="info" role="tabpanel">
                        <div class="p-20">
                          <div class="row">
                            <div class="col-md-6">
                              <p><?php t('Name/Phone/Email'); ?></p>
                              <input class="form-control form-control-line" type="text" name="string_search"
                              <?php if(isset($_GET['string_search'])){ echo 'value="'.$_GET['string_search'].'"' ;} ?>>
                            </div>
                            <div class="col-md-6">
                              <p><?php /*t('Help: Use <b>%</b> to search for containing characters. '); */ ?></p>
                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="tab-pane" id="level" role="tabpanel">
                        <div class="p-20">
                          <div class="row">
                            <div class="col-md-6">
                              <p><?php t('Select level'); ?></p>
                              <select name="level" class="form-control form-control-line">
                                <?php
                                  $api_level = new ApiClient();
                                  $api_level->get_filter('/zazu_card_level/', '(is_enabled=1)');
                                  if($api_level->get_response_code()==200){
                                    echo '<option value=""> - </option>';
                                    foreach($api_level->get_resource() as $row=>$level_row){ ?>
                                      <option
                                        <?php if(isset($_GET['level']) && $_GET['level']==$level_row->card_level_id) echo 'selected'; ?>
                                        value="<?php echo $level_row->card_level_id; ?>"><?php echo $level_row->title; ?></option>
                                    <?php }
                                  }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane" id="marketing" role="tabpanel">
                        <div class="p-20">
                          <div class="row">
                            <div class="col-md-6">
                              <p><?php t('Receives SMS Marketing'); ?></p>
                              <select name="sms_marketing" class="form-control form-control-line">
                                  <option value=""> - </option>';
                                  <option
                                    <?php if(isset($_GET['sms_marketing']) && $_GET['sms_marketing']=='1') echo 'selected'; ?>
                                    value="1"><?php t('Yes'); ?></option>
                                  <option
                                    <?php if(isset($_GET['sms_marketing']) && $_GET['sms_marketing']=='0') echo 'selected'; ?>
                                    value="0"><?php t('No'); ?></option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane " id="registration" role="tabpanel">
                        <div class="p-20">
                          <div class="row">

                            <div class="col-md-6">
                              <p><?php t('Store'); ?></p>
                              <select name="registration_store" class="form-control form-control-line">
                                  <?php
                                    $api_store = new ApiClient();
                                    $api_store->get_filter('/zazu_store/', '(is_enabled=1)');
                                    if($api_store->get_response_code()==200){
                                      echo '<option value=""> - </option>';
                                      foreach($api_store->get_resource() as $row=>$store_row){ ?>
                                        <option
                                          <?php if(isset($_GET['registration_store']) && $_GET['registration_store']==$store_row->store_id) echo 'selected'; ?>
                                          value="<?php echo $store_row->store_id; ?>"><?php echo $store_row->name.' ('.$store_row->code.')'; ?></option>
                                      <?php }
                                    }
                                  ?>
                              </select>
                            </div>

                            <div class="col-md-6">
                              <p><?php t('Date'); ?>:</p>
                                  <p><?php t('From'); ?>:</p>
                                  <input type="date" name="min_registration_date" class="form-control form-control-line"
                                    value="<?php if(isset($_GET['min_registration_date']) && $_GET['min_registration_date']!='') echo $_GET['min_registration_date']; ?>">
                                  <p><?php t('To'); ?>:</p>
                                  <input type="date" name="max_registration_date" class="form-control form-control-line"
                                    value="<?php if(isset($_GET['max_registration_date']) && $_GET['max_registration_date']!='') echo $_GET['max_registration_date']; ?>">

                            </div>

                          </div>
                        </div>
                      </div>

                      <div class="tab-pane" id="card" role="tabpanel">
                        <div class="p-20">
                          <div class="row">

                            <div class="col-md-6">
                              <p><?php t('Credits'); ?>:</p>
                              <p><?php t('From'); ?>:</p>
                                  <input type="number" pattern="[0-9]{0,10}" min="0" max="9999999" name="min_credit" class="form-control form-control-line"
                                    value="<?php if(isset($_GET['min_credit']) && $_GET['min_credit']!='') echo $_GET['min_credit']; ?>">
                            </div>

                            <div class="col-md-6">
                              <p>&nbsp;</p>
                                  <p><?php t('To'); ?>:</p>
                                  <input type="number" pattern="[0-9]{0,10}"  min="0" max="9999999" name="max_credit" class="form-control form-control-line"
                                    value="<?php if(isset($_GET['max_credit']) && $_GET['max_credit']!='') echo $_GET['max_credit']; ?>">

                            </div>

                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <p><?php t('Card'); ?>:</p>
                                  <input type="number" pattern="[0-9]{0,15}" min="0" max="999999999" name="card" class="form-control form-control-line"
                                    value="<?php if(isset($_GET['card']) && $_GET['card']!='') echo $_GET['card']; ?>">
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane " id="visits" role="tabpanel">
                        <div class="p-20">
                          <div class="row">

                            <div class="col-md-6">
                              <p><?php t('Last visit'); ?>:</p>
                                  <p><?php t('From'); ?>:</p>
                                  <input type="date" name="min_last_visit_date" class="form-control form-control-line"
                                    value="<?php if(isset($_GET['min_last_visit_date']) && $_GET['min_last_visit_date']!='')
                                            echo $_GET['min_last_visit_date']; ?>">
                                  <p><?php t('To'); ?>:</p>
                                  <input type="date" name="max_last_visit_date" class="form-control form-control-line"
                                    value="<?php if(isset($_GET['max_last_visit_date']) && $_GET['max_last_visit_date']!='')
                                            echo $_GET['max_last_visit_date']; ?>">

                            </div>

                          </div>
                        </div>
                      </div>


                      <div class="tab-pane" id="sort" role="tabpanel">
                        <div class="p-20">
                          <div class="row">
                            <div class="col-md-6">

                              <select name="sort" class="form-control form-control-line">
                                  <?php
                                    $sorting_options = ['last_visit_date'=>'Last Visit', 'registration_date'=>'Registration Date', 'card'=>'Card', 'last_name'=>'Name'];
                                  ?>
                                  <option value=""> - </option>
                                  <?php foreach($sorting_options as $sorting_option=>$sorting_option_title){ ?>
                                      <option
                                    <?php if(isset($_GET['sort'])&&$_GET['sort']==$sorting_option) echo 'selected'; ?>
                                    value="<?php echo $sorting_option; ?>"><?php t($sorting_option_title); ?></option>
                                  <?php } ?>
                              </select>

                            </div>
                            <div class="col-md-6">

                              <select name="sort_verb" class="form-control form-control-line">
                                  <?php
                                    $sorting_verbs = ['ASC'=>'Ascending', 'DESC'=>'Descending'];
                                  ?>
                                  <option value=""> - </option>
                                  <?php foreach($sorting_verbs as $sorting_verb=>$sorting_verb_title){ ?>
                                      <option
                                    <?php if(isset($_GET['sort_verb'])&&$_GET['sort_verb']==$sorting_verb) echo 'selected'; ?>
                                    value="<?php echo $sorting_verb; ?>"><?php t($sorting_verb_title); ?></option>
                                  <?php } ?>
                              </select>

                            </div>
                          </div>
                        </div>
                      </div>


                    </div>
                  </form>

          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
      <?php

        require_once 'utils/BigTable.php';
        require_once 'utils/ApiClient.php';
        $rows = 100;

        $page = 1;
        if (isset($_GET['page'])){
          $page = $_GET['page'];
        }
        $offset = ($page - 1) * $rows;

        //var_dump( $customer_filter );

        $api = new ApiClient();
        $api->get_count('/zazu_customer/',
          $customer_filter .
          ''
        );
        //var_dump( $api->get_results_count() );
        $count = $api->get_results_count();

        //var_dump( $api->get_response_json() );
        //var_dump( $count );

        if ($count>0){
          $api = new ApiClient();
          $api->get_filter('/zazu_customer/',
            $customer_filter .
            '&fields=customer_id,credit,first_name,last_name,registration_store_id,phone_mobile,city,registration_date,card,last_visit_date&limit='.$rows.'&offset='.$offset
          );
        }

        //var_dump( $api->get_response_json() );

        if($api->get_response_code()!=200){ ?>
          <h2>Error! No data received (<?php echo $api->get_response_code(); ?>)</h2>
        <?php } else {
          if (!$api->get_results_count()>0) { ?>
            <h2><?php t('No results found!');?> (<?php echo $api->get_response_code(); ?>)</h2>
          <?php } else {
            $table = new BigTable();
            $table->set_table_name(tr('Customers'));
            $table->set_action_uri('/customers/');
            $table->set_action_column('customer_id');
            $table->set_body_array($api->get_resource());
            $table->set_columns(8);
            $table->set_page($page);
            $table->set_count($count);
            $table->set_get_table_filter($customer_filter);
            $table->set_rows_per_page($rows);
            $table->set_custom_columns([
              /*8=>['header'=>'registration_store', 'title'=>tr('Registration Store'), 'id_field'=>'registration_store_id', 'api_uri'=>'/zazu_store/', 'resource_field'=>'name']*/
            ]);
            $table->set_header_array(
              [1=>'first_name', 2=>'last_name', 3=>'phone_mobile', 4=>'city',5=>'registration_date',6=>'card', 7=>'credit', 8=>'last_visit_date']);
            $table->set_header_titles(
              [1=>tr('First Name'), 2=>tr('Last Name'), 3=>tr('Mobile Phone'), 4=>tr('City'), 5=>tr('Registration'), 6=>tr('Card'), 7=>tr('Credits'), 8=>tr('Last Visit')]);
            $table->set_tr_action(true);
            $table->make_table();
          }
        }

      ?>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>
