<?php echo $this->template->load_view('header.php'); ?>

	<div id="contentwrapper">

		<div class="main_content"  class="clearfix">

			<?php echo $this->template->load_view('breadcrumb.php'); ?>

			<?php echo $template['body']; ?>
			
		</div>

	</div>

<?php echo $this->template->load_view('sidebar.php'); ?>

<?php echo $this->template->load_view('footer.php'); ?>