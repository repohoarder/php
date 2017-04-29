<style>
	#frmVerifyPhone {padding:50px 0;}

	#frmVerifyPhone input[type='submit'] {
		display:inline;
		padding:3px 20px;
		cursor:pointer;
		border:none;
		font-size:25px;
		font-weight:500;
		text-align:center;
		text-shadow:-1px -1px 1px #555;
		color:#fff;
		-webkit-border-radius:5px;
		border-radius:5px;
		margin:0 -1px;
		background: #76b01d; /* Old browsers */
		background: -moz-linear-gradient(top, #76b01d 0%, #548e13 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#76b01d), color-stop(100%,#548e13)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top, #76b01d 0%,#548e13 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top, #76b01d 0%,#548e13 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top, #76b01d 0%,#548e13 100%); /* IE10+ */
		background: linear-gradient(to bottom, #76b01d 0%,#548e13 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#76b01d', endColorstr='#548e13',GradientType=0 ); /* IE6-9 */
	}
	#frmVerifyPhone input[type='submit']:hover {
		text-shadow:1px 1px 1px #555;
		background: #548e13; /* Old browsers */
		background: -moz-linear-gradient(top, #548e13 0%, #76b01d 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#548e13), color-stop(100%,#76b01d)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top, #548e13 0%,#76b01d 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top, #548e13 0%,#76b01d 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top, #548e13 0%,#76b01d 100%); /* IE10+ */
		background: linear-gradient(to bottom, #548e13 0%,#76b01d 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#548e13', endColorstr='#76b01d',GradientType=0 ); /* IE6-9 */

	}

	#frmVerifyPhone .button_wrapper {
		padding:1px;
		-webkit-border-radius:5px;
		border-radius:5px;
		background: #548e13; /* Old browsers */
		background: -moz-linear-gradient(top, #548e13 0%, #76b01d 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#548e13), color-stop(100%,#76b01d)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top, #548e13 0%,#76b01d 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top, #548e13 0%,#76b01d 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top, #548e13 0%,#76b01d 100%); /* IE10+ */
		background: linear-gradient(to bottom, #548e13 0%,#76b01d 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#548e13', endColorstr='#76b01d',GradientType=0 ); /* IE6-9 */

	}

	#frmVerifyPhone .button_wrapper:hover {
		background: #76b01d; /* Old browsers */
		background: -moz-linear-gradient(top, #76b01d 0%, #548e13 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#76b01d), color-stop(100%,#548e13)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top, #76b01d 0%,#548e13 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top, #76b01d 0%,#548e13 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top, #76b01d 0%,#548e13 100%); /* IE10+ */
		background: linear-gradient(to bottom, #76b01d 0%,#548e13 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#76b01d', endColorstr='#548e13',GradientType=0 ); /* IE6-9 */
	}
	.msg-error {float:left;padding:25px;border:1px solid #cc5757;background:#ffd6d6;}
		.msg-error h1 {font-size:18px;padding:0 0 0 25px;margin:0;line-height:1em;background:url(/resources/modules/verify/assets/img/icon-error.png) 0 2px no-repeat;}
	.msg-warning {float:left;padding:25px 25px 25px 125px;border:1px solid #ded1a3;background:#f7f2e2;background:url(/resources/modules/verify/assets/img/icon-info.png) 25px 15px no-repeat;}
</style>
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
<div class="msg-error">
	<h1>Your account has been selected for an additional screening process.</h1>
	<p>In an effort to reduce fraud, some accounts are randomly selected for an additional verification step. To verify your account, please click the "Place Call" button below. You receive an instant call from our automated system which will provide you with your 4-digit PIN.</p>
	<p>Please enter the phone number you wish to receive your call at below. You will receive this call immediately after clicking the "Place Call" button.</p>
	<p><strong>We cannot complete your order until you have completed this verification process.</strong></p>
</div>
<form action="" method="post" id="frmVerifyPhone">
	<label for="phone">Please verify your phone number</label>
	<input type="text" id="phone" name="phone" value="<?php echo $phone_number; ?>"/>
	<input type="hidden" name="verify_phone" value="1" />
	<span class="button_wrapper">
		<input type="submit" value="Place Call"/>
	</span>
</form>
<div class="msg-warning">
	<h2>Phone verification info</h2>
	<p>Hosting companies are often targeted for fraud, so we are doing our best to cut out the fraudulent applications. This cuts down on the additional costs we may incur so we can then pass on the savings to you, our customer, as a reduction in the price of your service plan.</p>
	<p>We will not share the phone number you entere here with anyone. This phone number will only be used for verification and customer service purposes.</p>
</div>