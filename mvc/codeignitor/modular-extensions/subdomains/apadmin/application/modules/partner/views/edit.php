<?php

?>
<div class="row-fluid">
	<div class="span12">
		<h3 class="heading">Edit Partner Website Information</h3>
		<div class="row-fluid">
			<div class="span8">
				<form class="form-horizontal" id="loginsave_form" method="post" action="/partner/edit/<?php echo $partner_id; ?>" enctype="multipart/form-data">
					<fieldset>
					<div class="control-group formSep">
							<label class="control-label">&nbsp;</label>
							<div class="controls text_line" id="errorloginsave">
								<strong><?php echo $error;?></strong>
							</div>
						</div>
						
						<div class="control-group formSep">
							<label class="control-label">Company Name: </label>
							<div class="controls">
								<input type="text" id="company_name" name="company_name" class="input-xlarge" value="<?php echo (isset($website['company_name']))? $website['company_name']: '';?>" />
							</div>
						</div>

						<div class="control-group formSep">
							<label class="control-label">Company Domain: </label>
							<div class="controls">
								<input type="hidden" name="domain_type" value="<?php echo (isset($website['domain_type']))? $website['domain_type']: ''; ?>" />
								<input type="text" id="domain" name="domain" class="input-xlarge" value="<?php echo (isset($website['domain']))? $website['domain']: '';?>" />
							</div>
						</div>

						<div class="control-group formSep">
							<label class="control-label">Logo Type: </label>
							<div class="controls">
								<?php
								// text logo
								echo form_radio(array(
									'name'		=> 'logo_type',
									'id'		=> 'logo_type_text',
									'value'		=> 'text',
									'checked'	=> ( ! isset($website['logo_type']) OR $website['logo_type'] == 'text')? TRUE: FALSE
								)).' Text Logo';

								// custom logo
								echo form_radio(array(
									'name'		=> 'logo_type',
									'id'		=> 'logo_type_custom',
									'value'		=> 'upload',
									'checked'	=> (isset($website['logo_type']) AND $website['logo_type'] == 'upload')? TRUE: FALSE
								)).' Custom Logo';
								?>
							</div>
						</div>


						<div class="control-group formSep">
							<label class="control-label">Logo: </label>
							<div class="controls">
								<?php
								// logo input (switch between text and upload input)
								echo form_input(array(
									'name'		=> 'logo_text',
									'type'		=> 'text',
									'style'		=> 'margin-left: 45%;',
									'value'		=> @$website['logo']
								));
								?>

								<?php if ($website['logo_type'] == 'upload'): ?>
									<img src="<?php echo $website['logo_file']; ?>" alt="<?php echo $website['company_name']; ?>" />
								<?php endif; ?>
								<?php
								// logo input (switch between text and upload input)
								echo form_input(array(
									'name'		=> 'logo_upload',
									'type'		=> 'file',
									'style'		=> 'margin-left: 45%;',
									'value'		=> @$website['logo']
								));
								?>
								<p style="color:#464646;font-size:12px;margin:5px 5% 0 45%;">This logo appears on your hosting company’s website.  Upload your logo as a JPEG or PNG. We’ll resize your logo to fit into the 750 px x 75 px box.</p>

							</div>
						</div>



						<div class="control-group">
							<div class="controls">

								<input type="hidden" name="partner_id" value="<?php echo $partner_id; ?>" />

								<button class="btn btn-gebo" id="loginsave" type="submit">Update Partner</button>

							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
