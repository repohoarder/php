
<h1><?php echo $this->lang->line('paypal_headline'); ?></h1>

<div class="inner-wrap">

	<img src="/resources/brainhost/img/lang/<?php echo $language; ?>/img-paypal.jpg" alt="<?php echo $this->lang->line('paypal_qualify');?>" />

	<p class="msg-below">
		<?php echo $this->lang->line('paypal_complete');?>
	</p>

	<p>
		<?php echo $this->lang->line('paypal_nocharge');?>
		<br />
		<?php echo $this->lang->line('paypal_exclusive');?>
	</p>
	
	<form action="/paypal/loading/<?php echo $order_id; ?>/<?php echo $paypal_id; ?>" method="post">
		<button></button>
	</form>

	<p><a href='/paypal/returned/0'><?php echo $this->lang->line('paypal_nope'); ?></a></p>

</div>