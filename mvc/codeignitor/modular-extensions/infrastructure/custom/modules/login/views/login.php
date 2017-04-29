<script type="text/javascript">
	
	$(document).ready(function () {
		
		// VALIDATION
			$("#login_form").validate();
				
	});
		
</script>

<?php 

	// initialize form variables
			
	//$form_submission	= 'login/user/login';
	$form_submission	= '';

	$attributes			= array(
		'id'	=> 'login_form',
		'name'	=> 'login_form'
	);

	$hidden_fields		= array(
		
	);
	
?>

<div style="margin-left:10px;">

<h1><?php echo $header; ?></h1>

<div style="margin-left:24px;">

	<?php echo form_open_multipart($form_submission,$attributes,$hidden_fields); ?>
    
    	<div style="width:550px; margin-top:10px;">
        
        	<div class="form_l">User: </div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'text',
                    'id'	=> 'user',
                    'name'	=> 'user',
                    'class' => 'required'));
				?>
            </div>
            <div class="form_space"></div>
        
        	<div class="form_l">Password:</div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'password',
                    'id'	=> 'pass',
                    'name'	=> 'pass',
                    'class' => 'required'));
				?>
            </div>
			<div class="form_space"></div>
            
			
			<div class="form_l"></div>
            <div class="form_r">
				<?php echo form_input(array(
                    'type'	=> 'submit',
                    'value'	=> 'Login'));
				?>
            </div>
            <div class="form_space"></div>
			<br /><br />
			
        </div>
    
    <?php echo form_close(); ?>

</div>

</div>