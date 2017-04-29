<?php

class Hersterng extends MX_Controller {


	function index()
	{

		$this->lang->load('hosting');

		$this->template->set_layout('sales_funnel2');

		$this->template->title($this->lang->line('hosting_title'));

		$this->template->prepend_footermeta('

			<script type="text/javascript" src="/resources/modules/hersterng/assets/js/domain_spin.js"></script>

			<script type="text/javascript" src="/resources/brainhost/js/flowplayer/flowplayer-3.2.10.min.js"></script>
			<!-- this will install flowplayer inside previous A- tag. -->
			<script>
				flowplayer("player", "/resources/brainhost/js/flowplayer/flowplayer-3.2.5.swf", {
					plugins:  {
						controls: null,
					}
				});
			</script>
		');
		
		$this->template->build('hosting');
	}

}