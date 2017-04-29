<?php
// if no payment method has been set yet, default it to paypal
if ( ! isset($details['payment_method']['type']) OR empty($details['payment_method']['type']))
	$details['payment_method']['type'] = 'paypal';
?>

<h1>Manage | Account Information</h1>
<section id="pnl-accordion">
	<h2>Manage Account Information</h2>
	<div class="module s-manage-account">
		<div class="pad">
			<?php


			// show errors if any
			if ($error) echo '<p style="font-weight:bold;color:red;">'.$error.'</p>';

			// open the form
			echo form_open('manage/account',array('method' => 'POST',"id" =>"accountform"));

			// company name
			echo '<fieldset>';
			echo '<legend>Company Information</legend>';
			echo '<div class="row"><label for="txtCompany">Company Name</label>'.form_input(
				array(
					'name'			=> 'company',
					'id'			=> 'txtCompany',
					'class'			=> 'required',
					'type'			=> 'text',
					'value'			=> $details['company'],
					'placeholder'	=> 'Company Name'
				)
			).'</div>';

			// company website
			echo '<div class="row"><label for="txtWebsite">Website Address</label>'.form_input(
				array(
					'name'			=> 'website',
					'id'			=> 'txtWebsite',
					'type'			=> 'text',
					'value'			=> $website['domain'],
					'placeholder'	=> 'Website Address',
					'disabled'		=> TRUE
				)
			).'</div>';
			echo '</fieldset>';
			echo '<fieldset>';
			echo '<legend>Contact Information</legend>';

			// first name
			echo '<div class="row"><label for="txtFName">First Name</label>'.form_input(
				array(
					'name'			=> 'first_name',
					'id'			=> 'txtFName',
					'class'			=> 'required',
					'type'			=> 'text',
					'value'			=> $details['first_name'],
					'placeholder'	=> 'First Name'
				)
			).'</div>';

			// last name
			echo '<div class="row"><label for="txtLName">Last Name</label>'.form_input(
				array(
					'name'			=> 'last_name',
					'id'			=> 'txtLName',
					'class'			=> 'required',
					'type'			=> 'text',
					'value'			=> $details['last_name'],
					'placeholder'	=> 'Last Name'
				)
			).'</div>';

			// email
			echo '<div class="row"><label for="txtEmail">Email Address</label>'.form_input(
				array(
					'name'			=> 'email',
					'id'			=> 'txtEmail',
					'class'			=> 'required email',
					'type'			=> 'text',
					'value'			=> $details['email'],
					'placeholder'	=> 'Email Address'
				)
			).'</div>';

			// address
			echo '<div class="row"><label for="txtAddress">Street Address</label>'.form_input(
				array(
					'name'			=> 'address',
					'id'			=> 'txtAddress',
					'class'			=> 'required',
					'type'			=> 'text',
					'value'			=> $details['address'],
					'placeholder'	=> 'Street Address'
				)
			).'</div>';

			// city
			echo '<div class="row"><label for="txtCity">City</label>'.form_input(
				array(
					'name'			=> 'city',
					'id'			=> 'txtCity',
					'class'			=> 'required',
					'type'			=> 'text',
					'value'			=> $details['city'],
					'placeholder'	=> 'City'
				)
			).'</div>';

			// state
			echo '<div class="row"><label for="txtState">State</label>'.form_input(
				array(
					'name'			=> 'state',
					'id'			=> 'txtState',
					'class'			=> 'required',
					'type'			=> 'text',
					'value'			=> $details['state'],
					'placeholder'	=> 'State'
				)
			).'</div>';

			// zip
			echo '<div class="row"><label for="txtZip">Zip</label>'.form_input(
				array(
					'name'			=> 'zip',
					'id'			=> 'txtZip',
					'class'			=> 'required',
					'type'			=> 'text',
					'value'			=> $details['zip'],
					'placeholder'	=> 'Zip'
				)
			).'</div>';

			// phone
			echo '<div class="row"><label for="txtPhone">Phone</label>'.form_input(
				array(
					'name'			=> 'phone',
					'id'			=> 'txtPhone',
					'class'			=> 'required',
					'type'			=> 'text',
					'value'			=> $details['phone'],
					'placeholder'	=> 'Phone Number'
				)
			).'</div>';

			// username
			echo '<div class="row"><label for="txtUsername">Username</label>'.form_input(
				array(
					'name'			=> 'username',
					'id'			=> 'txtUsername',
					'type'			=> 'text',
					'value'			=> $details['username'],
					'placeholder'	=> 'Username',
					'disabled'		=> TRUE
				)
			).'</div>';

			echo '</fieldset>';
			echo '<fieldset>';
			echo '<legend>Change Password</legend>';

			// Old Password
			echo '<div class="row"><label for="txtOldPassword">Old Password</label>'.form_input(
				array(
					'name'			=> 'old_password',
					'id'			=> 'txtOldPassword',
					'type'			=> 'text',
					'value'			=> '',
					'placeholder'	=> 'Old Password'
				)
			).'</div>';

			// New Password
			echo '<div class="row"><label for="txtNewPassword">New Password</label>'.form_input(
				array(
					'name'			=> 'new_password',
					'id'			=> 'txtNewPassword',
					'type'			=> 'text',
					'value'			=> '',
					'placeholder'	=> 'New Password'
				)
			).'</div>';

			// Confirm new Password
			echo '<div class="row"><label for="txtConfirmPassword">Confirm Password</label>'.form_input(
				array(
					'name'			=> 'confirm_password',
					'id'			=> 'txtConfirmPassword',
					'type'			=> 'text',
					'value'			=> '',
					'placeholder'	=> 'Confirm Password'
				)
			).'</div>';

			echo '</fieldset>';
			echo '<fieldset>';
			echo '<legend>Payment Settings</legend>';

			echo '<div class="row">'.form_radio(
				array(
					'name'		=> 'payment_type',
					'id'		=> 'paypal',
					'value'		=> 'paypal',
					'checked'	=> ($details['payment_method']['type']	== 'paypal')? TRUE: FALSE
				)
			).' <img src="/resources/allphase/img/icon-paypal.png" alt="PayPal" style="position:relative;margin:0px 0 -6px 0;" /> ';

			echo form_radio(
				array(
					'name'		=> 'payment_type',
					'id'		=> 'check',
					'value'		=> 'check',
					'checked'	=> ($details['payment_method']['type']	== 'check')? TRUE: FALSE
				)
			).' Check Payment ';

			echo form_radio(
				array(
					'name'		=> 'payment_type',
					'id'		=> 'direct_deposit',
					'value'		=> 'direct_deposit',
					'checked'	=> ($details['payment_method']['type']	== 'direct_deposit')? TRUE: FALSE
				)
			).' Direct Deposit ';

			echo form_radio(
				array(
					'name'		=> 'payment_type',
					'id'		=> 'bank_wire',
					'value'		=> 'bank_wire',
					'checked'	=> ($details['payment_method']['type']	== 'bank_wire')? TRUE: FALSE
				)
			).' Bank Wire Transfer ';


			echo '</div></fieldset>';
			?>

			<div id="paypal_form">

				<?php
				echo '<fieldset>';
				echo '<legend>Paypal</legend>';
				echo '<div class="row"><label for="paypal_email">PayPal Email</label>'.form_input(
					array(
						'name'			=> 'paypal_email',
						'id'			=> 'paypal_email',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['paypal_email'],
						'placeholder'	=> 'PayPal Email'
					)
				).'</div></fieldset>';
				?>

			</div>

			<div id="check_form">

				<?php
				echo '<fieldset>';
				echo '<legend>Payable To:</legend>';
				echo '<div class="row"><label for="name_on_check"></label>'.form_input(
					array(
						'name'			=> 'name_on_check',
						'id'			=> 'name_on_check',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['name_on_check'],
						'placeholder'	=> 'Name to Appear on Check'
					)
				).'</div></fieldset>';
				?>

			</div>


			<div id="direct_deposit_form">
				<?php
				echo '<fieldset>';
				echo '<legend>Direct Deposit</legend>';
				echo '<div class="row"><label for="bank_name">Bank Name</label>'.form_input(
					array(
						'name'			=> 'bank_name',
						'id'			=> 'bank_name',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['bank_name'],
						'placeholder'	=> 'Bank Name'
					)
				).'</div>';

				echo '<div class="row"><label for="branch_name">Branch Name</label>'.form_input(
					array(
						'name'			=> 'branch_name',
						'id'			=> 'branch_name',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['branch_name'],
						'placeholder'	=> 'Branch Name'
					)
				).'</div>';

				echo '<div class="row"><label for="beneficiary_name">Beneficiary Name</label>'.form_input(
					array(
						'name'			=> 'beneficiary_name',
						'id'			=> 'beneficiary_name',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['beneficiary_name'],
						'placeholder'	=> 'Beneficiary Name'
					)
				).'</div>';

				echo '<div class="row"><label for="account_number">Account Number</label>'.form_input(
					array(
						'name'			=> 'account_number',
						'id'			=> 'account_number',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['account_number'],
						'placeholder'	=> 'Account Number'
					)
				).'</div>';

				echo '<div class="row"><label for="routing_number">Routing Number</label>'.form_input(
					array(
						'name'			=> 'routing_number',
						'id'			=> 'routing_number',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['routing_number'],
						'placeholder'	=> 'Routing Number'
					)
				).'</div>';

				echo '<div class="row"><label for="swift_code">Swift Code</label>'.form_input(
					array(
						'name'			=> 'swift_code',
						'id'			=> 'swift_code',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['swift_code'],
						'placeholder'	=> 'Swift Code'
					)
				).'</div>';
				?>
			</div>

			<div class="row">

				<?php
				// split test opt in
				
				$split = $details['split_test'] == 1 ? TRUE : FALSE;
				echo '<div class="row">'.form_checkbox(
					array(
						'name'			=> 'split_tests',
						'id'			=> 'split_tests',
						'checked'		=> $split,
						'value'			=> TRUE
					)
				).'<label for="split_tests" style="float:none;width:auto;padding:0;">Yes, I want to take part in Website Optimization Split Tests to increase my conversions. <a href="/tooltip/split_tests" class="lightbox" data-fancybox-type="iframe">What\'s This?</a></label></div>';
				?>

			</div>


			<div class="row" style="margin-bottom: 20px;margin-top: 20px;">

				<?php
				// Submit button
				echo form_input(
					array(
						'name'			=> 'submit',
						'id'			=> 'submit',
						'class'			=> 'btn-contact',
						'type'			=> 'submit',
						'style'			=> 'float:none;display:block;margin:0 auto;',
						'value'			=> 'Update Account'
					)
				);
				?>

			</div>

			<?php
			// close form
			echo form_close();

			?>
		</div>
	</div>
