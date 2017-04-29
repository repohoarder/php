<!-- Section specific Javascript -->

	<script>
		$(function() {
			$( "#txtCustomFrom" ).datepicker();
			$( "#txtCustomTo" ).datepicker();
		});
	</script>

<!-- *************************** -->

<h1>Manage | Website</h1>


<?php 
// show error if one is passed
if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>'; 
?>


<?php
// open form
echo form_open_multipart('manage/website',array('method' => 'POST'),array('partner_id' => $partner_id));
?>


<section id="pnl-accordion">

	<h2>Manage Website</h2>
	<div class="module s-manage-account">
		<div class="pad">
			<?php echo form_fieldset('Company Information'); ?>
			<div class="row">
				<label for="company_name">Company Name</label>
				<?php
				echo form_input(array(
					'name'		=> 'company_name',
					'id'		=> 'company_name',
					'type'		=> 'text',
					'value'		=> (isset($website['company_name']))? $website['company_name']: ''
				));
				?>
			</div>
			<div class="row">
				<label for="domain">Company Domain</label>
				<?php
				echo form_input(array(
					'name'		=> 'domain',
					'id'		=> 'domain',
					'type'		=> 'text',
					'value'		=> (isset($website['domain']))? $website['domain']: '',
					'disabled'		=> TRUE
				));
				?>
				<?php
				echo form_input(array(
					'name'		=> 'domain_type',
					'id'		=> 'domain_type',
					'type'		=> 'hidden',
					'value'		=> (isset($website['domain_type']))? $website['domain_type']: ''
				));
				?>
			</div>
			<div class="row">
				<label for="logo_type_text">Logo</label>
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
				<div id="logo_text">
					<?php
					// logo input (switch between text and upload input)
					echo form_input(array(
						'name'		=> 'logo_text',
						'type'		=> 'text',
						'style'		=> 'margin-left: 45%;',
						'value'		=> @$website['logo']
					));
					?>
				</div>
				<div id="logo_upload">
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
			<?php echo form_fieldset_close();?>
			
			<?php echo form_fieldset('Social Media Integration'); ?>
			
			<div class="row">
				<label for="domain">Facebook URL:</label>
				<?php
				echo form_input(array(
					'name'		=> 'facebook_url',
					'id'		=> 'facebook_url',
					'type'		=> 'text',
					'value'		=> (isset($website['facebook_url']))? $website['facebook_url']: ''
				));
				?>
			</div>
			<div class="row">
				<label for="domain">Twitter URL: </label>
				<?php
				echo form_input(array(
					'name'		=> 'twitter_url',
					'id'		=> 'twitter_url',
					'type'		=> 'text',
					'value'		=> (isset($website['twitter_url']))? $website['twitter_url']: ''
				));
				?>
			</div>
			<div class="row">
				<label for="domain">Google+ URL: </label>
				<?php
				echo form_input(array(
					'name'		=> 'google_url',
					'id'		=> 'google_url',
					'type'		=> 'text',
					'value'		=> (isset($website['google_url']))? $website['google_url']: ''
				));
				?>
			</div>
			<?php echo form_fieldset_close();?>
			
			
			<div class="row s-customer-reporting">
				<?php
				// submit button
				echo form_input(array(
					'name'	=> 'submit',
					'type'	=> 'submit',
					'style' => 'float:none;margin:30px auto;width:200px;display:block;',
					'value'	=> 'Save Website'
				));
				?>
			</div>
		</div>
	</div>
</section>


<?php
// close form
echo form_close();
?>




<!-- Custom Javascript -->
<script type="text/javascript">
$(document).ready(function (){
	<?php
	// if logo type is text, or not set, close logo_upload
	if ( ! isset($website['logo_type']) OR $website['logo_type'] == 'text'):
	?>
		// close logo upload
		$("#logo_upload").hide();
	<?php
	// else, close logo_text and show logo_upload
	else:
	?>
		// close logo_text
		$("#logo_text").hide();
	<?php
	endif;
	?>


	// onclick of logo radio buttons, show/hide proper inputs
	$("#logo_type_text").click(function(){
	
		// close upload input
		$("#logo_upload").hide();

		// show logo_text input
		$("#logo_text").show();
	});

	// onclick of logo radio buttons, show/hide proper inputs
	$("#logo_type_custom").click(function(){
	
		// close logo_text input
		$("#logo_text").hide();

		// show logo_upload input
		$("#logo_upload").show();
	});


});
</script>
