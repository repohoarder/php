<?php 
// initialize total
$total	= 0;
?>

<div id="completed">
	
	<div class="paypal">
		<h2><?php echo $this->lang->line('billing_pp_order_complete'); ?></h2>

		<p><?php echo $this->lang->line('billing_pp_email_receipt_sent_to'); ?><strong><?php echo $email; ?></strong></p>

		<p><?php echo $this->lang->line('billing_pp_paypal_receipt'); ?></p>
		

		<p style="margin-bottom:15px;">&nbsp;</p>
		<?php /*<p><?php echo $this->lang->line('billing_pp_also_receive_email'); ?> <?php echo $this->lang->line('billing_pp_brand_company'); ?> <?php echo $this->lang->line('billing_pp_confirm_account_setup'); ?>. <?php echo $this->lang->line('billing_pp_order_automatically_processed'); ?></p */ ?>

	</div>
	<section id="sec-summary">
		<h2><?php echo $this->lang->line('billing_pp_order_summary'); ?></h2>
		<table>
		
			<?php 
			// iterate through all items on the invoice
			foreach ($items['current_packs'] AS $key => $value):
			?>
			
				<tr>
					<td><?php echo preg_replace("/\([^)]+\)/",'',$value['desserv']).' ('.$period[$value['period']].')'; ?> </td>
					<td>$<?php echo $value['cost']; ?></td>
				</tr>
			
			<?php 
				// increase total
				$total	+= $value['cost'];
			
			endforeach;
			?>
			
			<?php 
			// iterate through all credits on the invoice
			foreach ($items['credits'] AS $key => $value):
			?>
			
				<tr>
					<td class="bold-green"><?php echo $value['reason']; ?> </td>
					<td class="bold-green">($<?php echo $value['amount']; ?>)</td>
				</tr>
			
			<?php 
				// subtract amount from total
				$total	+= $value['amount'];
			
			endforeach;
			?>
			
			<tr>
				<th><?php echo $this->lang->line('billing_pp_total'); ?>:</th>
				<th>$<?php echo $total; ?></th>
			</tr>
		</table>
	</section>
	<section id="sec-number">
		<dl>
			<dt><?php echo $this->lang->line('billing_pp_invoice_reference'); ?>:</dt>
				<dd><?php echo $items['invid']; ?></dd>
		</dl>
	</section>
	<section id="sec-cc">
		<p><?php echo $this->lang->line('billing_pp_credit_card_statement'); ?></p>
	</section><br />
	
	<?php 
	// only show this section if user has a build type - else show offer
	if ($build_type === TRUE):
	?>
		<a href="/free/website/setup/<?php echo $items['invid']; ?>/invoice" id="btn-setup"><?php echo $this->lang->line('billing_pp_setup');?></a>
	<?php 
	else:
	?>
		<div class="centering">
			<a href="http://e6fa40r5o-embxcgg6w7urx9nv.hop.clickbank.net/"><img src="/resources/modules/completed/assets/images/completed/ses.png"></a>
		</div>
	<?
	endif;
	?>
	
	
</div>