</section>
<!-- This code is to show/hide payment forms -->
<script type="text/javascript">
$(document).ready(function() {
	$('#accountform').validate();
	// onclick of paypal radio button
	$("#paypal").click(function() {
		hide_all_payment_methods();
		$("#paypal_form").show();			// show the paypal form
	});

	// onclick of check radio button
	$("#check").click(function() {
		hide_all_payment_methods();
		$("#check_form").show();			// show the check form
	});

	// onclick of direct_deposit radio button
	$("#direct_deposit").click(function() {
		hide_all_payment_methods();
		$("#direct_deposit_form").show();	// show the direct_deposit form
	});

	// onclick of bank_wire radio button
	$("#bank_wire").click(function() {
		hide_all_payment_methods();
		$("#direct_deposit_form").show();	// show the direct deposit form
	});

	// on load - hide all payment method forms
	hide_all_payment_methods();

	// on load - show the payment method form
	$('input:radio[value=<?php echo $details['payment_method']['type']; ?>]').click();

});


// this function hides all payment method forms
function hide_all_payment_methods(){
	$("#paypal_form").hide();			// hide paypal form
	$("#check_form").hide();			// hide check form
	$("#direct_deposit_form").hide();	// hide direct deposit form
	$("#bank_wire_form").hide();		// hide bank wire form
}

</script>
<!-- End code to show/hide payment forms -->