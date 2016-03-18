<div class="row">
	<div class="col-md-12">
		<div class="grid simple">
			<div class="grid-title no-border">
				<h4>
					اضافه کردن پیج جدید برای جمع اوری عکس 
					<span class="semi-bold"></span>
				</h4>
				<div class="tools">
					<a href="javascript:;" class="collapse"></a>
					<a href="#grid-config" data-toggle="modal" class="config"></a>
					<a href="javascript:;" class="reload"></a>
					<a href="javascript:;" class="remove"></a>
				</div>
			</div>
			<div class="grid-body no-border">
				<?php echo $this->Form->create(''); ?>
					<div class="form-group">
						<label class="form-label">userName :</label>
						<span class="help">e.g. "moharamiamir"</span>
						<div class="input-with-icon  right"> <i class=""></i>
							<input type="text" name="user_name" id="form1Amount" class="form-control"></div>
					</div>
					<div class="form-group">
					<?php						
						echo $this->Form->input(
						    'field_id',
						    array('options' => $fields,'class'=>'form-control input-lg m-bot15')
						); 
					?>
					</div>
					<div class="form-actions">

						<div class="pull-right">
							<button type="submit" class="btn btn-success btn-cons">
								<i class="icon-ok"></i>
								<?php echo $this->Form->end('add'); ?>
							</button>
							<button type="button" class="btn btn-white btn-cons">Cancel</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>	
</div>