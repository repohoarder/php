<?php
$this->load->helper('url');

// initialize varibales
$form_submission	= '';
$attributes			= array(
	'method'	=> 'POST',
	'id'		=> 'spinner_form'
);



// open the form
echo form_open(
	$form_submission,
	$attributes,
	array('action_id' => 31)	// Hidden Fields
);
?>
	<section class="step1">
		<h2><?php echo $this->lang->line('hosting_watch_video'); ?></h2>

		<a 
			 href="<?php echo site_url('/resources/modules/free/assets/vid/hosting.flv'); ?>"  
			 style="display:block;margin:0 auto;width:350px;height:250px"  
			 id="player">  
		</a>

		<?php /*
		<video width="350" height="250" controls="controls">
			<source src="vid/brainhost.mp4" type="video/mp4" />
			<a 
				 href="http://setup.brainhost.com/resources/modules/free/assets/vid/content.flv"  
				 style="display:block;width:350px;height:250px"  
				 id="player"> 
			</a>
		</video>
		*/ ?>

	</section>
	<section class="step2">
		<h2><?php echo $this->lang->line('hosting_fill_form_title'); ?></h2>
		
		<fieldset>
			<legend><?php echo $this->lang->line('hosting_fill_form'); ?></legend>
			
			<?php /* <div id="error"></div> */ ?>
			
			<div class="half">
				<label for="full_name"><?php echo $this->lang->line('hosting_label_name'); ?></label>
				<?php
				echo form_input(array(
					'name'		=> 'full_name',
					'type'		=> 'text',
					'id'		=> 'full_name'
				));
				?>
				<!-- <input type="text" id="full_name" name="full_name" /> -->
			</div>
			<div class="half">
				<label for="email"><?php echo $this->lang->line('hosting_label_email'); ?></label>
				<?php
				echo form_input(array(
					'name'		=> 'email',
					'type'		=> 'email',
					'id'		=> 'email'
				));
				?>
				<!-- <input type="email" id="email" name="email" /> -->
			</div>
			<div class="half">
				<label for="txtTopic"><?php echo $this->lang->line('hosting_label_topic'); ?></label>
				<?php
				echo form_input(array(
					'name'			=> 'txtTopic',
					'type'			=> 'text',
					'id'			=> 'txtTopic',
					'placeholder'	=> $this->lang->line('hosting_char_limit'),
					'maxlength'		=> 30
				));
				?>
				<!-- <input type="text" id="txtTopic" name="txtTopic" placeholder="<?php echo $this->lang->line('hosting_char_limit'); ?>" maxlength="30" /> -->
			</div>
			<div class="half">
				<a href="#" id="btn-domain"><?php echo $this->lang->line('hosting_get_domain'); ?></a>
			</div>
			<div class="last" id="pnl-domain">
				<label for="core_domain"><?php echo $this->lang->line('hosting_best_rank'); ?></label>
				<?php
				echo form_input(array(
					'name'			=> 'core_domain',
					'type'			=> 'text',
					'id'			=> 'core_domain',
					'placeholder'	=> $this->lang->line('hosting_example'),
					'disabled'		=> TRUE
				));
				?>
				<!-- <input type="text" id="core_domain" placeholder="<?php echo $this->lang->line('hosting_example'); ?>" disabled="disabled" /> -->

				<?php
				echo form_input(array(
					'name'			=> 'core_domain',
					'type'			=> 'hidden',
					'id'			=> 'hdnDomain'
				));
				?>
				<!-- <input id="hdnDomain" type="hidden" name="core_domain" value="" /> -->

				<a href="#" id="btn-spin"><?php echo $this->lang->line('hosting_spin'); ?></a>
			</div>
			<div id="pnl-results"></div>
			<div id="pnl-loading"></div>
		</fieldset>

	</section>
	<aside id="signup">

		<span style="visibility:hidden;"><?php echo $this->lang->line('hosting_not_setup'); ?></span>

		<?php
		echo form_input(array(
			'name'			=> 'submit',
			'type'			=> 'submit',
			'id'			=> 'btn-hosting',
			'value'			=> $this->lang->line('hosting_continue')
		));
		?>
		<!-- <button id="btn-hosting"><?php echo $this->lang->line('hosting_continue'); ?></button> -->

	</aside>

<?php
echo form_close();