		<!-- Plugin initialization -->
		<script>
			$(function() {
				$("#frmRegister").validate();
			});
		</script>

		<h1>Partner Program <span>Create your own hosting company</span></h1>
		<?php 
			// show error if one is passed
			if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.validation_errors().'</p>';
		?>
		<?php echo form_open('register/account',array('method' => 'POST', 'id' => 'frmRegister'),array('partner_id' => '')); ?>
		<div class="col-l">
			<div class="module registration">
				<?php echo form_fieldset('Successful Registration!'); ?>
					<p>Your account has been created successfully. An activation notice has been sent to your email address. We are working diligently to get your hosting company up and running as quickly as possible!</p>
					<p>If you haven’t seen the email, check your Spam filter to make sure that your email program didn’t mistakenly identify as unwanted.</p>
				<?php echo form_fieldset_close(); ?>
			</div>
		</div>
		<?php echo form_close(); ?>