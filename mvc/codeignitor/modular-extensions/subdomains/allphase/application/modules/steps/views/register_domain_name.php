<?php 

// initialize varibales
$tlds 				= array(
	'com'	=> '.com',
	'net'	=> '.net',
	'org'	=> '.org',
	'info'	=> '.info',
	'biz'	=> '.biz'
);
$form_submission	= '';
$attributes			= array(
	'method'	=> 'POST',
	'id'		=> 'domain_form',
	'name'		=> 'domain_form'
);
$hidden_fields		= array('form' => 'register');
?>

<br>

<section class="popup-message step-1-1">
	<h2><span>WAIT! Complete these steps to get started!</span></h2>
	<div class="container">
		<h3>STEP 1 - Register Your Domain Name</h3>

			<?php
			if ($error) echo '<center><p style="color:red;font-weight:bold;">'.$error.'</p></center>';
			?>


			<fieldset>
				<div class="col-l">
					<div class="row">
						<label for="txtCompany">Your Hosting Company Name</label>
					</div>
					<div class="row">
						<input type="radio" name="type" id="register_type" value="register" checked />
						<label for="register_type">Register Your Domain</label>
					</div>
					<div class="row">
						<input type="radio" name="type" id="transfer_type" value="transfer" />
						<label for="transfer_type">Use A Domain You Own</label>
					</div>
				</div>
				<div class="col-r">

					<?php
					// open the form
					echo form_open(
						$form_submission,
						$attributes,
						$hidden_fields
					);
					?>

					<div class="row">
						
						<?php
						// comapny name input field
						echo form_input(
							array(
								'name'			=> 'company',
								'id'			=> 'company',
								'type'			=> 'text',
								'value'			=> $this->input->post('company'),
								'placeholder'	=> 'Company Name',
								'class'			=> 'offset required'
							)
						);
						?>

					</div>
					<div class="row" id="register_form">

						<label for="txtRegisterWWW">www.</label>
						<?php
						// domain sld input field
						echo form_input(
							array(
								'name'			=> 'sld',
								'id'			=> 'sld',
								'type'			=> 'text',
								'value'			=> $this->input->post('sld'),
								'placeholder'	=> 'ie: google'
							)
						);
						
						echo form_input(
							array(
								'name'			=> 'type',
								'id'			=> 'domain_type_hidden',
								'type'			=> 'hidden',
								'value'			=> 'register'
							)
						);

						// domain tld dropdown
						echo form_dropdown(
							'tld',
							$tlds,
							'com',
							'id="tld"'
						);
						?>
						<a href="#" class="btn-contact" id="search">Search</a>

					</div>

					<?php
					echo form_close();
					?>

					<div class="row" id="transfer_form">

						<?php
						// open the form
						echo form_open(
							$form_submission,
							array(
								'method'	=> 'POST',
								'id'		=> 'domain_form_transfer',
								'name'		=> 'domain_form_transfer'
							),
							array('form' => 'transfer')
						);
						?>

						<label for="txtUseWWW">www.</label>

						<?php
						// domain sld input field
						echo form_input(
							array(
								'name'			=> 'sld',
								'id'			=> 'sld',
								'type'			=> 'text',
								'value'			=> $this->input->post('sld'),
								'placeholder'	=> 'ie: yourdomain'
							)
						);
						
						echo form_input(
							array(
								'name'			=> 'type',
								'id'			=> 'domain_type_hidden',
								'type'			=> 'hidden',
								'value'			=> 'transfer'
							)
						);

						// domain tld dropdown
						echo form_dropdown(
							'tld',
							$tlds,
							'com',
							'id="tld"'
						);
						?>

						<input type="hidden" id="hiddencompany_transfer" name="company" value=''>

						<a href="javascript:void(0);" class="btn-contact" id="transfer">Transfer</a>

						<?php
						echo form_close();
						?>

					</div>
				</div>
			</fieldset>


		<!-- Start domain suggestions -->
		<div id="suggestions_form" class="not-available">
			<p>We’re sorry, the domain you chose is unavailable. Here are some <strong>available</strong> domain names that are similar to your choice:</p>
			<?php
			// open the form
			echo form_open(
				$form_submission,
				array('method' => 'POST', 'id' => 'suggestion_form'),
				array('form' => 'suggestions', 'type' => 'suggestions')	// hidden fields
			);
			?>
				<input type="hidden" id="hiddencompany" name="company" value=''>
				
				<div id="show_suggestions"></div>

				<a href="#" class="btn-contact" id="buy">Buy This Domain</a>

			<?php
			//close form
			echo form_close();
			?>
		</div>


	</div>
</section>
