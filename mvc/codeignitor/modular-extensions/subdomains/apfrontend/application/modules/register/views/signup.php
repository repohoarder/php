		<style type="text/css">
			#password_strength {
				display:none;
				width:50%;
				margin:0 auto;
				text-align:center;
				clear:both;	
				font-size:0.8em;
				color:#999;
				padding-top:10px;
			}

			#password_strength .strength_bar {
				height:8px;
				background:#eee url('/resources/apfrontend/img/password_gradient.png') left top repeat-x;
				border-radius:4px;
			}

			#password_strength .strength {
				width:100%;
				border:1px solid #ddd;
				background-color:#eee;
				border-radius:2px;
				margin-top:4px;
				border-radius:4px;
			}

			#password_strength .weakest {
				background-color:#600;
			}

			#password_strength .weak {
				background-color:#933;
			}

			#password_strength .good {
				background-color:#090;
			}

			#password_strength .strong {
				background-color:#060;
			}

			#password_strength .superb {
				background-color:#030;
			}

		</style>	



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
								'type'		=> 'text',
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
							'maxlength' => '8',
							'required'	=> 'required',
							'value'		=> (isset($account['username']))? $account['username']: ''
						));
						?>
					</div>
					<div class="row half">
						<p style="margin-bottom:0px;line-height:33px;font-size:0.8em;">

							<a href="user_requirements" style="color:#3C6CC0;display:none;" id="user_reqs" class="lightview" data-lightview-type="inline">Requirements</a>

							<div id="user_requirements" style="display:none">
								Usernames must:
								<ul>
									<li>Be between 3 and 8 characters.</li>
									<li>Consist of <strong>lowercase</strong> letters and/or numbers.</li>
									<li>Begin with a letter.</li>
								</ul>
								Passwords must:
								<ul>
									<li>Be at least 8 characters in length</li>
								</ul>
							</div>
							
						</p>
					</div>

					<div style="clear:both;"></div>

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


					

					<div id="password_strength">
						Password Strength: <span>Weak</span>
						<div class="strength">
							<div class="strength_bar"></div>
						</div>
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


		<script type="text/javascript">

			$(document).ready(function(){

				$('#user_reqs').show();

				var 
					strengths = {
						'superb' : 101,
						'strong': 75,
						'good' : 60,
						'weak' : 40,
						'weakest' : 0
					},
					strength = 'weak',
					original_classes = $('#password_strength .strength_bar').attr('class');
				
				$('#password').complexify({minimumChars: '6', strengthScaleFactor: '.7'}, function(valid, complexity){

					complexity = parseFloat(complexity);

					$('#password_strength').show();
					$('#password_strength .strength_bar').css({
						width: complexity+'%'
					});	

					strength = 'weak';

					for (i in strengths){

						if (complexity < parseFloat(strengths[i])){
							
							strength = i;
						}
					}

					$('#password_strength span').text(strength.charAt(0).toUpperCase() + strength.slice(1));

					$('#password_strength .strength_bar').removeClass().addClass(original_classes).addClass(strength);

				});

			});

		</script>