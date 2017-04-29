<script type="text/javascript" src="/resources/allphase/js/loading.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

		$('form').submit(function(e) {
			if($('form').validate().checkForm()){
				show_loading_dialog();
			} else {
				e.preventDefault();
			}
		});
	});
</script>

<br>

<section class="popup-message step-2-2">
	<h2><span>WAIT! Complete these steps to get started!</span></h2>
	<div class="container">
		<h3>STEP 3 - Enter Your Profit Payment Settings</h3>
		<p>Choose from our payment options below to determine how you would like to receive your company earnings.</p><br>
		<form action="" method="post" id="paymentsettingsform"> 
			<fieldset>
				<div class="row">
					<input type="radio" name="radPayment" id="radPaymentPayPal"  value="paypal"/> <label for="radPaymentPayPal" class="radio-label"><img src="/resources/allphase/img/icon-paypal.png" alt="PayPal" /></label>
					<input type="radio" name="radPayment" id="radPaymentCheck" value="check"/> <label for="radPaymentCheck" class="radio-label">Check Payment</label>
					<input type="radio" name="radPayment" id="radPaymentDD" checked value="direct_deposit" /> <label for="radPaymentDD" class="radio-label">Direct Deposit</label>
					<input type="radio" name="radPayment" id="radPaymentWire" value='bank_wire'/> <label for="radPaymentWire" class="radio-label">Bank Wire Transfer</label>
				</div>
			</fieldset>
                    <div id="check_form">
                        <fieldset>
				<div class="row">
					<label for="name_on_check" class="large">Check Payment</label>
					<?php echo form_input(
					array(
						'name'			=> 'name_on_check',
						'id'			=> 'name_on_check',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['name_on_check'],
						'placeholder'	=> 'Name to Appear on Check'
					)
				);
                                ?>
				</div>
                        </fieldset>
                    </div>
                    <div id="paypal_form">
                        <fieldset>
				<div class="row">
					<label for="paypal_email" class="large">Paypal</label>
					<?php echo form_input(
					array(
						'name'			=> 'paypal_email',
						'id'			=> 'paypal_email',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['paypal_email'],
						'placeholder'	=> 'PayPal Email'
					)
				);
                                ?>
				</div>
                        </fieldset>
                    </div>
                    <div id="direct_deposit_form">
			<fieldset>
				<div class="row">
					<label for="bank_name" class="large">Bank Name</label>
					<?php echo form_input(
					array(
						'name'			=> 'bank_name',
						'id'			=> 'bank_name',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['bank_name'],
						'placeholder'	=> 'Bank Name'
					)
				);
                                ?>
				</div>
				<div class="row">
					<label for="branch_name" class="large">Branch Name</label>
				<?php echo 	form_input(
					array(
						'name'			=> 'branch_name',
						'id'			=> 'branch_name',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['branch_name'],
						'placeholder'	=> 'Branch Name'
					)
				);?>
				</div>
				<div class="row">
					<label for="beneficiary_name" class="large">Beneficiary Name</label>
					<?php echo form_input(
					array(
						'name'			=> 'beneficiary_name',
						'id'			=> 'beneficiary_name',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['beneficiary_name'],
						'placeholder'	=> 'Beneficiary Name'
					)
				);?>
				</div>
				<div class="row">
					<label for="account_number" class="large">Account Number</label>
					<?php echo form_input(
					array(
						'name'			=> 'account_number',
						'id'			=> 'account_number',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['account_number'],
						'placeholder'	=> 'Account Number'
					)
				);?>
				</div>
				<div class="row">
					<label for="routing_number" class="large">Routing Number</label>
					<?php echo form_input(
					array(
						'name'			=> 'routing_number',
						'id'			=> 'routing_number',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['routing_number'],
						'placeholder'	=> 'Routing Number'
					)
				);?>
				</div>
				<div class="row">
					<label for="swift_code" class="large">Swift Code</label>
					<?php echo form_input(
					array(
						'name'			=> 'swift_code',
						'id'			=> 'swift_code',
						'type'			=> 'text',
						'value'			=> @$details['payment_method']['swift_code'],
						'placeholder'	=> 'Swift Code'
					)
				);?>
                                       
				</div>
                            
			</fieldset>
                    </div>
		</form>
		<a href="javascript: void(0);" class="btn-contact" id="psfbutton">Update Account</a>
	</div>
</section>
<!-- This code is to show/hide payment forms -->
<script type="text/javascript">
$(document).ready(function() {

    $("#psfbutton").click(function(){
        $("#paymentsettingsform").submit();
    });
    
	// onclick of paypal radio button
	$("#radPaymentPayPal").click(function() {
		hide_all_payment_methods();
		$("#paypal_form").show();			// show the paypal form
	});

	// onclick of check radio button
	$("#radPaymentCheck").click(function() {
		hide_all_payment_methods();
		$("#check_form").show();			// show the check form
	});

	// onclick of direct_deposit radio button
	$("#radPaymentDD").click(function() {
		hide_all_payment_methods();
		$("#direct_deposit_form").show();	// show the direct_deposit form
	});

	// onclick of bank_wire radio button
	$("#radPaymentWire").click(function() {
		hide_all_payment_methods();
		$("#direct_deposit_form").show();	// show the direct deposit form
	});

	// on load - hide all payment method forms
	hide_all_payment_methods();

	// on load - show the payment method form
	$('#radPaymentDD').click();

});


// this function hides all payment method forms
function hide_all_payment_methods(){
	$("#paypal_form").hide();			// hide paypal form
	$("#check_form").hide();			// hide check form
	$("#direct_deposit_form").hide();	// hide direct deposit form
}

</script>
