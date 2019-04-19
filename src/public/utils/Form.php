<?php

  // TODO: Add CSRF

  class Form {

    public $inputs;
    public $action;
    public $method;
    public $buttons;

    public function __construct() {
      $this->inputs = '';
      $this->action = '#';
      $this->method = 'POST';
      $this->buttons = '';
    }

    public function set_action($action){ $this->action = $action; }

    public function set_method($method){ $this->method = $method; }

    public function set_inputs($inputs) { $this->inputs = $inputs; }

    public function set_buttons($buttons) { $this->buttons = $buttons; }


    public function include_rich_text_editor(){
      /*?>
      <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
      <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
      <?php*/
    }


    public function show_form() {
      $this->include_rich_text_editor();?>
      <div class="card-body">
          <form  enctype="multipart/form-data" class="form-horizontal form-material" method="<?php echo $this->method; ?>" action="<?php echo $this->action; ?>" >
            <?php foreach($this->inputs as $input){
                    $this->show_field($input);
            } ?>
            <div class="form-group">
                <div class="col-sm-12">
                  <?php if(isset($this->buttons[0])){
                    foreach($this->buttons as $button){
                          $this->show_button($button);
                    }
                  } ?>
                </div>
            </div>

          </form>
      </div>
    <?php
    }



    public function show_field($input) {
      switch($input['type']){
        case 'text':
          $this->show_field_text($input);
          break;
        case 'password':
          $this->show_field_password($input);
          break;
        case 'email':
          $this->show_field_email($input);
          break;
        case 'select' :
          $this->show_field_select($input);
          break;
        case 'date':
          $this->show_field_date($input);
          break;
        case 'select_multiple':
          $this->show_field_select_multiple($input);
          break;
        case 'textarea':
          $this->show_field_textarea($input);
          break;
        case 'file':
          $this->show_field_file($input);
          break;
        case 'hidden':
          $this->show_field_hidden($input);
          break;
      }
    }

    public function show_button($button) {
      switch($button['value']){
        case 'update':
          $this->show_button_update($button);
          break;
        case 'delete':
          $this->show_button_delete($button);
          break;
        case 'create':
          $this->show_button_create($button);
          break;
      }
    }



    /* Field Types */
    // TODO: Add TextArea field

    public function show_field_text($input) { ?>
      <div id="<?php echo $input['name']; ?>_div" class="form-group">
          <label class="col-md-12"><?php echo $input['title']; ?> <?php if( isset($input['required']) && $input['required'] ){ echo '*'; } ?></label>
          <!--<div class="col-md-12">-->
            <div class="input-group">
              <input type="text"
                name="<?php echo $input['name']; ?>"
                id="<?php echo $input['name']; ?>"
                <?php if(isset($input['placeholder'])){ echo 'placeholder="'.$input['placeholder'].'"'; }?>
                value="<?php echo $input['value']; ?>"
               <?php if( isset($input['required']) && $input['required'] ){ echo 'required=""'; } ?>
               class="form-control form-control-line"
               <?php if( isset($input['disabled']) && $input['disabled'] ){ echo 'Disabled '; } ?>
               <?php if(isset($input['pattern'])){ echo 'pattern="'.$input['pattern'].'"'; }?> >
        <?php if(isset($input['button_remove_content'])&&$input['button_remove_content']){ ?>
               <div class="input-group-addon">
                <i class="fa fa-close" onclick="removeValue_<?php echo $input['name']; ?>()"></i>
              </div>
          </div>
          <script>
            function removeValue_<?php echo $input['name']; ?>(){
              document.getElementById("<?php echo $input['name']; ?>").value = " ";
            }
          </script>
        <?php }else{ ?>
            </div>
        <?php } ?>
      </div>
      <?php
    }

    public function show_field_password($input) { ?>
      <div class="form-group">
          <label class="col-md-12"><?php echo $input['title']; ?> <?php if( isset($input['required']) && $input['required'] ){ echo '*'; } ?></label>
            <div class="input-group">
              <input type="password"
              name="<?php echo $input['name']; ?>"
              placeholder="<?php echo $input['placeholder']; ?>"
              <?php if($input['required']){ echo 'required=""'; } ?>
              class="form-control form-control-line"
              <?php if(isset($input['disabled'])){ if($input['disabled']){echo ' Disabled ';}} ?> >
          <?php if(isset($input['button_remove_content'])&&$input['button_remove_content']){ ?>
               <div class="input-group-addon">
                <i class="fa fa-close" onclick="removeValue_<?php echo $input['name']; ?>()"></i>
              </div>
          </div>
          <script>
            function removeValue_<?php echo $input['name']; ?>(){
              document.getElementById("<?php echo $input['name']; ?>").value = " ";
            }
          </script>
        <?php }else{ ?>
          </div>
        <?php } ?>
      </div>
      <?php
    }

    public function show_field_email($input) { ?>
      <div class="form-group">
          <label class="col-md-12"><?php echo $input['title']; ?> <?php if( isset($input['required']) && $input['required'] ){ echo '*'; } ?></label>
            <div class="input-group">
              <input type="email"
              name="<?php echo $input['name']; ?>"
              placeholder="<?php echo $input['placeholder']; ?>"
              <?php if($input['required']){ echo 'required=""'; } ?>
              class="form-control form-control-line"
              <?php if(isset($input['disabled'])){ if($input['disabled']){echo ' Disabled ';}} ?> >
          <?php if(isset($input['button_remove_content'])&&$input['button_remove_content']){ ?>
               <div class="input-group-addon">
                <i class="fa fa-close" onclick="removeValue_<?php echo $input['name']; ?>()"></i>
              </div>
          </div>
          <script>
            function removeValue_<?php echo $input['name']; ?>(){
              document.getElementById("<?php echo $input['name']; ?>").value = " ";
            }
          </script>
        <?php }else{ ?>
          </div>
        <?php } ?>
      </div>
      <?php
    }

    public function show_field_select($input) { ?>
    <div id="<?php echo $input['name']; ?>_div" class="form-group" >
        <label class="col-md-12"><?php echo $input['title']; ?> <?php if( isset($input['required']) && $input['required'] ){ echo '*'; } ?></label>
        <!--<div class="col-md-12">-->
          <div class="input-group">
        <select name="<?php echo $input['name']; ?>"
          id="<?php echo $input['name']; ?>"
          class="form-control custom-select"
          <?php if(isset($input['disabled'])){ if($input['disabled']){echo ' Disabled ';}} ?>
          <?php if(isset($input['onchange'])){ echo 'onchange="' . $input['onchange'] . '"'; } ?>
          <?php if(isset($input['onload'])){ echo 'onload="' . $input['onload'] . '"'; } ?>
          <?php if($input['required']){ echo 'required=""'; } ?>>
            <?php foreach($input['options'] as $option_key=>$option) { ?>
              <option  <?php if($option['selected']){ echo 'selected'; } ?>
                      value="<?php echo $option['value']; ?>"><?php echo $option['title']; ?>
              </option>
            <?php } ?>
        </select>
      </div>
    </div>
    <?php
    }


    public function show_field_date($input) { ?>
      <div class="form-group">
        <div class="col-md-12">
          <label class="control-label"><?php echo $input['title']; ?> <?php if( isset($input['required']) && $input['required'] ){ echo '*'; } ?></label>
          <input name="<?php echo $input['name']; ?>"
          type="date"
          class="form-control"
          placeholder="<?php echo $input['placeholder']; ?>"
          <?php if($input['required']){ echo 'required=""'; } ?>
          value="<?php echo $input['value']; ?>"
          data-date-format="DD-MM-YYYY"
          <?php if(isset($input['disabled'])){ if($input['disabled']){echo ' Disabled ';}} ?> >
      <?php if(isset($input['button_remove_content'])&&$input['button_remove_content']){ ?>
               <div class="input-group-addon">
                <i class="fa fa-close" onclick="removeValue_<?php echo $input['name']; ?>()"></i>
              </div>
          </div>
          <script>
            function removeValue_<?php echo $input['name']; ?>(){
              document.getElementById("<?php echo $input['name']; ?>").value = " ";
            }
          </script>
        <?php }else{ ?>
          </div>
        <?php } ?>  
      </div>
    <?php
    }


    public function show_field_select_multiple($input) { ?>
    <div class="form-group">
        <label class="col-md-12"><?php echo $input['title']; ?> <?php if( isset($input['required']) && $input['required'] ){ echo '*'; } ?></label>
        <div class="col-md-12">
        <select multiple=""
        name="<?php echo $input['name']; ?>"
          class="form-control"
          <?php if(isset($input['disabled'])){ if($input['disabled']){echo ' Disabled ';}} ?>
          <?php if($input['required']){ echo ' required="" '; } ?>>
            <?php foreach($input['options'] as $option) { ?>
              <option  <?php if($option['selected']){ echo 'selected=""'; } ?>
                      value="<?php echo $option['value']; ?>"><?php echo $option['title']; ?>
              </option>
            <?php } ?>
        </select>
      </div>
    </div>
    <?php
    }

    /*public function show_field_textarea($input) { ?>
      <div class="form-group">
          <label class="col-md-12"><?php echo $input['title']; ?></label>
          <div class="col-md-12">
              <textarea
                <?php if(isset($input['rows'])){echo ' rows="'.$input['rows'].'"';}?>
                name="<?php echo $input['name']; ?>"
                placeholder="<?php echo $input['placeholder']; ?>"
                style="width: 100%;"
                <?php if($input['required']){ echo 'required=""'; } ?>
                <?php if(isset($input['disabled'])){ if($input['disabled']){echo 'Disabled ';}} ?>
              ><?php echo $input['value']; ?></textarea>
          </div>
      </div>
      <?php
    }*/
    public function show_field_textarea($input) { ?>
      <link rel="stylesheet" href="/assets/css/lib/html5-editor/bootstrap-wysihtml5.css" />
      <div class="form-group">
          <label class="col-md-12"><?php echo $input['title']; ?> <?php if( isset($input['required']) && $input['required'] ){ echo '*'; } ?></label>
          <div class="col-md-12">
              <textarea
                <?php if(isset($input['rows'])){echo ' rows="'.$input['rows'].'"';}?>
                class="textarea_editor"
                name="<?php echo $input['name']; ?>"
                id="id_<?php echo $input['name']; ?>"
                placeholder="<?php echo $input['placeholder']; ?>"
                style="width: 100%;"
                <?php if($input['required']){ echo 'required=""'; } ?>
                <?php if(isset($input['disabled'])){ if($input['disabled']){echo 'Disabled ';}} ?>
              ><?php echo str_replace('\"', '"', $input['value']); ?></textarea>
          </div>
      </div>
      <?php
    }

    public function show_field_file($input) { ?>
      <div class="form-group">
          <label class="col-md-12"><?php echo $input['title']; ?> <?php if( isset($input['required']) && $input['required'] ){ echo '*'; } ?></label>
          <?php if(isset($input['preview'])){ ?><div class="col-md-6">
              <img src="data:image/jpeg;base64, <?php echo $input['preview']; ?>" style="height: 100px; width: auto;">
          </div><?php } ?>
          <div class="col-md-5">
              <input
                type="file"
                name="<?php echo $input['name']; ?>"
                <?php if($input['required']){ echo 'required=""'; } ?>
                <?php if(isset($input['disabled'])){ if($input['disabled']){echo 'Disabled ';}} ?> >
          </div>
      </div>
      <?php
    }

    public function show_field_hidden($input) { ?>
              <input type="hidden" name="<?php echo $input['name']; ?>"
               value="<?php echo $input['value']; ?>" >
      <?php
    }




    /* Button Types */

    public function show_button_update($button){ ?>
      <button type="submit" name="form-send" value="update" class="btn btn-success"><?php echo $button['title']; ?></button>
      <?php
    }

    public function show_button_create($button){ ?>
      <button type="submit" name="form-send" value="create" class="btn btn-success"><?php echo $button['title']; ?></button>
      <?php
    }

    public function show_button_delete($button){ ?>
      <button type="submit" name="form-send" value="delete" class="btn btn-warning"><?php echo $button['title']; ?></button>
      <?php
    }




}
 ?>
