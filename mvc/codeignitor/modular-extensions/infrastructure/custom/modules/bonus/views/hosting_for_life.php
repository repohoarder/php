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

<h1 id="override-hfl"><?php echo $this->lang->line('bonus_hfl_step3'); ?></h1>
<section class="content">
	<p><strong><?php echo $this->lang->line('bonus_hfl_customer1'); ?></strong> <?php echo $this->lang->line('bonus_hfl_customer2'); ?></p>
</section>
<section class="video-box">

	
	<h2><?php echo $this->lang->line('bonus_hfl_stop1'); ?> <span><?php echo $this->lang->line('bonus_hfl_stop2'); ?></span></h2>
	<?php /*
	<!--<video id="video" controls="controls" width="494" height="316" autoplay="autoplay">
		<source type="video/flv" src="https://setup.brainhost.com/resources/modules/bonus/assets/vid/TypeVideos_-_BrainHost_H4L_Video.flv">
		<source type="video/mp4" src="https://setup.brainhost.com/resources/modules/bonus/assets/vid/TypeVideos_-_BrainHost_H4L_Video.mp4">
		<source type="video/ogg" src="https://setup.brainhost.com/resources/modules/bonus/assets/vid/TypeVideos_-_BrainHost_H4L_Video.ogg">
		<source type="video/webm" src="https://setup.brainhost.com/resources/modules/bonus/assets/vid/TypeVideos_-_BrainHost_H4L_Video.webm">
		<a 
			 href="vid/TypeVideos_-_BrainHost_H4L_Video.flv"  
			 style="display:block;width:494px;height:316px"  
			 id="player"> 
		</a>
	</video>-->
	*/ ?>

		<?php /*
		<!--[if IE]>
        <object
                type="application/x-shockwave-flash"
                data="/resources/brainhost/js/flowplayer/flowplayer-3.2.6.swf"
                codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0"
                pluginspage="http://www.macromedia.com/go/getflashplayer"
                classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                width="494px" 
                height="316px">

			<param name="allowfullscreen" value="true">
			<param name="allowscriptaccess" value="always">
			<param name="quality" value="high">
			<param name="bgcolor" value="#000000">
			<param name="flashvars" value="config={&quot;playerId&quot;:&quot;player&quot;,&quot;clip&quot;:{&quot;url&quot;:&quot;https://setup.brainhost.com/vidyas/TypeVideos_-_BrainHost_H4L_Video.flv&quot;},&quot;playlist&quot;:[{&quot;url&quot;:&quot;https://setup.brainhost.com/vidyas/TypeVideos_-_BrainHost_H4L_Video.flv&quot;}]}">
        </object>
        <![endif]-->
		
	
	 	<!--[if !IE]><!-->
	 	*/ ?>
		<object width="494px" height="316px" id="player_api" name="player_api" data="https://infrastructure.brainhost.com/flowplayer/flowplayer-3.2.6.swf" type="application/x-shockwave-flash">
			<param name="allowfullscreen" value="true">
			<param name="allowscriptaccess" value="always">
			<param name="quality" value="high">
			<param name="bgcolor" value="#000000">
			<param name="flashvars" value="config={&quot;playerId&quot;:&quot;player&quot;,&quot;clip&quot;:{&quot;url&quot;:&quot;https://infrastructure.brainhost.com/vidyas/TypeVideos_-_BrainHost_H4L_Video.flv&quot;},&quot;playlist&quot;:[{&quot;url&quot;:&quot;https://infrastructure.brainhost.com/vidyas/TypeVideos_-_BrainHost_H4L_Video.flv&quot;}]}">
		</object>
		<?php /*
		<!--<![endif]-->
		*/ ?>


</section>
<section class="features">
	<h2><?php echo $this->lang->line('bonus_hfl_upgrade1'); ?> <span><?php echo $this->lang->line('bonus_hfl_upgrade2'); ?></span></h2>
	<ul>
		<li><span><?php echo $this->lang->line('bonus_hfl_value'); ?></span>
			<ul>
				<li><?php echo $this->lang->line('bonus_hfl_value1'); ?></li>
				<li><?php echo $this->lang->line('bonus_hfl_value2'); ?></li>
				<li><?php echo $this->lang->line('bonus_hfl_value3'); ?></li>
				<li><?php echo $this->lang->line('bonus_hfl_value4'); ?></li>
			</ul>
		</li>
		<li><?php echo $this->lang->line('bonus_hfl_never'); ?></li>
		<li><?php echo $this->lang->line('bonus_hfl_payment'); ?> <span>$<?php echo $price; ?></span></li>
	</ul>
</section>
<section class="continue">
	<p><?php echo $this->lang->line('bonus_hfl_click_continue'); ?></p>

	<?php

	// add to form attributes
	$attributes['id']	= 'frm-continue';

	// open the form
	echo form_open(
		$form_submission,
		$attributes,
		array('action_id' => 25, 'plans[]' => $page)	// Hidden Fields
	);

	// No Thanks Button
	echo form_input(array(
		'name'		=> 'submit',
		'type'		=> 'submit',
		'class'		=> '',
		'value'		=> $this->lang->line('bonus_hfl_continue')
	));

	?>
	<?php echo form_close(); ?>
	
	<?php

	// add to form attributes
	$attributes['id']	= 'frm-next';

	// open the form
	echo form_open(
		$form_submission,
		$attributes,
		array('action_id' => 26)	// Hidden Fields
	);

	// No Thanks Button
	echo form_input(array(
		'name'		=> 'submit',
		'type'		=> 'submit',
		'class'		=> '',
		'id'		=> 'lbl-nothanks',
		'value'		=> $this->lang->line('bonus_hfl_nothanks')
	));

	?>
	<?php echo form_close(); ?>

</section>
