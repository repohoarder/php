<?php
## TODO: jQuery to track action every time checkbox is checked or unchecked


// if there are no domain suggestions, then we need to skip this form (submit the "no_available_tlds" form)
if ( ! isset($suggestions) OR empty($suggestions)):
?>
	<!-- Submit "no_available_tlds" form (for tracking purposes) -->
	<script type="text/javascript">
	$(document).ready(function() {
		// submit no_available_tlds form
		$("#no_available_tlds").submit();		// submit form
	});
	</script>
<?php
endif; 	// end seeing if there is no suggestions
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
<h1 class="msg-wait hide-text"><?php echo $this->lang->line('bonus_addon_domains_title'); ?></h1>
<?php
// open form
echo form_open(
	'',
	array('method'		=> 'POST' , 'id' 			=> 'add_domain_form'),
	array('action_id'	=> 13)
);
?>
	<section id="content">
		<p><?php echo $this->lang->line('bonus_addon_domains_description'); ?></p>
		<p><strong><?php echo $this->lang->line('bonus_addon_domains_description2'); ?></strong></p>
		
		<hgroup>
			<h2><?php echo $this->lang->line('bonus_addon_domains_ext_title'); ?></h2>
			<h3><?php echo $this->lang->line('bonus_addon_domains_ext_subtitle'); ?></h3>
		</hgroup>
		<fieldset>
			<legend><?php echo $this->lang->line('bonus_addon_domains_ext_title'); ?></legend>
			<ul>
				<?php
				$cntr=1;
				// iterate through all suggestions & display choices
				foreach ($suggestions AS $key => $tld):
				?>
					<li>
						<input type="checkbox" id="chkExtension<?php echo $cntr; ?>" name="plans[]" value="<?php echo $domain_sld.'.'.$tld; ?>" />
						<label for="chkExtension<?php echo $cntr; ?>"><?php echo $domain_sld; ?><strong>.<?php echo $tld; ?></strong> <del>$<?php echo $price+5; ?></del> <em>$<?php echo $price; ?></em></label>
					</li>
				<?php
					$cntr++;	// increment counter
				endforeach;
				?>
			</ul>
		</fieldset>
	</section>
	<?php
	// submit button
	echo form_input(array(
		'name'	=> 'submit',
		'type'	=> 'submit',
		'class'	=> 'hide-text',
		'value'	=> $this->lang->line('bonus_addon_domains_btn_add')
	));

	// close form
	echo form_close();
	?>
	<!-- <input type="submit" class="hide-text" value="<?php echo $this->lang->line('bonus_addon_domains_btn_add'); ?>" /> -->

<?php if( ! isset($shownothanks) ) : ?>
<span class="lbl-or hide-text">or</span>
<?php
// open form
echo form_open(
	'',
	array('method'		=> 'POST' , 'id' => 'no_thanks_form'),
	array('action_id'	=> 14)
);
// close form
echo form_close();
?>
<p class="lbl-nothanks"><a href="#" id="no_thanks"><?php echo $this->lang->line('bonus_addon_domains_no_thanks'); ?></a></p>

<?php endif; ?>

<?php
// open form
echo form_open(
	'',
	array('method'		=> 'POST' , 'id' => 'no_available_tlds'),
	array('action_id'	=> 47)
);
// close form
echo form_close();
?>


<!-- Javascript to update action_id hidden field depending on button clicked -->
<script type="text/javascript">
$(document).ready(function() {

	// on click of no thanks link, submit form
	$("#no_thanks").click(function(e) {
		e.preventDefault();
		$("#no_thanks_form").submit();		// submit form
	});
	
});
</script>