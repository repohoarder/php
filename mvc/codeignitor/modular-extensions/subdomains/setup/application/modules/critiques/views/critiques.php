<div style="text-align:center;width:800px;margin:30px auto;font-family:arial,helvetica,sans-serif;">

	<div style="padding:30px 0;">

		<?php if ($this->input->post('submittered')): ?>

			<img src="/resources/modules/critiques/assets/shit.jpg" alt="Seriously, are you even trying?" title="Seriously, are you even trying?" />

		<?php else: ?>
	
			<form method="post" action="" style="display:block;float:none;">
				<strong style="font-size:1.2em;">Upload your design for professional reviews.</strong>
				<br/>
				<input type="file" name="design" style="margin:10px 0;"/>
				<br/>
				<input type="submit" name="submit" value="Get Critiques!" style="background:none;border:none;background:#7bde84;padding:10px;border-radius:10px;cursor:pointer;font-size:1.2em;font-weight:bold;">
				<input type="hidden" name="submittered" value="1"/>
			</form>
	

			<span style="margin-top:20px;display:block">
				API provided by <a href="http://pleasecritiqueme.com" rel="nofollow" target="_blank">PleaseCritiqueMe.com</a>
			</span>

		<?php endif; ?>

	</div>

</div>