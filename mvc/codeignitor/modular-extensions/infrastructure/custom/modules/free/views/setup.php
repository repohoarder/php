<script type="text/javascript">
	
	$(document).ready(function () {
		
		// VALIDATION
			$("#website_setup").validate();
				
	});
		
</script>

<?php 

	// initialize variables
		
	$form_submission	= 'free/website/setup';
	
	$attributes			= array(
		'id'	=> 'website_setup',
		'name'	=> 'website_setup'
	);
	
	$hidden_fields		= array(
		'order_id'	=> $order_id
	);

?>

<h1>Congratulations on your new website!</h1>

<section class="content">
	<p>You're only one step away from receiving your new, fully-customized website. Just fill out the simple form below and our expert team of web designers can get started on your site right away.</p>
	<p>When you're done just click on the "Create My Website" button and you're all set - it doesn't get any easier than that!</p>
</section>

<section class="step1">
	<h2><strong>Watch</strong> The Video:</h2>


	<a href="http://setup.brainhost.com/resources/modules/free/assets/vid/setup.flv" style="display:block;width:510px;height:287px" id="player">
	
	
		<!--[if IE]>
        <object
                type="application/x-shockwave-flash"
                data="/resources/brainhost/js/flowplayer/flowplayer-3.2.6.swf"
                codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0"
                pluginspage="http://www.macromedia.com/go/getflashplayer"
                classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                width="100%" 
                height="100%">

			<param name="allowfullscreen" value="true">
			<param name="allowscriptaccess" value="always">
			<param name="quality" value="high">
			<param name="bgcolor" value="#000000">
			<param name="flashvars" value="config={&quot;playerId&quot;:&quot;player&quot;,&quot;clip&quot;:{&quot;url&quot;:&quot;http://setup.brainhost.com/resources/modules/free/assets/vid/setup.flv&quot;},&quot;playlist&quot;:[{&quot;url&quot;:&quot;http://setup.brainhost.com/resources/modules/free/assets/vid/setup.flv&quot;}]}">
        </object>
        <![endif]-->
	
	 	<!--[if !IE]><!-->
		<object width="100%" height="100%" id="player_api" name="player_api" data="/resources/brainhost/js/flowplayer/flowplayer-3.2.6.swf" type="application/x-shockwave-flash">
			<param name="allowfullscreen" value="true">
			<param name="allowscriptaccess" value="always">
			<param name="quality" value="high">
			<param name="bgcolor" value="#000000">
			<param name="flashvars" value="config={&quot;playerId&quot;:&quot;player&quot;,&quot;clip&quot;:{&quot;url&quot;:&quot;http://setup.brainhost.com/resources/modules/free/assets/vid/setup.flv&quot;},&quot;playlist&quot;:[{&quot;url&quot;:&quot;http://setup.brainhost.com/resources/modules/free/assets/vid/setup.flv&quot;}]}">
		</object>
		<!--<![endif]-->
	</a>

	</section>
    
	<section class="step2">
    
		<h2><strong>Fill Out</strong> this Form:</h2>
        
		<?php 
		echo form_open($form_submission,$attributes,$hidden_fields);
		?>
			<fieldset>
				<legend>Fill Out This Form:</legend>
				<div class="half">
					<label for="txtName">Name</label>
					<?php 
					echo form_input(
						array(
							'type'	=> 'text',
							'id'	=> 'txtName',
							'name'	=> 'txtName',
							'class' => 'required'
						)
					);
					?>
				</div>
				<div class="half">
					<label for="txtEmail">Email</label>
					<?php 
					echo form_input(
						array(
							'type'	=> 'email',
							'id'	=> 'txtEmail',
							'name'	=> 'txtEmail',
							'class' => 'required'
						)
					);
					?>
				</div>
				<div>
					<label for="txtURL">Website URL <small>(Example: http://www.mychocolatewebsite.com)</small></label>
					<?php 
					echo form_input(
						array(
							'type'	=> 'text',
							'id'	=> 'txtURL',
							'name'	=> 'txtURL',
							'class' => 'required'
						)
					);
					?>
				</div>
				<div>
					<label for="selCategory">Website Category <small>(Select a category that best suits your site)</small></label>
					<?php 
					echo form_dropdown('selCategory',$categories,'','id="selCategory"', 'name="selCategory"','class="required"');
					?>
				</div>
				<div>
					<label for="txtTitle">Website Title <small>(Example: My Chocolate Store.)<span></span></small></label>
					<?php 
					echo form_input(
						array(
							'type'	=> 'text',
							'id'	=> 'txtTitle',
							'name'	=> 'txtTitle',
							'class' => 'required'
						)
					);
					?>
				</div>
				<div class="last">
					<label for="txtSlogan">Website Slogan <small>(Example: The finest chocolate on Earth.)<span></span></small></label>
					<?php 
					echo form_input(
						array(
							'type'	=> 'text',
							'id'	=> 'txtSlogan',
							'name'	=> 'txtSlogan',
							'class' => 'required'
						)
					);
					?>
				</div>
				<button>Create My Website</button>
			</fieldset>
		<?php 
		echo form_close();
		?>
	</section>
