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
		<?php echo form_open('signup/register/account',array('method' => 'POST', 'id' => 'frmRegister'),array('partner_id' => '')); ?>
		<div class="col-l">
			<div class="module registration">
				<h2>Partner Registration Form</h2>
				<?php echo form_fieldset('Account Details <em><span class="required-field">*</span>Required Field</em>'); ?>
					<div class="row half">
						<label for="first_name"><span class="required-field">*</span>First Name</label>
						<?php
							echo form_input(array(
								'name'		=> 'first_name',
								'id'		=> 'first_name',
								'type'		=> 'text',
								'required'	=> 'required',
								'value'		=> (isset($account['first_name']))? $account['first_name']: ''
							));
						?>
					</div>
					<div class="row half">
						<label for="last_name"><span class="required-field">*</span>Last Name </label>
						<?php
							echo form_input(array(
								'name'		=> 'last_name',
								'id'		=> 'last_name',
								'type'		=> 'text',
								'required'	=> 'required',
								'value'		=> (isset($account['last_name']))? $account['last_name']: ''
							));
						?>
					</div>
					<div class="row half">
						<label for="email"><span class="required-field">*</span>Email</label>
						<?php
                            // logo input (switch between text and upload input)
							echo form_input(array(
								'name'		=> 'email',
								'id'		=> 'email',
								'type'		=> 'email',
								'required'	=> 'required',
								'value'		=> (isset($account['email']))? $account['email']: ''
							));
						?>
					</div>
					<div class="row half">
						<label for="company"><span class="required-field">*</span>Company</label>
						
						<?php
							// logo input (switch between text and upload input)
							echo form_input(array(
								'name'		=> 'company',
								'id'		=> 'company',
								'type'		=> 'text',
								'required'	=> 'required',
								'value'		=> (isset($account['company']))? $account['company']: ''
							));
						?>
					</div>
					<div class="row half">
						<label for="phone"><span class="required-field">*</span>Phone</label>
						
						<?php
							// logo input (switch between text and upload input)
							echo form_input(array(
								'name'		=> 'phone',
								'id'		=> 'phone',
								'type'		=> 'phone',
								'required'	=> 'required',
								'value'		=> (isset($account['phone']))? $account['phone']: ''
							));
						?>
					</div>
					<div class="row half">
						<label for="address"><span class="required-field">*</span>Street Address</label>
							
						<?php
							// logo input (switch between text and upload input)
							echo form_input(array(
								'name'		=> 'address',
								'id'		=> 'address',
								'type'		=> 'text',
								'required'	=> 'required',
								'value'		=> (isset($account['address']))? $account['address']: ''
							));
						?>
					</div>
					<div class="row half">
						<label for="city"><span class="required-field">*</span>City</label>
						<?php
							// logo input (switch between text and upload input)
							echo form_input(array(
								'name'		=> 'city',
								'id'		=> 'city',
								'type'		=> 'text',
								'required'	=> 'required',
								'value'		=> (isset($account['city']))? $account['city']: ''
							));
						?>
					</div>
					<div class="row half">
						<label for="zip"><span class="required-field">*</span>Postal Code</label>
						<?php
							// logo input (switch between text and upload input)
							echo form_input(array(
								'name'		=> 'zip',
								'id'		=> 'zip',
								'type'		=> 'number',
								'required'	=> 'required',
								'value'		=> (isset($account['zip']))? $account['zip']: ''
							));
						?>
					</div>
					<div class="row half">
						<label for="state"><span class="required-field">*</span>State/Province</label>
						<?php
							// logo input (switch between text and upload input)
							echo form_input(array(
								'name'		=> 'state',
								'id'		=> 'state',
								'type'		=> 'text',
								'required'	=> 'required',
								'value'		=> (isset($account['state']))? $account['state']: ''
							));
						?>
					</div>
					<div class="row half">
						<label for="Country"><span class="required-field">*</span>Country</label>
						<?php
		                    $this->load->config('address_validation');

		                    $countries = array(
		                    'Please Select' => $this->config->item('addr_common_countries'),
		                    'All Countries' => $this->config->item('addr_countries')
		                    );
		                    $selcountry =  (isset($account['country']))? $account['country']: '';
		                    echo form_dropdown(
		                    'country', 
		                    $countries,
		                    $selcountry,
		                    'id="country" class="required"'
		                    );
		                ?>
					</div>
					<?php 
						echo form_fieldset_close(); 
						echo form_fieldset('Login Details');
					?>
					<div class="row half">
						<label for="username"><span class="required-field">*</span>Username</label>
						<?php
						echo form_input(array(
							'name'		=> 'username',
							'id'		=> 'username',
							'type'		=> 'text',
							'required'	=> 'required',
							'value'		=> (isset($account['username']))? $account['username']: ''
						));
						?>
					</div>
					<div class="row half" style="visibility:hidden;">
						<p>This is just a spacer to put Password and Retype Password onto the same line</p>
					</div>
					<div class="row half">
						<label for="password"><span class="required-field">*</span>Password</label>
						<?php
						echo form_input(array(
							'name'		=> 'password',
							'id'		=> 'password',
							'type'		=> 'password',
							'required'	=> 'required',
							'value'		=> (isset($account['password']))? $account['password']: ''
						));
						?>
					</div>
					<div class="row half">
						<label for="rpassword"><span class="required-field">*</span>Retype Password</label>
						<?php
						echo form_input(array(
							'name'		=> 'rpassword',
							'id'		=> 'rpassword',
							'type'		=> 'password',
							'required'	=> 'required',
							'value'		=> (isset($account['rpassword']))? $account['rpassword']: ''
						));
						?>
					</div>
					<?php echo form_fieldset_close(); ?>
					<div class="row center">
						<?php
						// submit button
						echo form_input(array(
							'name'	=> 'submit',
							'type'	=> 'submit',
							'class' => 'btn-yellow',
							'value'	=> 'Submit'
						));
						?>
					</div>
				</div>
			</div>
		<?php echo form_close(); ?>