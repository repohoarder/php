<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<form method="post" action="">
<h1>Manage | Thank You Page </h1>
<section id="pnl-accordion">
	<h2> Thank you Page Content</h2>
	<div class="module s-manage-account">
		<div class="pad">
			<p>
				<a data-fancybox-type="iframe" class="bigbox" href="http://infrastructure.hostingaccountsetup.com/initialize/demo/<?php echo $partnerid;?>/?frame=1">Preview</a>
			</p>
			<br>
			<textarea name="content" style='width:100%;'><?php echo stripslashes($content_custom); ?></textarea>
			<br>
			<input type="submit" class="btn-contact" id="submit" value="Update" name="submit">
		</div>
	</div>
</section>
</form>

