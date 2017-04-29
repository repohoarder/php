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

					<div style="text-align:center;border:1px dashed #3C64C3;padding:0 10px;margin:30px 0;">

						<h3 style="font-size:1.4em;color:#3C64C3">Anxious to check out all of the features?</h3>

						<p>Log into our <strong>Demo Account</strong> to check out all of our great features!</p>

						<p style="font-weight:bold;font-size:1.1em;"><a href="http://partner.allphasehosting.com" style="color:#009">http://partner.allphasehosting.com</a>
						<br>Username: Demo1234
						<br>Password:  Demo1234</p>

					</div>



					<h3 style="font-size:1.4em;color:#3C64C3;display:block;text-align:center;">What Kind of Partner Will You Be?</h3>

					<?php

					$customers['low'] = array(
						'name'   => 'Absolute Minimum',
						'number' => 1,
						'before' => 5,
						'after'  => 4,
					);

					$customers['mid'] = array(
						'name'   => 'Average Partner',
						'number' => 5,
						'before' => 175,
						'after'  => 135,
					);

					$customers['high'] = array(
						'name'   => 'TOP PARTNERS',
						'number' => 100,
						'before' => 275,
						'after'  => 225,
					);

					?>

					<table style="width:100%;whitespace:nowrap;">
						<thead>
							<tr>
								<th style="padding-bottom:10px;"></th>
								<th style="padding-bottom:10px;">Sales / Day</th>
								<?php /* <th style="padding-bottom:10px;">Avg. Order (Before Costs)</th> */ ?>
								<th style="padding-bottom:10px;">Avg. Sale <span style="font-size:0.8em;">(After Costs)</span></th>
								<th style="padding-bottom:10px;">First Month's Net</th>
								<th style="padding-bottom:10px;font-weight:bold;color:#060;">First Year's Net</th>
							</tr>
						</thead>
						<tbody>

							<?php foreach ($customers as $key => $customer): 

								$monthly = ($customer['number'] * $customer['after']) * 30;
								$yearly  = $monthly * 78;

								$row_style = '';

								if ($key == 'high'):
									$row_style = 'font-weight:bold;font-size:1.2em;';
								endif;
							?>

								<tr style="<?php echo $row_style;?>">
									<td style="padding-bottom:10px;"><?php echo $customer['name'];?></td>
									<td style="padding-bottom:10px;"><?php echo number_format($customer['number']);?></td>
									<?php /* <td style="padding-bottom:10px;">$<?php echo number_format($customer['before'],2);?></td> */?>
									<td style="padding-bottom:10px;">$<?php echo number_format($customer['after']);?></td>
									<td style="padding-bottom:10px;">$<?php echo number_format($monthly);?></td>
									<td style="color:#060;">$<?php echo number_format($yearly);?></td>
								</tr>

							<?php endforeach; ?>
						</tbody>
					</table>


				<?php echo form_fieldset_close(); ?>
			</div>
		</div>
		<?php echo form_close(); ?>