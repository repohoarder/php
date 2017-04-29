<form action="#" method="post" id="frmLogin">

	<fieldset>

		<legend><?php echo $this->lang->line('login_heading'); ?></legend>

		<input type="text" id="txtUsername" name="txtUsername" placeholder="<?php echo $this->lang->line('login_username'); ?>" required="required" />

		<input type="password" id="txtPassword" name="txtPassword" placeholder="<?php echo $this->lang->line('login_password'); ?>" required="required" />

		<input type="image" src="/resources/brainhost/img/btn-login.png" alt="<?php echo $this->lang->line('login_button'); ?>">

	</fieldset>

</form>

<nav class="utility">

	<a href="<?php echo $this->anchors->get_link('forgot_pass'); ?>"><?php echo $this->anchors->get_text('forgot_pass'); ?></a> 

	<a href="<?php echo $this->anchors->get_link('create_account'); ?>"><?php echo $this->anchors->get_text('create_account'); ?></a>

</nav>