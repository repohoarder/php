<?php
$form_submission	= '';
$attributes			= array(
	'method'	=> 'POST'
);
?>
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
<div class="content upsell-email">
	<?php echo $this->template->load_view('warning.php'); ?>
	<section class="module">
		<form action="" method="post">
			<h1 class="custom-bg1"><?php echo $this->lang->line('bonus_email_title'); ?></h1>
			<div class="box personal">
				<h2 class="custom-bg1"><?php echo $this->lang->line('bonus_email_personal'); ?></h2>
				<ul>
					<li><?php echo $this->lang->line('bonus_email_personal_item1'); ?></li>
					<li><?php echo $this->lang->line('bonus_email_personal_item2'); ?></li>
					<li><?php echo $this->lang->line('bonus_email_personal_item3'); ?></li>
				</ul>
				<div class="add">
					<?php
						echo form_open(
							$form_submission,
							$attributes,
							array('action_id' => 67,'variant'=>'default')
						);
						?>
					<strong>$<?php echo number_format($price,2); ?><span><?php echo $this->lang->line('bonus_email_month'); ?></span></strong>
					<input type="submit" value="<?php echo $this->lang->line('bonus_email_add'); ?>" class="btn-yellow">
					<?php echo form_close(); ?>
				</div>
			</div>
			<div class="value">
				<em><?php echo $this->lang->line('bonus_email_best_value'); ?></em>
				<div class="box enterprise">
					<h2 class="custom-bg1"><?php echo $this->lang->line('bonus_email_enter'); ?></h2>
					<ul>
						<li><?php echo $this->lang->line('bonus_email_enter_item1'); ?></li>
						<li><?php echo $this->lang->line('bonus_email_enter_item2'); ?></li>
						<li><?php echo $this->lang->line('bonus_email_enter_item3'); ?></li>
						<li><?php echo $this->lang->line('bonus_email_enter_item4'); ?></li>
						<li><?php echo $this->lang->line('bonus_email_enter_item5'); ?></li>
						<li><?php echo $this->lang->line('bonus_email_enter_item6'); ?></li>
					</ul>
					<div class="add">
						<?php
						echo form_open(
							$form_submission,
							$attributes,
							array('action_id' => 69,'variant'=>'enterprise')
						);
						?>
						<strong>$<?php echo number_format($enterprise['price'],2); ?><span><?php echo $this->lang->line('bonus_email_month'); ?></span></strong>
						<input type="submit" value="<?php echo $this->lang->line('bonus_email_add'); ?>" class="btn-yellow">
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div class="box professional">
				<h2 class="custom-bg1"><?php echo $this->lang->line('bonus_email_pro'); ?></h2>
				<ul>
					<li><?php echo $this->lang->line('bonus_email_pro_item1'); ?></li>
					<li><?php echo $this->lang->line('bonus_email_pro_item2'); ?></li>
					<li><?php echo $this->lang->line('bonus_email_pro_item3'); ?></li>
					<li><?php echo $this->lang->line('bonus_email_pro_item4'); ?></li>
				</ul>
				<div class="add">
					<strong>$<?php echo number_format($pro['price'],2); ?><span><?php echo $this->lang->line('bonus_email_month'); ?></span></strong>
					<?php
						echo form_open(
							$form_submission,
							$attributes,
							array('action_id' => 68,'variant'=>'pro')
						);
						?>
					<input type="submit" value="<?php echo $this->lang->line('bonus_email_add'); ?>" class="btn-yellow">
					<?php echo form_close(); ?>
				</div>
			</div>
			<p><?php echo $this->lang->line('bonus_email_essential'); ?></p>
			<p><?php echo $this->lang->line('bonus_email_diduknow1'); ?> <strong class="txt-large custom-color1"><?php echo $this->lang->line('bonus_email_diduknow2'); ?></strong> <?php echo $this->lang->line('bonus_email_diduknow3'); ?></p>
			<div class="features">
				<h2 class="custom-color1"><?php echo $this->lang->line('bonus_email_features'); ?></h2>
				<ul>
					<li><?php echo $this->lang->line('bonus_email_features1'); ?></li>
					<li><?php echo $this->lang->line('bonus_email_features2'); ?></li>
					<li><?php echo $this->lang->line('bonus_email_features3'); ?></li>
					<li><?php echo $this->lang->line('bonus_email_features4'); ?></li>
					<li><?php echo $this->lang->line('bonus_email_features5'); ?></li>
				</ul>
			</div>
			<?php
			echo form_open(
				$form_submission,
				$attributes,
				array('action_id' => 70)
			);
			?>
			<input type="submit" value="<?php echo $this->lang->line('bonus_email_nothanks'); ?>" class="btn-plain" />
			<?php echo form_close(); ?>
			<p class="light"><?php echo $this->lang->line('bonus_email_onetimecharge'); ?></p>
		</form>
	</section>
</div>