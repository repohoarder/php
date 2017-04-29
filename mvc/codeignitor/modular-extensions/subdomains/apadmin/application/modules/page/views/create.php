<div class="row-fluid">
	<div class="span12">
		<h3 class="heading">Add Partner Page</h3>
		<div class="row-fluid">
			<div class="span8">
				<form class="form-horizontal" id="loginsave_form" method="post" action="<?php echo $this->config->item('subdir'); ?>/page/create">
					<fieldset>
					<div class="control-group formSep">
							<label class="control-label">&nbsp;</label>
							<div class="controls text_line" id="errorloginsave">
								<strong><?php echo $error;?></strong>
							</div>
						</div>
						
						<div class="control-group formSep">
							<label for="name" class="control-label">Page Name</label>
							<div class="controls">
								<input type="text" id="name" name="name" class="input-xlarge" value="<?php echo (isset($page['name'])) ? $page['name']:'';?>" />
							</div>
						</div>
						<div class="control-group formSep">
							<label for="description" class="control-label">Description</label>
							<div class="controls">
								<textarea id="description" name="description" class="span8" rows="20" cols="40"><?php echo (isset($page['description'])) ? $page['description']:'';?></textarea>
							</div>
						</div>
						<div class="control-group formSep">
							<label for="type" class="control-label">Type</label>
							<div class="controls">
								<select id="type"  name="type">
									<?php 
										$page_type = isset($page['type']) ? $page['type'] :'';
										foreach ($page_types as $key=>$val) :

											$c = $page_type == $val ? ' selected="selected"':'';
											echo "<option value='$val'$c>$val</option>";

										endforeach;
									?>
								</select>
							</div>
						</div>
						<div class="control-group formSep">
							<label for="slug" class="control-label">Slug</label>
							<div class="controls">
								<input type="text" id="slug" name="slug" class="input-xlarge" value="<?php echo (isset($page['slug'])) ? $page['slug']:'';?>" />
							</div>
						</div>
						<div class="control-group formSep">
							<label for="uri" class="control-label">URI</label>
							<div class="controls">
								<input type="text" id="uri" name="uri" class="input-xlarge" value="<?php echo (isset($page['uri'])) ? $page['uri']:'';?>" />
							</div>
						</div>
						<div class="control-group formSep">
							<label for="plan_slug" class="control-label">Plan Slug</label>
							<div class="controls">
								<input type="text" id="plan_slug" name="plan_slug" class="input-xlarge" value="<?php echo (isset($page['plan_slug'])) ? $page['plan_slug']:'';?>" />
							</div>
						</div>
						<div class="control-group formSep">
							<label for="tern" class="control-label">Term</label>
							<div class="controls">
								<select id="term"  name="term">
									<?php 
										$page_term = isset($page['term']) ? $page['term'] :'';
										foreach ($page_terms as $key=>$val) :

											$c = $page_term == $val ? ' selected="selected"':'';
											echo "<option value='$val'$c>$val</option>";

										endforeach;
									?>
								</select>
							</div>
						</div>
						<div class="control-group formSep">
							<label for="variant" class="control-label">Variant</label>
							<div class="controls">
								<input type="text" id="variant" name="variant" class="input-xlarge" value="<?php echo (isset($page['variant'])) ? $page['variant']:'default';?>" />
							</div>
						</div>
						<div class="control-group formSep">
							<label for="theme" class="control-label">Theme</label>
							<div class="controls">
								<select id="theme"  name="theme">
									<?php 
										$page_theme = isset($page['theme']) ? $page['theme'] :'';
										foreach ($page_themes as $key=>$val) :

											$c = $page_theme == $val ? ' selected="selected"':'';
											echo "<option value='$val'$c>$val</option>";

										endforeach;
									?>
								</select>
							</div>
						</div>
						<div class="control-group formSep">
							<label for="layout" class="control-label">Layout</label>
							<div class="controls">
								<select id="layout"  name="layout">
									<?php 
										$page_layout = isset($page['layout']) ? $page['layout'] :'';
										foreach ($page_layouts as $key=>$val) :

											$c = $page_layout == $val ? ' selected="selected"':'';
											echo "<option value='$val'$c>$val</option>";

										endforeach;
									?>
								</select>
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
