<?php 

foreach ($suggestions as $key => $value): ?>

	<form action="<?php echo $submit_url;?>" method="post" target="_top" class="form_container">
		
		<span class="domain-name"><?php echo $value; ?> <strong>Is Available!</strong></span>
		<button type="submit">Click Here Get This Domain and Website Now</button>

		<input type="hidden" name="core_domain" value="<?php echo $value; ?>"/>
		<input type="hidden" name="core_num_years" value="1"/>
		<input type="hidden" name="core_type" value="register"/>

	</form>

	<?php

endforeach; ?>

	<form action="<?php echo $submit_url;?>" id="choose_own">
		<input type="image" value="Go Here To Choose Your Own Domain and Website Now!" src="/resources/modules/spinner/assets/click-here.png" />
	</form>
