<?php if (isset($errors) && is_array($errors) && count($errors)): ?>

<div class="errors_wrapper">
	<ul class="errors">
		<?php 
		foreach ($errors as $error): ?>
			<li><?php echo $error;?></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif;