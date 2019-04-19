<?php require_once 'header.php'; ?>
<?php /* TODO: Fix This page. Maybe no custom form is needed, using a form-beform-the-form asking
      ** for credit or money type of achievement, and making the type not editable */ ?>

<div class="row">
  <div class="col-md-6">
    <div class="card">
<?php

      require_once 'utils/Form.php';
      require_once 'utils/ApiClient.php';
      require_once 'utils/PostHandler.php';

      if($achievement_id>0){
        /* Edit */

        $api = new ApiClient();
        $api->get('/zazu_achievement/', $achievement_id);
        foreach($api->get_resource() as $resource){
          $api_resource = $resource;
        }

        // Edit / Custom Form
        ?>


        <div class="card-body">
            <form class="form-horizontal form-material" method="POST" action="#">

                  <div class="form-group">
                      <label class="col-md-12"><?php t('Achievement');?></label>
                      <div class="col-md-12">
                          <input type="text"
                            name="title"
                            placeholder="Achievement"
                            value="<?php echo $api_resource->title; ?>"
                            required=""
                            class="form-control form-control-line">
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-md-12"><?php t('Type');?></label>
                      <div class="col-md-12">
                      <select name="temp_achievement_type"
                        class="form-control custom-select"
                        required="" >
                            <option <?php if($api_resource->is_min_previous_credits) echo 'selected'; ?>
                                    value="is_min_previous_credits"> <?php t('Minimum Previous Credits');?>
                            </option>
                            <option <?php if($api_resource->is_min_money_spent) echo 'selected'; ?>
                                    value="is_min_money_spent"> <?php t('Minimum Money Spent');?>
                            </option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                      <label class="col-md-12"><?php t('Value');?></label>
                      <div class="col-md-12">
                          <input type="text"
                            name="temp_value"
                            placeholder="Value"
                            value="<?php if($api_resource->is_min_previous_credits) {echo $api_resource->min_credits;} else {echo $api_resource->min_money;} ?>"
                            required=""
                            class="form-control form-control-line">
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-md-12"><?php t('Position');?></label>
                      <div class="col-md-12">
                          <input type="text"
                            name="position"
                            placeholder="Position"
                            value=<?php echo $api_resource->position; ?>
                            required=""
                            class="form-control form-control-line">
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-md-12"><?php t('Opening Card Level');?></label>
                      <div class="col-md-12">
                      <select name="card_level"
                        class="form-control custom-select"
                        required="" >
                        <?php
                        $api_get_card_levels = new ApiClient();
                        $api_get_card_levels->get_filter('/zazu_card_level/', '(card_level_id='.$api_resource->card_level_id.')&limit=1');
                        foreach($api_get_card_levels->get_resource() as $level_key=>$level_row){?>
                          <option selected
                                  value="<?php echo $level_row->card_level_id; ?>"> <?php echo $level_row->title; ?>
                          </option>
                        <?php } ?>
                        <?php
                        $api_get_card_levels = new ApiClient();
                        $api_get_card_levels->get_filter('/zazu_card_level/', '(card_level_id!='.$api_resource->card_level_id.')');
                        foreach($api_get_card_levels->get_resource() as $level_key=>$level_row){?>
                          <option
                                  value="<?php echo $level_row->card_level_id; ?>"> <?php echo $level_row->title; ?>
                          </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                      <label class="col-md-12">Enabled</label>
                      <div class="col-md-12">
                      <select name="is_enabled"
                        class="form-control custom-select"
                        required="" >
                            <option <?php if($api_resource->is_enabled) echo 'selected'; ?>
                                    value="1"> Yes
                            </option>
                            <option <?php if(!$api_resource->is_enabled) echo 'selected'; ?>
                                    value="0"> No
                            </option>
                      </select>
                    </div>
                  </div>

                  <button type="submit" name="form-send" value="update" class="btn btn-success">Save</button>
                  <button type="submit" name="form-send" value="delete" class="btn btn-alert">Delete</button>
                </form>
            </div><!-- End Cardboard body of edit form -->



        <?php
        if(isset( $_POST['form-send'] )){
          if($_POST['form-send']=='delete'){
            // Delete

            $api = new ApiClient();
            $api->delete('/zazu_achievement/', $achievement_id);
            redirect('/achievements');

          } else {
            // Update

            // Create Custom Handling
            $temp_min_credits=0;
            $temp_min_money=0;
            if(isset( $_POST['title'] ) && isset( $_POST['temp_achievement_type'] ) && isset( $_POST['temp_value'] )){
              // Create Handling
              if($_POST['temp_achievement_type']=='is_min_previous_credits'){
                $temp_min_credits=$_POST['temp_value'];
              }else{
                $temp_min_money=$_POST['temp_value'];
              }

              $post_body = [
                'resource'=>[
                  'title'=>$_POST['title'],
                  'min_credits'=>$temp_min_credits,
                  'min_money'=>$temp_min_money,
                  'is_min_previous_credits'=>(int)$_POST['temp_achievement_type']=='is_min_previous_credits',
                  'is_min_previous_money'=>(int)$_POST['temp_achievement_type']=='is_min_previous_money',
                  'is_enabled'=>(int)$_POST['is_enabled'],
                  'card_level_id'=>$_POST['card_level'],
                  'position'=>$_POST['position']
                ],
                'ids'=>[$achievement_id]
              ];

              $api = new ApiClient();
              $api->patch('/zazu_achievement/', $post_body);
              redirect('/achievements/'.$achievement_id);

            }else{
              // Break Handling
            }
          }
        }





        /* End: Edit */
      } else {
        /* Create */





        // Create / Custom Form
        ?>


        <div class="card-body">
            <form class="form-horizontal form-material" method="POST" action="#">


                  <div class="form-group">
                      <label class="col-md-12">Achievement</label>
                      <div class="col-md-12">
                          <input type="text"
                            name="title"
                            placeholder="Achievement"
                            value=""
                            required=""
                            class="form-control form-control-line">
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-md-12">Type</label>
                      <div class="col-md-12">
                      <select name="temp_achievement_type"
                        class="form-control custom-select"
                        required="" >
                            <option selected
                                    value="is_min_previous_credits"> Minimum Previous Credits
                            </option>
                            <option
                                    value="is_min_money_spent"> Minimum Money Spent
                            </option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                      <label class="col-md-12">Value</label>
                      <div class="col-md-12">
                          <input type="text"
                            name="temp_value"
                            placeholder="Value"
                            value=""
                            required=""
                            class="form-control form-control-line">
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-md-12">Position</label>
                      <div class="col-md-12">
                          <input type="text"
                            name="position"
                            placeholder="Position"
                            value=""
                            required=""
                            class="form-control form-control-line">
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-md-12">Opening Card Level</label>
                      <div class="col-md-12">
                      <select name="card_level"
                        class="form-control custom-select"
                        required="" >
                        <?php
                        $api_get_card_levels = new ApiClient();
                        $api_get_card_levels->get_filter('/zazu_card_level/', '(is_enabled=1)');
                        foreach($api_get_card_levels->get_resource() as $level_key=>$level_row){?>
                          <option
                                  value="<?php echo $level_row->card_level_id; ?>"> <?php echo $level_row->title; ?>
                          </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                      <label class="col-md-12">Enabled</label>
                      <div class="col-md-12">
                      <select name="is_enabled"
                        class="form-control custom-select"
                        required="" >
                            <option selected
                                    value="1"> Yes
                            </option>
                            <option
                                    value="0"> No
                            </option>
                      </select>
                    </div>
                  </div>

                  <button type="submit" name="form-send" value="create" class="btn btn-success">Create</button>

                </form>
            </div> <!-- End: Card Body of creation form -->





        <?php

        // Create Custom Handling
        $temp_min_credits=0;
        $temp_min_money=0;
        if(isset( $_POST['title'] ) && isset( $_POST['temp_achievement_type'] ) && isset( $_POST['temp_value'] )){
          // Create Handling
          if($_POST['temp_achievement_type']=='is_min_previous_credits'){
            $temp_min_credits=$_POST['temp_value'];
          }else{
            $temp_min_money=$_POST['temp_value'];
          }

          $post_body = [
            'resource'=>[
              'title'=>$_POST['title'],
              'min_credits'=>$temp_min_credits,
              'min_money'=>$temp_min_money,
              'is_min_previous_credits'=>(int)$_POST['temp_achievement_type']=='is_min_previous_credits',
              'is_min_previous_money'=>(int)$_POST['temp_achievement_type']=='is_min_previous_money',
              'is_enabled'=>(int)$_POST['is_enabled'],
              'card_level_id'=>$_POST['card_level'],
              'position'=>$_POST['position']
            ]
          ];

          $api = new ApiClient();
          $api->set_id_name('achievement_id');
          $api->post('/zazu_achievement/', $post_body);
          $id = $api->get_id();
          if($id>0){
            redirect('/achievements/'.$id);
          }else{
            redirect('/zazu_achievement/0');
          }
        }else{
          // Break Handling
        }
        ?>




      <?php    /* End: Create */
      }
      ?>

    </div> <!-- End: Card -->
  </div><!-- End: column -->
  <div class="col-md-3">
    <div class="card">
      <!-- NOTES CONTENT -->
      <h3>Achievement</h3>
    </div>
  </div>

<?php require_once 'footer.php'; ?>
