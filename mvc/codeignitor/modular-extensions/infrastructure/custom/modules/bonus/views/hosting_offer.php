<?php 
// initialize varibales
$form_submission	= '';
$attributes			= array(
	'method'	=> 'POST'
);
?>
<div class="content upsell-hosting">
	<?php echo $this->template->load_view('warning.php'); ?>
	<section class="module">
			<h1 class="custom-bg1"><?php echo $this->lang->line('bonus_web_host_title'); ?></h1>
			<div class="col-l">
				<p><?php echo $this->lang->line('bonus_web_host_diduknow1'); ?><br /> <strong class="txt-large custom-color1"><?php echo $this->lang->line('bonus_web_host_diduknow2'); ?></strong><br /> <?php echo $this->lang->line('bonus_web_host_diduknow3'); ?></p>
			</div>
			<div class="col-r">
				<div class="features">
					<h2 class="custom-color1"><?php echo $this->lang->line('bonus_web_host_congrats'); ?></h2>
					<h3><?php echo $this->lang->line('bonus_web_host_congrats', $domain); ?></h3>
					<h3 class="custom-color1"><?php echo $this->lang->line('bonus_web_host_promise'); ?></h3>
					<ul>
						<li><?php echo $this->lang->line('bonus_web_host_promise1'); ?></li>
						<li><?php echo $this->lang->line('bonus_web_host_promise2'); ?></li>
						<li><?php echo $this->lang->line('bonus_web_host_promise3'); ?></li>
						<li><?php echo $this->lang->line('bonus_web_host_promise4'); ?></li>
					</ul>
					
					<h2 class="custom-color1"><?php echo $this->lang->line('bonus_web_host_promise1'). ' $'.$price; ?>/month </h2>
					<p>*Plus a one time setup fee of $20.00</p>
				</div>

				<?php
				// open the form
				echo form_open(
					$form_submission,
					$attributes,
					array('action_id' => 62, 'plans[]' => $page,'period'=>$term)	// Hidden Fields
				);

				// No Thanks Button
				echo form_input(array(
					'name'		=> 'submit',
					'type'		=> 'submit',
					'class'		=> 'btn-yellow',
					'value'		=> $this->lang->line('bonus_web_host_add'),
					'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
				));

				?>
				<?php echo form_close(); ?>


				<?php
				// open the form
				echo form_open(
					$form_submission,
					$attributes,
					array('action_id' => 63)	// Hidden Fields
				);

				// No Thanks Button
				echo form_input(array(
					'name'		=> 'submit',
					'type'		=> 'submit',
					'class'		=> 'btn-plain',
					//'id'		=> 'lbl-nothanks',
					'value'		=> $this->lang->line('bonus_web_host_nothanks'),
					'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
				));

				?>
				<?php echo form_close(); ?>

				<p class="light"><?php echo $this->lang->line('bonus_web_host_onetimecharge'); ?></p>
			</div>
	</section>
</div>