<?php

class Modal{
	
	public $name;
	public $content;
	public $title;

	public function __construct() {
		$this->name = '';
		$this->content = '';
		$this->title = '';
	}

	public function set_content($content) {$this->content = $content;}

	public function set_name($name) {$this->name = $name;}

	public function set_title($title) {$this->title = $title;}

	public function show() {;}

	public function show_button($button){ ?>
		


		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_<?php echo $this->name; ?>">
          <?php echo $button; ?>
        </button>

        <script>
        	// Fix modal hide
			function hideModal_<?php echo $this->name; ?> (){
				//alert('why');
				//$('#modal_<?php echo $this->name; ?>').modal('hide');
				/*jQuery('.modal-backdrop').click();
				$('#modal_<?php echo $this->name; ?>').hide();
				$('.modal-backdrop').hide();*/
				location.reload();
			}
		</script>

		<!-- Modal -->
		<div class="modal fade" id="modal_<?php echo $this->name; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $this->title; ?></h5>
		        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">esgrtfg</span>
		        </button>-->
		      </div>
		      <div class="modal-body">
		        <?php $this->content; ?>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="hideModal_<?php echo $this->name; ?>()">Close</button>
		        <!--<button type="button" class="btn btn-secondary">Save changes</button>-->
		      </div>
		    </div>
		  </div>
		</div>


	<?php }


}

?>