<?php 
// initialize total
$total	= 0;

## CUSTOM ATKINSON HACK TO PASSBACK DATA
/*
if ($partner_id == 208):

	// Passback customer data to atkinson's API  $client is array with client info
	//$this->debug->show($client,true);

endif;
*/

?>

<div id="completed">
	<h1><?php echo $this->lang->line('billing_transaction_complete'); ?></h1><br />
	<p class="intro"><?php echo $this->lang->line('billing_email_receipt_sent_to'); ?><!--<strong><?php echo $email; ?></strong>-->. <?php echo $this->lang->line('billing_order_automatically_processed'); ?></p>

	<section id="sec-summary">
		<h2><?php echo $this->lang->line('billing_order_summary'); ?></h2>
		<table>
		
			<?php 
			// iterate through all items on the invoice
			foreach ($items['current_packs'] AS $key => $value): ?>
			
				<tr>
					<td><?php echo preg_replace("/\([^)]+\)/",'',$value['desserv']).' ('.$period[$value['period']].')'; ?> </td>
					<td>$<?php echo number_format($value['cost'],2); ?></td>
				</tr>
			
				<?php 
				// increase total
				$total	+= $value['cost'];
			
			endforeach;
			



			// iterate through all credits on the invoice
			foreach ($items['credits'] AS $key => $value): ?>
			
				<tr>
					<td class="bold-green"><?php echo $value['reason']; ?> </td>
					<td class="bold-green">($<?php echo number_format($value['amount'],2); ?>)</td>
				</tr>
			
				<?php
				// subtract amount from total
				$total	+= number_format($value['amount'],2);
			
			endforeach; ?>
			
			<tr>
				<th><?php echo $this->lang->line('billing_total'); ?>:</th>
				<th>$<?php echo number_format($total,2); ?></th>
			</tr>
		</table>
	</section>
	<section id="sec-number">
		<dl>
			<dt><?php echo $this->lang->line('billing_invoice_reference'); ?>:</dt>
				<dd><?php echo $items['invid']; ?></dd>
		</dl>
	</section>
	<section id="sec-cc">
		<p><?php echo $this->lang->line('billing_credit_card_statement', $descriptor); ?></p>
	</section><br />
	
	<?php 
	// determine if user has charity = if so, show charity image
	if (FALSE && $charity === TRUE): ?>

		<!-- Currently Show Habitat For Humanity -->
		<div class="centering">
			<a href="http://www.brainhostgivesback.com/" target="_blank"><img src="/resources/modules/completed/assets/images/charity/habitat_for_humanity.png"></a>
		</div>

	<?php endif;	// end seeing if user has charity

	// only show this section if user has a build type - else show offer
	if ($build_type === TRUE && $show_button === TRUE): ?>

		<a href="/free/website/setup/<?php echo $items['invid']; ?>/invoice" id="btn-setup"></a>

	<?php else: ?>
		<!-- Currently SES --
		<div class="centering">
			<a href="http://e6fa40r5o-embxcgg6w7urx9nv.hop.clickbank.net/"><img src="/resources/modules/completed/assets/images/completed/ses.png"></a>
		</div>
		<!-- End SES -->

	<?php
	endif;
	
	// custom thankyou page content for affiliates
	if( ! empty($content_custom)): ?>

		<div class="intro">
			<?php echo $content_custom; ?>
		</div>
		
	<?php endif;

	// see if we need to show desktop lightning
	if ($desktop_lightning): ?>

		<!-- Desktop Integration -->
		<div class="centering" id="desktoplightning">
			<script src="http://www.desktoplightning.com/i/script.php?s=brainhost&amp;t=thankyou1"></script>
		</div>
		<!-- End Desktop Integration -->

	<?php endif; 


	### HACK FOR JESSICA
	### PARTNER ID 239
	if ($partner_id == 239):

		// show pixel
		echo '
		<img src="https://clickbetter.com/vip.gif?campid=100&fname='.urlencode($client['first']).'&lname='.urlencode($client['last']).'&cemail='.urlencode($client['email']).'&phone='.urlencode($client['phone']).'&address='.urlencode($client['address']).'&city='.urlencode($client['city']).'&state='.urlencode($client['state']).'&country='.urlencode($client['country']).'">
		';

	endif;


	if ($partner_id == 251):

		echo '
<!-- Offer Conversion: Simple Click Profits -->
<iframe src="https://mynewathomecareer.go2cloud.org/aff_l?offer_id=10" scrolling="no" frameborder="0" width="1" height="1"></iframe>
<!-- // End Offer Conversion -->

<iframe width="1" height="1" frameborder="0" src="https://www.clickermetrics.com/rd/ipx.php?hid={aff_sub2}&sid=846&transid='.$items['invid'].'&event=145.00"></iframe>
		';

	endif;

	if ($partner_id == 225):

		echo '
<!-- Offer Goal Conversion: Cool Handle -->
<iframe src="https://mynewathomecareer.go2cloud.org/GL2a" scrolling="no" frameborder="0" width="1" height="1"></iframe>
<!-- // End Offer Goal Conversion -->

<iframe width="1" height="1" frameborder="0" src="https://www.clickermetrics.com/rd/ipx.php?hid={aff_sub2}&sid=846&transid='.$items['invid'].'&event=115.00"></iframe>
		';

	endif;

	if ($partner_id == 169):

		echo '
<!-- Offer Goal Conversion: Cool Handle Downsell -->
<iframe src="https://mynewathomecareer.go2cloud.org/GL2g" scrolling="no" frameborder="0" width="1" height="1"></iframe>
<!-- // End Offer Goal Conversion -->

<iframe width="1" height="1" frameborder="0" src="https://www.clickermetrics.com/rd/ipx.php?hid={aff_sub2}&sid=846&transid='.$items['invid'].'&event=85.00"></iframe>
		';

	endif;



	if (isset($pixel_data) && count($pixel_data) AND ! $this->session->userdata('_show_pixels')): 

		// if _show_pixels is set, then reset clicksure pixel
		if ($this->session->userdata('_show_pixels') AND $partner_id == 208):

			$pixel_data 	= array(
				'<iframe src="https://8923.clicksurecpa.com/pixel?transactionRef=[[order_id]]" width="1px" height="1px" frameborder="0"></iframe>
<iframe src="https://11033.clicksurecpa.com/pixel?transactionRef=[[order_id]]" width="1px" height="1px" frameborder="0"></iframe>'
			);

		endif;


		// set variable to not shjow pixels again
		$this->session->set_userdata('_show_pixels',TRUE);

		?>

		<div id="callback_pixels" style="width:1px;height:1px;visibility;hidden">

			<?php foreach ($pixel_data as $pixel): 

				echo $pixel;

			endforeach; ?>

		</div>

	<?php endif; ?>

</div>