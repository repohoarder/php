<?php 
// initialize varibales
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
<hgroup>
	<h1 class="msg-search-engines hide-text"><?php echo $this->lang->line('bonus_seo_package_title'); ?></h1>
	<h2 class="msg-source"><?php echo $this->lang->line('bonus_seo_package_description'); ?></h2>
	<p class="msg-se-desc"><img src="/resources/brainhost/img/img-search-engines.jpg" alt="Google, Yahoo!, Bing" /></p>
</hgroup>
<h2 class="msg-panic hide-text"><?php echo $this->lang->line('bonus_seo_package_panic'); ?></h2>
<p class="msg-work" style="text-align:center;"><?php echo $this->lang->line('bonus_seo_package_work'); ?></p>


<!-- Use Codeignitor's Form Helpers
<form action="#" method="post">
	<input type="submit" class="seo hide-text" value="<?php echo $this->lang->line('bonus_seo_package_btn_add'); ?>" />
</form>
-->

<?php
// open the form
echo form_open(
	$form_submission,
	$attributes,
	array('action_id' => 11, 'domain'	=> $domain, 'domain_pack_id' => $domain_pack_id, 'plans[]' => $page)	// Hidden Fields
);

// No Thanks Button
echo form_input(array(
	'name'		=> 'submit',
	'type'		=> 'submit',
	'style'		=> 'text-indent:0;overflow:visible;text-transform:none;text-align:center;font-size: 17px;font-weight: 700;',
	'class'		=> 'btn-yellow',
	'value'		=> $this->lang->line('bonus_seo_package_btn_add').$price.'!',
	'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
));

?>
<?php echo form_close(); ?>
<?php if( ! isset($shownothanks) ) : ?>
<span class="lbl-or hide-text">or</span>


<?php
// open the form
echo form_open(
	$form_submission,
	$attributes,
	array('action_id' => 12)	// Hidden Fields
);

// No Thanks Button
echo form_input(array(
	'name'		=> 'submit',
	'type'		=> 'submit',
	'class'		=> 'lbl-nothanks',
	'id'		=> 'lbl-nothanks',
	'value'		=> $this->lang->line('bonus_seo_package_no_thanks'),
	'onsubmit'	=> "_gaq.push(['_linkByPost', this]);"
));

?>
<?php echo form_close();
endif;
?>

<!-- <p class="lbl-nothanks"><a href="#"><?php echo $this->lang->line('bonus_seo_package_no_thanks'); ?></a></p> -->