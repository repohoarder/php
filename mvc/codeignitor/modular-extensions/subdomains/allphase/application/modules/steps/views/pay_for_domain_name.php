<?php
// generate exp month and year dropdown arrays
$years	= range(date('Y'),date('Y')+10);
$month 	= array();

// iterate through the 12 months
foreach (range(1,12) as $key=>$mo):

    $key = str_pad($mo,2,"0",STR_PAD_LEFT);

    $months[$key] = $key.' '.date('M',mktime(0, 0, 0, $mo, 1));

endforeach;
?>

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
		<h3>STEP 2 - Pay For Domain Name</h3>
		<?php
		if ($error) echo '<center><p style="color:red;font-weight:bold;">'.$error.'</p></center>';
		?>
		<form action="#" method="post">
			<fieldset>
				<legend>Domain Cost:</legend>
				<?php if ($partner['domain_type']=='transfer'): ?>
					<span>$0.00 for transfer</span>
				<?php else: ?>
					<span>$8.50 for 1 year <small>(lowest price!)</small></span>
				<?php endif; ?>
			</fieldset>
			<fieldset>
				<legend>Billing Information:</legend>
				<div class="row">
					<input type="text" id="txtFName" name="txtFName" placeholder="First Name" value="<?php echo $partner['first_name']; ?>" />
					<input type="text" id="txtLName" name="txtLName" placeholder="Last Name" value="<?php echo $partner['last_name']; ?>" />
				</div>
				<div class="row">
					<input type="text" id="txtEmail" name="txtEmail" placeholder="Email" value="<?php echo $partner['email']; ?>" />
				</div>
				<div class="row">
					<select name="selCountry" id="selCountry">
						<option value="US">USA</option>
						<option value="CA">Canada</option>
						<option value="GB">United Kingdom</option>
					</select>
					<input type="text" id="txtState" name="txtState" placeholder="State" value="<?php echo $partner['state']; ?>" />
				</div>
				<div class="row">
					<input type="text" id="txtAddress" name="txtAddress" placeholder="Street Address" value="<?php echo $partner['address']; ?>" />
				</div>
				<div class="row">
					<input type="text" id="txtCity" name="txtCity" placeholder="City" value="<?php echo $partner['city']; ?>" />
					<input type="text" id="txtZip" name="txtZip" placeholder="Zip" value="<?php echo $partner['zip']; ?>" />
				</div>
				<div class="row">
					<p>*Billing Information should match credit card address</p>
				</div>
			</fieldset>
			<fieldset>
				<legend>Credit Card Information <img src="/resources/allphase/img/icon-cc.png" alt="Visa, MasterCard, American Express" /></legend>
				<div class="row">
					<label for="txtCardNum">Card Number</label>
					<input type="text" id="txtCardNum" name="txtCardNum" />
				</div>
				<div class="row">
					<label for="selExpMonth">Expiration</label>

                    <?php

                        echo form_dropdown(
                            'selExpMonth', 
                            $months,
                            '01',
                            'id="selExpMonth" style="width:20%;"'
                        );

                        echo form_dropdown(
                            'selExpYear', 
                            array_combine($years,$years),
                            date('Y'),
                            'id="selExpYear" style="width:20%;"'
                        );

                        
                    ?>

				</div>
				<div class="row">
					<label for="txtCVV">Security Code</label>
					<input type="text" id="txtCVV" name="txtCVV" style="width:15%;" />
				</div>
				<div class="row"><input type="submit" class="btn-contact" value="Process Payment" style="margin:25px 0 25px 210px;padding:7px 35px;text-transform:uppercase;" /></div>
			</fieldset>
			
		</form>
	</div>
</section>