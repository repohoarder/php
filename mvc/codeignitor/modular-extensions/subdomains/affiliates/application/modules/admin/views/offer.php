<script type="text/javascript">
	
	$(document).ready(function () {
		
		// VALIDATION
			$("#offer_form").validate();
				
	});
		
</script>

<?php 

	// initialize form variables
			
	$form_submission	= 'admin/offer/admin';

	$attributes			= array(
		'id'	=> 'offer_form',
		'name'	=> 'offer_form'
	);

	$hidden_fields		= array(
		
	);
	
?>


<div style="margin-left:10px;">

<h1>BH 2.0 Affiliate/Offer Admin</h1>

<div style="margin-left:24px;">

	<?php echo form_open_multipart($form_submission,$attributes,$hidden_fields); ?>
    
    	<div style="width:550px; margin-top:10px;">
        
        	<div class="form_l">Affiliate ID: *</div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'number',
                    'id'	=> 'affID',
                    'name'	=> 'affID',
                    'class' => 'required'));
				?>
            </div>
            <div class="form_space"></div>
        
        	<div class="form_l">Offer ID:</div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'number',
                    'id'	=> 'offID',
                    'name'	=> 'offID'));
				?>
            </div>
			<div class="form_space"></div>
            
			<div class="form_l">Logo:</div>
            <div class="form_r">
				<?php echo form_upload(array(
                    'id'	=> 'logo',
                    'name'	=> 'logo'));
				?>
            </div>
			<div class="form_space"></div>
			
			<div class="form_l">Video:</div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'url',
                    'id'	=> 'video',
                    'name'	=> 'video'));
				?>
            </div>
			<div class="form_space"></div>
			
			<div class="form_l">Header:</div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'text',
                    'id'	=> 'header',
                    'name'	=> 'header'));
				?>
            </div>
			<div class="form_space"></div>
			
			<p>* - Required.</p>
			
			<div class="form_l"></div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'submit',
                    'value'	=> 'Submit'));
				?>
            </div>
            <div class="form_space"></div>
			
        </div>
    
    <?php echo form_close(); ?>

</div>

</div>
