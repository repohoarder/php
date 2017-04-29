<?php
$page_types = array('hosting','partner','domain');
?>

<div class="row-fluid">
	<div class="span12">
		<h3 class="heading">Add Funnel Page</h3>
		<div class="row-fluid">
			<div class="span8">
				<form class="form-horizontal" id="loginsave_form" method="post" action="<?php echo $this->config->item('subdir'); ?>/funnels/create">
					<fieldset>
					<div class="control-group formSep">
							<label class="control-label">&nbsp;</label>
							<div class="controls text_line" id="errorloginsave">
								<strong><?php echo $error;?></strong>
							</div>
						</div>
						
						<div class="control-group formSep">
							<label for="name" class="control-label"> Name</label>
							<div class="controls">
								<input type="text" id="name" name="name" class="input-xlarge" value="<?php echo (isset($page['name'])) ? $page['name']:'';?>" />
							</div>
						</div>
						
						<div class="control-group formSep">
							<label for="type" class="control-label">Type</label>
							<div class="controls">
								<select id="type"  name="funnel_type">
									<?php 
										$page_type = isset($page['funnel_type']) ? $page['funnel_type'] :'';
										foreach ($page_types as $key) :

											$c = $page_type == $key ? ' selected="selected"':'';
											echo "<option value='$key'$c>$key</option>";

										endforeach;
									?>
								</select>
							</div>
						</div>
						<div class="control-group formSep">
							<label for="slug" class="control-label">Default Page ID</label>
							<div class="controls">
								<input type="text" id="default_page_id" name="default_page_id" class="input-xlarge" value="<?php echo (isset($page['default_page_id'])) ? $page['default_page_id']:'';?>" />
							</div>
						</div>
						<div class="control-group formSep">
							<label for="uri" class="control-label">Default?</label>
							<div class="controls">
								<input type="radio"  id="isyes" name='is_default' value="1" <?php echo $page['is_default'] == 1 ? ' checked' : ''; ?>>Yes<br>
								<input type="radio"  id='isno" 'name='is_default' value="0" <?php echo $page['is_default'] == 0 ? ' checked' : ''; ?>>No<br>
							</div>
						</div>
						
						<div class="control-group formSep">
							<div class="controls">
								<input type='hidden' id='id' name='id' value="<?php echo (isset($page['id'])) ? $page['id']:'0';?>">
							</div>
						</div>
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-gebo" id="loginsave" type="submit">Add/Update Page</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
