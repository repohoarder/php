	<div id="main" role="main">

		<?php 
		if( ! isset($skipfilters) && strstr($_SERVER['QUERY_STRING'],'whitelabel=0') === FALSE) :
		echo $this->template->load_view('menu_date_filters'); 
		endif;
		?>
		
		<section id="s-reports">
			<div class="pad">